<?php
	class Admin_Controller extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			if($this->session->userdata('user_type_id')!=1)
				{$this->_check_admin();}
			$this->_container="member/container";
		}

		public function index()
		{
			$this->_check_admin();
		}

		private function _check_admin()
		{

				if($this->session->userdata('user_type_id') == 1)
				{
					redirect(site_url('admin/home','location'));	
				}

				else
				{
					redirect(site_url('auth'));
				}
		}
	}