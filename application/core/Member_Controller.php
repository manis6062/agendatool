<?php
	class Member_Controller extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			if($this->session->userdata('user_type_id')==null)
				{$this->_check_member();}
			$this->_container="member/container";
		}

		public function index()
		{
			$this->_check_member();
		}

		private function _check_member()
		{
			if($this->session->userdata('user_type_id') == (1 || 2 || 3))
			{
				redirect(site_url('member/home','location'));	
			}

			else
			{
				redirect(site_url('auth'));
			}
		}

	}