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
	$data["pieceLogsMovements"] = $this->adminmodel->getPieceLogsMovements();
	$data["lineWiseEfficiency"] = $this->adminmodel->getLineWiseDetails();
	
	$this->load->view('header', $data);
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
	$data["sectionName"] = "";
	
	if($userId > 0)
	{
		foreach($res as $row)
		{
			$data["userName"] = $row->firstname;
			$data["userEmail"] = $row->email;
			$data["userType"] = $row->usertype;
			$data["sectionName"] = $row->sectionname;
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
	$userPassword = $this->input->post('userPassword');
	$sectionName = $this->input->post('sectionName');
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
	
	if($userName != "" && $userEmail != "" && $userType != "" && $sectionName != "")
	{
		$resCheck = $this->adminmodel->checkUserAvailability($userId, $userEmail);
		if($resCheck > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "User Already Exists.";
		}
		else
		{
			if((($userId == "" || $userId == 0) && $userPassword != "") || $userId > 0)
			{
				$this->adminmodel->saveUser($userId, $userName, $userEmail, $userType, $userPassword, $sectionName, $menuPermissionsArr);
			
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
			else
			{
				$data["isError"] = TRUE;
				$data["msg"] = "Password Should Not Be Empty.";
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
	
	if($barcodeId > 0)
	{
		$res = $this->adminmodel->getBarcodeDetails($barcodeId);
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

public function printBarcodeSticker($barcodeId)
{
	$barcode = '';
	if($barcodeId > 0)
	{
		$res = $this->adminmodel->getBarcodeDetails($barcodeId);
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$barcode = str_replace(" ", "_", $row->barcode);
			}
		}
	}
	
	$this->adminmodel->generateBarcode($barcode);
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
	$data["deliveryFrom"] = '';
	$data["deliveredBy"] = '';
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
				$data["deliveryFrom"] = $row->delivered_from;
				$data["deliveredBy"] = $row->delivered_by;
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
	if($barcode != "")
	{
		$res = $this->adminmodel->getBarcodeDetails('', $barcode);
		
		$data["res"] = $res;
		$data["isError"] = FALSE;
		$data["msg"] = "";
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
	$deliveryFrom = $this->input->post('deliveryFrom');
	$deliveredBy = $this->input->post('deliveredBy');
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
	
	if($deliveryNo != "" && $dcDate != "" && $supplierName != "" && $supplierAddress != "" && $customerName != "" && $receiverName != "" && $totalAmount > 0 && $deliveryFrom != "" && $deliveredBy != "" && count($dtlArr) > 0)
	{
		$this->adminmodel->saveDeliveryNote($deliveryNoteId, $deliveryNo, $dcDate, $supplierName, $supplierAddress, $customerName, $receiverName, $totalAmount, $deliveryFrom, $deliveredBy, $remarks, $dtlArr);
		
		if($deliveryNoteId > 0)
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
	$data["deliveryFrom"] = '';
	$data["deliveredBy"] = '';
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
			$data["deliveryFrom"] = $row->delivered_from;
			$data["deliveredBy"] = $row->delivered_by;
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

public function operation($operationId = '')
{
	$data["menuId"] = 5;
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

public function manualwork($manualWorkId = '')
{
	$data["menuId"] = 6;
	
	$data["manualWorkId"] = $manualWorkId;
	
	$data["manualWorkName"] = '';
	$data["manualWorkDesc"] = '';
	
	$res = $this->adminmodel->getManualWorkDetails($manualWorkId);
	
	if($manualWorkId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["manualWorkName"] = $row->manualworkname;
				$data["manualWorkDesc"] = $row->manualworkdesc;
			}
		}
	}
	else
	{
		$data["allManualWorks"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('manualwork', $data);
	$this->load->view('footer');
}

public function saveManualWork()
{
	$menuId = $this->input->post('menuId');
	$manualWorkId = $this->input->post('manualWorkId');
	$manualWorkName = $this->input->post('manualWorkName');
	$manualWorkDesc = $this->input->post('manualWorkDesc');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $manualWorkId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($manualWorkName != "")
	{
		$this->adminmodel->saveManualWork($manualWorkId, $manualWorkName, $manualWorkDesc);
		
		$data["isError"] = FALSE;
		if($manualWorkId > 0)
		{
			$data["msg"] = "Manual Work Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Manual Work Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function employee_vs_operation($entryId = '')
{
	$data["menuId"] = 31;
	$data["entryId"] = $entryId;
	
	$data["entryDate"] = date('d/m/Y');
	$data["employeeId"] = '';
	$data["lineId"] = '';
	$data["shiftId"] = '';
	$data["styleId"] = '';
	$data["tableName"] = '';
	$data["operationId"] = '';
	$data["machinaryId"] = '';
	$data["smv"] = '';
	$data["targetMinutes"] = '';
	$data["otHours"] = '';
	
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	$data["machinaryDtls"] = $this->adminmodel->getMachineryDetails();
	$data["operationDtls"] = $this->adminmodel->getOperationDetails();
	$data["shiftTimingDtls"] = $this->adminmodel->getShiftTimings();
	$data["lineVsStyleDtls"] = $this->adminmodel->getLineVsStyleDetails();
	$data["styleDtls"] = $this->adminmodel->getStyleHeaderDetails();
	
	$res = $this->adminmodel->getEmployeeVsOperationDetails($entryId);
	
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["entryDate"] = $row->entrydate;
				$data["employeeId"] = $row->empid;
				$data["lineId"] = $row->lineid;
				$data["shiftId"] = $row->shiftid;
				$data["styleId"] = $row->styleid;
				$data["tableName"] = $row->tablename;
				$data["operationId"] = $row->operationid;
				$data["machinaryId"] = $row->machinaryid;
				$data["smv"] = $row->smv;
				$data["targetMinutes"] = $row->targetminutes;
				$data["otHours"] = $row->ot_hours;
			}
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('employee_vs_operation', $data);
	$this->load->view('footer');
}

public function getSMVFromOBSheet()
{
	$styleId = $this->input->post('styleId');
	$operationId = $this->input->post('operationId');
	$machinaryId = $this->input->post('machinaryId');
	
	if($styleId > 0 && $operationId > 0 && $machinaryId > 0)
	{
		$smv = $this->adminmodel->getSMVFromOBSheet($styleId, $operationId, $machinaryId);
			
		$data["isError"] = FALSE;
		$data["msg"] = "";
		$data["smv"] = $smv;
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function saveEmployeeVsOperation()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$entryDate = $this->input->post('entryDate');
	$employeeId = $this->input->post('employeeId');
	$lineId = $this->input->post('lineId');
	$shiftId = $this->input->post('shiftId');
	$styleId = $this->input->post('styleId');
	$tableName = $this->input->post('tableName');
	$operationId = $this->input->post('operationId');
	$machinaryId = $this->input->post('machinaryId');
	$smv = $this->input->post('smv');
	$targetMinutes = $this->input->post('targetMinutes');
	$otHours = $this->input->post('otHours');
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($entryDate != "" && $employeeId > 0 && $operationId > 0 && $machinaryId > 0 && $smv != "")
	{
		$res = $this->adminmodel->checkEmployeeVsOperationavailability($entryId, $entryDate, $employeeId, $lineId, $shiftId, $tableName);
		if($res > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "This Entry Is Already Available. Please Check and Update.";
		}
		else
		{
			$this->adminmodel->saveEmployeeVsOperation($entryId, $entryDate, $employeeId, $lineId, $shiftId, $styleId, $tableName, $operationId, $machinaryId, $smv, $targetMinutes, $otHours);
			
			$data["isError"] = FALSE;
			if($empId > 0)
			{
				$data["msg"] = "Employee Vs Operation Details Updated Successfully.";
			}
			else
			{
				$data["msg"] = "Employee Vs Operation Details Saved Successfully.";
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

public function skillmatrix($skillMatrixId = '')
{
	$data["menuId"] = 17;
	
	$data["skillMatrixId"] = $skillMatrixId;
	
	$data["empDtls"] = $this->adminmodel->getEmployeeDetails();
	$data["styleDtls"] = $this->adminmodel->getStyleHeaderDetails();
	$data["operationDtls"] = $this->adminmodel->getOperationDetails();
	$data["shiftTimingDtls"] = $this->adminmodel->getShiftTimings();
	
	$res = $this->adminmodel->getSkillMatrix_HdrDetails($skillMatrixId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["shiftId"] = "";
	$data["dtlArr"] = array();
	
	if($skillMatrixId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["shiftId"] = $row->shiftid;
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

public function getPieceLogsDetailsByEmployee()
{
	$entryDate = $this->input->post('entryDate');
	$shiftId = $this->input->post('shiftId');
	$lineName = $this->input->post('lineName');
	$empId = $this->input->post('empId');
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	if($entryDate != "" && $shiftId > 0 && $lineName != "" && $empId > 0)
	{
		$res = $this->adminmodel->getPieceLogsDetailsByEmployee($entryDate, $shiftId, $lineName, $empId);
		
			$data["isError"] = FALSE;
			$data["msg"] = "";
			$data["res"] = $res;
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function saveSkillMatrix()
{
	$menuId = $this->input->post('menuId');
	$skillMatrixId = $this->input->post('skillMatrixId');
	$entryDate = $this->input->post('entryDate');
	$shiftId = $this->input->post('shiftId');
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
	
	if($entryDate != "" && $shiftId > 0 && $lineName != "" && count($dtlArr) > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_SkillMatrix($skillMatrixId, $entryDate, $shiftId, $lineName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "This Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveSkillMatrix($skillMatrixId, $entryDate, $shiftId, $lineName, $dtlArr);
		
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
	
	$data["machinaryRequirements"] = $this->adminmodel->getMachineryDetails();
	
	$res = $this->adminmodel->getNoWork_HdrDetails($noWorkId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["shiftName"] = "";
	$data["dtlArr"] = array();
	
	if($noWorkId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["shiftName"] = $row->shiftname;
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
	$shiftName = $this->input->post('shiftName');
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
	
	if($entryDate != "" && $lineName != "" && $shiftName != "" && count($dtlArr) > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_NoWork($noWorkId, $entryDate, $lineName, $shiftName);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "This Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveNoWorkTime($noWorkId, $entryDate, $lineName, $shiftName, $dtlArr);
			
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
	$data["shiftTimingDtls"] = $this->adminmodel->getShiftTimings();
	$data["lineVsStyleDtls"] = $this->adminmodel->getLineVsStyleDetails();
	
	$res = $this->adminmodel->getAssemblyLoadingDetails($assemblyLoadingId);
	
	$data["entryDate"] = date('d/m/Y');
	$data["lineId"] = "";
	$data["shiftId"] = "";
	$data["lineIncharge"] = "";
	$data["target"] = "";
	$data["hour1"] = "";
	$data["hour2"] = "";
	$data["hour3"] = "";
	$data["hour4"] = "";
	$data["hour5"] = "";
	$data["hour6"] = "";
	$data["hour7"] = "";
	$data["hour8"] = "";
	$data["otHour"] = "";
	$data["totalPieces"] = "";
	$data["isTargetAchieved"] = "No";
	
	if($assemblyLoadingId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineId"] = $row->lineid;
			$data["shiftId"] = $row->shiftid;
			$data["lineIncharge"] = $row->lineincharge;
			$data["target"] = $row->target;
			$data["hour1"] = $row->hour1;
			$data["hour2"] = $row->hour2;
			$data["hour3"] = $row->hour3;
			$data["hour4"] = $row->hour4;
			$data["hour5"] = $row->hour5;
			$data["hour6"] = $row->hour6;
			$data["hour7"] = $row->hour7;
			$data["hour8"] = $row->hour8;
			$data["otHour"] = $row->othour;
			$data["totalPieces"] = $row->totalpieces;
			$data["isTargetAchieved"] = $row->is_targetachieved;
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

public function getPieceLogsDetailsByDateLine()
{
	$entryDate = $this->input->post('entryDate');
	$lineId = $this->input->post('lineId');
	$shiftId = $this->input->post('shiftId');
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	if($entryDate != "" && $lineId > 0 && $shiftId > 0)
	{
		$data["isError"] = FALSE;
		$data["msg"] = "";
		$data["lineInchargeDtls"] = $this->adminmodel->getEmployeeVsOperationDetails('', $entryDate, $lineId);
		$res = $this->adminmodel->getLineVsStyleDetails($lineId);
		$lineName = '';
		$lineLocation = '';
		foreach($res as $row)
		{
			$lineName = $row->line_name;
			$lineLocation = $row->line_location;
		}
		
		$data["pieceLogDtls"] = $this->adminmodel->getPieceLogsDetailsByDateLine($entryDate, $lineName, $lineLocation, $shiftId);
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function saveAssemblyLoading()
{
	$menuId = $this->input->post('menuId');
	$assemblyLoadingId = $this->input->post('assemblyLoadingId');
	$entryDate = $this->input->post('entryDate');
	$lineId = $this->input->post('lineId');
	$shiftId = $this->input->post('shiftId');
	$lineIncharge = $this->input->post('lineIncharge');
	$hour1 = $this->input->post('hour1');
	$hour2 = $this->input->post('hour2');
	$hour3 = $this->input->post('hour3');
	$hour4 = $this->input->post('hour4');
	$hour5 = $this->input->post('hour5');
	$hour6 = $this->input->post('hour6');
	$hour7 = $this->input->post('hour7');
	$hour8 = $this->input->post('hour8');
	$otHour = $this->input->post('otHour');
	$totalPieces = $this->input->post('totalPieces');
	$target = $this->input->post('target');
	$isTargetAchieved = $this->input->post('isTargetAchieved');
	
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $assemblyLoadingId);
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($entryDate != "" && $lineId > 0 && $shiftId > 0 && $lineIncharge > 0 && $totalPieces > 0 && $target > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_AssemblyLoading($assemblyLoadingId, $entryDate, $lineId, $shiftId);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveAssemblyLoading($assemblyLoadingId, $entryDate, $lineId, $shiftId, $lineIncharge, $hour1, $hour2, $hour3, $hour4, $hour5, $hour6, $hour7, $hour8, $otHour, $totalPieces, $target, $isTargetAchieved);
		
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

public function hourlyproduction_linewise($entryId = '')
{
	$data["menuId"] = 26;
	
	$data["entryId"] = $entryId;
	
	$data["operationDtls"] = $this->adminmodel->getOperationDetails();
	$data["shiftTimingDtls"] = $this->adminmodel->getShiftTimings();
	$res = $this->adminmodel->getHourlyProductionLineWiseDetails($entryId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["shiftId"] = "";
	$data["operationIdArr"] = "";
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
	
	if($entryId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydt;
			$data["lineName"] = $row->linename;
			$data["shiftId"] = $row->shiftid;
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
			
			$data["operationIdArr"] = $this->adminmodel->getHourlyProductionLineWise_OperationDetails($entryId);
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
	$entryId = $this->input->post('entryId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$shiftId = $this->input->post('shiftId');
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
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($entryDate != "" && $lineName != "" && $shiftId > 0 && $operationId != "" && $noOfWorkers > 0 && $daysTarget > 0 && $targetPerHour > 0 && $noOfOperators > 0 && $availMinutes > 0 && $currentTarget > 0)
	{
		$availRes = $this->adminmodel->checkDateLineNameAvailability_HourlyProduction_Linewise($entryId, $entryDate, $lineName, $shiftId);
		if($availRes > 0)
		{
			$data["isError"] = TRUE;
			$data["msg"] = "Date and Line name is already available. Please check.";
		}
		else
		{
			$this->adminmodel->saveHourlyProduction_LineWise($entryId, $entryDate, $lineName, $shiftId, $operationId, $noOfWorkers, $daysTarget, $targetPerHour, $noOfOperators, $availMinutes, $currentTarget, $issues, $wip, $idleTime, $breakDownTime, $reworkTime, $noWorkTime, $lineEfficiency);
		
			$data["isError"] = FALSE;
			if($entryId > 0)
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
	$shiftId = $this->input->post('shiftId');
	$entryDate = substr($entryDate,6,4).'-'.substr($entryDate,3,2).'-'.substr($entryDate,0,2);
	if($entryDate != "" && $lineName != "" && $shiftId > 0)
	{
		$lineDtls = $this->adminmodel->getLineDetails($entryDate, $lineName, $shiftId);
		$curTarget = $this->adminmodel->getCurrentTargetAchieved($entryDate, $lineName, $shiftId);
		
		$data["lineDtls"] = $lineDtls;
		$data["curTarget"] = $curTarget;
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
	
	$data["buyer"] = '';
	$data["merchant"] = '';
	$data["styleNo"] = '';
	$data["styleDesc"] = '';
	$data["colour"] = '';
	$data["size"] = '';
	$data["styleImage"] = '';
	
	$data["machinaryRequirements"] = $this->adminmodel->getMachineryDetails();
	$data["operations"] = $this->adminmodel->getOperationDetails();
	
	$res = $this->adminmodel->getStyleHeaderDetails($styleId);
	
	if($styleId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["buyer"] = $row->buyer;
				$data["merchant"] = $row->merchant;
				$data["styleNo"] = $row->styleno;
				$data["styleDesc"] = $row->styledesc;
				$data["colour"] = $row->colour;
				$data["size"] = $row->size;
				$data["styleImage"] = $row->imagepath;
			}
			
			$data["dtlArr"] = $this->adminmodel->getStyleListDetails($styleId);
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
	$buyer = $this->input->post('buyer');
	$merchant = $this->input->post('merchant');
	$styleNo = $this->input->post('styleNo');
	$styleDesc = $this->input->post('styleDesc');
	$colour = $this->input->post('colour');
	$size = $this->input->post('size');
	$oldStyleImagePath = $this->input->post('oldStyleImagePath');
	$dtlArr = $this->input->post('dtlArr');
	$dtlArr = json_decode($dtlArr);
	
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
		$imgArr = $this->uploadSingleImage('', 'uploads', 'style', '', 'styleImage', $oldStyleImagePath);
		
		if($imgArr["isError"])
		{
			$data["isError"] = $imgArr["isError"];
			$data["msg"] = $imgArr["msg"];
			echo json_encode($data);
			return;
		}
		$styleImage = $imgArr["imageSrc"];
		
		$this->adminmodel->saveStyle($styleId, $buyer, $merchant, $styleNo, $styleDesc, $colour, $size, $styleImage, $dtlArr);
		
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
	
	$data["styleDtls"] = $this->adminmodel->getStyleHeaderDetails();
	
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
	
	$data["mn_TotalNumbers"] = "";
	$data["mn_TotalSMV"] = "";
	
	$data["machinaryRequirements"] = $this->adminmodel->getMachineryDetails();
	$data["manualWorkRequirements"] = $this->adminmodel->getManualWorkDetails();
	$data["operations"] = $this->adminmodel->getOperationDetails();
	
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
				
				$data["mn_TotalNumbers"] = $row->mn_totalnumbers;
				$data["mn_TotalSMV"] = $row->mn_totalsmv;
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

public function getStyle_Operation_Details()
{
	$styleId = $this->input->post('styleId');
	$operationId = $this->input->post('operationId');
	$machinaryId = $this->input->post('machinaryId');
	
	if($styleId > 0 && $operationId > 0 && $machinaryId > 0)
	{
		$data["res"] = $this->adminmodel->getStyleListDetails($styleId, $operationId, $machinaryId);
		$data["isError"] = FALSE;
		$data["msg"] = "";
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
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
	
	$mn_TotalNumbers = $this->input->post('mn_TotalNumbers');
	$mn_TotalSMV = $this->input->post('mn_TotalSMV');
	
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
		$this->adminmodel->saveOperationBulletin($bulletinId, $styleId, $stdNoOfWorkStations, $stdNoOfOperators, $stdNoOfHelpers, $totalSAM, $machineSAM, $manualSAM, $possibleDailyOutput, $expectedPeakEfficiency, $expectedOutput, $expectedAvgEfficiency, $expectedDailyOutput, $avgOutputPerMachine, $mc_TotalNumbers, $mc_TotalSMV, $mn_TotalNumbers, $mn_TotalSMV, $operationDtlArr, $machineryDtlArr, $manualWorkDtlArr);
		
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

public function hanger($hangerId = '')
{
	$data["menuId"] = 32;
	$data["hangerId"] = $hangerId;
	
	$data["hangerSlNo"] = '';
	$data["hangerName"] = '';
	
	$res = $this->adminmodel->getHangerDetails($hangerId);
	
	if($hangerId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["hangerSlNo"] = $row->hanger_slno;
				$data["hangerName"] = $row->hanger_name;
			}
		}
	}
	else
	{
		$data["allHangers"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('hanger', $data);
	$this->load->view('footer');
}

public function saveHanger()
{
	$menuId = $this->input->post('menuId');
	$hangerId = $this->input->post('hangerId');
	$hangerSlNo = $this->input->post('hangerSlNo');
	$hangerName = $this->input->post('hangerName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $hangerId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($hangerSlNo != "" && $hangerName != "")
	{
		$this->adminmodel->saveHanger($hangerId, $hangerSlNo, $hangerName);
		
		$data["isError"] = FALSE;
		if($hangerId > 0)
		{
			$data["msg"] = "Hanger Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Hanger Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function line_vs_style($entryId = '')
{
	$data["menuId"] = 33;
	$data["entryId"] = $entryId;
	
	$data["entryDate"] = date('d/m/Y');
	$data["lineName"] = '';
	$data["lineLocation"] = '';
	$data["inTable"] = '';
	$data["outTable"] = '';
	$data["styleId"] = '';
	
	$res = $this->adminmodel->getLineVsStyleDetails($entryId);
	$data["styleDtls"] = $this->adminmodel->getStyleHeaderDetails();
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["entryDate"] = $row->entrydt;
				$data["lineName"] = $row->line_name;
				$data["lineLocation"] = $row->line_location;
				$data["inTable"] = $row->intable;
				$data["outTable"] = $row->outtable;
				$data["styleId"] = $row->styleid;
			}
		}
	}
	else
	{
		$data["allDetails"] = $res;
		$data["pieceLogDtls"] = $this->adminmodel->getPieceLogsHeaderDetails();
	}
	
	$this->load->view('header');
	$this->load->view('line_vs_style', $data);
	$this->load->view('footer');
}

public function saveLineVsStyle()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$entryDate = $this->input->post('entryDate');
	$lineName = $this->input->post('lineName');
	$linelocation = $this->input->post('linelocation');
	$inTable = $this->input->post('inTable');
	$outTable = $this->input->post('outTable');
	$styleId = $this->input->post('styleId');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($entryDate != "" && $lineName != "" && $linelocation != "" && $inTable != "" && $outTable != "" && $styleId > 0)
	{
		$this->adminmodel->saveLineVsStyle($entryId, $entryDate, $lineName, $linelocation, $inTable, $outTable, $styleId);
		
		$data["isError"] = FALSE;
		if($entryId > 0)
		{
			$data["msg"] = "Line Vs Style Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Line Vs Style Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function piecelogdtl($entryId)
{
	$data["pieceLogDtls"] = $this->adminmodel->getPieceLogsDetails($entryId);
	$data["styleDtls"] = $this->adminmodel->getStyleHeaderDetails();
	
	$this->load->view('header');
	$this->load->view('piecelogdtl', $data);
	$this->load->view('footer');
}

public function assemblyloadingprint($entryId)
{
	$data["menuId"] = 23;
	
	$data["assemblyLoadingId"] = $entryId;
	
	$res = $this->adminmodel->getAssemblyLoadingDetails($entryId);
	
	$data["entryDate"] = "";
	$data["lineName"] = "";
	$data["shiftId"] = "";
	$data["shiftName"] = "";
	$data["lineIncharge"] = "";
	$data["target"] = "";
	$data["hour1"] = "";
	$data["hour2"] = "";
	$data["hour3"] = "";
	$data["hour4"] = "";
	$data["hour5"] = "";
	$data["hour6"] = "";
	$data["hour7"] = "";
	$data["hour8"] = "";
	$data["otHour"] = "";
	$data["totalPieces"] = "";
	$data["isTargetAchieved"] = "No";
	
	if($entryId > 0)
	{
		foreach($res as $row)
		{
			$data["entryDate"] = $row->entrydate;
			$data["lineName"] = $row->linename;
			$data["shiftId"] = $row->shiftid;
			$data["shiftName"] = $row->shiftname;
			$data["lineIncharge"] = $row->lineincharge;
			$data["target"] = $row->target;
			$data["hour1"] = $row->hour1;
			$data["hour2"] = $row->hour2;
			$data["hour3"] = $row->hour3;
			$data["hour4"] = $row->hour4;
			$data["hour5"] = $row->hour5;
			$data["hour6"] = $row->hour6;
			$data["hour7"] = $row->hour7;
			$data["hour8"] = $row->hour8;
			$data["otHour"] = $row->othour;
			$data["totalPieces"] = $row->totalpieces;
			$data["isTargetAchieved"] = $row->is_targetachieved;
		}
	}
	
	$this->load->view('reports/header');
	$this->load->view('print/assemblyloading',$data);
	$this->load->view('reports/footer');
}

public function shifttiming($entryId = '')
{
	$data["menuId"] = 33;
	$data["entryId"] = $entryId;
	
	$data["shiftName"] = '';
	$data["fromTime"] = '';
	$data["toTime"] = '';
	
	$res = $this->adminmodel->getShiftTimings($entryId);
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["shiftName"] = $row->shiftname;
				$data["fromTime"] = $row->fromtime;
				$data["toTime"] = $row->totime;
			}
		}
	}
	else
	{
		$data["allDetails"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('shifttiming', $data);
	$this->load->view('footer');
}

public function saveShiftTiming()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$shiftName = $this->input->post('shiftName');
	$fromTime = $this->input->post('fromTime');
	$toTime = $this->input->post('toTime');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($shiftName != "" && $fromTime != "" && $toTime != "")
	{
		$this->adminmodel->saveShiftTiming($entryId, $shiftName, $fromTime, $toTime);
		
		$data["isError"] = FALSE;
		if($entryId > 0)
		{
			$data["msg"] = "Shift Timing Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Shift Timing Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function table($entryId = '')
{
	$data["menuId"] = 34;
	$data["entryId"] = $entryId;
	
	$data["tableSlNo"] = '';
	$data["tableName"] = '';
	
	$res = $this->adminmodel->getTableDetails($entryId);
	
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["tableSlNo"] = $row->table_slno;
				$data["tableName"] = $row->table_name;
			}
		}
	}
	else
	{
		$data["allTables"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('table', $data);
	$this->load->view('footer');
}

public function saveTable()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$tableSlNo = $this->input->post('tableSlNo');
	$tableName = $this->input->post('tableName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($tableSlNo != "" && $tableName != "")
	{
		$this->adminmodel->saveTable($entryId, $tableSlNo, $tableName);
		
		$data["isError"] = FALSE;
		if($entryId > 0)
		{
			$data["msg"] = "Table Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Table Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function nowork($entryId = '')
{
	$data["menuId"] = 35;
	$data["entryId"] = $entryId;
	
	$data["noworkSlNo"] = '';
	$data["noworkName"] = '';
	
	$res = $this->adminmodel->getNoWorkDetails($entryId);
	
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["noworkSlNo"] = $row->nowork_slno;
				$data["noworkName"] = $row->nowork_name;
			}
		}
	}
	else
	{
		$data["allEntries"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('nowork', $data);
	$this->load->view('footer');
}

public function saveNoWork()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$noworkSlNo = $this->input->post('noworkSlNo');
	$noworkName = $this->input->post('noworkName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($noworkSlNo != "" && $noworkName != "")
	{
		$this->adminmodel->saveNoWork($entryId, $noworkSlNo, $noworkName);
		
		$data["isError"] = FALSE;
		if($entryId > 0)
		{
			$data["msg"] = "Nowork Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Nowork Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function rework($entryId = '')
{
	$data["menuId"] = 36;
	$data["entryId"] = $entryId;
	
	$data["reworkSlNo"] = '';
	$data["reworkName"] = '';
	
	$res = $this->adminmodel->getReworkDetails($entryId);
	
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["reworkSlNo"] = $row->rework_slno;
				$data["reworkName"] = $row->rework_name;
			}
		}
	}
	else
	{
		$data["allEntries"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('rework', $data);
	$this->load->view('footer');
}

public function saveRework()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$reworkSlNo = $this->input->post('reworkSlNo');
	$reworkName = $this->input->post('reworkName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($reworkSlNo != "" && $reworkName != "")
	{
		$this->adminmodel->saveRework($entryId, $reworkSlNo, $reworkName);
		
		$data["isError"] = FALSE;
		if($entryId > 0)
		{
			$data["msg"] = "Rework Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Rework Details Saved Successfully.";
		}
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
	}
	echo json_encode($data);
}

public function breakdown($entryId = '')
{
	$data["menuId"] = 37;
	$data["entryId"] = $entryId;
	
	$data["breakdownSlNo"] = '';
	$data["breakdownName"] = '';
	
	$res = $this->adminmodel->getBreakdownDetails($entryId);
	
	if($entryId > 0)
	{
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$data["breakdownSlNo"] = $row->breakdown_slno;
				$data["breakdownName"] = $row->breakdown_name;
			}
		}
	}
	else
	{
		$data["allEntries"] = $res;
	}
	
	$this->load->view('header');
	$this->load->view('breakdown', $data);
	$this->load->view('footer');
}

public function saveBreakdown()
{
	$menuId = $this->input->post('menuId');
	$entryId = $this->input->post('entryId');
	$breakdownSlNo = $this->input->post('breakdownSlNo');
	$breakdownName = $this->input->post('breakdownName');
	
	$permissions = $this->checkScreenPermissionAvailability($menuId, 'save_update', $entryId);
	
	if($permissions["isError"])
	{
		$data["isError"] = TRUE;
		$data["msg"] = $permissions["msg"];
		echo json_encode($data);
		return;
	}
	
	if($breakdownSlNo != "" && $breakdownName != "")
	{
		$this->adminmodel->saveBreakdown($entryId, $breakdownSlNo, $breakdownName);
		
		$data["isError"] = FALSE;
		if($entryId > 0)
		{
			$data["msg"] = "Breakdown Details Updated Successfully.";
		}
		else
		{
			$data["msg"] = "Breakdown Details Saved Successfully.";
		}
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
	//$filterBy = $this->input->post('filterBy');
	
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
	//$data["filterBy"] = $filterBy;
	
	$res = $this->adminmodel->getSkillMatrixReport($fromDate, $toDate, $employeeId);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		/*$str = "Sl No.,Entry Date,Line Name";
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
		}*/
		$str = "Sl No.,Entry Date,Line Name,Shift Name,Employee No.,Style Name,Table Name,Operation Name,Machinary Name,SMV,Target Minutes,OT Hours,Input Pieces,Output Pieces,Efficiency\n";
		$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$lineName = $row->line_name.' - '.$row->line_location;
				$empName = $row->empno.' - '.$row->empname;
				$efficiency = number_format(($row->op_pieces/$row->ip_pieces),2);
				
				$str .= $i.',"'.$row->entrydt.'","'.$lineName.'","'.$row->shiftname.'","'.$empName.'","'.$row->styleno.'","'.$row->tablename.'","'.$row->operationname.'","'.$row->machineryname.'","'.$row->smv.'","'.$row->targetminutes.'","'.$row->ot_hours.'","'.$row->ip_pieces.'","'.$row->op_pieces.'","'.$efficiency.'"'."\n";
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
				$lineName = $row->line_name.' - '.$row->line_location;
				$str .= $i.',"'.$row->entrydt.'","'.$lineName.'","'.$row->shiftname.'","'.$row->empno.'","'.$row->empname.'","'.$row->target.'","'.$row->sewing.'","'.$row->incentive.'","'.$row->amount.'"'."\n";
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
				$lineName = $row->line_name.' - '.$row->line_location;
				$str .= $i.',"'.$row->entrydt.'","'.$lineName.'","'.$row->shiftname.'","'.$row->empno.'","'.$row->empname.'","'.$row->hour1.'","'.$row->hour2.'","'.$row->hour3.'","'.$row->hour4.'","'.$row->hour5.'","'.$row->hour6.'","'.$row->hour7.'","'.$row->hour8.'","'.$row->othour.'","'.$row->totalpieces.'"'."\n";
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
		/*$str = "Sl No.,Entry Date,Line Name,Shift,Operation,No. Of Workers,Day's Target,Target Per Hour,No. Of Operators,Avail Minutes,Current Target,Issues,Hour 1,Hour 2,Hour 3,Hour 4,Hour 5,Hour 6,Hour 7,Hour 8,OT Pieces,Total Output,WIP,Idle Time,Breakdown Time,Rework Time,No Work Time,Line Efficiency\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->entrydt.'","'.$row->linename.'","'.$row->shiftname.'","'.$row->operationname.'","'.$row->no_of_workers.'","'.$row->days_target.'","'.$row->target_per_hr.'","'.$row->no_of_operators.'","'.$row->avail_min.'","'.$row->current_target.'","'.$row->issues.'","'.$row->hour1.'","'.$row->hour2.'","'.$row->hour3.'","'.$row->hour4.'","'.$row->hour5.'","'.$row->hour6.'","'.$row->hour7.'","'.$row->hour8.'","'.$row->othour.'","'.$row->totalpieces.'","'.$row->wip.'","'.$row->idletime.'","'.$row->breakdown_time.'","'.$row->rework_time.'","'.$row->nowork_time.'","'.$row->line_efficiency.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}*/
		
		$str = "Sl. No.,Entry Date,Line Name,Line Location,Style No.,No. Of Workers,Input Pieces,Output Pieces,WIP,Breakdown Timings,Issue Type\n";
		$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->createddt.'","'.$row->lineid.'","'.$row->linelocation.'","'.$row->styleno.'","'.$row->noofworkers.'","'.$row->input_cnt.'","'.$row->output_cnt.'","'.$row->wip.'","'.$row->timings.'","'.$row->issuetype.'"'."\n";
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
				$lineName = $row->line_name.' - '.$row->line_location;
				$str .= $i.',"'.$row->entrydt.'","'.$lineName.'","'.$row->shiftname.'","'.$row->empno.'","'.$row->empname.'","'.$row->hour1.'","'.$row->hour2.'","'.$row->hour3.'","'.$row->hour4.'","'.$row->hour5.'","'.$row->hour6.'","'.$row->hour7.'","'.$row->hour8.'","'.$row->othour.'","'.$row->totalpieces.'"'."\n";
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

public function piecelogreport()
{
	$this->load->view('header');
	$this->load->view('reports/piecelog');
	$this->load->view('footer');
}

public function getPiecelogReport()
{
	$fromDate = $this->input->post('fromDate');
	$toDate = $this->input->post('toDate');
	$lineName = $this->input->post('lineName');
	
	if($fromDate != "")
	{
		$fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
	}
	if($toDate != "")
	{
		$toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
	}
	
	$exportAsCSV = $this->input->post('checkValue');
	
	$data["title"] = "PIECELOG REPORT";
	$data["subtitle"] = "Piecelog Report";
	
	$res = $this->adminmodel->getPiecelogReport($fromDate, $toDate, $lineName);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Style No.,Style Desc,Table Name,Hanger Name,In Time,Out Time,Time Taken,Time Taken For Moving\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->createddt.'","'.$row->lineid.'","'.$row->styleno.'","'.$row->styledesc.'","'.$row->table_name.'","'.$row->hanger_name.'","'.$row->in_time.'","'.$row->out_time.'","'.$row->timetaken.'","'.$row->timetakenformoving.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="PiecelogReport.csv"');
	  	echo $str;
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/piecelog_reportprint',$data);
	$this->load->view('reports/footer');
}

public function issuesreport()
{
	$this->load->view('header');
	$this->load->view('reports/issues');
	$this->load->view('footer');
}

public function getIssuesReport()
{
	$fromDate = $this->input->post('fromDate');
	$toDate = $this->input->post('toDate');
	$lineName = $this->input->post('lineName');
	$issueType = $this->input->post('issueType');
	
	if($fromDate != "")
	{
		$fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
	}
	if($toDate != "")
	{
		$toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
	}
	
	$exportAsCSV = $this->input->post('checkValue');
	
	$data["title"] = "ISSUES REPORT";
	$data["subtitle"] = "Issues Report";
	
	$res = $this->adminmodel->getIssuesReport($fromDate, $toDate, $lineName, $issueType);
	
	$data["datas"] = $res;
	
	if($exportAsCSV == 1)
	{
		$str = "Sl No.,Entry Date,Line Name,Table Name,Issue Type,In Time,Out Time,Time Taken\n";
	  	$i=0;
	  	if(count($res) > 0)
		{
		  	foreach($res as $row)
			{
			   	$i++;
				
				$str .= $i.',"'.$row->createddt.'","'.$row->lineid.'","'.$row->table_name.'","'.ucwords($row->issuetype).'","'.$row->in_time.'","'.$row->out_time.'","'.$row->timetaken.'"'."\n";
		  	}
		}
		else
		{
			$str .= "No Data\'s Found...";
		}
	  	header('Content-Type: application/csv');
	  	header('Content-Disposition: attachement; filename="IssuesReport.csv"');
	  	echo $str;
		return;
	}
	
	$this->load->view('reports/header');
	$this->load->view('reports/issues_reportprint',$data);
	$this->load->view('reports/footer');
}

/*Report Ends*/

/*Common Function Starts*/

public function uploadSingleImage($required = '', $dir1, $dir2, $dir3 = '', $fileName, $oldImgPath = '')
{
	$imageSrc = "";
	$imagePath = "";
	
	$dir = './'.$dir1.'/';
	if (!is_dir($dir)) 
	{
	   mkdir($dir);
	}
	
	$dir = './'.$dir1.'/'.$dir2.'/';
	if (!is_dir($dir)) 
	{
	   mkdir($dir);
	}
	
	if($dir3 != "")
	{
		$dir = './'.$dir1.'/'.$dir2.'/'.$dir3.'/';
		if (!is_dir($dir)) 
		{
		   mkdir($dir);
		}
	}
	
	$config['upload_path'] = $dir;
	$config['allowed_types'] = 'gif|jpg|png|jpeg';
	$config['max_size']	= '5000';
	
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	
	$isError = FALSE;
	$errMsg = "";
	
	if(!$this->upload->do_upload($fileName))
	{
		if($oldImgPath == "")
		{
			if($required == "required")
			{
				$isError = TRUE;
				$errMsg = strip_tags($this->upload->display_errors());
			}
		}
		$imageSrc = $oldImgPath;
	}
	else
	{
		if($oldImgPath != "")
		unlink($oldImgPath);
		$data = array('upload_data' => $this->upload->data());
		foreach($data as $row)
		{
			$imagePath = $row["raw_name"]."".$row["file_ext"];
		} 
		$imageSrc = $dir.$imagePath;
		$imageSrc = substr($imageSrc, 2);
	}
	
	$resArr = array();
	$resArr["isError"] = $isError;
	$resArr["msg"] = $errMsg;
	$resArr["imageSrc"] = $imageSrc;
	return $resArr;
}

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

public function getBarcode()
{
	$this->adminmodel->generateBarcode(123);
}

/*Common Function Ends*/
	
/*Manju Ends*/

/*Pratheep Starts*/
/*Pratheep Ends*/

}