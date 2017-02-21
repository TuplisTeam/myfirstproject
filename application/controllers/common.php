<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends CI_Controller 
{

/*Manju Starts*/

public function sendForgotPassword($email)
{
 	$subject = "Forget Password | Tracking System"; 
	$heading = "Reset Password Link"; 
	$msg = 'Please Click <a href="'.base_url().'login/resetpassword/'.urlencode(base64_encode($email)).'" target="_blank">Here</a> to Reset Password..';
	
	$this->sendEmail($email, $subject, $heading, $msg);
}

public function sendEmail($toEmail, $subject, $heading, $msg)
{
	$fromEmail = $this->config->item("fromEmail");
	
	$this->load->library('email');
	$this->email->from($fromEmail);
	$this->email->to($toEmail);
	$this->email->set_mailtype("html");
	$this->email->subject($subject);
	
	$arr = array();
	$arr["subject"] = $subject;
	$arr["heading"] = $heading;
	$arr["message"] = $msg;
	$arr["email"] = $toEmail;

	$msgHTML = "";
	
	$msgHTML = $this->load->view('emailtemplate', $arr, TRUE);
	$this->email->message($msgHTML);
	$this->email->send();
}

/*Manju Ends*/

}