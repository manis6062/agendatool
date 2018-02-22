<?php
	class User extends Admin_Controller
	{
		var $upload_path = 'uploads/userimage';
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('trial_period_model');
			$this->load->model('company_info_model');
			$this->load->model('trainer_info_model');
			$this->load->model('language_model');
			$this->load->library('pagination');
		}


		public function index($page = 0)
		{
			$filter = 'All';
			$active_status = 'All';
			
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
				if($this->input->post('active_status'))
				{
					$active_status = $this->input->post('active_status');
					$this->session->set_userdata('active_status',$active_status);
				}
				else
					$this->session->unset_userdata('active_status');
				if($this->input->post('per_page'))
				{
					$per_page = $this->input->post('per_page');
					$this->session->set_userdata('per_page',$per_page);
				}
				else
					$this->session->unset_userdata('per_page');
				if($this->input->post('filter'))
				{
					$filter = $this->input->post('filter');
					$this->session->set_userdata('filter',$filter);
				}
				else
					$this->session->unset_userdata('filter');
				if($this->session->userdata('searchByName')!='')
					$search_parameters['searchByName'] = $this->session->userdata('searchByName');
				if($this->session->userdata('searchByUserName')!='')
					$search_parameters['searchByUserName'] = $this->session->userdata('searchByUserName');
				if($this->session->userdata('searchByEmailAddress')!='')
					$search_parameters['searchByEmailAddress'] = $this->session->userdata('searchByEmailAddress');
				if($this->session->userdata('active_status')!='')
					$active_status = $this->session->userdata('active_status');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
				if($this->session->userdata('filter')!='')
					$filter = $this->session->userdata('filter');
			}
			else
			{

				if($this->session->userdata('searchByName')!='')
					$search_parameters['searchByName'] = $this->session->userdata('searchByName');
				if($this->session->userdata('searchByUserName')!='')
					$search_parameters['searchByUserName'] = $this->session->userdata('searchByUserName');
				if($this->session->userdata('searchByEmailAddress')!='')
					$search_parameters['searchByEmailAddress'] = $this->session->userdata('searchByEmailAddress');
				if($this->session->userdata('active_status')!='')
					$active_status = $this->session->userdata('active_status');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
				if($this->session->userdata('filter')!='')
					$filter = $this->session->userdata('filter');
			}
			
			
			$config['base_url'] = base_url()."admin/user/index/";
			if(!empty($search_parameters))
			{
				if($filter == 'All')
					$config['total_rows'] = count($this->user_model->get_users(null,null,null,$search_parameters,$active_status)->result_array());
				elseif($filter == 'Trainer')
					$config['total_rows'] = count($this->user_model->get_users(3,null,null,$search_parameters,$active_status)->result_array());
				elseif($filter == 'Company')
					$config['total_rows'] = count($this->user_model->get_users(2,null,null,$search_parameters,$active_status)->result_array());
			}
			else
			{
				if($filter == 'All')
					$config['total_rows'] = count($this->user_model->get_users(null,null,null,null,$active_status)->result_array());
				elseif($filter == 'Trainer')
					$config['total_rows'] = count($this->user_model->get_users(3,null,null,null,$active_status)->result_array());
				elseif($filter == 'Company')
					$config['total_rows'] = count($this->user_model->get_users(2,null,null,null,$active_status)->result_array());
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
			
			$data['page'] = 'member/user/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Users';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			if(!empty($search_parameters))
			{
				if($filter == 'All')
					$data['users'] = ($this->user_model->get_users(null,$per_page,$page,$search_parameters,$active_status)->result_array());
				elseif($filter == 'Trainer')
					$data['users'] = ($this->user_model->get_users(3,$per_page,$page,$search_parameters,$active_status)->result_array());
				elseif($filter == 'Company')
					$data['users'] = ($this->user_model->get_users(2,$per_page,$page,$search_parameters,$active_status)->result_array());
			}
			else
			{
				if($filter == 'All')
					$data['users'] = ($this->user_model->get_users(null,$per_page,$page,null,$active_status)->result_array());
				elseif($filter == 'Trainer')
					$data['users'] = ($this->user_model->get_users(3,$per_page,$page,null,$active_status)->result_array());
				elseif($filter == 'Company')
					$data['users'] = ($this->user_model->get_users(2,$per_page,$page,null,$active_status)->result_array());
			}
			$data['pagination'] = $this->pagination->create_links();
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}

		public function delete_user($id)
		{
			$user_data = $this->user_model->get_user_info($id)->row_array();
			$this->trial_period_model->delete("trial_period",array("id"=>$user_data['trial_period_id']));
			$this->delete_image($user_data['photo_logo']);
			if($user_data['is_company'] == 1)
			{
				$companydata=$this->company_info_model->get_specific_company($id)->row_array();
				$this->company_info_model->delete('company_info',array('id'=>$companydata['id']));
			}
			else
			{
				$trainerdata=$this->trainer_info_model->get_specific_trainer($id)->row_array();
				$this->trainer_info_model->delete('trainer_info',array('id'=>$trainerdata['id']));
			}
			$this->user_model->delete('speciality_detail',array('general_reg_info_id'=>$id));
			$this->user_model->delete('user_login',array('id'=>$user_data['user_login_id']));
            $this->user_model->delete('general_reg_info',array('id'=>$id));
			$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0326']);
			$this->session->set_flashdata('flash_message',$flashdata);
			$this->redirect_back();
		}

		public function delete_image($filename)
		{
			@unlink($this->upload_path . '/' . $filename);
		} 	

		public function deactivate()
		{
			$id = $this->input->post('id');
			$userdata = $this->user_model->get_user_info($id)->row_array();
			$data['is_active'] = 0;
			$this->user_model->update('user_login',$data,array('id'=>$userdata['user_id']));
		}

		public function activate()
		{
			$id = $this->input->post('id');
			$userdata = $this->user_model->get_user_info($id)->row_array();
			$data['is_active'] = 1;
			$this->user_model->update('user_login',$data,array('id'=>$userdata['user_id']));
		}

		public function redirect_back()
		{
			if(!empty($_SERVER['HTTP_REFERER']))
		        {
		            header('Location: '.$_SERVER['HTTP_REFERER']);
		        }
		        else
		        {
		            header('Location: http://'.$_SERVER['SERVER_NAME']);
		        }
		}



        public function change_to_inactive($id){
            $user_data = $this->user_model->get_user_info($id)->row_array();
            $data['is_active'] = 0;
            $this->user_model->update('user_login',$data,array('id'=>$user_data['user_login_id']));
            $flashdata = array("type"=>"error","message"=>$this->_language['DTL_0326']);
            $this->session->set_flashdata('flash_message',$flashdata);
            $this->redirect_back();
        }
		
	}