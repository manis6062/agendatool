<?php
	class export extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('language_model');
		}


		public function index()
		{
			$data['page'] = 'member/export/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Export';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function create_csv_company()
		{
			$csv_data[] = array('contact_person'=>'contact_person','username'=>'username','email_address'=>'email_address','name'=>'name','address'=>'address','zip_code'=>'zip_code','phone_no'=>'phone_no','iban_number'=>'iban_number','bic_number'=>'bic_number');
			$this->load->helper('csv');
			$data= $this->user_model->get_company_data()->result_array();
			$csv_data = array_merge($csv_data,$data);
			array_to_csv($csv_data,'company.csv');
		}
		
		public function create_csv_trainer()
		{
			$csv_data[] = array('education'=>'education','work_experience'=>'work_experience','cost_per_hour'=>'cost_per_hour','username'=>'username','email_address'=>'email_address','name'=>'name','address'=>'address','zip_code'=>'zip_code','phone_no'=>'phone_no','iban_number'=>'iban_number','bic_number'=>'bic_number');
			$this->load->helper('csv');
			$data= $this->user_model->get_trainer_data()->result_array();
			$csv_data = array_merge($csv_data,$data);
			array_to_csv($csv_data,'trainer.csv');
		}
		
		public function csv_create()
		{
			$search_parameters['searchByName'] = $this->input->post('searchByName');
			$search_parameters['searchByUserName'] = $this->input->post('searchByUserName');
			$search_parameters['searchByEmailAddress'] = $this->input->post('searchByEmailAddress');
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			$active_status = $this->input->post('active_status');
			$filter = $this->input->post('filter');
			if(!empty($search_parameters))
			{
				if($filter == 'Trainer' && $active_status=='Active')
					$data['users'] = $this->user_model->get_trainer_data(array('u.user_type_id'=>3,'u.is_active'=>1),$search_parameters,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Active')
					$data['users'] = $this->user_model->get_company_data(array('u.user_type_id'=>2,'u.is_active'=>1),$search_parameters,$from_date,$to_date)->result_array();
				elseif($filter == 'Trainer' && $active_status=='Inactive')
					$data['users'] = $this->user_model->get_trainer_data(array('u.user_type_id'=>3,'u.is_active'=>0),$search_parameters,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Inactive')
					$data['users'] = $this->user_model->get_company_data(array('u.user_type_id'=>2,'u.is_active'=>0),$search_parameters,$from_date,$to_date)->result_array();
			}
			else
			{
				if($filter == 'Trainer' && $active_status=='Active')
					$data['users'] = $this->user_model->get_trainer_data(array('u.user_type_id'=>3,'u.is_active'=>1),null,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Active')
					$data['users'] = $this->user_model->get_company_data(array('u.user_type_id'=>2,'u.is_active'=>1),null,$from_date,$to_date)->result_array();
				elseif($filter == 'Trainer' && $active_status=='Inactive')
					$data['users'] = $this->user_model->get_trainer_data(array('u.user_type_id'=>3,'u.is_active'=>0),null,$from_date,$to_date)->result_array();
				elseif($filter == 'Company' && $active_status=='Inactive')
					$data['users'] = $this->user_model->get_company_data(array('u.user_type_id'=>2,'u.is_active'=>0),null,$from_date,$to_date)->result_array();
			}
			if($filter =='Trainer')
			{
				$csv_data[] = array('education'=>'education','work_experience'=>'work_experience','cost_per_hour'=>'cost_per_hour','username'=>'username','email_address'=>'email_address','name'=>'name','address'=>'address','zip_code'=>'zip_code','phone_no'=>'phone_no','iban_number'=>'iban_number','bic_number'=>'bic_number');
				

			}
			elseif($filter=='Company')
			{
				$csv_data[] = array('contact_person'=>'contact_person','username'=>'username','email_address'=>'email_address','name'=>'name','address'=>'address','zip_code'=>'zip_code','phone_no'=>'phone_no','iban_number'=>'iban_number','bic_number'=>'bic_number');
			}
			//print_r($this->session->all_userdata());
			//exit;
			$this->load->helper('csv');
			$csv_data = array_merge($csv_data,$data['users']);
			array_to_csv($csv_data,'data.csv');
			//redirect(site_url('admin/home/setting_page'));
		}
		
	}