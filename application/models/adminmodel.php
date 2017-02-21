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

public function checkForgotPassword($email)
{
 	$myURL = base_url().'common/sendForgotPassword/'.$email;
	$this->initializeCURL($myURL);
}

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

public function updatePassword($userId, $newPassword)
{
	$sql = "UPDATE users SET 
				PASSWORD = md5('".mysql_real_escape_string($newPassword)."') 
			WHERE userid = $userId";
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

public function saveDeliveryNote($deliveryNoteId, $deliveryNo, $dcDate, $supplierName, $supplierAddress, $customerName, $receiverName, $totalAmount, $remarks, $dtlArr)
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
					totalamount = '".$totalAmount."', remarks = '".$remarks."', 
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
					totalamount = '".$totalAmount."', remarks = '".$remarks."', 
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

public function getEmployeeVsOperationDetails($entryId = '', $entryDate = '', $lineName = '')
{
	$sql = "SELECT 
				h.id, DATE_FORMAT(h.entrydate,'%d/%m/%Y') AS entrydate, h.linename, 
				h.empid, e.empname, h.operationid, o.operationname, h.smv, 
				h.machinaryid, m.machineryname
			FROM 
				employee_vs_operation h
				INNER JOIN employee e ON h.empid = e.id
				INNER JOIN operations o ON h.operationid = o.id
				INNER JOIN machineries m ON h.machinaryid = m.id
			WHERE 
				h.status <> 'inactive' AND o.status <> 'inactive' AND 
				e.status <> 'inactive' AND m.status <> 'inactive'";
	if($entryId > 0)
	{
		$sql .= " AND h.id = $entryId";	
	}
	if($entryDate != "")
	{
		$sql .= " AND h.entrydate = '".$entryDate."'";
	}
	if($lineName != "")
	{
		$sql .= " AND h.linename = '".$lineName."'";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkEmployeeVsOperationavailability($entryId, $entryDate, $employeeId)
{
	$sql = "SELECT * FROM employee_vs_operation 
			WHERE 
				status <> 'inactive' AND entrydate = '".$entryDate."' AND 
				empid = '".$employeeId."'";
	if($entryId > 0)
	{
		$sql .= " AND id <> $entryId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveEmployeeVsOperation($entryId, $entryDate, $employeeId, $lineName, $operationId, $machinaryId, $smv)
{
	if($entryId > 0)
	{
		$sql = "UPDATE employee_vs_operation SET 
					entrydate = '".$entryDate."', 
					empid = '".$employeeId."', 
					linename = '".$lineName."', 
					operationid = '".$operationId."', 
					machinaryid = '".$machinaryId."', 
					smv = '".$smv."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $entryId";
	}
	else
	{
		$sql = "INSERT INTO employee_vs_operation SET 
					entrydate = '".$entryDate."', 
					empid = '".$employeeId."', 
					linename = '".$lineName."', 
					operationid = '".$operationId."', 
					machinaryid = '".$machinaryId."', 
					smv = '".$smv."', 
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

public function checkDateLineNameAvailability_SkillMatrix($skillMatrixId, $entryDate, $shiftTime, $lineName)
{
	$sql = "SELECT * FROM skillmatrix_hdr 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				shifttime = '".$shiftTime."' AND linename = '".$lineName."'";
	if($skillMatrixId > 0)
	{
		$sql .= " AND id <> $skillMatrixId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveSkillMatrix($skillMatrixId, $entryDate, $shiftTime, $lineName, $dtlArr)
{
	if($skillMatrixId > 0)
	{
		$sql = "UPDATE skillmatrix_hdr SET 
					entry_date = '".$entryDate."', 
					shifttime = '".$shiftTime."', 
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
					shifttime = '".$shiftTime."', 
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
				h.*, DATE_FORMAT(h.entry_date,'%d/%m/%Y') AS entrydate, 
				e.empname AS lineinchargename
			FROM 
				assemblyloading h 
				INNER JOIN employee e ON h.lineincharge = e.id
			WHERE h.status <> 'inactive' AND e.status <> 'inactive'";
	if($assemblyLoadingId > 0)
	{
		$sql .= " AND h.id = $assemblyLoadingId";
	}
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkDateLineNameAvailability_AssemblyLoading($assemblyLoadingId, $entryDate, $lineName, $shiftName)
{
	$sql = "SELECT * FROM assemblyloading 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				linename = '".$lineName."' AND shift = '".$shiftName."'";
	if($assemblyLoadingId > 0)
	{
		$sql .= " AND id <> $assemblyLoadingId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveAssemblyLoading($assemblyLoadingId, $entryDate, $lineName, $shiftName, $lineIncharge, $target, $hour1, $hour2, $hour3, $hour4, $hour5, $hour6, $hour7, $hour8, $otHour, $totalPieces)
{
	if($assemblyLoadingId > 0)
	{
		$sql = "UPDATE assemblyloading SET 
					entry_date = '".$entryDate."', 
					linename = '".$lineName."', 
					shift = '".$shiftName."', 
					lineincharge = '".$lineIncharge."', 
					target = '".$target."', 
					hour1 = '".$hour1."', 
					hour2 = '".$hour2."', 
					hour3 = '".$hour3."', 
					hour4 = '".$hour4."', 
					hour5 = '".$hour5."', 
					hour6 = '".$hour6."', 
					hour7 = '".$hour7."', 
					hour8 = '".$hour8."', 
					othour = '".$otHour."', 
					totalpieces = '".$totalPieces."', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE id = $assemblyLoadingId";
	}
	else
	{
		$sql = "INSERT INTO assemblyloading SET 
					entry_date = '".$entryDate."', 
					linename = '".$lineName."', 
					shift = '".$shiftName."', 
					lineincharge = '".$lineIncharge."', 
					target = '".$target."', 
					hour1 = '".$hour1."', 
					hour2 = '".$hour2."', 
					hour3 = '".$hour3."', 
					hour4 = '".$hour4."', 
					hour5 = '".$hour5."', 
					hour6 = '".$hour6."', 
					hour7 = '".$hour7."', 
					hour8 = '".$hour8."', 
					othour = '".$otHour."', 
					totalpieces = '".$totalPieces."', 
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

public function checkDateLineNameAvailability_HourlyProduction_Linewise($lineId, $entryDate, $lineName, $shiftName)
{
	$sql = "SELECT * FROM hourlyproduction_linewise 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				linename = '".$lineName."' AND shift = '".$shiftName."'";
	if($lineId > 0)
	{
		$sql .= " AND id <> $lineId";
	}
	$res = $this->db->query($sql);
	return $res->num_rows();
}

public function saveHourlyProduction_LineWise($lineId, $entryDate, $lineName, $shiftName, $operationId, $noOfWorkers, $daysTarget, $targetPerHour, $noOfOperators, $availMinutes, $currentTarget, $issues, $wip, $idleTime, $breakDownTime, $reworkTime, $noWorkTime, $lineEfficiency)
{
	if($lineId > 0)
	{
		$sql = "UPDATE hourlyproduction_linewise SET 
					entry_date = '".$entryDate."', linename = '".$lineName."', 
					shift = '".$shiftName."', operationid = '".$operationId."', 
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
				WHERE id = $lineId";
	}
	else
	{
		$sql = "INSERT INTO hourlyproduction_linewise SET 
					entry_date = '".$entryDate."', linename = '".$lineName."', 
					shift = '".$shiftName."', operationid = '".$operationId."', 
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
	}
	$this->db->query($sql);
}

public function getLineDetails($entryDate, $lineName, $shift)
{
	$sql = "SELECT * FROM hourlyproduction_linewise 
			WHERE 
				STATUS <> 'inactive' AND entry_date = '".$entryDate."' AND 
				linename = '".$lineName."' AND shift = '".$shift."'";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getStyleDetails($styleId = '')
{
	$sql = "SELECT * FROM style WHERE status <> 'inactive'";
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
		$sql = "UPDATE style SET 
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
	}
	else
	{
		$sql = "INSERT INTO style SET 
					buyer = '".$buyer."', 
					merchant = '".$merchant."', 
					styleno = '".$styleNo."', 
					styledesc = '".$styleDesc."', 
					colour = '".$colour."', 
					size = '".$size."',  
					imagepath = '".$styleImage."', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
	}
	$this->db->query($sql);
}

public function getOperationBulletinDetails($bulletinId = '')
{
	$sql = "SELECT 
				h.*, DATE_FORMAT(h.created_on,'%d %b, %Y') AS preparedon, 
				DATE_FORMAT(h.modified_on,'%d %b, %Y') AS revisedon, 
				s.styleno, s.styleno, s.styledesc, u.firstname
			FROM 
				operationbulletin_hdr h 
				INNER JOIN style s ON h.styleid = s.id 
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

public function saveOperationBulletin($bulletinId, $styleId, $stdNoOfWorkStations, $stdNoOfOperators, $stdNoOfHelpers, $totalSAM, $machineSAM, $manualSAM, $possibleDailyOutput, $expectedPeakEfficiency, $expectedOutput, $expectedAvgEfficiency, $expectedDailyOutput, $avgOutputPerMachine, $mc_TotalNumbers, $mc_TotalSMV, $mn_TotalNumbers, $mn_TotalSMV, $operationDtlArr, $machineryDtlArr, $manualWorkDtlArr)
{
	$sql = "INSERT INTO operationbulletin_hdr SET 
				styleid = '".$styleId."', workstations = '".$stdNoOfWorkStations."', 
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

/*Common Function Starts*/

public function delEntry($entryId, $tableName, $columnName)
{
	$sql = "UPDATE $tableName SET status = 'inactive' WHERE $columnName = $entryId";
	$this->db->query($sql);
}


/*Common Function Ends*/

/*Report Starts*/

public function getSkillMatrixReport($fromDate, $toDate, $employeeId, $filterBy)
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
	if($filterBy == "EmployeeWise" && $employeeId > 0)
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
	}
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
				DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, h.linename, h.shift, 
				h.lineincharge, e.empno, e.empname, 
				h.target, h.totalpieces, 
				IF(h.target - h.totalpieces < 0, h.target, h.totalpieces) AS sewing, 
				IF(h.target - h.totalpieces < 0, ABS(h.target - h.totalpieces), 0) AS incentive, 
				0 AS amount
			FROM 
				assemblyloading h 
				INNER JOIN employee e ON h.lineincharge = e.id
			WHERE h.status <> 'inactive' AND e.status <> 'inactive' $whrStr
			ORDER BY h.entry_date, h.linename, h.shift, h.lineincharge";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getHourlyProductionReport($fromDate, $toDate, $employeeId)
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
				DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, h.linename, h.shift, 
				h.lineincharge, e.empno, e.empname, 
				h.target, h.hour1, h.hour2, h.hour3, h.hour4, 
				h.hour5, h.hour6, h.hour7, h.hour8, 
				h.othour, h.totalpieces, 
				IF(h.target - h.totalpieces < 0, h.target, h.totalpieces) AS sewing, 
				IF(h.target - h.totalpieces < 0, ABS(h.target - h.totalpieces), 0) AS incentive, 
				0 AS amount
			FROM 
				assemblyloading h 
				INNER JOIN employee e ON h.lineincharge = e.id
			WHERE h.status <> 'inactive' AND e.status <> 'inactive' $whrStr 
			ORDER BY h.entry_date, h.linename, h.shift, h.lineincharge";
	$res = $this->db->query($sql);
	return $res->result();
}

public function getHourlyProductionLineWiseReport($fromDate, $toDate)
{
	$whrStr = '';
	if($fromDate != "")
	{
		$whrStr .= " AND a.entry_date >= '".$fromDate."'";
	}
	if($toDate != "")
	{
		$whrStr .= " AND a.entry_date <= '".$toDate."'";
	}
	
	$sql = "SELECT 
				DATE_FORMAT(a.entry_date,'%d-%m-%Y') AS entrydt, h.linename, h.shift, 
				h.operationid, o.operationname, h.no_of_workers, 
				h.days_target, h.target_per_hr, h.no_of_operators, 
				h.avail_min, h.current_target, h.issues, 
				a.hour1, a.hour2, a.hour3, a.hour4, 
				a.hour5, a.hour6, a.hour7, a.hour8, a.othour, a.totalpieces, 
				h.wip, h.idletime, h.breakdown_time, h.rework_time, 
				h.nowork_time, h.line_efficiency
			FROM 
				hourlyproduction_linewise h 
				INNER JOIN operations o ON h.operationid = o.id
				INNER JOIN assemblyloading a ON h.linename = a.linename AND h.shift = a.shift
			WHERE 
				h.status <> 'inactive' AND o.status <> 'inactive' AND 
				a.status <> 'inactive' $whrStr";
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
				DATE_FORMAT(h.entry_date,'%d-%m-%Y') AS entrydt, h.linename, h.shift, 
				h.lineincharge, e.empno, e.empname, 
				h.hour1, h.hour2, h.hour3, h.hour4, 
				h.hour5, h.hour6, h.hour7, h.hour8, h.othour, h.totalpieces
			FROM 
				assemblyloading h 
				INNER JOIN employee e ON h.lineincharge = e.id
			WHERE h.status <> 'inactive' AND e.status <> 'inactive' $whrStr 
			ORDER BY h.entry_date, h.shift, h.lineincharge";
	$res = $this->db->query($sql);
	return $res->result();
}

/*Report Ends*/

/*Common Function Starts*/

public function generateBarcode($code)
{
	//load library
	$this->load->library('zend');
	//load in folder Zend
	$this->zend->load('Zend/Barcode');
	//generate barcode
	Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
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

/*Pratheep Starts*/
/*Pratheep Ends*/

}