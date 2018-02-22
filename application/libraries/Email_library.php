<?php 
Class Email_library {
	private $_CI;
	public function __construct() {
		$this->_CI = & get_instance();
	}
	public function sendEmail($to,$subject,$message) {
		$this->sendNormalEmail($to,$subject,$message);
	}
	private function sendNormalEmail($to,$subject,$message) {
		$this->_CI->load->library('email');
		$this->_CI->email->from('no-reply@agendatool.com', 'AgendaTool');
		$this->_CI->email->to($to); 
		$this->_CI->email->subject($subject);
		$this->_CI->email->message($message);
		$result = $this->_CI->email->send();
	}
	private function sendPhpMailerEmail() {
		
	}
}