<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{

public function __construct()
{
	parent::__construct();

	$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
	$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
	$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
	$this->output->set_header('Pragma: no-cache');
	
	if(($this->session->userdata('userid') == null) || ($this->session->userdata('userid') == ""))
	{
		redirect(base_url().'login');
	}	
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
}

/*Manju Starts*/

public function index()
{
	$this->load->view('header');
	$this->load->view('dashboard');
	$this->load->view('footer');
}

public function logout($a)
{
	$userData = array();	
	$this->session->set_userdata($userData);
	$this->session->sess_destroy();	
	$this->load->helper('cookie');
	delete_cookie('ci_barcodetracking');	
	redirect(base_url().'login');
}

public function changepassword()
{
	$this->load->view('changepassword');
}

public function updatePassword()
{
	$userId = $this->session->userdata('userid');
	$oldPassword = $this->input->post('oldPassword');
	$newPassword = $this->input->post('newPassword');
	
	if($newPassword != "")
	{
		$result = $this->adminmodel->getUsers($userId);
		$checkPassword = '';
		foreach($result as $row)
		{
			$checkPassword = $row->password;
		}
		
		if($checkPassword == md5($oldPassword))
		{
			$this->adminmodel->updatePassword($userId, $newPassword);
			$data["isError"] = FALSE;
			$data["msg"] = "Password Updated Successfully...";
		}
		else
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Your Old Password is Wrong. Please Check.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Your Password Should Not Be Empty. Please Check.";
	}
	echo json_encode($data);
}

public function users($userId = '')
{	
	$data['userId'] = $userId;
	$data['userName'] = '';
	$data['userEmail'] = '';
	
	$allUsers = $this->adminmodel->getUsers($userId);	
	if($userId > 0)
	{
		foreach($allUsers as $row)
		{
			$data['userName'] = $row->firstname;
			$data['userEmail'] = $row->email;
		}
	}
	else
	{
		$data['allUsers'] = $allUsers;
	}
	
	$this->load->view('header');
	$this->load->view('users',$data);
	$this->load->view('footer');
}

public function saveUser()
{
	$userId = $this->input->post('userId');
	$userName = $this->input->post('userName');
	$userEmail = $this->input->post('userEmail');
	
	if($userName != "" && $userEmail != "")
	{
		$resCheck = $this->adminmodel->checkUserAvailability($userId, $userEmail);
		if($resCheck > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "User Already Exists.";
		}
		else
		{
			$res = $this->adminmodel->saveUser($userId, $userName, $userEmail);
			if($userId > 0)
			{
				$data["isError"] = FALSE;
				$data["msg"] = "User Updated Successfully.";
			}
			else
			{
				$data["isError"] = FALSE;
				$data["msg"] = "User Created Successfully.";
			}
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please fill all fields";
	}
	echo json_encode($data);
}

public function delUser()
{
	$userId = $this->input->post('userId');
	
	if($userId > 0)
	{
		$this->adminmodel->updateUserStatus($userId,'inactive');
		
		$data["isError"] = FALSE;
		$data["msg"] = "User Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please fill all fields";
	}
	echo json_encode($data);
}

public function barcodegeneration($barcodeId = '')
{
	$data["barcodeId"] = $barcodeId;
	
	$data["barcodeName"] = '';
	$data["receiptDate"] = '';
	$data["orderNo"] = '';
	$data["process"] = '';
	$data["style"] = '';
	$data["item"] = '';
	$data["rate"] = '';
	$data["buyerName"] = '';
	$data["color"] = '';
	$data["size"] = '';
	
	$data["barcodeDtls"] = array();
	
	$res = $this->adminmodel->getBarcodeDetails($barcodeId);
	
	if($barcodeId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["barcodeName"] = $row->barcode;
				$data["receiptDate"] = $row->receiptdt;
				$data["orderNo"] = $row->orderno;
				$data["process"] = $row->processname;
				$data["style"] = $row->style;
				$data["item"] = $row->itemname;
				$data["rate"] = $row->rate;
				$data["buyerName"] = $row->buyername;
				$data["color"] = $row->color;
				$data["size"] = $row->size;
			}
		}
	}
	else
	{
		$data["barcodeDtls"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('barcodegeneration', $data);
	$this->load->view('footer');
}

public function checkBarcodeAvailability()
{
	$barcodeId = $this->input->post('barcodeId');
	$barcodeName = $this->input->post('barcodeName');
	if($barcodeName != "")
	{
		$count = $this->adminmodel->checkBarcodeAvailability($barcodeId, $barcodeName);
		if($count > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Barcode Already Available.";
		}
		else
		{
			$data["isError"] = FALSE;
			$data["msg"] = "";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Fields";
	}
	echo json_encode($data);
}

public function saveBarcodeGeneration()
{
	$barcodeId = $this->input->post('barcodeId');
	$barcodeName = $this->input->post('barcodeName');
	$receiptDate = $this->input->post('receiptDate');
	$orderNo = $this->input->post('orderNo');
	$process = $this->input->post('process');
	$style = $this->input->post('style');
	$item = $this->input->post('item');
	$rate = $this->input->post('rate');
	$buyerName = $this->input->post('buyerName');
	$color = $this->input->post('color');
	$size = $this->input->post('size');
	
	$receiptDate = substr($receiptDate,6,4).'-'.substr($receiptDate,3,2).'-'.substr($receiptDate,0,2);
	
	if($barcodeName != "" && $receiptDate != "" && $orderNo != "" && $process != "" && $style != "" && $item != "" && $rate != "" && $buyerName != "" && $color != "" && $size != "")
	{
		$count = $this->adminmodel->checkReceiptDateAvailability($barcodeId, $receiptDate);
		if($count > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Receipt Date Already Available.";
		}
		else
		{
			$this->adminmodel->saveBarcodeGeneration($barcodeId, $barcodeName, $receiptDate, $orderNo, $process, $style, $item, $rate, $buyerName, $color, $size);
			
			if($barcodeId > 0)
			{
				$data["isError"] = FALSE;
				$data["msg"] = "Barcode Details Updated Successfully.";
			}
			else
			{
				$data["isError"] = FALSE;
				$data["msg"] = "Barcode Generated Successfully.";
			}
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Fields";
	}
	echo json_encode($data);
}

public function delBarcode()
{
	$barcodeId = $this->input->post('barcodeId');
	if($barcodeId > 0)
	{
		$this->adminmodel->delBarcode($barcodeId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Barcode Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function printBarcode($barcodeId, $isprint = FALSE)
{
	$data["barcodeId"] = $barcodeId;
	$data["isprint"] = $isprint;
	
	$data["barcodeName"] = '';
	$data["receiptDate"] = '';
	$data["orderNo"] = '';
	$data["process"] = '';
	$data["style"] = '';
	$data["item"] = '';
	$data["rate"] = '';
	$data["buyerName"] = '';
	$data["color"] = '';
	$data["size"] = '';
	
	$res = $this->adminmodel->getBarcodeDetails($barcodeId);
	
	if($barcodeId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["barcodeName"] = $row->barcode;
				$data["receiptDate"] = $row->receiptdt;
				$data["orderNo"] = $row->orderno;
				$data["process"] = $row->processname;
				$data["style"] = $row->style;
				$data["item"] = $row->itemname;
				$data["rate"] = $row->rate;
				$data["buyerName"] = $row->buyername;
				$data["color"] = $row->color;
				$data["size"] = $row->size;
			}
		}
	}
	
	if($isprint)
	{	
		$str = '';
		$str .= $this->load->view('print/barcode', $data,TRUE);
		
		$this->downloadAsPDF($str);
	}		
	else
	{
		$this->load->view('print/barcode', $data);
	}
}

public function deliverynote($deliveryNoteId = '')
{
	$data["deliveryNoteId"] = $deliveryNoteId;
	
	$data["deliveryNo"] = '';
	$data["dcDate"] = '';
	$data["supplierName"] = '';
	$data["supplierAddress"] = '';
	$data["customerName"] = '';
	$data["receiverName"] = '';
	$data["totalAmount"] = '';
	$data["remarks"] = '';
	
	$data["deliveryNoteHdrDtls"] = array();
	$data["itemDtls"] = array();
	
	$res = $this->adminmodel->getDeliveryNoteHeaderDetails($deliveryNoteId);
	
	if($deliveryNoteId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["deliveryNo"] = $row->deliveryno;
				$data["dcDate"] = $row->dcdt;
				$data["supplierName"] = $row->suppliername;
				$data["supplierAddress"] = $row->supplieraddress;
				$data["customerName"] = $row->customername;
				$data["receiverName"] = $row->receivername;
				$data["totalAmount"] = $row->totalamount;
				$data["remarks"] = $row->remarks;
				
				$data["itemDtls"] = $this->adminmodel->getDeliveryNoteItemDetails($deliveryNoteId, 'no');
			}
		}
	}
	else
	{
		$data["deliveryNoteHdrDtls"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('deliverynote', $data);
	$this->load->view('footer');
}

public function getBarcodeDetails()
{
	$barcode = $this->input->post('barcode');
	if($barcode > 0)
	{
		$res = $this->adminmodel->getBarcodeDetails('', $barcode);
		
		$data["res"] = $res;
		
		$data["isError"] = FALSE;
		$data["msg"] = "Delivery Challan Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function saveDeliveryNote()
{
	$deliveryNoteId = $this->input->post('deliveryNoteId');
	$deliveryNo = $this->input->post('deliveryNo');
	$dcDate = $this->input->post('dcDate');
	$supplierName = $this->input->post('supplierName');
	$supplierAddress = $this->input->post('supplierAddress');
	$customerName = $this->input->post('customerName');
	$receiverName = $this->input->post('receiverName');
	$totalAmount = $this->input->post('totalAmount');
	$remarks = $this->input->post('remarks');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$dcDate = substr($dcDate,6,4).'-'.substr($dcDate,3,2).'-'.substr($dcDate,0,2);
	
	if($deliveryNo != "" && $dcDate != "" && $supplierName != "" && $supplierAddress != "" && $customerName != "" && $receiverName != "" && $totalAmount > 0 && count($dtlArr) > 0)
	{
		$this->adminmodel->saveDeliveryNote($deliveryNoteId, $deliveryNo, $dcDate, $supplierName, $supplierAddress, $customerName, $receiverName, $totalAmount, $remarks, $dtlArr);
		
		if($deliveryNo > 0)
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Delivery Note Details Updated Successfully";
		}
		else
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Delivery Note Details Saved Successfully";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delDeliveryChallan()
{
	$deliveryNoteId = $this->input->post('deliveryNoteId');
	if($deliveryNoteId > 0)
	{
		$this->adminmodel->delDeliveryChallan($deliveryNoteId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Delivery Challan Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function printDeliveryNote($deliveryNoteId, $isprint=FALSE)
{
	$data["deliveryNoteId"] = $deliveryNoteId;
	$data["isprint"] = $isprint;
	
	$data["deliveryNo"] = '';
	$data["dcDate"] = '';
	$data["supplierName"] = '';
	$data["supplierAddress"] = '';
	$data["customerName"] = '';
	$data["receiverName"] = '';
	$data["totalAmount"] = '';
	$data["remarks"] = '';
	$data["itemDtls"] = array();
	
	$res = $this->adminmodel->getDeliveryNoteHeaderDetails($deliveryNoteId);
	
	if(count($res) > 0)
	{
		foreach($res as $row)
		{
			$data["deliveryNo"] = $row->deliveryno;
			$data["dcDate"] = $row->dcdt;
			$data["supplierName"] = $row->suppliername;
			$data["supplierAddress"] = $row->supplieraddress;
			$data["customerName"] = $row->customername;
			$data["receiverName"] = $row->receivername;
			$data["totalAmount"] = $row->totalamount;
			$data["remarks"] = $row->remarks;
			$data["itemDtls"] = $this->adminmodel->getDeliveryNoteItemDetails($deliveryNoteId);
		}
	}
	
	if($isprint)
	{	
		$str = '';
		$str .= $this->load->view('print/deliverynote', $data,TRUE);
		
		$this->downloadAsPDF($str);
	}		
	else
	{
		$this->load->view('print/deliverynote', $data);	
	}
}

public function receptioncheck($receptionCheckId = '')
{
	$data["receptionCheckId"] = $receptionCheckId;
	
	$data["fromName"] = '';
	$data["toName"] = '';
	$data["dcNo"] = '';
	$data["lotNo"] = '';
	$data["vehicleNo"] = '';
	$data["rcDate"] = '';
	$data["unitName"] = '';
	$data["descCheck1"] = '';
	$data["descCheck2"] = '';
	$data["descCheck3"] = '';
	$data["descCheck4"] = '';
	$data["checkedBy"] = '';
	$data["incharge"] = '';
	$data["remarks"] = '';
	
	$data["dcDtls"] = $this->adminmodel->getDeliveryNoteHeaderDetails();
	
	$data["receptionCheckDtls"] = array();
	
	$res = $this->adminmodel->getReceptionCheckDetails($receptionCheckId);
	
	if($receptionCheckId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["fromName"] = $row->fromname;
				$data["toName"] = $row->toname;
				$data["dcNo"] = $row->dcno;
				$data["lotNo"] = $row->lotno;
				$data["vehicleNo"] = $row->vehicleno;
				$data["rcDate"] = $row->rcdt;
				$data["unitName"] = $row->unitname;
				$data["descCheck1"] = $row->desc_check_1;
				$data["descCheck2"] = $row->desc_check_2;
				$data["descCheck3"] = $row->desc_check_3;
				$data["descCheck4"] = $row->desc_check_4;
				$data["checkedBy"] = $row->checkedby;
				$data["incharge"] = $row->incharge;
				$data["remarks"] = $row->remarks;
			}
		}
	}
	else
	{
		$data["receptionCheckDtls"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('receptioncheck', $data);
	$this->load->view('footer');
}

public function saveReceptionCheck()
{
	$receptionCheckId = $this->input->post('receptionCheckId');
	$fromName = $this->input->post('fromName');
	$toName = $this->input->post('toName');
	$dcNo = $this->input->post('dcNo');
	$lotNo = $this->input->post('lotNo');
	$vehicleNo = $this->input->post('vehicleNo');
	$rcDate = $this->input->post('rcDate');
	$unitName = $this->input->post('unitName');
	$descCheck1 = $this->input->post('descCheck1');
	$descCheck2 = $this->input->post('descCheck2');
	$descCheck3 = $this->input->post('descCheck3');
	$descCheck4 = $this->input->post('descCheck4');
	$checkedBy = $this->input->post('checkedBy');
	$incharge = $this->input->post('incharge');
	$remarks = $this->input->post('remarks');
	
	$rcDate = substr($rcDate,6,4).'-'.substr($rcDate,3,2).'-'.substr($rcDate,0,2);
	
	if($fromName != "" && $toName != "" && $dcNo > 0 && $lotNo != "" && $vehicleNo != "" && $rcDate != "" && $unitName != "" && $checkedBy != "" && $incharge != "")
	{
		$this->adminmodel->saveReceptionCheck($receptionCheckId, $fromName, $toName, $dcNo, $lotNo, $vehicleNo, $rcDate, $unitName, $descCheck1, $descCheck2, $descCheck3, $descCheck4, $checkedBy, $incharge, $remarks);
		
		if($deliveryNo > 0)
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Delivery Note Details Updated Successfully";
		}
		else
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Delivery Note Details Saved Successfully";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delReceptionCheck()
{
	$receptionCheckId = $this->input->post('receptionCheckId');
	if($receptionCheckId > 0)
	{
		$this->adminmodel->delReceptionCheck($receptionCheckId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Reception Check Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function printReceptionCheck($receptionCheckId, $isprint = FALSE)
{
	$data["receptionCheckId"] = $receptionCheckId;
	$data["isprint"] = $isprint;
	
	$data["fromName"] = '';
	$data["toName"] = '';
	$data["dcNo"] = '';
	$data["dcName"] = '';
	$data["lotNo"] = '';
	$data["vehicleNo"] = '';
	$data["rcDate"] = '';
	$data["unitName"] = '';
	$data["descCheck1"] = '';
	$data["descCheck2"] = '';
	$data["descCheck3"] = '';
	$data["descCheck4"] = '';
	$data["checkedBy"] = '';
	$data["incharge"] = '';
	$data["remarks"] = '';
	
	$res = $this->adminmodel->getReceptionCheckDetails($receptionCheckId);
	
	if(count($res) > 0)
	{
		foreach($res as $row)
		{
			$data["fromName"] = $row->fromname;
			$data["toName"] = $row->toname;
			$data["dcNo"] = $row->dcno;
			$data["dcName"] = $row->deliveryno;
			$data["lotNo"] = $row->lotno;
			$data["vehicleNo"] = $row->vehicleno;
			$data["rcDate"] = $row->rcdt;
			$data["unitName"] = $row->unitname;
			$data["descCheck1"] = $row->desc_check_1;
			$data["descCheck2"] = $row->desc_check_2;
			$data["descCheck3"] = $row->desc_check_3;
			$data["descCheck4"] = $row->desc_check_4;
			$data["checkedBy"] = $row->checkedby;
			$data["incharge"] = $row->incharge;
			$data["remarks"] = $row->remarks;
		}
	}
	
	if($isprint)
	{	
		$str = '';
		$str .= $this->load->view('print/receptioncheck', $data,TRUE);
		
		$this->downloadAsPDF($str);
	}		
	else
	{
		$this->load->view('print/receptioncheck', $data);
	}
}

public function scan()
{
	$res = $this->adminmodel->getDeliveryNoteItemDetails();
	$data["dcDtls"] = $res;
	$data["page"] = "scan";
	
	$this->load->view('header');
	$this->load->view('scansearch', $data);
	$this->load->view('footer');
}

public function search()
{
	$res = $this->adminmodel->getDeliveryNoteItemDetails();
	$data["dcDtls"] = $res;
	$data["page"] = "search";
	
	$this->load->view('header');
	$this->load->view('scansearch', $data);
	$this->load->view('footer');
}

public function receivedgoods()
{
	$res = $this->adminmodel->getReceivedGoods();
	$data["rcDtls"] = $res;
	
	$this->load->view('header');
	$this->load->view('receivedgoods', $data);
	$this->load->view('footer');
}

public function saveReceivedGoods()
{
	$barcodeName = $this->input->post('barcodeName');
	if($barcodeName != "")
	{
		$this->adminmodel->saveReceivedGoods($barcodeName);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Goods Received Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function rackdisplay($rackDisplayId = '')
{
	$data["rackDisplayId"] = $rackDisplayId;
	
	$data["entryDate"] = "";
	$data["dtlArr"] = array();
	
	$data["rackDisplayDtls"] = array();
	$data["processDtlsArr"] = array();
	
	$res = $this->adminmodel->getRackHeaderDetails($rackDisplayId);
	
	if($rackDisplayId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["entryDate"] = $row->entrydate;
				$data["dtlArr"] = $this->adminmodel->getRackOrderDetails($rackDisplayId);
				$data["processDtlsArr"] = $this->adminmodel->getRackProcessInfoDetails($rackDisplayId);
			}
		}
	}
	else
	{
		$data["rackDisplayDtls"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('rackdisplay', $data);
	$this->load->view('footer');
}

public function saveRackDetails()
{
	$rackDisplayId = $this->input->post('rackDisplayId');
	$entryDate = $this->input->post('entryDate');
	$dtlArr = $this->input->post('dtlArr');
	$processInfoArr = $this->input->post('processInfoArr');
	$dtlArr = json_decode($dtlArr);
	$processInfoArr = json_decode($processInfoArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	if($entryDate != "" && count($dtlArr) > 0)
	{
		$this->adminmodel->saveRackDetails($rackDisplayId, $entryDate, $dtlArr, $processInfoArr);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Rack Details Updated Successfully.";
	}
	else
	{
		$data["isError"] = FALSE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delRackDisplay()
{
	$rackDisplayId = $this->input->post('rackDisplayId');
	if($rackDisplayId > 0)
	{
		$this->adminmodel->delRackDisplay($rackDisplayId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Rack Display Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function employee($empId = '')
{
	$data["empId"] = $empId;
	
	$data["empNo"] = '';
	$data["empName"] = '';
	
	$res = $this->adminmodel->getEmployeeDetails($empId);
	
	if($empId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["empNo"] = $row->empno;
				$data["empName"] = $row->empname;
			}
		}
	}
	else
	{
		$data["allEmployees"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('employee', $data);
	$this->load->view('footer');
}

public function saveEmployee()
{
	$empId = $this->input->post('empId');
	$empNo = $this->input->post('empNo');
	$empName = $this->input->post('empName');
	
	if($empNo != "" && $empName != "")
	{
		$this->adminmodel->saveEmployee($empId, $empNo, $empName);
		
		$data["isError"] = FALSE;
		if($empId > 0)
		{
			$data["msg"] = "Employee Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Employee Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delEmployee()
{
	$empId = $this->input->post('empId');
	
	if($empId > 0)
	{
		$this->adminmodel->delEmployee($empId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Employee Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function operation($operationId = '')
{
	$data["operationId"] = $operationId;
	
	$data["operationName"] = '';
	$data["operationDesc"] = '';
	
	$res = $this->adminmodel->getOperationDetails($operationId);
	
	if($operationId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["operationName"] = $row->operationname;
				$data["operationDesc"] = $row->operationdesc;
			}
		}
	}
	else
	{
		$data["allOperations"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('operations', $data);
	$this->load->view('footer');
}

public function saveOperation()
{
	$operationId = $this->input->post('operationId');
	$operationName = $this->input->post('operationName');
	$operationDesc = $this->input->post('operationDesc');
	
	if($operationName != "")
	{
		$this->adminmodel->saveOperation($operationId, $operationName, $operationDesc);
		
		$data["isError"] = FALSE;
		if($operationId > 0)
		{
			$data["msg"] = "Operation Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Operation Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delOperation()
{
	$operationId = $this->input->post('operationId');
	
	if($operationId > 0)
	{
		$this->adminmodel->delOperation($operationId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Operation Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function machinery($machineryId = '')
{
	$data["machineryId"] = $machineryId;
	
	$data["machineryName"] = '';
	$data["machineryDesc"] = '';
	
	$res = $this->adminmodel->getMachineryDetails($machineryId);
	
	if($machineryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["machineryName"] = $row->machineryname;
				$data["machineryDesc"] = $row->machinerydesc;
			}
		}
	}
	else
	{
		$data["allMachineries"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('machineries', $data);
	$this->load->view('footer');
}

public function saveMachinery()
{
	$machineryId = $this->input->post('machineryId');
	$machineryName = $this->input->post('machineryName');
	$machineryDesc = $this->input->post('machineryDesc');
	
	if($machineryName != "")
	{
		$this->adminmodel->saveMachinery($machineryId, $machineryName, $machineryDesc);
		
		$data["isError"] = FALSE;
		if($machineryId > 0)
		{
			$data["msg"] = "Machinery Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Machinery Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delMachinery()
{
	$machineryId = $this->input->post('machineryId');
	
	if($machineryId > 0)
	{
		$this->adminmodel->delMachinery($machineryId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Machinery Details Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function skillmatrix($skillMatrixId = '')
{
	$data["skillMatrixId"] = $skillMatrixId;
	
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	$data["operationDtls"] = $this->adminmodel->getOperationDetails();
	
	$res = $this->adminmodel->getSkillMatrix_HdrDetails($skillMatrixId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["dtlArr"] = array();
	
	if($skillMatrixId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["dtlArr"] = $this->adminmodel->getSkillMatrix_EmpDetails($skillMatrixId);
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('skillmatrix', $data);
	$this->load->view('footer');
}

public function saveSkillMatrix()
{
	$skillMatrixId = $this->input->post('skillMatrixId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	if($entryDate != "" && $lineName != "" && count($dtlArr) > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_SkillMatrix($skillMatrixId, $entryDate, $lineName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "This Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveSkillMatrix($skillMatrixId, $entryDate, $lineName, $dtlArr);
		
			$data["isError"] = FALSE;
			if($skillMatrixId > 0)
			{
				$data["msg"] = "Skill Matrix Updated Successfully.";
			}
			else
			{
				$data["msg"] = "Skill Matrix Created Successfully.";
			}
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delSkillMatrix()
{
	$skillMatrixId = $this->input->post('skillMatrixId');
	
	if($skillMatrixId > 0)
	{
		$this->adminmodel->delSkillMatrix($skillMatrixId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Skill Matrix Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function noworktime($noWorkId = '')
{
	$data["noWorkId"] = $noWorkId;
	
	$res = $this->adminmodel->getNoWork_HdrDetails($noWorkId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["dtlArr"] = array();
	
	if($noWorkId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["dtlArr"] = $this->adminmodel->getNoWork_ReasonDetails($noWorkId);
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('noworktime', $data);
	$this->load->view('footer');
}

public function saveNoWorkTime()
{
	$noWorkId = $this->input->post('noWorkId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	if($entryDate != "" && $lineName != "" && count($dtlArr) > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_NoWork($noWorkId, $entryDate, $lineName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "This Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveNoWorkTime($noWorkId, $entryDate, $lineName, $dtlArr);
			
			$data["isError"] = FALSE;
			if($noWorkId > 0)
			{
				$data["msg"] = "No Work Time Updated Successfully.";
			}
			else
			{
				$data["msg"] = "No Work Time Created Successfully.";
			}
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delNoWorkTime()
{
	$noWorkId = $this->input->post('noWorkId');
	
	if($noWorkId > 0)
	{
		$this->adminmodel->delNoWorkTime($noWorkId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "No Work Time Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function assemblyloading($assemblyLoadingId = '')
{
	$data["assemblyLoadingId"] = $assemblyLoadingId;
	
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	$res = $this->adminmodel->getAssemblyLoading_HdrDetails($assemblyLoadingId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["dtlArr"] = array();
	
	if($assemblyLoadingId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["dtlArr"] = $this->adminmodel->getAssemblyLoading_EmpDetails($assemblyLoadingId);
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('assemblyloading', $data);
	$this->load->view('footer');
}

public function saveAssemblyLoading()
{
	$assemblyLoadingId = $this->input->post('assemblyLoadingId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	if($entryDate != "" && $lineName != "" && count($dtlArr) > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_AssemblyLoading($assemblyLoadingId, $entryDate, $lineName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveAssemblyLoading($assemblyLoadingId, $entryDate, $lineName, $dtlArr);
		
			$data["isError"] = FALSE;
			if($assemblyLoadingId > 0)
			{
				$data["msg"] = "Assembly Loading Updated Successfully.";
			}
			else
			{
				$data["msg"] = "Assembly Loading Created Successfully.";
			}
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function delAssemblyLoading()
{
	$entryId = $this->input->post('entryId');
	
	if($entryId > 0)
	{
		$this->adminmodel->delAssemblyLoading($entryId);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Assembly Loading Removed Successfully.";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

/*Report Starts*/

public function skillmatrixreport()
{
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	
	$this->load->view('header');
	$this->load->view('reports/skillmatrix', $data);
	$this->load->view('footer');
}

public function getSkillMatrixReport()
{
	$fromDate = $this->input->post('fromDate');
	$toDate = $this->input->post('toDate');
	$employeeId = $this->input->post('employeeId');
	$filterBy = $this->input->post('filterBy');
	
	if($fromDate != "")
	{
		$fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
	}
	if($toDate != "")
	{
		$toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
	}
	
	$exportAsCSV = $this->input->post('checkValue');
	
	$data["title"] = "SKILL MATRIX REPORT";
	$data["subtitle"] = "Skill Matrix Report";
	$data["filterBy"] = $filterBy;
	
	$res = $this->adminmodel->getSkillMatrixReport($fromDate, $toDate, $employeeId, $filterBy);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name";
		if($filterBy == "EmployeeWise")
		{
			$str .= ",Employee No.,Employee Name";
		}
		$str .= ",Operation Name,Produced Minutes,Pieces,SAM,Shift Hours,OT Hours,Efficiency\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				if($filterBy == "EmployeeWise")
				{
					$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->empno.'","'.$row->empname.'","'.$row->operationname.'","'.$row->producedmin.'","'.$row->pieces.'","'.$row->sam.'","'.$row->shifthrs.'","'.$row->othours.'","'.$row->efficiency.'"'."\n";
				}
				else if($filterBy == "OperationWise")
				{
					$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->operationname.'","'.$row->producedmin.'","'.$row->pieces.'","'.$row->sam.'","'.$row->shifthrs.'","'.$row->othours.'","'.$row->efficiency.'"'."\n";
				}
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="SkillMatrixReport.csv"');
	  	echo $str; 
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/skillmatrix_reportprint',$data);
	$this->load->view('reports/footer');
}

public function individualperformancereport()
{
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	
	$this->load->view('header');
	$this->load->view('reports/individualperformance', $data);
	$this->load->view('footer');
}

public function getIndividualPerformanceReport()
{
	$fromDate = $this->input->post('fromDate');
	$toDate = $this->input->post('toDate');
	$employeeId = $this->input->post('employeeId');
	
	if($fromDate != "")
	{
		$fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
	}
	if($toDate != "")
	{
		$toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
	}
	
	$exportAsCSV = $this->input->post('checkValue');
	
	$data["title"] = "INDIVIDUAL PERFORMANCE REPORT";
	$data["subtitle"] = "Individual Performance Report";
	$data["filterBy"] = $filterBy;
	
	$res = $this->adminmodel->getIndividualPerformanceReport($fromDate, $toDate, $employeeId);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Employee No.,Employee Name,Total Pieces,Efficiency,Amount\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->empno.'","'.$row->empname.'","'.$row->pieces.'","'.$row->efficiency.'","'.$row->amount.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="IndividualPerformanceReport.csv"');
	  	echo $str; 
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/individualperformance_reportprint',$data);
	$this->load->view('reports/footer');
}

public function pricerateincentivereport()
{
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	
	$this->load->view('header');
	$this->load->view('reports/pricerateincentive', $data);
	$this->load->view('footer');
}

public function getPriceRateIncentiveReport()
{
	$fromDate = $this->input->post('fromDate');
	$toDate = $this->input->post('toDate');
	$employeeId = $this->input->post('employeeId');
	
	if($fromDate != "")
	{
		$fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
	}
	if($toDate != "")
	{
		$toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
	}
	
	$exportAsCSV = $this->input->post('checkValue');
	
	$data["title"] = "PRICE RATE INCENTIVE REPORT";
	$data["subtitle"] = "Price Rate Incentive Report";
	$data["filterBy"] = $filterBy;
	
	$res = $this->adminmodel->getPriceRateIncentiveReport($fromDate, $toDate, $employeeId);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Employee No.,Employee Name,Target Pieces,Sewing Pieces,Incentive Pieces,Amount\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->empno.'","'.$row->empname.'","'.$row->target.'","'.$row->sewing.'","'.$row->incentive.'","'.$row->amount.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="PriceRateIncentiveReport.csv"');
	  	echo $str; 
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/pricerateincentive_reportprint',$data);
	$this->load->view('reports/footer');
}

/*Report Ends*/

/*Common Function Starts*/

public function downloadAsPDF($str='')
{
	if($str == "")
	{
		$str = $this->input->post('PDFContent');
	}
	else
	{
		$str = $str;
	}
	downloadPDFByContent($str);
}

/*Common Function Ends*/

/*Manju Starts*/

/*Pratheep Starts*/
/*Pratheep Ends*/

}