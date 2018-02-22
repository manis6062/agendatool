<?php
	class Trainer_overview extends Member_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('trainer_agenda_model');
			$this->load->model('user_model');
			$this->load->model('trainer_info_model');
			$this->load->model('trainer_agenda_time_model');
			$this->load->model('speciality_model');
			$this->load->model('email_model');
			$this->load->model('language_model');
			$this->load->model('email_log_model');
			$this->load->library('pagination');
		}
		
		public function index()
		{
			$data['header'] = "Trainer Overview";
			$data['page'] = "member/profile/trainer_overview";
			$data['words'] = $this->_language;
			$general_info_id = $this->session->userdata('general_id');
			$messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
			if(!empty($messages))
			{
				foreach($messages as $message)
				{
					if($message['email_to_id'] == $general_info_id)
					{
						$message_specific = $this->email_log_model->get_specific_mail($message['id'],array('delete_by_receiver'=>0))->row_array();
					}
					elseif($message['email_by_id'] == $general_info_id)
					{
						$message_specific = $this->email_log_model->get_specific_mail($message['id'],array('delete_by_sender'=>0))->row_array();
					}
					if(!empty($message_specific))
						$data['messages'][] = $message_specific;
				}
				$count = 0;
				if(!empty($data['messages']))
					foreach($data['messages'] as $d)
					{
						$data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
						$data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
						$count++;
					}
			}
			$data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
			$data['userdata'] = $this->user_model->get_user_info($general_info_id)->row_array();
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$data['same_user'] = 1;
			$this->load->view($this->_container,$data);
		}
		public function your_appointments($general_id,$offset = 0) {
			$limit = 5;
			$data['words'] = $this->_language;
			$data['general_id'] = $general_id;
			$data['userdata'] = $this->user_model->get_user_info($general_id)->row_array();
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$data['header'] = 'Your Appointments';
			$data['page'] = 'member/profile/company_your_appointments';
			$no_of_agendas = count($this->trainer_agenda_model->getTrainerAcceptedAppointments($general_id,0,0));
			$data['pagination'] = createPagination(base_url()."member/trainer_overview/your_appointments/".$general_id,$no_of_agendas,$limit,5);
			$data['agendas'] = $this->trainer_agenda_model->getTrainerAcceptedAppointments($general_id,$limit,$offset);
			$count = 0;
			if (!empty($data['agendas'])) {
				foreach ($data['agendas'] as $agenda) {
					$data['agendas'][$count]['trainer_detail'] = $this->user_model->get_user_by_trainer_id($agenda['trainer_info_id'])->row_array();
					$count++;
				}
			}
			$this->load->view($this->_container, $data);
		}
		public function view_agendas($id,$page = 0)
		{
			$order = 'DESC';
			$per_page = 5;
			$filter = 'All';
			$search_parameters = null;
			$searchByTrainerName = null;
			$searchByAppointmentName = null;
			$searchByAppointmentDate = null;
			/*if($this->session->userdata('order'))
			{
				$data['order'] = $this->session->userdata('order');
				$order = $data['order'];
			}
			else
				$data['order'] = $order;
			if($this->session->userdata('per_page'))
			{
				$data['per_page'] = $this->session->userdata('per_page');
				$per_page = $data['per_page'];
			}
			else
				$data['per_page'] = $per_page;
			if($this->session->userdata('filter'))
			{
				$data['filter'] = $this->session->userdata('filter');
				$filter = $data['filter'];
			}
			else
				$data['filter'] = $filter;
			if($this->input->post('per_page'))
			{
				$per_page = $this->input->post('per_page');
				$data['per_page'] = $per_page;
				$this->session->set_userdata('per_page',$per_page);
			}
			if($this->input->post('filter'))
			{
				$filter = $this->input->post('filter');
				$data['filter'] = $filter;
				$this->session->set_userdata('filter',$filter);
			}
			if($this->input->post('order'))
			{
				$order = $this->input->post('order');
				$data['order'] = $order;
				$this->session->set_userdata('order',$order);
			}*/

			if($this->input->post())
			{
				if($this->input->post('searchByTrainerName'))
				{
					$searchByTrainerName = $this->input->post('searchByTrainerName');
					$this->session->set_userdata('searchByTrainerName',$searchByTrainerName);
				}
				else
					$this->session->unset_userdata('searchByTrainerName');
				if($this->input->post('searchByAppointmentName'))
				{
					$searchByAppointmentName = $this->input->post('searchByAppointmentName');
					$this->session->set_userdata('searchByAppointmentName',$searchByAppointmentName);
				}
				else
					$this->session->unset_userdata('searchByAppointmentName');
				if($this->input->post('searchByAppointmentDate'))
				{
					$searchByAppointmentDate = $this->input->post('searchByAppointmentDate');
					$this->session->set_userdata('searchByAppointmentDate',$searchByAppointmentDate);
				}
				else
					$this->session->unset_userdata('searchByAppointmentDate');
				if($this->input->post('filter'))
				{
					$filter = $this->input->post('filter');
					$this->session->set_userdata('filter',$filter);
				}
				else
					$this->session->unset_userdata('filter');
				if($this->input->post('per_page'))
				{
					$per_page = $this->input->post('per_page');
					$this->session->set_userdata('per_page',$per_page);
				}
				else
					$this->session->unset_userdata('per_page');
				if($this->input->post('order'))
				{
					$order = $this->input->post('order');
					$this->session->set_userdata('order',$order);
				}
				else
					$this->session->unset_userdata('order');
				if($this->session->userdata('searchByTrainerName')!='')
					$search_parameters['searchByTrainerName'] = $this->session->userdata('searchByTrainerName');
				if($this->session->userdata('searchByAppointmentName')!='')
					$search_parameters['searchByAppointmentName'] = $this->session->userdata('searchByAppointmentName');
				if($this->session->userdata('searchByAppointmentDate')!='')
					$search_parameters['searchByAppointmentDate'] = $this->session->userdata('searchByAppointmentDate');
				if($this->session->userdata('filter')!='')
					$search_parameters['filter'] = $this->session->userdata('filter');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
				if($this->session->userdata('order')!='')
					$order = $this->session->userdata('order');
			}
			else
			{

				if($this->session->userdata('searchByTrainerName')!='')
					$search_parameters['searchByTrainerName'] = $this->session->userdata('searchByTrainerName');
				if($this->session->userdata('searchByAppointmentName')!='')
					$search_parameters['searchByAppointmentName'] = $this->session->userdata('searchByAppointmentName');
				if($this->session->userdata('searchByAppointmentDate')!='')
					$search_parameters['searchByAppointmentDate'] = $this->session->userdata('searchByAppointmentDate');
				if($this->session->userdata('filter')!='')
					$search_parameters['filter'] = $this->session->userdata('filter');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
				if($this->session->userdata('order')!='')
					$order = $this->session->userdata('order');
			}


			$config['base_url'] = base_url()."member/trainer_overview/view_agendas/".$id;
			if($search_parameters)
			{
				if($filter == 'All')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,null,null,null,null,null,null,$search_parameters)->result_array());
				elseif($filter == 'Reserved')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,1,null,null,null,null,null,$search_parameters)->result_array());
				elseif($filter == 'Rejected')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,null,null,1,null,null,null,$search_parameters)->result_array());
				elseif($filter == 'Confirmed')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,null,1,null,null,null,null,$search_parameters)->result_array());
			}
			else
			{
				if($filter == 'All')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id)->result_array());
				elseif($filter == 'Reserved')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,1,null,null)->result_array());
				elseif($filter == 'Rejected')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,null,null,1)->result_array());
				elseif($filter == 'Confirmed')
					$config['total_rows'] = count($this->trainer_agenda_model->get_company_agenda($id,null,1,null)->result_array());
			}
			$config['per_page'] = $per_page;
			$config['num_links'] = 2;

			$config['uri_segment'] = 5; /* segment of your uri which contains the page number */
			
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
			
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
					
			$count = 0;
			$data['page'] = "member/profile/trainer_overview";
			$data['words'] = $this->_language;
			$data['header'] = "Trainer Overview";
			$data['general_user_id'] = $this->session->userdata('general_id');
			$data['userdata'] = $this->user_model->get_user_info($id)->row_array();
			if($search_parameters)
			{
				if($filter == 'All')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,null,null,null,$order,$per_page,$page,$search_parameters)->result_array();
				elseif($filter == 'Reserved')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,1,null,null,$order,$per_page,$page,$search_parameters)->result_array();
				elseif($filter == 'Rejected')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,null,null,1,$order,$per_page,$page,$search_parameters)->result_array();
				elseif($filter == 'Confirmed')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,null,1,null,$order,$per_page,$page,$search_parameters)->result_array();
			}
			else
			{
				if($filter == 'All')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,null,null,null,$order,$per_page,$page)->result_array();
				elseif($filter == 'Reserved')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,1,null,null,$order,$per_page,$page)->result_array();
				elseif($filter == 'Rejected')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,null,null,1,$order,$per_page,$page)->result_array();
				elseif($filter == 'Confirmed')
					$data['agendas'] = $this->trainer_agenda_model->get_company_agenda($id,null,1,null,$order,$per_page,$page)->result_array();
			}
			$data['pagination'] = $this->pagination->create_links();
			
			foreach($data['agendas'] as $agenda)
			{
				$data['agendas'][$count]['trainer_detail'] = $this->user_model->get_user_by_trainer_id($agenda['trainer_info_id'])->row_array();
				$data['agendas'][$count]['trainer_detail']['specialities'] = $this->speciality_model->get_speciality_by_general_id($data['agendas'][$count]['trainer_detail']['general_info_id'])->result_array();
				$count++;
			}
			
			if($id == $data['general_user_id'])
				$data['same_user'] = 1;
			else
				$data['same_user'] = 0;
			$data['header'] = 'View Agendas';
			$general_info_id = $this->session->userdata('general_id');
			$messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
			if(!empty($messages))
			{
				foreach($messages as $message)
				{
					if($message['email_to_id'] == $general_info_id)
					{
						$message_specific = $this->email_log_model->get_specific_mail($message['id'],array('delete_by_receiver'=>0))->row_array();
					}
					elseif($message['email_by_id'] == $general_info_id)
					{
						$message_specific = $this->email_log_model->get_specific_mail($message['id'],array('delete_by_sender'=>0))->row_array();
					}
					if(!empty($message_specific))
						$data['messages'][] = $message_specific; 
				}
				$count = 0;
				if(!empty($data['messages']))
					foreach($data['messages'] as $d)
					{
						$data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
						$data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
						$count++;
					}
			}
			$data['filter'] = $filter;
			$data['order'] = $order;
			$data['per_page'] = $per_page;
			$data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
	}