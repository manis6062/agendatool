<?php
	class Payment extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('trial_period_model');
			$this->load->model('user_subscription_model');
			$this->load->model('user_model');
			$this->load->model('language_model');
			$this->load->model('email_model');
			$this->load->library('pagination');
			$this->load->library('email_library');
		}


		public function index($page = 0)
		{
			$per_page = 10;
			if($this->input->post())
			{
				if($this->input->post('searchByName'))
				{
					$searchByName = $this->input->post('searchByName');
					$this->session->set_userdata('searchByName',$searchByName);
				}
				else
					$this->session->unset_userdata('searchByName');
				if($this->input->post('searchByAmount'))
				{
					$searchByAmount = $this->input->post('searchByAmount');
					$this->session->set_userdata('searchByAmount',$searchByAmount);
				}
				else
					$this->session->unset_userdata('searchByAmount');
				if($this->input->post('searchByStartDate'))
				{
					$searchByStartDate = $this->input->post('searchByStartDate');
					$this->session->set_userdata('searchByStartDate',$searchByStartDate);
				}
				else
					$this->session->unset_userdata('searchByStartDate');
				if($this->input->post('searchByExpiryDate'))
				{
					$searchByExpiryDate = $this->input->post('searchByExpiryDate');
					$this->session->set_userdata('searchByExpiryDate',$searchByExpiryDate);
				}
				else
					$this->session->unset_userdata('searchByExpiryDate');
				if($this->input->post('per_page'))
				{
					$per_page = $this->input->post('per_page');
					$this->session->set_userdata('per_page',$per_page);
				}
				else
					$this->session->unset_userdata('per_page');
				if($this->session->userdata('searchByName')!='')
					$search_parameters['searchByName'] = $this->session->userdata('searchByName');
				if($this->session->userdata('searchByAmount')!='')
					$search_parameters['searchByAmount'] = $this->session->userdata('searchByAmount');
				if($this->session->userdata('searchByStartDate')!='')
					$search_parameters['searchByStartDate'] = $this->session->userdata('searchByStartDate');
				if($this->session->userdata('searchByExpiryDate')!='')
					$search_parameters['searchByExpiryDate'] = $this->session->userdata('searchByExpiryDate');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
			}
			else
			{
				if($this->session->userdata('searchByName')!='')
					$search_parameters['searchByName'] = $this->session->unset_userdata('searchByName');
				if($this->session->userdata('searchByAmount')!='')
					$search_parameters['searchByAmount'] = $this->session->unset_userdata('searchByAmount');
				if($this->session->userdata('searchByStartDate')!='')
					$search_parameters['searchByStartDate'] = $this->session->unset_userdata('searchByStartDate');
				if($this->session->userdata('searchByExpiryDate')!='')
					$search_parameters['searchByExpiryDate'] = $this->session->unset_userdata('searchByExpiryDate');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->unset_userdata('per_page');
			}

			$config['base_url'] = base_url()."admin/payment/index/";

			if(!empty($search_parameters))
			{
					$config['total_rows'] = count($this->user_subscription_model->get_all_subscription(null,null,$search_parameters)->result_array());
			}
			else
			{
					$config['total_rows'] = count($this->user_subscription_model->get_all_subscription(null,null,null)->result_array());
			}

			$config['per_page'] = $per_page;
			$config['num_links'] = 2;

			$config['uri_segment'] = 4; /* segment of your uri which contains the page number */
			
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
				
			$config['next_link'] = 'Next →';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';

			$config['prev_link'] = '← Previous';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';

			$config['first_link'] = false;
			$config['last_link'] = false;
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

			$data['page'] = 'member/payment/all_subscription';
			$data['words'] = $this->_language;
			$data['header'] = 'Payment';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			if(!empty($search_parameters))
			{
					$data['subscriptions'] = ($this->user_subscription_model->get_all_subscription($per_page,$page,$search_parameters)->result_array());
			}
			else
			{
					$data['subscriptions'] = ($this->user_subscription_model->get_all_subscription($per_page,$page,null)->result_array());
			}
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$data['pagination'] = $this->pagination->create_links();
			$this->load->view($this->_container,$data);
		}
		
		public function subscription_page()
		{
			$data['page'] = 'member/payment/subscription_page';
			$data['words'] = $this->_language;
			$data['header'] = 'Subscription Settings';
			$data['subscriptions'] = $this->user_subscription_model->get_subscription_detail()->result_array();
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function subscription_edit_page()
		{
			$data['page'] = 'member/payment/subscription_edit_page';
			$data['words'] = $this->_language;
			$data['header'] = 'Subscription Settings';
			$data['subscriptions'] = $this->user_subscription_model->get_subscription_detail()->result_array();
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function edit_trial_time()
		{
			$id = $this->input->post('id');
			$data['trial_period_time'] = $this->input->post('trial_period_time');
			$this->form_validation->set_rules('trial_period_time', 'Trial Period Time', 'required|numeric' );
			if($this->form_validation->run() == FALSE)
			{
				$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0323']);
			}
			else
			{
				$this->trial_period_model->update('trial_period',$data,array("id"=>$id));
				$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0324']);
			}
			
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect(site_url('admin/home/setting_page'));
		}
		
		public function edit_subscription_time()
		{
			$id = $this->input->post('id');
			$data['trial_period_time'] = $this->input->post('trial_period_time');
			$this->form_validation->set_rules('trial_period_time', 'Trial Period Time', 'required|numeric' );
			if($this->form_validation->run() == FALSE)
			{
				$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0323']);
			}
			else
			{
				$this->trial_period_model->update('trial_period',$data,array("id"=>$id));
				$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0324']);
			}
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect(site_url('admin/home/setting_page'));
		}
		public function add_subscription()
		{
			$data['general_reg_info_id'] = $this->input->post('general_id');
			$data['amount'] = $this->input->post('amount');
			$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
			if($this->form_validation->run()==FALSE)
			{
				$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0323']);
				$this->session->set_flashdata('flash_message',$flashdata);
				redirect(site_url('admin/payment'));
			}
			else
			{
				$user_general_details = $this->user_model->getUserGeneralRegInfoId($data['general_reg_info_id'])->row_array();
				if($user_general_details['is_company']) {
					$email_data = $this->email_model->get_email(2,12)->row_array();
				}
				else {
					$email_data = $this->email_model->get_email(3,12)->row_array();
				}
				if($email_data['flag_bit']) {
					$this->email_library->sendEmail($user_general_details['email_address'],$email_data['subject'],$email_data['email']);
				}
				$data['payment_mode'] = 'Bank Transfer';
				$data['is_active'] = 1;
				$data['from_date'] = date('Y-m-d');
				$data['is_deleted'] = 0;
				$user_type_id = $this->input->post('user_type_id');
				$subscription = $this->user_subscription_model->get_subscription_detail(array('user_type_id'=>$user_type_id))->row_array();
				$time = strtotime($data['from_date']);
				$data['to_date'] = date("Y-m-d", strtotime("+".$subscription['trial_period_time']."days", $time));
				$this->user_subscription_model->insert('user_subscriptions',$data);
				$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
				$this->session->set_flashdata('flash_message',$flashdata);
				redirect(site_url('admin/payment'));
			}
		}
		
		public function search_user()
		{
			$param = $this->input->post('search_param');
			$data = $this->user_model->search_user_subscription($param)->row_array();
			echo json_encode($data);
		}
		
		public function searchUser($name) 
		{
			$name = urldecode($name);
			$user_array = array();
			$users = $this->user_subscription_model->search_user($name)->result_array();
			foreach($users as $user) 
			{
				$user_array[] = $user['name']."-".$user['username'];
			}
			echo json_encode($user_array);
		}
		
		public function get_user()
		{
			$value=$this->input->post('user_detail');
			$user_array = explode("-",$value);
			$data = $this->user_subscription_model->get_specific_user_from_name(array('g.name'=>$user_array[0],'u.username'=>$user_array[1]))->row_array();
			echo json_encode($data);
		}
		
		public function delete_subscription($id)
		{
			$this->user_subscription_model->delete('user_subscriptions',array('id'=>$id));
            $flashdata = array("type"=>"error","message"=>$this->_language['DTL_0326']);
            $this->session->set_flashdata('flash_message',$flashdata);
			redirect(site_url('admin/payment'));
		}
		
		public function edit_subscription()
		{
			$id = $this->input->post('id');
			$data['amount'] = $this->input->post('amount');
			$this->user_subscription_model->update('user_subscriptions',$data,array('id'=>$id));
			redirect(site_url('admin/payment'));
		}
		
	}