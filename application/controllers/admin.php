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
		$result = $this->adminmodel->getUserDetails('all', $userId);
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
	$data["menuId"] = 3;
	$data['userId'] = $userId;
	
	$res = $this->adminmodel->getUserDetails('others', $userId);
	$data["menuDtls"] = $this->adminmodel->getMenuDetails();
	
	$data["userPermissions"] = array();
	if($userId > 0)
	{
		$data["userPermissions"] = $this->adminmodel->getMenuDetails_User($userId);
	}
	
	$data["userName"] = "";
	$data["userEmail"] = "";
	$data["userType"] = "";
	
	if($userId > 0)
	{
		foreach($res as $row)
		{
			$data["userName"] = $row->firstname;
			$data["userEmail"] = $row->email;
			$data["userType"] = $row->usertype;
		}
	}
	else
	{
		$data["allUsers"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('users', $data);
	$this->load->view('footer');
}

public function saveUser()
{
	$menuId = $this->input->post('menuId');
	$userId = $this->input->post('userId');
	$userName = $this->input->post('userName');
	$userEmail = $this->input->post('userEmail');
	$userType = $this->input->post('userType');
	$menuPermissionsArr = $this->input->post('menuPermissionsArr');
	$menuPermissionsArr = json_decode($menuPermissionsArr);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $userId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($userName != "" && $userEmail != "" && $userType != "")
	{
		$resCheck = $this->adminmodel->checkUserAvailability($userId, $userEmail);
		if($resCheck > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "User Already Exists.";
		}
		else
		{
			$this->adminmodel->saveUser($userId, $userName, $userEmail, $userType, $menuPermissionsArr);
			
			$data["isError"] = FALSE;
			if($userId > 0)
			{
				$data["msg"] = "User Updated Successfully...";
			}
			else
			{
				$data["msg"] = "User Created Successfully...";
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

public function barcodegeneration($barcodeId = '')
{
	$data["menuId"] = 8;
	
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
	$menuId = $this->input->post('menuId');
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
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $barcodeId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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

public function printBarcode($barcodeId)
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
	
	$this->load->view('print/barcode', $data);
}

public function deliverynote($deliveryNoteId = '')
{
	$data["menuId"] = 9;
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
	$menuId = $this->input->post('menuId');
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
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $deliveryNoteId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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

public function printDeliveryNote($deliveryNoteId)
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
	
	$this->load->view('print/deliverynote', $data);	
}

public function receptioncheck($receptionCheckId = '')
{
	$data["menuId"] = 10;
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
	$menuId = $this->input->post('menuId');
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
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $receptionCheckId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($fromName != "" && $toName != "" && $dcNo > 0 && $lotNo != "" && $vehicleNo != "" && $rcDate != "" && $unitName != "" && $checkedBy != "" && $incharge != "")
	{
		$this->adminmodel->saveReceptionCheck($receptionCheckId, $fromName, $toName, $dcNo, $lotNo, $vehicleNo, $rcDate, $unitName, $descCheck1, $descCheck2, $descCheck3, $descCheck4, $checkedBy, $incharge, $remarks);
		
		if($receptionCheckId > 0)
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Reception Check Details Updated Successfully";
		}
		else
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Reception Check Details Saved Successfully";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function printReceptionCheck($receptionCheckId)
{
	$data["receptionCheckId"] = $receptionCheckId;
	
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
	
	$this->load->view('print/receptioncheck', $data);
}

public function scan()
{
	$data["menuId"] = 11;
	
	$res = $this->adminmodel->getDeliveryNoteItemDetails();
	$data["dcDtls"] = $res;
	$data["page"] = "scan";
	
	$this->load->view('header');
	$this->load->view('scansearch', $data);
	$this->load->view('footer');
}

public function search()
{
	$data["menuId"] = 12;
	
	$res = $this->adminmodel->getDeliveryNoteItemDetails();
	$data["dcDtls"] = $res;
	$data["page"] = "search";
	
	$this->load->view('header');
	$this->load->view('scansearch', $data);
	$this->load->view('footer');
}

public function receivedgoods()
{
	$data["menuId"] = 13;
	
	$res = $this->adminmodel->getReceivedGoods();
	$data["rcDtls"] = $res;
	
	$this->load->view('header');
	$this->load->view('receivedgoods', $data);
	$this->load->view('footer');
}

public function saveReceivedGoods()
{
	$menuId = $this->input->post('menuId');
	$barcodeName = $this->input->post('barcodeName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', '');
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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
	$data["menuId"] = 14;
	
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
	$menuId = $this->input->post('menuId');
	$rackDisplayId = $this->input->post('rackDisplayId');
	$entryDate = $this->input->post('entryDate');
	$dtlArr = $this->input->post('dtlArr');
	$processInfoArr = $this->input->post('processInfoArr');
	$dtlArr = json_decode($dtlArr);
	$processInfoArr = json_decode($processInfoArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $rackDisplayId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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

public function employee($empId = '')
{
	$data["menuId"] = 4;
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
	$menuId = $this->input->post('menuId');
	$empId = $this->input->post('empId');
	$empNo = $this->input->post('empNo');
	$empName = $this->input->post('empName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $empId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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
	$data["menuId"] = 6;
	
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
	$menuId = $this->input->post('menuId');
	$machineryId = $this->input->post('machineryId');
	$machineryName = $this->input->post('machineryName');
	$machineryDesc = $this->input->post('machineryDesc');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $machineryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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

public function skillmatrix($skillMatrixId = '')
{
	$data["menuId"] = 17;
	
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
	$menuId = $this->input->post('menuId');
	$skillMatrixId = $this->input->post('skillMatrixId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $skillMatrixId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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

public function noworktime($noWorkId = '')
{
	$data["menuId"] = 20;
	
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
	$menuId = $this->input->post('menuId');
	$noWorkId = $this->input->post('noWorkId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $noWorkId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
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

public function assemblyloading($assemblyLoadingId = '')
{
	$data["menuId"] = 23;
	
	$data["assemblyLoadingId"] = $assemblyLoadingId;
	
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	$res = $this->adminmodel->getAssemblyLoading_HdrDetails($assemblyLoadingId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["shiftName"] = "";
	$data["totalWorkers"] = "";
	$data["totalPieces"] = "";
	$data["dtlArr"] = array();
	
	if($assemblyLoadingId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["shiftName"] = $row->shift;
			$data["totalWorkers"] = $row->totalworkers;
			$data["totalPieces"] = $row->totalpieces;
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
	$menuId = $this->input->post('menuId');
	$assemblyLoadingId = $this->input->post('assemblyLoadingId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$shiftName = $this->input->post('shiftName');
	$totalWorkers = $this->input->post('totalWorkers');
	$totalPieces = $this->input->post('totalPieces');
	$totalTarget = $this->input->post('totalTarget');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $assemblyLoadingId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($entryDate != "" && $lineName != "" && $shiftName != "" && $totalWorkers > 0 && $totalPieces > 0 && $totalTarget > 0 && count($dtlArr) > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_AssemblyLoading($assemblyLoadingId, $entryDate, $lineName, $shiftName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveAssemblyLoading($assemblyLoadingId, $entryDate, $lineName, $shiftName, $totalWorkers, $totalPieces, $totalTarget, $dtlArr);
		
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

public function hourlyproduction_linewise($lineId = '')
{
	$data["menuId"] = 26;
	
	$data["lineId"] = $lineId;
	
	$data["operationDtls"] = $this->adminmodel->getOperationDetails();
	$res = $this->adminmodel->getHourlyProductionLineWiseDetails($lineId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["shiftName"] = "";
	$data["operationId"] = "";
	$data["noOfWorkers"] = "";
	$data["daysTarget"] = "";
	$data["targetPerHour"] = "";
	$data["noOfOperators"] = "";
	$data["availMinutes"] = "";
	$data["currentTarget"] = "";
	$data["issues"] = "";
	$data["wip"] = "";
	$data["idleTime"] = "";
	$data["breakDownTime"] = "";
	$data["reworkTime"] = "";
	$data["noWorkTime"] = "";
	$data["lineEfficiency"] = "";
	
	if($lineId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydt;
			$data["lineName"] = $row->linename;
			$data["shiftName"] = $row->shift;
			$data["operationId"] = $row->operationid;
			$data["noOfWorkers"] = $row->no_of_workers;
			$data["daysTarget"] = $row->days_target;
			$data["targetPerHour"] = floatval($row->days_target)/8;
			$data["noOfOperators"] = $row->no_of_operators;
			$data["availMinutes"] = $row->avail_min;
			$data["currentTarget"] = $row->current_target;
			$data["issues"] = $row->issues;
			$data["wip"] = $row->wip;
			$data["idleTime"] = $row->idletime;
			$data["breakDownTime"] = $row->breakdown_time;
			$data["reworkTime"] = $row->rework_time;
			$data["noWorkTime"] = $row->nowork_time;
			$data["lineEfficiency"] = $row->line_efficiency;
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('hourlyproduction_linewise', $data);
	$this->load->view('footer');
}

public function saveHourlyProduction_LineWise()
{
	$menuId = $this->input->post('menuId');
	$lineId = $this->input->post('lineId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$shiftName = $this->input->post('shift');
	$operationId = $this->input->post('operationId');
	$noOfWorkers = $this->input->post('noOfWorkers');
	$daysTarget = $this->input->post('daysTarget');
	$targetPerHour = $this->input->post('targetPerHour');
	$noOfOperators = $this->input->post('noOfOperators');
	$availMinutes = $this->input->post('availMinutes');
	$currentTarget = $this->input->post('currentTarget');
	$issues = $this->input->post('issues');
	$wip = $this->input->post('wip');
	$idleTime = $this->input->post('idleTime');
	$breakDownTime = $this->input->post('breakDownTime');
	$reworkTime = $this->input->post('reworkTime');
	$noWorkTime = $this->input->post('noWorkTime');
	$lineEfficiency = $this->input->post('lineEfficiency');
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $lineId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($entryDate != "" && $lineName != "" && $shiftName != "" && $operationId > 0 && $noOfWorkers > 0 && $daysTarget > 0 && $targetPerHour > 0 && $noOfOperators > 0 && $availMinutes > 0 && $currentTarget > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_HourlyProduction_Linewise($lineId, $entryDate, $lineName, $shiftName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveHourlyProduction_LineWise($lineId, $entryDate, $lineName, $shiftName, $operationId, $noOfWorkers, $daysTarget, $targetPerHour, $noOfOperators, $availMinutes, $currentTarget, $issues, $wip, $idleTime, $breakDownTime, $reworkTime, $noWorkTime, $lineEfficiency);
		
			$data["isError"] = FALSE;
			if($lineId > 0)
			{
				$data["msg"] = "Line Details Updated Successfully.";
			}
			else
			{
				$data["msg"] = "Line Details Created Successfully.";
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

public function getLineDetails()
{
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$shift = $this->input->post('shift');
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	if($entryDate != "" && $lineName != "" && $shift != "")
	{
		$res = $this->adminmodel->getLineDetails($entryDate, $lineName, $shift);
		
		$data["res"] = $res;
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

public function style($styleId = '')
{
	$data["menuId"] = 7;
	
	$data["styleId"] = $styleId;
	
	$data["styleNo"] = '';
	$data["styleDesc"] = '';
	
	$res = $this->adminmodel->getStyleDetails($styleId);
	
	if($styleId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["styleNo"] = $row->styleno;
				$data["styleDesc"] = $row->styledesc;
			}
		}
	}
	else
	{
		$data["allStyles"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('style', $data);
	$this->load->view('footer');
}

public function saveStyle()
{
	$menuId = $this->input->post('menuId');
	$styleId = $this->input->post('styleId');
	$styleNo = $this->input->post('styleNo');
	$styleDesc = $this->input->post('styleDesc');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $styleId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($styleNo != "" && $styleDesc != "")
	{
		$this->adminmodel->saveStyle($styleId, $styleNo, $styleDesc);
		
		$data["isError"] = FALSE;
		if($styleId > 0)
		{
			$data["msg"] = "Style Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Style Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function operationbulletin($bulletinId = '')
{
	$data["menuId"] = 29;
	
	$data["bulletinId"] = $bulletinId;
	
	$data["styleDtls"] = $this->adminmodel->getStyleDetails();
	
	$res = $this->adminmodel->getOperationBulletinDetails($bulletinId);
	
	$data["styleId"] = "";
	$data["stdNoOfWorkStations"] = "";
	$data["stdNoOfOperators"] = "";
	$data["stdNoOfHelpers"] = "";
	$data["totalSAM"] = "";
	$data["machineSAM"] = "";
	$data["manualSAM"] = "";
	$data["possibleDailyOutput"] = "";
	$data["expectedPeakEfficiency"] = "";
	$data["expectedOutput"] = "";
	$data["expectedAvgEfficiency"] = "";
	$data["expectedDailyOutput"] = "";
	$data["avgOutputPerMachine"] = "";
	
	$data["mc_TotalNumbers"] = "";
	$data["mc_TotalSMV"] = "";
	
	$data["operationDtls"] = array();
	$data["machineryDtls"] = array();
	$data["manualWorkDtls"] = array();
	
	if($bulletinId > 0)
	{
		if(count($res) > 0)
		{
			$data["operationDtls"] = $this->adminmodel->getOperationBulletin_OperationDetails($bulletinId);
			$data["machineryDtls"] = $this->adminmodel->getOperationBulletin_MachineryDetails($bulletinId);
			$data["manualWorkDtls"] = $this->adminmodel->getOperationBulletin_ManualWorkDetails($bulletinId);
			
			foreach($res as $row)
			{
				$data["styleId"] = $row->styleid;
				$data["stdNoOfWorkStations"] = $row->workstations;
				$data["stdNoOfOperators"] = $row->operators_in_line;
				$data["stdNoOfHelpers"] = $row->helpers_in_line;
				$data["totalSAM"] = $row->total_sam;
				$data["machineSAM"] = $row->machine_sam;
				$data["manualSAM"] = $row->manual_sam;
				$data["possibleDailyOutput"] = $row->daily_output;
				$data["expectedPeakEfficiency"] = $row->expected_peak_eff;
				$data["expectedOutput"] = $row->expected_output;
				$data["expectedAvgEfficiency"] = $row->expected_avg_eff;
				$data["expectedDailyOutput"] = $row->expected_daily_output;
				$data["avgOutputPerMachine"] = $row->avg_output_per_mc;
				
				$data["mc_TotalNumbers"] = $row->mc_totalnumbers;
				$data["mc_TotalSMV"] = $row->mc_totalsmv;
			}
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('operationbulletin', $data);
	$this->load->view('footer');
}

public function saveOperationBulletin()
{
	$menuId = $this->input->post('menuId');
	$bulletinId = $this->input->post('bulletinId');
	$styleId = $this->input->post('styleId');
	$stdNoOfWorkStations = $this->input->post('stdNoOfWorkStations');
	$stdNoOfOperators = $this->input->post('stdNoOfOperators');
	$stdNoOfHelpers = $this->input->post('stdNoOfHelpers');
	$totalSAM = $this->input->post('totalSAM');
	$machineSAM = $this->input->post('machineSAM');
	$manualSAM = $this->input->post('manualSAM');
	$possibleDailyOutput = $this->input->post('possibleDailyOutput');
	$expectedPeakEfficiency = $this->input->post('expectedPeakEfficiency');
	$expectedOutput = $this->input->post('expectedOutput');
	$expectedAvgEfficiency = $this->input->post('expectedAvgEfficiency');
	$expectedDailyOutput = $this->input->post('expectedDailyOutput');
	$avgOutputPerMachine = $this->input->post('avgOutputPerMachine');
	
	$mc_TotalNumbers = $this->input->post('mc_TotalNumbers');
	$mc_TotalSMV = $this->input->post('mc_TotalSMV');
	
	$operationDtlArr = $this->input->post('operationDtlArr');
	$machineryDtlArr = $this->input->post('machineryDtlArr');
	$manualWorkDtlArr = $this->input->post('manualWorkDtlArr');
	$operationDtlArr = json_decode($operationDtlArr);
	$machineryDtlArr = json_decode($machineryDtlArr);
	$manualWorkDtlArr = json_decode($manualWorkDtlArr);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $bulletinId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($styleId > 0 && count($operationDtlArr) > 0 && count($machineryDtlArr) > 0 && count($manualWorkDtlArr) > 0)
	{
		$this->adminmodel->saveOperationBulletin($bulletinId, $styleId, $stdNoOfWorkStations, $stdNoOfOperators, $stdNoOfHelpers, $totalSAM, $machineSAM, $manualSAM, $possibleDailyOutput, $expectedPeakEfficiency, $expectedOutput, $expectedAvgEfficiency, $expectedDailyOutput, $avgOutputPerMachine, $mc_TotalNumbers, $mc_TotalSMV, $operationDtlArr, $machineryDtlArr, $manualWorkDtlArr);
		
		if($bulletinId > 0)
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Operation Bulletin Details Updated Successfully";
		}
		else
		{
			$data["isError"] = FALSE;
			$data["msg"] = "Operation Bulletin Details Saved Successfully";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function operationbulletinprint($bulletinId = '')
{
	$data["bulletinId"] = $bulletinId;
	
	$res = $this->adminmodel->getOperationBulletinDetails($bulletinId);
	
	$data["styleId"] = "";
	$data["styleNo"] = "";
	$data["styleDesc"] = "";
	$data["createdBy"] = "";
	$data["preparedOn"] = "";
	$data["revisedOn"] = "";
	$data["stdNoOfWorkStations"] = "";
	$data["stdNoOfOperators"] = "";
	$data["stdNoOfHelpers"] = "";
	$data["totalSAM"] = "";
	$data["machineSAM"] = "";
	$data["manualSAM"] = "";
	$data["possibleDailyOutput"] = "";
	$data["expectedPeakEfficiency"] = "";
	$data["expectedOutput"] = "";
	$data["expectedAvgEfficiency"] = "";
	$data["expectedDailyOutput"] = "";
	$data["avgOutputPerMachine"] = "";
	
	$data["mc_TotalNumbers"] = "";
	$data["mc_TotalSMV"] = "";
	
	$data["mn_TotalNumbers"] = "";
	$data["mc_TotalSMV"] = "";
	
	$data["operationDtls"] = array();
	$data["machineryDtls"] = array();
	$data["manualWorkDtls"] = array();
	
	if($bulletinId > 0)
	{
		if(count($res) > 0)
		{
			$data["operationDtls"] = $this->adminmodel->getOperationBulletin_OperationDetails($bulletinId);
			$data["machineryDtls"] = $this->adminmodel->getOperationBulletin_MachineryDetails($bulletinId);
			$data["manualWorkDtls"] = $this->adminmodel->getOperationBulletin_ManualWorkDetails($bulletinId);
			
			foreach($res as $row)
			{
				$data["styleId"] = $row->styleid;
				$data["styleNo"] = $row->styleno;
				$data["styleDesc"] = $row->styledesc;
				$data["createdBy"] = $row->firstname;
				$data["preparedOn"] = $row->preparedon;
				$data["revisedOn"] = $row->revisedon;
				$data["stdNoOfWorkStations"] = $row->workstations;
				$data["stdNoOfOperators"] = $row->operators_in_line;
				$data["stdNoOfHelpers"] = $row->helpers_in_line;
				$data["totalSAM"] = $row->total_sam;
				$data["machineSAM"] = $row->machine_sam;
				$data["manualSAM"] = $row->manual_sam;
				$data["possibleDailyOutput"] = $row->daily_output;
				$data["expectedPeakEfficiency"] = $row->expected_peak_eff;
				$data["expectedOutput"] = $row->expected_output;
				$data["expectedAvgEfficiency"] = $row->expected_avg_eff;
				$data["expectedDailyOutput"] = $row->expected_daily_output;
				$data["avgOutputPerMachine"] = $row->avg_output_per_mc;
				
				$data["mc_TotalNumbers"] = $row->mc_totalnumbers;
				$data["mc_TotalSMV"] = $row->mc_totalsmv;
				
				$data["mn_TotalNumbers"] = $row->mn_totalnumbers;
				$data["mc_TotalSMV"] = $row->mn_totalsmv;
			}
		}
	}
	
	$this->load->view('reports/header');
	$this->load->view('operationbulletinprint',$data);
	$this->load->view('reports/footer');
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
	
	$res = $this->adminmodel->getPriceRateIncentiveReport($fromDate, $toDate, $employeeId);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Shift,Employee No.,Employee Name,Target Pieces,Sewing Pieces,Incentive Pieces,Amount\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->shift.'","'.$row->empno.'","'.$row->empname.'","'.$row->target.'","'.$row->sewing.'","'.$row->incentive.'","'.$row->amount.'"'."\n";
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

public function hourlyproductionreport()
{
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	
	$this->load->view('header');
	$this->load->view('reports/hourlyproduction', $data);
	$this->load->view('footer');
}

public function getHourlyProductionReport()
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
	
	$data["title"] = "HOURLY PRODUCTION REPORT";
	$data["subtitle"] = "Hourly Production Report";
	
	$res = $this->adminmodel->getHourlyProductionReport($fromDate, $toDate, $employeeId);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Shift,Employee No.,Employee Name,Hour 1,Hour 2,Hour 3,Hour 4,Hour 5,Hour 6,Hour 7,Hour 8,OT Pieces,Total Pieces\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->shift.'","'.$row->empno.'","'.$row->empname.'","'.$row->hour_1.'","'.$row->hour_2.'","'.$row->hour_3.'","'.$row->hour_4.'","'.$row->hour_5.'","'.$row->hour_6.'","'.$row->hour_7.'","'.$row->hour_8.'","'.$row->ot_pieces.'","'.$row->totalpieces.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="HourlyProductionReport.csv"');
	  	echo $str; 
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/hourlyproduction_reportprint',$data);
	$this->load->view('reports/footer');
}

public function hourlyproduction_linewise_report()
{
	$this->load->view('header');
	$this->load->view('reports/hourlyproduction_linewise');
	$this->load->view('footer');
}

public function getHourlyProductionLineWiseReport()
{
	$fromDate = $this->input->post('fromDate');
	$toDate = $this->input->post('toDate');
	
	if($fromDate != "")
	{
		$fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
	}
	if($toDate != "")
	{
		$toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
	}
	
	$exportAsCSV = $this->input->post('checkValue');
	
	$data["title"] = "HOURLY PRODUCTION LINE WISE REPORT";
	$data["subtitle"] = "Hourly Production Line Wise Report";
	
	$res = $this->adminmodel->getHourlyProductionLineWiseReport($fromDate, $toDate);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Shift,Operation,No. Of Workers,Day's Target,Target Per Hour,No. Of Operators,Avail Minutes,Current Target,Issues,Hour 1,Hour 2,Hour 3,Hour 4,Hour 5,Hour 6,Hour 7,Hour 8,OT Pieces,Total Output,WIP,Idle Time,Breakdown Time,Rework Time,No Work Time,Line Efficiency\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->shift.'","'.$row->operationname.'","'.$row->no_of_workers.'","'.$row->days_target.'","'.$row->target_per_hr.'","'.$row->no_of_operators.'","'.$row->avail_min.'","'.$row->current_target.'","'.$row->issues.'","'.$row->hour_1.'","'.$row->hour_2.'","'.$row->hour_3.'","'.$row->hour_4.'","'.$row->hour_5.'","'.$row->hour_6.'","'.$row->hour_7.'","'.$row->hour_8.'","'.$row->ot_pieces.'","'.$row->totalpieces.'","'.$row->wip.'","'.$row->idletime.'","'.$row->breakdown_time.'","'.$row->rework_time.'","'.$row->nowork_time.'","'.$row->line_efficiency.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="HourlyProductionLineWiseReport.csv"');
	  	echo $str; 
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/hourlyproduction_linewise_reportprint',$data);
	$this->load->view('reports/footer');
}

public function assemblyloadingreport()
{
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	
	$this->load->view('header');
	$this->load->view('reports/assemblyloading', $data);
	$this->load->view('footer');
}

public function getAssemblyLoadingReport()
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
	
	$data["title"] = "ASSEMBLY LOADING REPORT";
	$data["subtitle"] = "Assembly Loading Report";
	
	$res = $this->adminmodel->getAssemblyLoadingReport($fromDate, $toDate, $employeeId);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Shift,Employee No.,Employee Name,Hour 1,Hour 2,Hour 3,Hour 4,Hour 5,Hour 6,Hour 7,Hour 8,OT Pieces,Total Output\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->shift.'","'.$row->empno.'","'.$row->empname.'","'.$row->hour_1.'","'.$row->hour_2.'","'.$row->hour_3.'","'.$row->hour_4.'","'.$row->hour_5.'","'.$row->hour_6.'","'.$row->hour_7.'","'.$row->hour_8.'","'.$row->ot_pieces.'","'.$row->totalpieces.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="AssemblyLoadingReport.csv"');
	  	echo $str; 
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/assemblyloading_reportprint',$data);
	$this->load->view('reports/footer');
}

/*Report Ends*/

/*Common Function Starts*/

public function delEntry()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$tableName = $this->input->post('tableName');
	$columnName = $this->input->post('columnName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'delete');
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($menuId > 0 && $entryId > 0 && $tableName != "" && $columnName != "")
	{
		$this->adminmodel->delEntry($entryId, $tableName, $columnName);
		
		$data["isError"] = FALSE;
		$data["msg"] = "Entry Removed Successfully...";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Fields";
	}
	echo json_encode($data);
}

public function checkScreenPermissionAvailability($menuId, $type, $id = '')
{
	$r = $this->session->userdata('menudata');
	
	$data["isError"] = FALSE;
	$data["msg"] = "";
	
	if($menuId > 0 && $type != "")
	{
		if($type == "save_update")
		{
			if(($id == "" && $r[$menuId]["save"] == "yes") || ($id != "" && $r[$menuId]["edit"] == "yes"))
			{	
			}
			else
			{
				$data["isError"] = TRUE;
				$data["msg"] = "You Have No Rights to Do this Insert / Update...";
			}
		}
		else if($type == "delete")
		{
			if($r[$menuId]["delete"] != 'yes')
			{
				$data["isError"] = TRUE;
				$data["msg"] = "You Have No Rights to Delete This Entry.";
			}
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Menu Id Is Not Valid.";
	}
	return $data;
}

/*Common Function Ends*/

/*Manju Starts*/

/*Pratheep Starts*/
/*Pratheep Ends*/

}