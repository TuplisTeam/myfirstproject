<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adminmodel extends CI_Model 
{

public function __construct()
{
	parent::__construct();
}

/*Manju Starts*/

public function getMenus()
{
	$sql = "SELECT * FROM menu_mas WHERE STATUS <> 'inactive' ORDER BY slno";
	$res = $this->db->query($sql);
	return $res->result();
}

public function checkLogin($email, $password, $store)
{
	$sql = "SELECT *, COUNT(*) AS rows FROM users 
			WHERE email = '".$email."' AND password = '".md5($password)."'";
	$res = $this->db->query($sql);
	
	$rows = 0;
	$status = 0;
	if($res->num_rows() > 0)
	{
		foreach($res->result() as $row)
		{
			$rows = $row->rows;
			$status = $row->status;
			
			if($status == "active")
			{
				$sql = "UPDATE users SET lastlogin = NOW() WHERE email='".$row->email."'";
				$this->db->query($sql);
				
				$userData = array(
		    		'userid' => $row->userid,
					'email' => $row->email,
					'firstname' => $row->firstname,
					'usertype' => $row->usertype,
		    		'status' => $row->status,
					'loggedin'=> TRUE
		    	);
				$this->session->set_userdata($userData);
			}
		}
	}
	
	$resArr["rowCount"] = $rows;
	$resArr["status"] = $status;
	return $resArr;
}

public function getUsers($userId = '')
{
	$userType = $this->session->userdata('usertype');
	
	$sql = "SELECT * FROM users WHERE usertype <> 'admin'";
	if($userType != "admin")
	{
		$sql .= " AND STATUS <> 'inactive' AND userid = '".$this->session->userdata('userid')."'";
	}
	if($userId > 0)
	{
		$sql .= " AND userid = $userId";
	}
	$sql .= " ORDER BY firstname, status";
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

public function saveUser($userId, $userName, $userEmail)
{
	if($userId > 0)
	{
		$sql = "UPDATE users SET 
					email = '".$userEmail."', 
					firstname = '".$userName."', usertype = 'user', 
					modified_on = NOW(), 
					modified_by = '".$this->session->userdata('userid')."'
				WHERE userid = $userId";
		$this->db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO users SET 
					email = '".$userEmail."', password = md5('123'), 
					firstname = '".$userName."', usertype = 'user', 
					created_on = NOW(), 
					created_by = '".$this->session->userdata('userid')."'";
		$this->db->query($sql);
		$userId = $this->db->insert_id();
	}
}

public function updateUserStatus($userId,$status)
{
	$sql = "UPDATE users SET status = '$status' WHERE userid = $userId";
	$this->db->query($sql);
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

public function delBarcode($barcodeId)
{
	$sql = "UPDATE barcode SET status = 'inactive' WHERE id = $barcodeId";
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

public function delDeliveryChallan($deliveryNoteId)
{
	$sql = "UPDATE deliverynote_hdr SET status = 'inactive' WHERE id = $deliveryNoteId";
	$this->db->query($sql);
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

public function delReceptionCheck($receptionCheckId)
{
	$sql = "UPDATE receptioncheck SET status = 'inactive' WHERE id = $receptionCheckId";
	$this->db->query($sql);
}

public function getReceivedGoods($deliveryNoteId = '')
{
	/*$sql = "SELECT 
				h.id, h.deliveryno, DATE_FORMAT(h.dcdate,'%d-%m-%Y') AS dcdt, 
				h.suppliername, h.customername, h.receivername, d.is_received, 
				d.quantity,d.Nos
			FROM
				deliverynote_hdr h
				INNER JOIN 
					(SELECT 
						deliverynoteid,is_received,SUM(quantity) AS quantity,
						SUM(1) AS Nos
					FROM deliverynote_dtl GROUP BY deliverynoteid,is_received) d 
					ON h.id=d.deliverynoteid";*/
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

public function delRackDisplay($rackDisplayId)
{
	$sql = "UPDATE rackhdr SET status = 'inactive' WHERE id = $rackDisplayId";
	$this->db->query($sql);
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

public function delEmployee($empId)
{
	$sql = "UPDATE employee SET status = 'inactive' WHERE id = $empId";
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

public function delOperation($operationId)
{
	$sql = "UPDATE operations SET status = 'inactive' WHERE id = $operationId";
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

public function delMachinery($machineryId)
{
	$sql = "UPDATE machineries SET status = 'inactive' WHERE id = $machineryId";
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

public function saveSkillMatrix($skillMatrixId, $entryDate, $lineName, $dtlArr)
{
	if($skillMatrixId > 0)
	{
		$sql = "UPDATE skillmatrix_hdr SET 
					entry_date = '".$entryDate."', 
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
					empid = '".$row->empId."', operationid = '".$row->operationId."', 
					producedmin = '".$row->producedMin."', pieces = '".$row->pieces."', 
					sam = '".$row->sam."', shifthrs = '".$row->shiftHrs."', 
					othours = '".$row->otHours."'";
		$this->db->query($sql1);
	}
}

public function delSkillMatrix($skillMatrixId)
{
	$sql = "UPDATE skillmatrix_hdr SET status = 'inactive' WHERE id = $skillMatrixId";
	$this->db->query($sql);
}

/*Manju Ends*/

/*Pratheep Starts*/
/*Pratheep Ends*/

}