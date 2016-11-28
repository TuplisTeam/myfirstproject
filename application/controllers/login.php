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
	$store = $this->input->post('store');
	
	if($email != "" && $password != "" && $store != "")
	{
		$res = $this->adminmodel->checkLogin($email, $password, $store);
		$rowCount = $res["rowCount"];
		$status = $res["status"];
		
		if($rowCount == 1 && $status == "active")
		{
			$data["isError"] = FALSE;
			$data["msg"] = "You Are Logged In Successfully.";
		}
		else
		{
			if($status == "inactive")
			{
				$data["isError"] = TRUE;
				$data["msg"] = "Your Account Has Been Suspended By Admin. Please Contact Admin.";
			}
			else
			{
				$data["isError"] = TRUE;
				$data["msg"] = "Email Or Password Is Not Matched.";
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

/*Manju Ends*/

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */