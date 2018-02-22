<?php
	class Home extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('api_model');
			$this->load->model('trial_period_model');
			$this->load->model('user_subscription_model');
			$this->load->model('language_model');
			$this->load->library('form_validation');
			$this->load->model('day_model');
			$this->load->model('time_model');
		}

		public function index()
		{
			$data['page'] = 'member/home/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Admin Home';
			$this->session->set_userdata('filter','Trainer');
			$this->session->set_userdata('active_status','Active');
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function change_email_page()
		{
			$data['page'] = 'member/home/change_email_page';
			$data['words'] = $this->_language;
			$data['header'] = 'Change Admin Email';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function change_admin_email()
		{
			$data['email_address'] = $this->input->post('email_address');
			$this->form_validation->set_rules('email_address', 'Email Address', 'required|is_unique[user_login.email_address]|valid_email');
			$this->form_validation->set_message('required','%s '.$this->_language['DTL_0301']);
			$this->form_validation->set_message('is_unique','%s '.$this->_language['DTL_0302']);
			$this->form_validation->set_message('valid_email','%s '.$this->_language['DTL_0304']);
			if($this->form_validation->run()==FALSE)
				$this->setting_page();
			else
			{
				$this->user_model->update('user_login',$data,array('user_type_id'=>1));
                $flashdata = array("type"=>"success","message"=>$this->_language['DTL_0324']);
                $this->session->set_flashdata('flash_message',$flashdata);
				$this->setting_page();
			}
		}
		
		public function change_admin_password()
		{

			$general_info_id = $this->session->userdata('general_id');
			$userdata = $this->user_model->get_user_info($general_info_id)->row_array();
			$current_password = md5($this->input->post('current_password'));
			$new_password = $this->input->post('new_password');
			$enc_password = md5($this->input->post('new_password'));
			$confirm_password = md5($this->input->post('confirm_password'));
			$this->form_validation->set_rules('current_password', 'Current Password', 'required|callback_current_password_check');
			$this->form_validation->set_rules('new_password', 'New Password', 'required');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
			$this->form_validation->set_message('matches',$this->_language['DTL_0317']);
			$this->form_validation->set_message('required','%s '.$this->_language['DTL_0301']);
			$data['password'] = $enc_password;
			if($this->form_validation->run()!=FALSE)
			{
				
				$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0318']);
				$this->session->set_flashdata('flash_message',$flashdata);
				$this->setting_page();
			}
			else
			{
				$this->user_model->update('user_login',$data,array('id'=>$userdata['user_id']));
				$this->setting_page();
			}
					
		}
		
		public function current_password_check($value)
		{
			$general_info_id = $this->session->userdata('general_id');
			$userdata = $this->user_model->get_user_info($general_info_id)->row_array();
			if($userdata['password'] != md5($this->input->post('confirm_password')))
			{
				$this->form_validation->set_message('current_password_check',$this->_language['DTL_0321']);
				return false;
			}
			else
			{
				return true;
			}
		}

		public function setting_page()
		{
			if($this->session->userdata('tab_index')!= 'change_password' && $this->session->userdata('tab_index')!= 'export' && $this->session->userdata('tab_index')!= 'email_address' && $this->session->userdata('tab_index')!= 'trail_period' && $this->session->userdata('tab_index')!= 'active_time' && $this->session->userdata('tab_index')!= 'api' && $this->session->userdata('tab_index')!= 'day' && $this->session->userdata('tab_index')!= 'time')
				$this->session->set_userdata('tab_index','email_address');
			$filter = 'Trainer';
			$active_status = 'Active';
			$from_date = null;
			$to_date = null;
			if($this->input->post())
			{
				if($this->input->post('searchByName'))
				{
					$searchByName = $this->input->post('searchByName');
					$this->session->set_userdata('searchByName',$searchByName);
				}
				else
					$this->session->unset_userdata('searchByName');
				if($this->input->post('searchByUserName'))
				{
					$searchByUserName = $this->input->post('searchByUserName');
					$this->session->set_userdata('searchByUserName',$searchByUserName);
				}
				else
					$this->session->unset_userdata('searchByUserName');
				if($this->input->post('searchByEmailAddress'))
				{
					$searchByEmailAddress = $this->input->post('searchByEmailAddress');
					$this->session->set_userdata('searchByEmailAddress',$searchByEmailAddress);
				}
				else
					$this->session->unset_userdata('searchByEmailAddress');
				if($this->input->post('from_date'))
				{
					$from_date = $this->input->post('from_date');
					$this->session->set_userdata('from_date',$from_date);
				}
				else
					$this->session->unset_userdata('from_date');
				if($this->input->post('to_date'))
				{
					$to_date = $this->input->post('to_date');
					$this->session->set_userdata('to_date',$to_date);
				}
				else
					$this->session->unset_userdata('to_date');
				if($this->session->userdata('searchByName'))
					$search_parameters['searchByName'] = $this->session->userdata('searchByName');
				if($this->session->userdata('searchByUserName'))
					$search_parameters['searchByUserName'] = $this->session->userdata('searchByUserName');
				if($this->session->userdata('searchByEmailAddress'))
					$search_parameters['searchByEmailAddress'] = $this->session->userdata('searchByEmailAddress');
				if($this->session->userdata('from_date'))
					$from_date = $this->session->userdata('from_date');
				if($this->session->userdata('to_date'))
					$to_date = $this->session->userdata('to_date');
			}
			else
			{
				if($this->session->userdata('searchByName'))
					$search_parameters['searchByName'] = $this->session->userdata('searchByName');
				if($this->session->userdata('searchByUserName'))
					$search_parameters['searchByUserName'] = $this->session->userdata('searchByUserName');
				if($this->session->userdata('searchByEmailAddress'))
					$search_parameters['searchByEmailAddress'] = $this->session->userdata('searchByEmailAddress');
				if($this->session->userdata('from_date'))
					$from_date = $this->session->userdata('from_date');
				if($this->session->userdata('to_date'))
					$to_date = $this->session->userdata('to_date');
			}
			$data['page'] = 'member/home/setting_page';
			$data['words'] = $this->_language;
			$data['header'] = 'Admin Setting';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['apis'] = $this->api_model->get_api()->result_array();
			$data['trial_periods'] = $this->trial_period_model->get_trial_periods()->result_array();
			$data['subscriptions'] = $this->user_subscription_model->get_subscription_detail()->result_array();
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			if($this->session->userdata('filter'))
			{
				$data['filter'] = $this->session->userdata('filter');
				$filter = $data['filter'];
			}
			else
				$data['filter'] = $filter;
			
			if($this->input->post('filter'))
			{
				$filter = $this->input->post('filter');
				$data['filter'] = $filter;
				$this->session->set_userdata('filter',$filter);
			}

			if($this->session->userdata('active_status'))
			{
				$data['active_status'] = $this->session->userdata('active_status');
				$active_status = $data['active_status'];
			}
			else
				$data['active_status'] = $active_status;
			
			if($this->input->post('active_status'))
			{
				$active_status = $this->input->post('active_status');
				$data['active_status'] = $active_status;
				$this->session->set_userdata('active_status',$active_status);
			}
			if(!empty($search_parameters))
			{
				if($filter == 'Trainer' && $active_status=='Active')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>3,'u.is_active'=>1),$search_parameters,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Active')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>2,'u.is_active'=>1),$search_parameters,$from_date,$to_date)->result_array();
				elseif($filter == 'Trainer' && $active_status=='Inactive')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>3,'u.is_active'=>0),$search_parameters,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Inactive')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>2,'u.is_active'=>0),$search_parameters,$from_date,$to_date)->result_array();
			}
			else
			{
				if($filter == 'Trainer' && $active_status=='Active')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>3,'u.is_active'=>1),null,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Active')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>2,'u.is_active'=>1),null,$from_date,$to_date)->result_array();
				elseif($filter == 'Trainer' && $active_status=='Inactive')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>3,'u.is_active'=>0),null,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Inactive')
					$data['users'] = $this->user_model->search_users(array('u.user_type_id'=>2,'u.is_active'=>0),null,$from_date,$to_date)->result_array();
			}
			$days = $this->day_model->get_days()->result_array();
			foreach($days as $value)
			{
				$data[$value['day']] = $value['status'];
			}
			for($i = 0; $i <= 23; $i++)
			{
				$data['time'][$i] = null;
			}
			$times = $this->time_model->get_times()->result_array();
			foreach($times as $time)
			{
				$data['time'][$time['time']] = $time['status'];
			}
			$this->load->view($this->_container,$data);
		}
		
		public function save_api()
		{
			$data['id'] = $this->input->post('id');
			$data['api_key'] = $this->input->post('api_key');
			$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
			$this->session->set_flashdata('flash_message',$flashdata);
			$result = $this->api_model->update('api',$data,array('id'=>$data['id']));
			echo json_encode($result);
		}

		public function set_session($value)
		{
			$this->session->set_userdata('tab_index',$value);
		}
		
	}