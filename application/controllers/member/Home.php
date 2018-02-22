<?php
	class Home extends Member_Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			$data['page'] = 'member/home/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Home';
			//$data['name'] = $this->session->userdata('username');
			$this->load->view($this->_container,$data);
		}
	}
?>