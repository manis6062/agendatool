<?php
	class Day extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('api_model');
			$this->load->model('trial_period_model');
			$this->load->model('language_model');
			$this->load->model('keyword_model');
			$this->load->model('translation_model');
			$this->load->model('day_model');
			$this->load->model('time_model');
			$this->load->model('user_subscription_model');
			$this->load->library('form_validation');
		}

		public function index()
		{
			$data['page'] = 'member/day/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Day Select';
			$data['photo_logo'] = $this->session->userdata('photo');
			$days = $this->day_model->get_days()->result_array();
			foreach($days as $value)
			{
				$data[$value['day']] = $value['status'];
			}
			$id = $this->session->userdata('general_id');
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$this->load->view($this->_container,$data);
		}

		public function save_day()
		{
			$data = $this->input->post();
			foreach($data as $key=>$val)
			{
				$day = $key;
				$d['status'] = $val;
				$this->day_model->update('days',$d,array('day'=>$day));
			}
			redirect(site_url('admin/home/setting_page'));
		}

		public function save_time()
		{
			$data = $this->input->post();
			foreach($data as $key=>$val)
			{
				$time = $key;
				$t['status'] = $val;
				$this->time_model->update('times',$t,array('time'=>$time));
			}
			redirect(site_url('admin/home/setting_page'));
		}
	}