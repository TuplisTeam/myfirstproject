<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adminmodel extends CI_Model 
{

public function __construct()
{
	parent::__construct();
}

/*Manju Starts*/

public function getMenuDetails()
{
	if($this->session->userdata('usertype') == "admin")
	{
		$sql = "SELECT *,
					'yes' AS save_access, 
					'yes' AS edit_access, 
					'yes' AS delete_access
				FROM menu_mas WHERE STATUS <> 'inactive' 
				ORDER BY slno, menu_level";
	}
	else
	{
		$sql = "SELECT 
					m.*, 
					IFNULL(um.save_access,'no') AS save_access, 
					IFNULL(um.edit_access,'no') AS edit_access, 
					IFNULL(um.delete_access,'no') AS delete_access
				FROM 
					menu_mas m 
					LEFT OUTER JOIN 
						(SELECT * FROM user_vs_menu 
						WHERE userid = '".$this->session->userdata('userid')."') um 
						ON m.menu_id = um.menu_id
				WHERE 
					m.status <> 'inactive' AND m.is_admin_menu = 'no'
				ORDER BY m.slno, m.menu_level";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getMenuDetails_User($userId = '')
{
	$whrStr = "";
	if($this->session->userdata('usertype') != "admin")
	{
		$whrStr .= " AND m.is_admin_menu = 'no'";
	}
	if($userId > 0)
	{
		$whrStr .= " AND um.userid = $userId";
	}
	$sql = "SELECT DISTINCT 
				m.*, 
				IFNULL(um.save_access,'no') AS save_access, 
				IFNULL(um.edit_access,'no') AS edit_access, 
				IFNULL(um.delete_access,'no') AS delete_access
			FROM 
				menu_mas m 
				LEFT OUTER JOIN user_vs_menu um ON m.menu_id = um.menu_id
			WHERE 
				m.status <> 'inactive' $whrStr
			ORDER BY m.slno, m.menu_level";
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkLogin($email, $password, $sectionName)
{
	$res = array();
	
	$sql = "SELECT * FROM users 
			WHERE email = '".$email."' AND password = '".md5($password)."'";
	$result = $this->db->query($sql);
	
	foreach($result->result() as $user)
	{
		$a = array();
		$sql1 = "";
		if($user->usertype == "admin")
		{
			$sql1 = "SELECT *,
						'yes' AS save_access, 
						'yes' AS edit_access, 
						'yes' AS delete_access
					FROM menu_mas WHERE STATUS <> 'inactive' 
					ORDER BY slno, menu_level";
		}
		else
		{
			$sql1 = "SELECT 
						m.*, 
						IFNULL(um.save_access,'no') AS save_access, 
						IFNULL(um.edit_access,'no') AS edit_access, 
						IFNULL(um.delete_access,'no') AS delete_access
					FROM 
						menu_mas m 
						LEFT OUTER JOIN user_vs_menu um ON m.menu_id = um.menu_id
					WHERE 
						m.status <> 'inactive' AND m.is_admin_menu = 'no' AND 
						um.userid = '".$user->userid."'
					ORDER BY m.slno, m.menu_level";
		}
		$result1 = $this->db->query($sql1);
		foreach($result1->result() as $menu)
		{
			$arr = array("save"=>$menu->save_access,
						  "edit" => $menu->edit_access,
						  "delete"=> $menu->delete_access);
			array_push($a[$menu->menu_id] = $arr,$arr);
		}
		
		$userdata = array(
    		'userid' => $user->userid,
			'username' => $user->email,
			'firstname' => $user->firstname,
    		'usertype' => $user->usertype,
    		'status' => $user->status,
			'sectionName' => $sectionName, 
			'loggedin' => TRUE,
			'menudata' => $a
    	);
		
		if($user->status == 'inactive')
		{
			$res["isError"] = TRUE;
			$res["msg"] = "Account in InActive Mode.. Please Contact Administrator.";
			echo json_encode($res);
			return;
		}		
				
		$sql = "UPDATE users SET lastlogin = NOW() WHERE userid = '".$user->userid."'";
		$this->db->query($sql);		
	
		$this->session->set_userdata($userdata);
		
		$res["isError"] = FALSE;
		$res["msg"] = "Successfully login";	
		echo json_encode($res);
		return;	
	}
	
	$res["isError"] = TRUE;
	$res["msg"] = "Invalid Login";
	echo json_encode($res);
	return;
}

/*public function checkForgotPassword($email)
{
 	$myURL = base_url().'common/sendForgotPassword/'.$email;
	$this->initializeCURL($myURL);
}*/

public function getUserDetails($userType = '', $userId = '')
{
	$sql = "SELECT u.*, IFNULL(cb.firstname,'') AS createdby
			FROM 
				users u 
				LEFT OUTER JOIN 
					(SELECT * FROM users WHERE status <> 'inactive') cb 
					ON u.created_by = cb.userid
			WHERE 
				u.status <> 'inactive'";
	if($userType != "all")
	{
		$sql .= " AND u.usertype <> 'admin'";
		$sql .= " AND u.created_by = '".$this->session->userdata('userid')."'";
	}
	if($userId > 0)
	{
		$sql .= " AND u.userid = $userId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getUserDetailsByEmail($email)
{
	$sql = "SELECT * FROM users WHERE status <> 'inactive' AND email = '".$email."'";
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkUserAvailability($userId, $userEmail)
{
	$sql = "SELECT * FROM users 
				WHERE status <> 'inactive' AND email = '".$userEmail."'";
	if($userId > 0)
	{
		$sql .= " AND userid <> $userId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveUser($userId, $userName, $userEmail, $userType, $userPassword, $sectionName, $menuPermissionsArr)
{
	if($userId > 0)
	{
		$myStr = '';
		if($userPassword != "")
		{
			$myStr = "password = md5('".$userPassword."'), ";
		}
		$sql = "UPDATE users SET 
					email = '".$userEmail."', $myStr
					firstname = '".$userName."', usertype = '".$userType."', 
					sectionname = '".$sectionName."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE userid = $userId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO users SET 
					email = '".$userEmail."', password = md5('".$userPassword."'), 
					firstname = '".$userName."', usertype = '".$userType."', 
					sectionname = '".$sectionName."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$userId = $this->db->insert_id();
	}
	
	$sqlDel = "DELETE FROM user_vs_menu WHERE userid = $userId";
	$this->db->query($sqlDel);
	
	foreach($menuPermissionsArr as $row)
	{
		$sql = "INSERT INTO user_vs_menu SET 
					userid = $userId, menu_id = '".$row->menuId."', 
					save_access = '".$row->saveAccess."', 
					edit_access = '".$row->editAccess."', 
					delete_access = '".$row->delAccess."'";
		$this->db->query($sql);
	}
}

public function updatePassword($email, $newPassword)
{
	$sql = "UPDATE users SET 
				PASSWORD = md5('".mysql_real_escape_string($newPassword)."') 
			WHERE email = '".$email."'";
	$this->db->query($sql);
}

public function getBarcodeDetails($barcodeId = '', $barcodeName = '')
{
	$sql = "SELECT *, DATE_FORMAT(receipt_date,'%d/%m/%Y') AS receiptdt FROM barcode 
			WHERE status <> 'inactive'";
	if($barcodeId > 0)
	{
		$sql .= " AND id = $barcodeId";
	}
	if($barcodeName != "")
	{
		$sql .= " AND barcode = '".$barcodeName."'";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkBarcodeAvailability($barcodeId, $barcodeName)
{
	$sql = "SELECT * FROM barcode 
			WHERE status <> 'inactive' AND barcode = '".$barcodeName."'";
	if($barcodeId > 0)
	{
		$sql .= " AND id <> $barcodeId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function checkReceiptDateAvailability($barcodeId, $receiptDate)
{
	$sql = "SELECT * FROM barcode 
			WHERE status <> 'inactive' AND receipt_date = '".$receiptDate."'";
	if($barcodeId > 0)
	{
		$sql .= " AND id <> $barcodeId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveBarcodeGeneration($barcodeId, $barcodeName, $receiptDate, $orderNo, $process, $style, $item, $rate, $buyerName, $color, $size)
{
	if($barcodeId > 0)
	{
		$sql = "UPDATE barcode SET 
					receipt_date = '".$receiptDate."', orderno = '".$orderNo."', 
					processname = '".$process."', style = '".$style."', 
					itemname =  '".$item."', rate = '".$rate."', 
					buyername = '".$buyerName."', color = '".$color."', 
					size = '".$size."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $barcodeId";
	}
	else
	{
		$sql = "INSERT INTO barcode SET 
					barcode = '".$barcodeName."', 
					receipt_date = '".$receiptDate."', orderno = '".$orderNo."', 
					processname = '".$process."', style = '".$style."', 
					itemname =  '".$item."', rate = '".$rate."', 
					buyername = '".$buyerName."', color = '".$color."', 
					size = '".$size."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getDeliveryNoteHeaderDetails($deliveryNoteId = '')
{
	$sql = "SELECT *, DATE_FORMAT(dcdate,'%d/%m/%Y') AS dcdt 
			FROM deliverynote_hdr WHERE status <> 'inactive'";
	if($deliveryNoteId > 0)
	{
		$sql .= " AND id = $deliveryNoteId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getDeliveryNoteItemDetails($deliveryNoteId = '', $isReceived = '')
{
	$sql = "SELECT 
				h.deliveryno, DATE_FORMAT(h.dcdate,'%d/%m/%Y') AS dcdt, d.*
			FROM 
				deliverynote_hdr h 
				INNER JOIN deliverynote_dtl d ON h.id = d.deliverynoteid
			WHERE h.status <> 'inactive'";
	if($isReceived != "")
	{
		$sql .= " AND d.is_received = '$isReceived'";
	}
	if($deliveryNoteId > 0)
	{
		$sql .= " AND h.id = $deliveryNoteId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveDeliveryNote($deliveryNoteId, $deliveryNo, $dcDate, $supplierName, $supplierAddress, $customerName, $receiverName, $totalAmount, $deliveryFrom, $deliveredBy, $remarks, $dtlArr)
{
	$supplierAddress = str_replace("'", "\'", $supplierAddress);
	$remarks = str_replace("'", "\'", $remarks);
	
	if($deliveryNoteId > 0)
	{
		$sql = "UPDATE deliverynote_hdr SET 
					deliveryno = '".$deliveryNo."', dcdate = '".$dcDate."', 
					suppliername = '".$supplierName."', 
					supplieraddress = '".$supplierAddress."', 
					customername = '".$customerName."', 
					receivername = '".$receiverName."', 
					totalamount = '".$totalAmount."', 
					delivered_from = '".$deliveryFrom."', 
					delivered_by = '".$deliveredBy."', 
					remarks = '".$remarks."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $deliveryNoteId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO deliverynote_hdr SET 
					deliveryno = '".$deliveryNo."', dcdate = '".$dcDate."', 
					suppliername = '".$supplierName."', 
					supplieraddress = '".$supplierAddress."', 
					customername = '".$customerName."', 
					receivername = '".$receiverName."', 
					totalamount = '".$totalAmount."', 
					delivered_from = '".$deliveryFrom."', 
					delivered_by = '".$deliveredBy."', 
					remarks = '".$remarks."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$deliveryNoteId = $this->db->insert_id();
	}
	
	$sqlDel = "DELETE FROM deliverynote_dtl 
				WHERE deliverynoteid = $deliveryNoteId AND is_received = 'no'";
	$this->db->query($sqlDel);
	
	foreach($dtlArr as $row)
	{
		$sql1 = "INSERT INTO deliverynote_dtl SET 
					deliverynoteid = $deliveryNoteId, 
					barcode = '".$row->barcode."', itemname = '".$row->itemName."', 
					division = '".$row->division."', rate = '".$row->rate."', 
					quantity = '".$row->quantity."', amount = '".$row->amount."'";
		$this->db->query($sql1);
	}
}

public function getReceptionCheckDetails($receptionCheckId = '')
{
	$sql = "SELECT 
				r.id, r.fromname, r.toname, 
				r.dcno, d.deliveryno, r.lotno, r.vehicleno, 
				DATE_FORMAT(d.dcdate,'%d/%m/%Y') AS dcdt, d.totalamount, 
				DATE_FORMAT(rcdate,'%d/%m/%Y') AS rcdt, r.unitname, 
				r.desc_check_1, r.desc_check_2, r.desc_check_3, r.desc_check_4, 
				r.checkedby, r.incharge, r.status, 
				r.created_on, r.created_by, r.modified_on, r.modified_by
			FROM 
				receptioncheck r
				INNER JOIN deliverynote_hdr d ON r.dcno = d.id 
			WHERE 
				r.status <> 'inactive' AND d.status <> 'inactive'";
	if($receptionCheckId > 0)
	{
		$sql .= " AND r.id = $receptionCheckId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveReceptionCheck($receptionCheckId, $fromName, $toName, $dcNo, $lotNo, $vehicleNo, $rcDate, $unitName, $descCheck1, $descCheck2, $descCheck3, $descCheck4, $checkedBy, $incharge, $remarks)
{
	if($receptionCheckId > 0)
	{
		$sql = "UPDATE receptioncheck SET 
					fromname = '".$fromName."', toname = '".$toName."', 
					dcno = '".$dcNo."', lotno = '".$lotNo."', 
					vehicleno = '".$vehicleNo."', rcdate = '".$rcDate."', 
					unitname = '".$unitName."', desc_check_1 = '".$descCheck1."', 
					desc_check_2 = '".$descCheck2."', desc_check_3 = '".$descCheck3."', 
					desc_check_4 = '".$descCheck4."', checkedby = '".$checkedBy."', 
					incharge = '".$incharge."', remarks = '".$remarks."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $receptionCheckId";
	}
	else
	{
		$sql = "INSERT INTO receptioncheck SET 
					fromname = '".$fromName."', toname = '".$toName."', 
					dcno = '".$dcNo."', lotno = '".$lotNo."', 
					vehicleno = '".$vehicleNo."', rcdate = '".$rcDate."', 
					unitname = '".$unitName."', desc_check_1 = '".$descCheck1."', 
					desc_check_2 = '".$descCheck2."', desc_check_3 = '".$descCheck3."', 
					desc_check_4 = '".$descCheck4."', checkedby = '".$checkedBy."', 
					incharge = '".$incharge."', remarks = '".$remarks."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getReceivedGoods($deliveryNoteId = '')
{
	$sql = "SELECT 
				h.id, h.deliveryno, DATE_FORMAT(h.dcdate,'%d-%m-%Y') AS dcdt, 
				h.totalamount, h.suppliername, h.customername, h.receivername, 
				SUM(IF(d.is_received = 'yes',d.Nos,0)) AS yes, 
				SUM(IF(d.is_received = 'no',d.Nos,0)) AS nos,
				SUM(IF(d.is_received = 'yes',d.quantity,0)) AS yesquantity, 
				SUM(IF(d.is_received = 'no',d.quantity,0)) AS noquantity, 
				SUM(IF(d.is_received = 'yes',d.quantity,0)) + SUM(IF(d.is_received = 'no',d.quantity,0)) AS totalqty
			FROM
				deliverynote_hdr h
				INNER JOIN 
					(SELECT 
						deliverynoteid,is_received,COUNT(*) AS Nos,
						SUM(quantity) AS quantity
					FROM deliverynote_dtl GROUP BY deliverynoteid,is_received) d 
					ON h.id=d.deliverynoteid
			WHERE h.status <> 'inactive'";
	if($deliveryNoteId > 0)
	{
		$sql .= " AND h.id = $deliveryNoteId";
	}
	$sql .= " GROUP BY h.id";
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveReceivedGoods($barcodeName)
{
	$sql = "UPDATE deliverynote_dtl SET 
				is_received = 'yes' , 
				received_on = NOW(), 
				received_by = '".$this->session->userdata('userid')."'
			WHERE barcode = '".$barcodeName."'";
	$this->db->query($sql);
	
	$sql1 = "SELECT h.id
			FROM 
			deliverynote_hdr h
			INNER JOIN deliverynote_dtl d ON h.id = d.deliverynoteid
			WHERE h.status <> 'inactive'";
	$res1 = $this->db->query($sql1);
	$deliveryNoteId = 0;
	foreach($res1->result() as $row)
	{
		$deliveryNoteId = $row->id;
	}
	
	$res = $this->getReceivedGoods($deliveryNoteId);
	foreach($res as $row)
	{
		if($row->yes > 0 && $row->nos == 0)
		{
			$sql = "UPDATE deliverynote_hdr SET all_items_received = 'yes'
					WHERE id = $deliveryNoteId";
			$this->db->query($sql);
		}
	}
}

public function getRackHeaderDetails($rackDisplayId = '')
{
	$sql = "SELECT *, DATE_FORMAT(entry_date, '%d-%m-%Y') AS entrydate FROM rackhdr 
			WHERE STATUS <> 'inactive'";
	if($rackDisplayId > 0)
	{
		$sql .= " AND id = $rackDisplayId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getRackOrderDetails($rackDisplayId)
{
	$sql = "SELECT d.*
			FROM 
				rackhdr h 
				INNER JOIN rackdtl d ON h.id = d.rackdispid
			WHERE h.status <> 'inactive' AND h.id = $rackDisplayId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getRackProcessInfoDetails($rackDisplayId)
{
	$sql = "SELECT 
				d.rackdispid, d.id AS rackdtlid, d.rackname, 
				IFNULL(p.process1,'') AS process1, 
				IFNULL(p.process2,'') AS process2, 
				IFNULL(p.process3,'') AS process3
			FROM 
				rackhdr h 
				INNER JOIN rackdtl d ON h.id = d.rackdispid
				LEFT OUTER JOIN rackdtl_process p ON h.id = p.rackdispid AND d.id = p.rackdtlid
			WHERE h.status <> 'inactive' AND h.id = $rackDisplayId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveRackDetails($rackDisplayId, $entryDate, $dtlArr, $processInfoArr)
{	
	if($rackDisplayId > 0)
	{
		$sql = "UPDATE rackhdr SET 
					entry_date = '".$entryDate."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $rackDisplayId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO rackhdr SET 
					entry_date = '".$entryDate."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$rackDisplayId = $this->db->insert_id();
	}
	
	$sqlDel = "DELETE FROM rackdtl WHERE rackdispid = $rackDisplayId";
	$this->db->query($sqlDel);

	$sqlDel1 = "DELETE FROM rackdtl_process WHERE rackdispid = $rackDisplayId";
	$this->db->query($sqlDel1);
		
	foreach($dtlArr as $row)
	{
		$remarks = str_replace("'", "\'", $row->remarks);
		
		$sql1 = "INSERT INTO rackdtl SET 
					rackdispid = $rackDisplayId, 
					rackname = '".$row->rackNo."', orderno = '".$row->orderNo."', 
					noofrolls = '".$row->noOfRolls."', barcodeid = '".$row->barcodeId."', 
					lineincharge = '".$row->lineIncharge."', weight = '".$row->weight."', 
					dispatched = '".$row->dispatched."', pending = '".$row->pending."', 
					totalavailstock = '".$row->totalAvailStock."', remarks = '".$remarks."'";
		$this->db->query($sql1);
		$rackDisplayDtlId = $this->db->insert_id();
		
		foreach($processInfoArr as $res)
		{
			if($row->rackNo == $res->rackNo)
			{
				$sql2 = "INSERT INTO rackdtl_process SET 
							rackdispid = '".$rackDisplayId."', 
							rackdtlid = '".$rackDisplayDtlId."', 
							process1 = '".$res->process1."', 
							process2 = '".$res->process2."', 
							process3 = '".$res->process3."'";
				$this->db->query($sql2);
			}
		}
	}
}

public function getEmployeeDetails($empId = '')
{
	$sql = "SELECT * FROM employee WHERE status <> 'inactive'";
	if($empId > 0)
	{
		$sql .= " AND id = $empId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveEmployee($empId, $empNo, $empName)
{
	if($empId > 0)
	{
		$sql = "UPDATE employee SET 
					empno = '".$empNo."', empname = '".$empName."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $empId";
	}
	else
	{
		$sql = "INSERT INTO employee SET 
					empno = '".$empNo."', empname = '".$empName."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getOperationDetails($operationId = '')
{
	$sql = "SELECT * FROM operations WHERE status <> 'inactive'";
	if($operationId > 0)
	{
		$sql .= " AND id = $operationId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveOperation($operationId, $operationName, $operationDesc)
{
	if($operationId > 0)
	{
		$sql = "UPDATE operations SET 
					operationname = '".$operationName."', 
					operationdesc = '".$operationDesc."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $operationId";
	}
	else
	{
		$sql = "INSERT INTO operations SET 
					operationname = '".$operationName."', 
					operationdesc = '".$operationDesc."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getMachineryDetails($machineryId = '')
{
	$sql = "SELECT * FROM machineries WHERE status <> 'inactive'";
	if($machineryId > 0)
	{
		$sql .= " AND id = $machineryId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveMachinery($machineryId, $machineryName, $machineryDesc)
{
	if($machineryId > 0)
	{
		$sql = "UPDATE machineries SET 
					machineryname = '".$machineryName."', 
					machinerydesc = '".$machineryDesc."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $machineryId";
	}
	else
	{
		$sql = "INSERT INTO machineries SET 
					machineryname = '".$machineryName."', 
					machinerydesc = '".$machineryDesc."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getManualWorkDetails($manualWorkId = '')
{
	$sql = "SELECT * FROM manualwork WHERE status <> 'inactive'";
	if($manualWorkId > 0)
	{
		$sql .= " AND id = $manualWorkId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveManualWork($manualWorkId, $manualWorkName, $manualWorkDesc)
{
	if($manualWorkId > 0)
	{
		$sql = "UPDATE manualwork SET 
					manualworkname = '".$manualWorkName."', 
					manualworkdesc = '".$manualWorkDesc."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $manualWorkId";
	}
	else
	{
		$sql = "INSERT INTO manualwork SET 
					manualworkname = '".$manualWorkName."', 
					manualworkdesc = '".$manualWorkDesc."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getEmployeeVsOperationDetails($entryId = '', $entryDate = '', $lineId = '')
{
	$sql = "SELECT 
				h.id, DATE_FORMAT(h.entrydate,'%d/%m/%Y') AS entrydate, 
				h.lineid, lv.line_name, lv.line_location, 
				h.shiftid, s.shiftname, h.styleid, sh.styleno, sh.styledesc, 
				h.tablename, h.empid, e.empname, 
				h.operationid, o.operationname, h.smv, h.targetminutes, h.ot_hours, 
				h.machinaryid, m.machineryname
			FROM 
				employee_vs_operation h
				INNER JOIN employee e ON h.empid = e.id
				INNER JOIN line_vs_style lv ON h.lineid = lv.id
				INNER JOIN shifttiming s ON h.shiftid = s.id
				INNER JOIN style_hdr sh ON h.styleid = sh.id
				INNER JOIN operations o ON h.operationid = o.id
				INNER JOIN machineries m ON h.machinaryid = m.id
			WHERE 
				h.status <> 'inactive' AND s.status <> 'inactive' AND 
				lv.status <> 'inactive' AND o.status <> 'inactive' AND 
				e.status <> 'inactive' AND m.status <> 'inactive' AND 
				sh.status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND h.id = $entryId";	
	}
	if($entryDate != "")
	{
		$sql .= " AND h.entrydate = '".$entryDate."'";
	}
	if($lineId > 0)
	{
		$sql .= " AND h.lineid = '".$lineId."'";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getSMVFromOBSheet($styleId, $operationId, $machinaryId)
{
	$sql = "SELECT h.id, h.targetminutes, d.operationid, d.machine, d.smv
			FROM 
				operationbulletin_hdr h 
				INNER JOIN operationbulletin_operation_dtl d ON h.id = d.bulletinid
			WHERE 
				h.status <> 'inactive' AND h.styleid = $styleId AND 
				d.operationid = $operationId AND d.machine = $machinaryId";
	$res = $this->db->query($sql);
	$smv = 0;
	$totalTargetMin = 0;
	foreach($res->result() as $row)
	{
		$smv = $row->smv;
		$totalTargetMin = $row->targetminutes;
	}
	$data["smv"] = $smv;
	$data["totalTargetMin"] = $totalTargetMin;
	return $data;
}

public function checkEmployeeVsOperationavailability($entryId, $entryDate, $employeeId, $lineId, $shiftId, $tableName)
{
	$sql = "SELECT * FROM employee_vs_operation 
			WHERE 
				status <> 'inactive' AND entrydate = '".$entryDate."' AND 
				empid = '".$employeeId."' AND lineid = '".$lineId."' AND 
				shiftid = '".$shiftId."' AND tablename = '".$tableName."'";
	if($entryId > 0)
	{
		$sql .= " AND id <> $entryId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveEmployeeVsOperation($entryId, $entryDate, $employeeId, $lineId, $shiftId, $styleId, $tableName, $operationId, $machinaryId, $smv, $targetMinutes, $otHours)
{
	if($entryId > 0)
	{
		$sql = "UPDATE employee_vs_operation SET 
					entrydate = '".$entryDate."', 
					empid = '".$employeeId."', 
					lineid = '".$lineId."', 
					shiftid = '".$shiftId."', 
					styleid = '".$styleId."', 
					tablename = '".$tableName."', 
					operationid = '".$operationId."', 
					machinaryid = '".$machinaryId."', 
					smv = '".$smv."', 
					targetminutes = '".$targetMinutes."', 
					ot_hours = '".$otHours."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO employee_vs_operation SET 
					entrydate = '".$entryDate."', 
					empid = '".$employeeId."', 
					lineid = '".$lineId."', 
					shiftid = '".$shiftId."', 
					styleid = '".$styleId."', 
					tablename = '".$tableName."', 
					operationid = '".$operationId."', 
					machinaryid = '".$machinaryId."', 
					smv = '".$smv."', 
					targetminutes = '".$targetMinutes."', 
					ot_hours = '".$otHours."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getSkillMatrix_HdrDetails($skillMatrixId = '')
{
	$sql = "SELECT h.*, DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydate
			FROM 
				skillmatrix_hdr h 
			WHERE h.status <> 'inactive'";
	if($skillMatrixId > 0)
	{
		$sql .= " AND h.id = $skillMatrixId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getSkillMatrix_EmpDetails($skillMatrixId)
{
	$sql = "SELECT d.*
			FROM 
				skillmatrix_hdr h 
				INNER JOIN skillmatrix_dtl d ON h.id = d.skillmatrix_id
			WHERE h.status <> 'inactive' AND h.id = $skillMatrixId";
	$res = $this->db->query($sql);
	return $res->result();
}

/*public function getCurrentTargetMinutes()
{
	$sql = "SELECT TIME_TO_SEC(IF(CURTIME() > '19:00:00', TIMEDIFF('19:00:00', '08:30:00'), TIMEDIFF(CURTIME(), '08:30:00'))) AS actualtime";
	$res = $this->db->query($sql);
	$actualTime = 0;
	foreach($res->result() as $row)
	{
		$actualTime = $row->actualtime;
	}
	return $actualTime;
}*/

public function getPieceLogsDetailsByEmployee($entryDate, $shiftId, $lineName, $empId)
{
	$sql = "SELECT 
				COUNT(*) AS cnt, eo.operationid, eo.smv, 
				TIME_TO_SEC(IF(CURTIME() > '19:00:00', TIMEDIFF('19:00:00', '08:30:00'), TIMEDIFF(CURTIME(), '08:30:00'))) AS actualtime
			FROM 
				piecelogs_hdr h 
				INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
				INNER JOIN employee_vs_operation eo 
					ON DATE_FORMAT(d.in_time,'%Y-%m-%d') = eo.entrydate AND 
					h.lineid = eo.linename AND d.tablename = eo.tablename
			WHERE 
				h.status <> 'inactive' AND eo.status <> 'inactive' AND 
				eo.entrydate = '".$entryDate."' AND h.lineid = '".$lineName."' AND 
				eo.empid = '".$empId."'
			GROUP BY eo.entrydate, eo.linename, d.tablename = eo.tablename";
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkDateLineNameAvailability_SkillMatrix($skillMatrixId, $entryDate, $shiftId, $lineName)
{
	$sql = "SELECT * FROM skillmatrix_hdr 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				shiftid = '".$shiftId."' AND linename = '".$lineName."'";
	if($skillMatrixId > 0)
	{
		$sql .= " AND id <> $skillMatrixId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveSkillMatrix($skillMatrixId, $entryDate, $shiftId, $lineName, $dtlArr)
{
	if($skillMatrixId > 0)
	{
		$sql = "UPDATE skillmatrix_hdr SET 
					entry_date = '".$entryDate."', 
					shiftid = '".$shiftId."', 
					linename = '".$lineName."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $skillMatrixId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO skillmatrix_hdr SET 
					entry_date = '".$entryDate."', 
					shiftid = '".$shiftId."', 
					linename = '".$lineName."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$skillMatrixId = $this->db->insert_id();
	}
	
	$sqlDel = "DELETE FROM skillmatrix_dtl WHERE skillmatrix_id = $skillMatrixId";
	$this->db->query($sqlDel);
	
	foreach($dtlArr as $row)
	{
		$sql1 = "INSERT INTO skillmatrix_dtl SET 
					skillmatrix_id = $skillMatrixId, 
					empid = '".$row->empId."', 
					operationid = '".$row->operationId."', 
					producedmin = '".$row->producedMin."', pieces = '".$row->pieces."', 
					sam = '".$row->sam."', shifthrs = '".$row->shiftHrs."', 
					othours = '".$row->otHours."'";
		$this->db->query($sql1);
	}
}

public function getNoWork_HdrDetails($noWorkId = '')
{
	$sql = "SELECT h.*, DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydate
			FROM 
				nowork_hdr h 
			WHERE h.status <> 'inactive'";
	if($noWorkId > 0)
	{
		$sql .= " AND h.id = $noWorkId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getNoWork_ReasonDetails($noWorkId)
{
	$sql = "SELECT d.*
			FROM 
				nowork_hdr h 
				INNER JOIN nowork_dtl d ON h.id = d.noworkid
			WHERE h.status <> 'inactive' AND h.id = $noWorkId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkDateLineNameAvailability_NoWork($noWorkId, $entryDate, $lineName, $shiftName)
{
	$sql = "SELECT * FROM nowork_hdr 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				linename = '".$lineName."' AND shiftname = '".$shiftName."'";
	if($noWorkId > 0)
	{
		$sql .= " AND id <> $noWorkId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveNoWorkTime($noWorkId, $entryDate, $lineName, $shiftName, $dtlArr)
{
	if($noWorkId > 0)
	{
		$sql = "UPDATE nowork_hdr SET 
					entry_date = '".$entryDate."', 
					linename = '".$lineName."', 
					shiftname = '".$shiftName."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $noWorkId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO nowork_hdr SET 
					entry_date = '".$entryDate."', 
					linename = '".$lineName."', 
					shiftname = '".$shiftName."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$noWorkId = $this->db->insert_id();
	}
	
	$sqlDel = "DELETE FROM nowork_dtl WHERE noworkid = $noWorkId";
	$this->db->query($sqlDel);
	
	foreach($dtlArr as $row)
	{
		$sql1 = "INSERT INTO nowork_dtl SET 
					noworkid = $noWorkId, 
					reason = '".$row->reason."', machinaryid = '".$row->machinaryId."', 
					starttime = '".$row->startTime."', endtime = '".$row->endTime."'";
		$this->db->query($sql1);
	}
}

public function getAssemblyLoadingDetails($assemblyLoadingId = '')
{
	$sql = "SELECT 
				h.*, DATE_FORMAT(h.entry_date,'%d/%m/%Y') AS entrydate, s.shiftname, 
				e.empname AS lineinchargename, lv.line_name, lv.line_location
			FROM 
				assemblyloading h 
				INNER JOIN line_vs_style lv ON h.lineid = lv.id
				INNER JOIN shifttiming s ON h.shiftid = s.id
				INNER JOIN employee e ON h.lineincharge = e.id
			WHERE 
				h.status <> 'inactive' AND lv.status <> 'inactive' AND 
				s.status <> 'inactive' AND e.status <> 'inactive'";
	if($assemblyLoadingId > 0)
	{
		$sql .= " AND h.id = $assemblyLoadingId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkDateLineNameAvailability_AssemblyLoading($assemblyLoadingId, $entryDate, $lineId, $shiftId)
{
	$sql = "SELECT * FROM assemblyloading 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				lineid = '".$lineId."' AND shiftid = '".$shiftId."'";
	if($assemblyLoadingId > 0)
	{
		$sql .= " AND id <> $assemblyLoadingId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function getPieceLogsDetailsByDateLine($entryDate, $lineName, $lineLocation, $shiftId)
{
	$sql = "SELECT * FROM piecelogs_hdr 
			WHERE 
				STATUS <> 'inactive' AND lineid = '".$lineName."' AND 
				linelocation = '".$lineLocation."' AND 
				created_dt = '".$entryDate."'";
	$res = $this->db->query($sql);
	$pieceLogId = 0;
	foreach($res->result() as $row)
	{
		$pieceLogId = $row->id;
	}
	
	$shiftTimings = $this->getShiftTimings($shiftId);
	$shiftFromTiming = '';
	$shiftToTiming = '';
	foreach($shiftTimings as $row)
	{
		$shiftFromTiming = $entryDate.' '.$row->fromtime;
		$shiftToTiming = $entryDate.' '.$row->totime;
	}
	
	$resArr = array();
	$resArr["pieceLogId"] = $pieceLogId;
	$resArr["shiftFromTiming"] = $shiftFromTiming;
	$resArr["shiftToTiming"] = $shiftToTiming;
	return $resArr;
}

public function callPieceLogDetails_Procedure($pieceLogId, $shiftFromTiming, $shiftToTiming)
{
	try
	{
		$this->db->reconnect();
		$sql = "CALL get_piecelogs_dtl('".$pieceLogId."','".$shiftFromTiming."','".$shiftToTiming."')"; 
		$result = $this->db->query($sql,$data);
		$this->db->close();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	return $result->result();
}

public function saveAssemblyLoading($assemblyLoadingId, $entryDate, $lineId, $shiftId, $lineIncharge, $hour1, $hour2, $hour3, $hour4, $hour5, $hour6, $hour7, $hour8, $otHour, $totalPieces, $target, $isTargetAchieved)
{
	if($assemblyLoadingId > 0)
	{
		$sql = "UPDATE assemblyloading SET 
					entry_date = '".$entryDate."', lineid = '".$lineId."', 
					shiftid = '".$shiftId."', lineincharge = '".$lineIncharge."', 
					hour1 = '".$hour1."', hour2 = '".$hour2."', hour3 = '".$hour3."', 
					hour4 = '".$hour4."', hour5 = '".$hour5."', hour6 = '".$hour6."', 
					hour7 = '".$hour7."', hour8 = '".$hour8."', othour = '".$otHour."', 
					totalpieces = '".$totalPieces."', target = '".$target."', 
					is_targetachieved = '".$isTargetAchieved."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $assemblyLoadingId";
	}
	else
	{
		$sql = "INSERT INTO assemblyloading SET 
					entry_date = '".$entryDate."', lineid = '".$lineId."', 
					shiftid = '".$shiftId."', lineincharge = '".$lineIncharge."', 
					hour1 = '".$hour1."', hour2 = '".$hour2."', hour3 = '".$hour3."', 
					hour4 = '".$hour4."', hour5 = '".$hour5."', hour6 = '".$hour6."', 
					hour7 = '".$hour7."', hour8 = '".$hour8."', othour = '".$otHour."', 
					totalpieces = '".$totalPieces."', target = '".$target."', 
					is_targetachieved = '".$isTargetAchieved."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getHourlyProductionLineWiseDetails($lineId = '')
{
	$sql = "SELECT *, DATE_FORMAT(entry_date,'%d-%m-%Y') AS entrydt 
			FROM hourlyproduction_linewise 
			WHERE status <> 'inactive'";
	if($lineId > 0)
	{
		$sql .= " AND id = $lineId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getHourlyProductionLineWise_OperationDetails($entryId)
{
	$sql = "SELECT d.*
			FROM 
				hourlyproduction_linewise h 
				INNER JOIN hourlyproduction_linewise_operations d ON h.id = d.hourly_linewise_id
			WHERE h.status <> 'inactive' AND h.id = $entryId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkDateLineNameAvailability_HourlyProduction_Linewise($lineId, $entryDate, $lineName, $shiftId)
{
	$sql = "SELECT * FROM hourlyproduction_linewise 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				linename = '".$lineName."' AND shiftid = '".$shiftId."'";
	if($lineId > 0)
	{
		$sql .= " AND id <> $lineId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveHourlyProduction_LineWise($entryId, $entryDate, $lineName, $shiftId, $operationId, $noOfWorkers, $daysTarget, $targetPerHour, $noOfOperators, $availMinutes, $currentTarget, $issues, $wip, $idleTime, $breakDownTime, $reworkTime, $noWorkTime, $lineEfficiency)
{
	if($entryId > 0)
	{
		$sql = "UPDATE hourlyproduction_linewise SET 
					entry_date = '".$entryDate."', linename = '".$lineName."', 
					shiftid = '".$shiftId."', 
					no_of_workers = '".$noOfWorkers."', 
					days_target = '".$daysTarget."', 
					target_per_hr = '".$targetPerHour."', 
					no_of_operators = '".$noOfOperators."', 
					avail_min = '".$availMinutes."', 
					current_target = '".$currentTarget."', 
					issues = '".$issues."', wip = '".$wip."', 
					idletime = '".$idleTime."', 
					breakdown_time = '".$breakDownTime."', 
					rework_time = '".$reworkTime."', 
					nowork_time = '".$noWorkTime."', 
					line_efficiency = '".$lineEfficiency."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
		$this->db->query($sql);
		
		$sql = "DELETE FROM hourlyproduction_linewise_operations 
				WHERE hourly_linewise_id = $entryId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO hourlyproduction_linewise SET 
					entry_date = '".$entryDate."', linename = '".$lineName."', 
					shiftid = '".$shiftId."', 
					no_of_workers = '".$noOfWorkers."', 
					days_target = '".$daysTarget."', 
					target_per_hr = '".$targetPerHour."', 
					no_of_operators = '".$noOfOperators."', 
					avail_min = '".$availMinutes."', 
					current_target = '".$currentTarget."', 
					issues = '".$issues."', wip = '".$wip."', 
					idletime = '".$idleTime."', 
					breakdown_time = '".$breakDownTime."', 
					rework_time = '".$reworkTime."', 
					nowork_time = '".$noWorkTime."', 
					line_efficiency = '".$lineEfficiency."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$entryId = $this->db->insert_id();
	}
	
	foreach($operationId as $row)
	{
		$sql = "INSERT INTO hourlyproduction_linewise_operations SET 
					hourly_linewise_id = $entryId, operationid = $row";
		$this->db->query($sql);
	}
}

public function getLineDetails($entryDate, $lineName, $shiftId)
{
	$sql = "SELECT a.entry_date, a.linename, a.shiftid, e.noofworkers, a.target
			FROM 
				assemblyloading a 
				INNER JOIN (SELECT entrydate, linename, shiftid, SUM(1) AS noofworkers
					FROM employee_vs_operation 
					WHERE STATUS <> 'inactive'
					GROUP BY entrydate, linename, shiftid) e 
					ON a.entry_date = e.entrydate AND a.linename = e.linename AND 
					a.shiftid = e.shiftid
			WHERE 
				a.status <> 'inactive' AND a.entry_date = '".$entryDate."' AND 
				a.linename = '".$lineName."' AND a.shiftid = '".$shiftId."'";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getCurrentTargetAchieved($entryDate, $lineName, $shiftId)
{
	$shiftTimings = $this->getShiftTimings($shiftId);
	$shiftFromTiming = '';
	$shiftToTiming = '';
	foreach($shiftTimings as $row)
	{
		$shiftFromTiming = $entryDate.' '.$row->fromtime;
		$shiftToTiming = $entryDate.' '.$row->totime;
	}
	
	$sql = "SELECT h.lineid, SUM(1) AS curtarget
			FROM 
				piecelogs_hdr h 
				INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
			WHERE 
				h.status <> 'inactive' AND 
				d.in_time >= '".$shiftFromTiming."' AND 
				d.out_time <= '".$shiftToTiming."'
			GROUP BY h.id";
	$res = $this->db->query($sql);
	$curTarget = 0;
	foreach($res->result() as $row)
	{
		$curTarget = $row->curtarget;
	}
	return $curTarget;
}

public function getStyleHeaderDetails($styleId = '')
{
	$sql = "SELECT * FROM style_hdr WHERE status <> 'inactive'";
	if($styleId > 0)
	{
		$sql .= " AND id = $styleId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveStyle($styleId, $buyer, $merchant, $styleNo, $styleDesc, $colour, $size, $styleImage)
{
	if($styleId > 0)
	{
		$sql = "UPDATE style_hdr SET 
					buyer = '".$buyer."', 
					merchant = '".$merchant."', 
					styleno = '".$styleNo."', 
					styledesc = '".$styleDesc."', 
					colour = '".$colour."', 
					size = '".$size."', 
					imagepath = '".$styleImage."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $styleId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO style_hdr SET 
					buyer = '".$buyer."', 
					merchant = '".$merchant."', 
					styleno = '".$styleNo."', 
					styledesc = '".$styleDesc."', 
					colour = '".$colour."', 
					size = '".$size."',  
					imagepath = '".$styleImage."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$styleId = $this->db->insert_id();
	}
}

public function getOperationBulletinDetails($bulletinId = '')
{
	$sql = "SELECT 
				h.*, DATE_FORMAT(h.created_on,'%d %b, %Y') AS preparedon, 
				DATE_FORMAT(h.modified_on,'%d %b, %Y') AS revisedon, 
				s.styleno, s.styleno, s.styledesc, u.firstname
			FROM 
				operationbulletin_hdr h 
				INNER JOIN style_hdr s ON h.styleid = s.id 
				INNER JOIN users u ON h.created_by = u.userid
			WHERE 
				h.status <> 'inactive' AND s.status <> 'inactive' AND 
				u.status <> 'inactive'";
	if($bulletinId > 0)
	{
		$sql .= " AND h.id = $bulletinId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function getOperationBulletin_OperationDetails($bulletinId)
{
	$sql = "SELECT d.*, o.operationname
			FROM 
				operationbulletin_hdr h 
				INNER JOIN operationbulletin_operation_dtl d ON h.id = d.bulletinid
				INNER JOIN operations o ON d.operationid = o.id
			WHERE h.status <> 'inactive' AND o.status <> 'inactive' AND h.id = $bulletinId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getOperationBulletin_MachineryDetails($bulletinId)
{
	$sql = "SELECT d.*, m.machineryname
			FROM 
				operationbulletin_hdr h 
				INNER JOIN operationbulletin_machinery_dtl d ON h.id = d.bulletinid
				INNER JOIN machineries m ON d.machinery_requirement = m.id
			WHERE h.status <> 'inactive' AND m.status <> 'inactive' AND h.id = $bulletinId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getOperationBulletin_ManualWorkDetails($bulletinId)
{
	$sql = "SELECT d.*, m.manualworkname
			FROM 
				operationbulletin_hdr h 
				INNER JOIN operationbulletin_manual_dtl d ON h.id = d.bulletinid 
				INNER JOIN manualwork m ON d.manualwork = m.id
			WHERE h.status <> 'inactive' AND m.status <> 'inactive' AND h.id = $bulletinId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveOperationBulletin($bulletinId, $styleId, $targetMinutes, $stdNoOfWorkStations, $stdNoOfOperators, $stdNoOfHelpers, $totalSAM, $machineSAM, $manualSAM, $possibleDailyOutput, $expectedPeakEfficiency, $expectedOutput, $expectedAvgEfficiency, $expectedDailyOutput, $avgOutputPerMachine, $mc_TotalNumbers, $mc_TotalSMV, $mn_TotalNumbers, $mn_TotalSMV, $operationDtlArr, $machineryDtlArr, $manualWorkDtlArr)
{
	$generatedOBName = '';
	$prevOBName = '';
	
	$sql1 = "SELECT obname FROM operationbulletin_hdr ORDER BY id DESC LIMIT 1";
	$res = $this->db->query($sql1);
	$res1 = $res->result();
	
	foreach($res1 as $row)
	{
		$prevOBName = $row->obname;
	}
	
	if($prevOBName != "")
	{
		$totalOBNameLen = 6;
		
		$obNameArr = explode("OB",$prevOBName);
		$obNameArr = $obNameArr[1];
		
		$k = 0;
		for($n=0; $n<strlen($obNameArr); $n++)
		{
			if($obNameArr[$n] == 0)
			{
				$k++;
			}
			else
			{
				break;
			}
		}
		$prevCode = substr($obNameArr, intval($k));
		
		$curCode = intval($prevCode) + 1;
		$curCodeLen = strlen($curCode) + 1;
		$zerosCount = intval($totalOBNameLen) - $curCodeLen;
		$zeroStr = '';
		if($zerosCount > 0)
		{
			for($n=0; $n<$zerosCount; $n++)
			{
				$zeroStr .= "0";
			}
		}
		$generatedOBName = "OB".$zeroStr.$curCode;
	}
	else
	{
		$generatedOBName = "OB00001";
	}
	
	
	$sql = "INSERT INTO operationbulletin_hdr SET 
				obname = '".$generatedOBName."', 
				styleid = '".$styleId."', targetminutes = '".$targetMinutes."', 
				workstations = '".$stdNoOfWorkStations."', 
				operators_in_line = '".$stdNoOfOperators."', 
				helpers_in_line = '".$stdNoOfHelpers."', 
				total_sam = '".$totalSAM."', machine_sam = '".$machineSAM."', 
				manual_sam = '".$manualSAM."', 
				daily_output = '".$possibleDailyOutput."', 
				expected_peak_eff = '".$expectedPeakEfficiency."', 
				expected_output = '".$expectedOutput."', 
				expected_avg_eff = '".$expectedAvgEfficiency."', 
				expected_daily_output = '".$expectedDailyOutput."', 
				avg_output_per_mc = '".$avgOutputPerMachine."', 
				mc_totalnumbers = '".$mc_TotalNumbers."', 
				mc_totalsmv = '".$mc_TotalSMV."', 
				mn_totalnumbers = '".$mn_TotalNumbers."', 
				mn_totalsmv = '".$mn_TotalSMV."', 
				created_on = NOW(), 
				created_by = '".$this->session->userdata('userid')."'";
	$this->db->query($sql);
	$entryId = $this->db->insert_id();
	
	foreach($operationDtlArr as $row)
	{
		$sql = "INSERT INTO operationbulletin_operation_dtl SET 
					bulletinid = $entryId, 
					operationid = '".$row->operationId."', 
					frequency = '".$row->frequency."', 
					machine = '".$row->machine."', 
					smv = '".$row->smv."', 
					no_of_workers = '".$row->noOfWorkers."', 
					balanced_workers = '".$row->balancedWorkers."', 
					sec_per_unit = '".$row->secPerUnit."', 
					folders_clips_guides = '".$row->folders."'";
		$this->db->query($sql);
	}
	
	foreach($machineryDtlArr as $row)
	{
		$sql = "INSERT INTO operationbulletin_machinery_dtl SET 
					bulletinid = $entryId, 
					machinery_requirement = '".$row->machineryRequirement."', 
					numbers = '".$row->numbers."', 
					smv = '".$row->smv."'";
		$this->db->query($sql);
	}
	
	foreach($manualWorkDtlArr as $row)
	{
		$sql = "INSERT INTO operationbulletin_manual_dtl SET 
					bulletinid = $entryId, 
					manualwork = '".$row->manualWork."', 
					numbers = '".$row->numbers."', 
					smv = '".$row->smv."'";
		$this->db->query($sql);
	}
}

public function getHangerDetails($hangerId = '')
{
	$sql = "SELECT * FROM hanger WHERE status <> 'inactive'";
	if($hangerId > 0)
	{
		$sql .= " AND id = $hangerId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveHanger($hangerId, $assertName, $hangerRFID, $hangerName)
{
	if($hangerId > 0)
	{
		$sql = "UPDATE hanger SET 
					assert_name = '".$assertName."', 
					hanger_slno = '".$hangerRFID."', 
					hanger_name = '".$hangerName."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $hangerId";
	}
	else
	{
		$sql = "INSERT INTO hanger SET 
					assert_name = '".$assertName."', 
					hanger_slno = '".$hangerRFID."', 
					hanger_name = '".$hangerName."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getLineVsStyleDetails($entryId = '')
{
	$sql = "SELECT 
				h.*, ob.styleid, ob.obname, 
				DATE_FORMAT(h.entrydate,'%d/%m/%Y') AS entrydt, s.styleno
			FROM 
				line_vs_style h 
				INNER JOIN operationbulletin_hdr ob ON h.obid = ob.id
				INNER JOIN style_hdr s ON ob.styleid = s.id
			WHERE h.status <> 'inactive' AND ob.status <> 'inactive' AND s.status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND h.id = $entryId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveLineVsStyle($entryId, $entryDate, $lineName, $linelocation, $inTable, $outTable, $obId)
{
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	if($entryId > 0)
	{
		$sql = "UPDATE line_vs_style SET 
					entrydate = '".$entryDate."', 
					line_name = '".$lineName."', 
					line_location = '".$linelocation."', 
					intable = '".$inTable."', 
					outtable = '".$outTable."', 
					obid = '".$obId."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO line_vs_style SET 
					entrydate = '".$entryDate."', 
					line_name = '".$lineName."', 
					line_location = '".$linelocation."', 
					intable = '".$inTable."', 
					outtable = '".$outTable."', 
					obid = '".$obId."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getPieceLogsHeaderDetails()
{
	$sql = "SELECT 
				h.id, h.lineid, h.styleid, IFNULL(s.styleno,'') AS styleno, 
				DATE_FORMAT(h.created_dt,'%d-%m-%Y') AS createddt
			FROM 
				piecelogs_hdr h 
				LEFT OUTER JOIN (SELECT * FROM style_hdr WHERE STATUS <> 'inactive') s 
				ON h.styleid = s.id
			WHERE h.status <> 'inactive'";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getPieceLogsDetails($entryId)
{
	$sql = "SELECT 
				h.styleid, IFNULL(s.styleno,'') AS styleno, 
				DATE_FORMAT(h.created_dt,'%d-%m-%Y') AS createddt, d.*
			FROM 
				piecelogs_hdr h 
				LEFT OUTER JOIN (SELECT * FROM style_hdr WHERE STATUS <> 'inactive') s 
				ON h.styleid = s.id
				INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
			WHERE h.status <> 'inactive' AND h.id = $entryId";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getShiftTimings($entryId = '')
{
	$sql = "SELECT * FROM shifttiming WHERE status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND id = $entryId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveShiftTiming($entryId, $shiftName, $fromTime, $toTime)
{
	if($entryId > 0)
	{
		$sql = "UPDATE shifttiming SET 
					shiftname = '".$shiftName."', 
					fromtime = '".$fromTime."', 
					totime = '".$toTime."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO shifttiming SET 
					shiftname = '".$shiftName."', 
					fromtime = '".$fromTime."', 
					totime = '".$toTime."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getTableDetails($entryId = '')
{
	$sql = "SELECT * FROM tablenames WHERE status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND id = $entryId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveTable($entryId, $assertName, $tableName)
{
	if($entryId > 0)
	{
		$sql = "UPDATE tablenames SET 
					table_slno = '".$assertName."', 
					table_name = '".$tableName."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO tablenames SET 
					table_slno = '".$assertName."', 
					table_name = '".$tableName."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getNoWorkDetails($entryId = '')
{
	$sql = "SELECT * FROM nowork WHERE status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND id = $entryId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveNoWork($entryId, $noworkSlNo, $noworkName)
{
	if($entryId > 0)
	{
		$sql = "UPDATE nowork SET 
					nowork_slno = '".$noworkSlNo."', 
					nowork_name = '".$noworkName."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO nowork SET 
					nowork_slno = '".$noworkSlNo."', 
					nowork_name = '".$noworkName."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getReworkDetails($entryId = '')
{
	$sql = "SELECT * FROM rework WHERE status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND id = $entryId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveRework($entryId, $reworkSlNo, $reworkName)
{
	if($entryId > 0)
	{
		$sql = "UPDATE rework SET 
					rework_slno = '".$reworkSlNo."', 
					rework_name = '".$reworkName."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO rework SET 
					rework_slno = '".$reworkSlNo."', 
					rework_name = '".$reworkName."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getBreakdownDetails($entryId = '')
{
	$sql = "SELECT * FROM breakdown WHERE status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND id = $entryId";	
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function saveBreakdown($entryId, $breakdownSlNo, $breakdownName)
{
	if($entryId > 0)
	{
		$sql = "UPDATE breakdown SET 
					breakdown_slno = '".$breakdownSlNo."', 
					breakdown_name = '".$breakdownName."',
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO breakdown SET 
					breakdown_slno = '".$breakdownSlNo."', 
					breakdown_name = '".$breakdownName."',
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getPieceLogsMovements()
{
	$sql = "SELECT h.id, h.lineid, d.tablename, h.created_dt, SUM(1) AS cnt
			FROM 
				piecelogs_hdr h 
				INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
			WHERE h.status <> 'inactive' AND h.created_dt = CURDATE()
			GROUP BY h.lineid, d.tablename, h.created_dt
			ORDER BY h.id, h.lineid, d.tablename, h.created_dt";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getLineWiseDetails()
{
	$sql = "SELECT h.linename, s.shiftname, h.line_efficiency
			FROM 
				hourlyproduction_linewise h 
				INNER JOIN shifttiming s ON h.shiftid = s.id
			WHERE 
				h.status <> 'inactive' AND s.status <> 'inactive' AND 
				h.entry_date = CURDATE()";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getIssueDetails()
{
	$sql = "SELECT 
				h.lineid, h.linelocation, t.table_slno, t.table_name, 
				h.in_time, h.out_time, 
				IF(h.out_time > h.in_time, 'Closed', 'Active') AS issuestatus, 
				DATE_FORMAT(h.created_dt, '%d-%m-%Y') AS createddt
			FROM 
				nowork_breaddown_issues h 
				INNER JOIN tablenames t ON h.tablename = t.table_slno
			WHERE 1=1 AND t.status <> 'inactive'
			ORDER BY h.lineid, h.linelocation, h.created_dt DESC";
	$res = $this->db->query($sql);
	return $res->result();
}

/*Report Starts*/

public function getSkillMatrixReport($fromDate, $toDate, $employeeId)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND eo.entrydate >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND eo.entrydate <= '".$toDate."'";
	}
	if($employeeId > 0)
	{
		$whrStr .= " AND eo.empid = '".$employeeId."'";
	}
	
	$sql = "SELECT 
				eo.id, DATE_FORMAT(eo.entrydate,'%d-%m-%Y') AS entrydt, 
				eo.lineid, ls.line_name, ls.line_location, 
				eo.shiftid, st.shiftname, eo.empid, e.empno, e.empname, 
				eo.styleid, sh.styleno, sh.styledesc, eo.tablename, 
				eo.operationid, o.operationname, o.operationdesc, 
				eo.machinaryid, m.machineryname, m.machinerydesc, 
				eo.smv, eo.targetminutes, eo.ot_hours, 
				SUM(TIME_TO_SEC(IFNULL(p.timetaken,'00:00:00')))/60 AS producedmin, 
				SUM(IF(p.out_time > p.in_time,1,0)) AS output_cnt
			FROM 
				employee_vs_operation eo 
				INNER JOIN employee e ON eo.empid = e.id
				INNER JOIN line_vs_style ls ON eo.lineid = ls.id
				INNER JOIN shifttiming st ON eo.shiftid = st.id
				INNER JOIN style_hdr sh ON eo.styleid = sh.id
				INNER JOIN operations o ON eo.operationid = o.id
				INNER JOIN machineries m ON eo.machinaryid = m.id
				LEFT OUTER JOIN 
					(SELECT 
						h.id, h.lineid, h.linelocation, h.created_dt, 
						d.tablename, d.hanger_id, d.in_time, d.out_time, d.timetaken
					FROM 
						piecelogs_hdr h 
						INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
					WHERE h.status <> 'inactive') p 
					ON ls.line_name = p.lineid AND 
						ls.line_location = p.linelocation AND 
						eo.tablename = p.tablename AND eo.entrydate = p.created_dt
			WHERE 
				eo.status <> 'inactive' AND e.status <> 'inactive' AND 
				ls.status <> 'inactive' AND st.status <> 'inactive' AND 
				sh.status <> 'inactive' AND o.status <> 'inactive' AND 
				m.status <> 'inactive' $whrStr
			GROUP BY eo.entrydate, eo.lineid, eo.empid
			ORDER BY eo.entrydate, eo.lineid, eo.empid";
			
	/*if($filterBy == "EmployeeWise" && $employeeId > 0)
	{
		$whrStr .= " AND d.empid = $employeeId";
	}
	
	if($filterBy == "EmployeeWise")
	{
		$sql = "SELECT 
					DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, h.linename, 
					d.empid, e.empno, e.empname, d.operationid, o.operationname, 
					SUM(d.producedmin) AS producedmin, SUM(d.pieces) AS pieces,
					SUM(d.sam) AS sam, SUM(d.shifthrs) AS shifthrs, 
					SUM(d.othours) AS othours, 
					ROUND(((SUM(d.producedmin) * SUM(d.pieces) * SUM(d.sam))/(SUM(d.shifthrs) + SUM(d.othours))),2) AS efficiency
				FROM 
					skillmatrix_hdr h 
					INNER JOIN skillmatrix_dtl d ON h.id = d.skillmatrix_id
					INNER JOIN employee e ON d.empid = e.id
					INNER JOIN operations o ON d.operationid = o.id
				WHERE 
					h.status <> 'inactive' AND e.status <> 'inactive' AND 
					o.status <> 'inactive' $whrStr
				GROUP BY h.entry_date, h.linename, d.empid";
	}
	else if($filterBy == "OperationWise")
	{
		$sql = "SELECT 
					DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, h.linename, 
					d.operationid, o.operationname, 
					SUM(d.producedmin) AS producedmin, SUM(d.pieces) AS pieces,
					SUM(d.sam) AS sam, SUM(d.shifthrs) AS shifthrs, 
					SUM(d.othours) AS othours, 
					ROUND(((SUM(d.producedmin) * SUM(d.pieces) * SUM(d.sam))/(SUM(d.shifthrs) + SUM(d.othours))),2) AS efficiency
				FROM 
					skillmatrix_hdr h 
					INNER JOIN skillmatrix_dtl d ON h.id = d.skillmatrix_id
					INNER JOIN operations o ON d.operationid = o.id
				WHERE 
					h.status <> 'inactive' AND o.status <> 'inactive' $whrStr
				GROUP BY h.entry_date, h.linename, d.operationid";
	}*/
	$res = $this->db->query($sql);
	return $res->result();
}

public function getIndividualPerformanceReport($fromDate, $toDate, $employeeId)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND h.entry_date >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND h.entry_date <= '".$toDate."'";
	}
	if($employeeId > 0)
	{
		$whrStr .= " AND d.empid = $employeeId";
	}
	
	$sql = "SELECT 
				DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, h.linename, 
				d.empid, e.empno, e.empname, d.operationid, o.operationname, 
				SUM(d.producedmin) AS producedmin, SUM(d.pieces) AS pieces,
				SUM(d.sam) AS sam, SUM(d.shifthrs) AS shifthrs, 
				SUM(d.othours) AS othours, 
				ROUND(((SUM(d.producedmin) * SUM(d.pieces) * SUM(d.sam))/(SUM(d.shifthrs) + SUM(d.othours))),2) AS efficiency, 0 AS amount
			FROM 
				skillmatrix_hdr h 
				INNER JOIN skillmatrix_dtl d ON h.id = d.skillmatrix_id
				INNER JOIN employee e ON d.empid = e.id
				INNER JOIN operations o ON d.operationid = o.id
			WHERE 
				h.status <> 'inactive' AND e.status <> 'inactive' AND 
				o.status <> 'inactive' $whrStr
			GROUP BY h.entry_date, h.linename, d.empid
			ORDER BY d.empid, h.entry_date";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getPriceRateIncentiveReport($fromDate, $toDate, $employeeId)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND h.entry_date >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND h.entry_date <= '".$toDate."'";
	}
	if($employeeId > 0)
	{
		$whrStr .= " AND h.lineincharge = $employeeId";
	}
	
	$sql = "SELECT 
				DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, 
				h.lineid, ls.line_name, ls.line_location, 
				h.shiftid, s.shiftname, h.lineincharge, 
				e.empno, e.empname, h.target, h.totalpieces, 
				IF(h.target - h.totalpieces < 0, h.target, h.totalpieces) AS sewing, 
				IF(h.target - h.totalpieces < 0, ABS(h.target - h.totalpieces), 0) AS incentive, 
				0 AS amount
			FROM 
				assemblyloading h 
				INNER JOIN line_vs_style ls ON h.lineid = ls.id
				INNER JOIN employee e ON h.lineincharge = e.id
				INNER JOIN shifttiming s ON h.shiftid = s.id
			WHERE 
				h.status <> 'inactive' AND e.status <> 'inactive' AND 
				s.status <> 'inactive' $whrStr
			ORDER BY h.entry_date, h.lineid, h.shiftid, h.lineincharge";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getHourlyProductionReport($entryDate, $lineName, $locationName, $shiftId, $employeeId)
{
	$employeeId = $employeeId ? $employeeId : 0;
	
	$resArr = $this->getPieceLogsDetailsByDateLine($entryDate, $lineName, $locationName, $shiftId);
	$pieceLogId = $resArr["pieceLogId"];
	$shiftFromTiming = $resArr["shiftFromTiming"];
	$shiftToTiming = $resArr["shiftToTiming"];
	$empHourlyProdDetails = array();
	if($pieceLogId > 0 && $shiftFromTiming != "" && $shiftToTiming != "")
	{
		$empHourlyProdDetails = $this->callEmployeeWiseHourlyProductionDetails_Procedure($pieceLogId, $shiftFromTiming, $shiftToTiming, $employeeId);
	}
	return $empHourlyProdDetails;
}

public function callEmployeeWiseHourlyProductionDetails_Procedure($pieceLogId, $shiftFromTiming, $shiftToTiming, $employeeId)
{
	try
	{
		$this->db->reconnect();
		$sql = "CALL get_emphourlyproduction('".$pieceLogId."','".$shiftFromTiming."','".$shiftToTiming."','".$employeeId."')"; 
		$result = $this->db->query($sql,$data);
		$this->db->close();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	return $result->result();
}

public function getHourlyProductionLineWiseReport($fromDate, $toDate)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND h.created_dt >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND h.created_dt <= '".$toDate."'";
	}
	$sql = "SELECT 
				DATE_FORMAT(h.created_dt,'%d-%m-%Y') AS createddt, 
				h.lineid, h.linelocation, ls.intable, ls.outtable, 
				h.styleid, IFNULL(s.styleno,'') AS styleno, 
				SUM(IF(d.tablename=ls.intable,1,0)) AS input_cnt,
				SUM(IF(d.tablename=ls.outtable AND d.out_time > d.in_time,1,0)) AS output_cnt, 
				SUM(IF(d.tablename=ls.intable,1,0)) - SUM(IF(d.tablename=ls.outtable AND d.out_time > d.in_time,1,0)) AS wip, 
				IFNULL(n.timings,'') AS timings, IFNULL(n.issuetype,'') AS issuetype, 
				e.noofworkers, obh.total_sam, obh.operators_in_line, obh.helpers_in_line, 
				SUM(TIME_TO_SEC(IFNULL(d.timetaken,'00:00:00')))/60 AS producedmin
			FROM 
				piecelogs_hdr h 
				INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
				INNER JOIN line_vs_style ls 
					ON h.lineid = ls.line_name AND h.linelocation = ls.line_location AND h.created_dt = ls.entrydate
				INNER JOIN operationbulletin_hdr obh ON ls.obid = obh.id
				INNER JOIN 
					(SELECT lineid, entrydate, COUNT(*) AS noofworkers
					FROM employee_vs_operation 
					WHERE STATUS <> 'inactive' 
					GROUP BY lineid, entrydate) e 
					ON ls.id = e.lineid AND h.created_dt = e.entrydate
				LEFT OUTER JOIN 
					(SELECT * FROM style_hdr WHERE STATUS <> 'inactive') s 
					ON h.styleid = s.id
				LEFT OUTER JOIN 
					(SELECT 
						lineid, linelocation, GROUP_CONCAT(issuetype,'') AS issuetype, 
					GROUP_CONCAT(CONCAT(in_time,',',out_time),'') AS timings, created_dt
					FROM nowork_breaddown_issues 
					GROUP BY lineid, linelocation, created_dt
					ORDER BY lineid, linelocation, created_dt) n
					ON h.lineid = n.lineid AND h.linelocation = n.linelocation AND 
					h.created_dt = n.created_dt
			WHERE 
				h.status <> 'inactive' AND ls.status <> 'inactive' AND 
				obh.status <> 'inactive' $whrStr
			GROUP BY h.lineid, h.linelocation, h.created_dt
			ORDER BY h.lineid, h.linelocation, h.created_dt";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getAssemblyLoadingReport($fromDate, $toDate, $employeeId)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND h.entry_date >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND h.entry_date <= '".$toDate."'";
	}
	if($employeeId > 0)
	{
		$whrStr .= " AND h.lineincharge = $employeeId";
	}
	
	$sql = "SELECT 
				DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, 
				h.lineid, ls.line_name, ls.line_location, 
				h.shiftid, s.shiftname, h.lineincharge, 
				e.empno, e.empname, h.target, 
				h.hour1, h.hour2, h.hour3, h.hour4, 
				h.hour5, h.hour6, h.hour7, h.hour8, 
				h.othour, h.totalpieces
			FROM 
				assemblyloading h 
				INNER JOIN line_vs_style ls ON h.lineid = ls.id
				INNER JOIN employee e ON h.lineincharge = e.id
				INNER JOIN shifttiming s ON h.shiftid = s.id
			WHERE 
				h.status <> 'inactive' AND e.status <> 'inactive' AND 
				s.status <> 'inactive' $whrStr
			ORDER BY h.entry_date, h.lineid, h.shiftid, h.lineincharge";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getPiecelogReport($fromDate, $toDate, $lineName)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND h.created_dt >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND h.created_dt <= '".$toDate."'";
	}
	if($lineName != "")
	{
		$whrStr .= " AND h.lineid = '".$lineName."'";
	}
	
	$sql = "SELECT 
				h.id, h.lineid, h.styleid, IFNULL(s.styleno,'') AS styleno, 
				DATE_FORMAT(h.created_dt,'%d-%m-%Y') AS createddt, 
				IFNULL(s.styledesc,'') AS styledesc, d.linename, d.tablename AS tableslno, 
				t.table_name AS table_name, 
				d.hanger_id, d.hanger_name, d.in_time, d.out_time, d.timetaken, 
				IFNULL(tt.timetaken,'') AS timetakenformoving
			FROM 
				piecelogs_hdr h 
				INNER JOIN piecelogs_dtl d ON h.id = d.piecelog_id
				INNER JOIN tablenames t ON d.tablename = t.table_slno
				LEFT OUTER JOIN 
					(SELECT id, styleno, styledesc FROM style_hdr WHERE STATUS <> 'inactive') s 
					ON h.styleid = s.id
				LEFT OUTER JOIN 
				(SELECT 
					t1.id, t1.piecelog_id, t1.tablename, t1.hanger_name, 
					IF(t1.piecelog_id = t2.piecelog_id, 
					IFNULL(TIMEDIFF(t1.in_time, t2.out_time),''), '') AS timetaken
				FROM piecelogs_dtl t1
				LEFT JOIN piecelogs_dtl AS t2 ON t2.id = t1.id - 1
				WHERE 1=1) tt ON h.id = tt.piecelog_id AND d.id = tt.id
			WHERE h.status <> 'inactive' AND t.status <> 'inactive' $whrStr";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getIssuesReport($fromDate, $toDate, $lineName, $issueType)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND h.created_dt >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND h.created_dt <= '".$toDate."'";
	}
	if($lineName != "")
	{
		$whrStr .= " AND h.lineid = '".$lineName."'";
	}
	if($issueType != "")
	{
		$whrStr .= " AND h.issuetype = '".$issueType."'";
	}
	$sql = "SELECT 
				h.lineid, DATE_FORMAT(h.created_dt,'%d-%m-%Y') AS createddt, 
				h.tablename AS tableslno, t.table_name, h.in_time, h.out_time, 
				TIMEDIFF(h.out_time,h.in_time) AS timetaken, h.issuetype
			FROM 
				nowork_breaddown_issues h 
				INNER JOIN tablenames t ON h.tablename = t.table_slno
			WHERE t.status <> 'inactive' $whrStr";
	$res = $this->db->query($sql);
	return $res->result();
}
/*Report Ends*/

/*Common Function Starts*/

public function delEntry($entryId, $tableName, $columnName)
{
	$sql = "UPDATE $tableName SET status = 'inactive' WHERE $columnName = $entryId";
	$this->db->query($sql);
}

public function generateBarcode($code, $barcodeId)
{
	//load library
	$this->load->library('zend');
	//load in folder Zend
	$this->zend->load('Zend/Barcode');
	//generate barcode
	//Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
	
	$imgName = 'barcode_'.$barcodeId.'.png';
	//Save As Image To Folder 
	$img = Zend_Barcode::factory('code128', 'image', array('text'=>$code), array())->draw();
	imagepng($img, 'images/barcodes/'.$imgName);
}

public function initializeCURL($url)
{
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);   
	curl_exec($ch);
	curl_close($ch);
}

/*Common Function Ends*/

/*Manju Ends*/

}