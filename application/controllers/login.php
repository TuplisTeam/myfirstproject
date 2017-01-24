<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller 
{

public function __construct()
{
	parent::__construct();

	$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
	$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
	$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
	$this->output->set_header('Pragma: no-cache');
	//$this->session->set_userdata('isLogged', FALSE);
	
	if(($this->session->userdata('userid') == null) || ($this->session->userdata('userid') == ""))
	{
	}
	else
	{
		redirect(base_url().'admin');
	}			
}

/*Manju Starts*/

public function index()
{
	$this->load->view('loginpage');
}

public function checkLogin()
{
	$email = $this->input->post('email');
	$password = $this->input->post('password');
	$sectionName = $this->input->post('sectionName');
	
	if($email != "" && $password != "")
	{
		$this->adminmodel->checkLogin($email, $password, $sectionName);
	}
	else
	{
		$data["isError"] = TRUE;
		$data["msg"] = "Please Fill All Details.";
		
		echo json_encode($data);
		return;
	}
}

public function getUserDetailsByEmail()
{
	$email = $this->input->post('email');
	if($email != "")
	{
		$res = $this->adminmodel->getUserDetailsByEmail($email);
		
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

/*Manju Ends*/

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */