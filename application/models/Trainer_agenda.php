<?php
	class Trainer_agenda extends Member_Controller
	{
		var $upload_document_path = 'uploads/document';
		public function __construct()
		{
			parent::__construct();
			$this->load->model('trainer_agenda_model');
			$this->load->model('user_model');
			$this->load->model('trainer_info_model');
			$this->load->model('trainer_agenda_time_model');
			$this->load->model('document_model');
			$this->load->library('pagination');
		}

		public function get_trainer_agenda($id)
		{
			$data['agendas'] = $this->trainer_agenda_model->get_trainer_agenda($id)->result_array();
			$data['header'] = "Trainer Agenda";
			$data['page'] = "member/profile/trainer_agenda";
			$data['general_user_id'] = $this->session->userdata('general_id');
			$data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($data['userdata']['id'])->row_array();
			if($id == $data['general_user_id'])
				$data['same_user'] = 1;
			else
				$data['same_user'] = 0;
			$general_info_id = $this->session->userdata('general_id');
			$data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
			$this->load->view($this->_container,$data);
		}

		public function save_agenda()
		{
			$data = $this->_get_posted_data();
			$data['date_time'] = date('Y-m-d H:i:s');
			$data['is_appointed'] = 1;
			$data['appointed_by'] = $this->session->userdata('general_id');
			$data['is_required'] = 1;
			$data['is_deleted'] = 0;
			$success = $this->trainer_agenda_model->insert('trainer_agenda',$data);
			$trainer_agenda_id = $this->user_model->get_last_inserted_id();
			$this->save_document($trainer_agenda_id);
			$this->save_date($trainer_agenda_id);
			
			//Mail send start
			$maildata = $this->email_model->get_email(3,3)->row_array();
			$trainerdata = $this->user_model->get_user_by_trainer_id($data['trainer_info_id'])->row_array();
			if($maildata['flag_bit'] == 1) {
				$this->mail_send("noreply@agendatool.com","Admin",$trainerdata['email_address'],$maildata['email_type_name'],$maildata['email']);//send mail to trainer
				//Mail send end
			}
			
			$flashdata = array("type"=>"success","message"=>"Agenda Booked.");
			$this->session->set_flashdata('flash_message',$flashdata);
			$this->redirect_back();
		}
		
		public function mail_send($from,$name,$to,$subject,$message)
		{
			$this->load->library('email');
			$this->email->from($from, $name);
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->set_mailtype('html');
			$this->email->send();
			$flashdata = array("type"=>"success","message"=>"Mail Sent.");
			$this->session->set_flashdata('flash_message',$flashdata);
		}

		private function _get_posted_data()
		{
			$data['trainer_info_id'] = $this->input->post('trainer_id');
			$data['appointment_name'] = $this->input->post('appointmentName');
			$data['location'] = $this->input->post('location');
			$data['appointment_description'] = $this->input->post('description');
			$data['appoint_date'] = $this->input->post('date');
			return $data;
		}

		public function save_date($id)
		{
			$selected_time_array = $this->_get_date_data();
			foreach($selected_time_array as $s)
			{
				$data['trainer_agenda_id'] = $id;
				$data['work_time'] = $s;
				$data['is_deleted'] = 0;
				$this->trainer_agenda_time_model->insert('trainer_agenda_time',$data);
			}
		}
		
		public function save_document($trainer_agenda_id)
		{
			$this->upload_document();
			$document_name = $this->upload->data();
			if($document_name['file_name']!=null)
			{
				$documentdata['document_name'] = $document_name['file_name'];
				$documentdata['trainer_info_id'] = $this->input->post('trainer_id');
				$documentdata['trainer_agenda_id'] = $trainer_agenda_id;
				$documentdata['uploaded_date'] = date("Y-m-d H:i:s");
				$documentdata['display_order'] = 1;
				$documentdata['is_deleted'] = 0;
				$this->document_model->insert('document',$documentdata);
				$flashdata = array("type"=>"success","message"=>"Document Uploaded.");
				$this->session->set_flashdata('flash_message',$flashdata);
			}
			else
			{
				$flashdata = array("type"=>"error","message"=>"File Not Chosen.");
				$this->session->set_flashdata('flash_message',$flashdata);
			}
			
		}
		
		public function upload_document()
		{
			$config['upload_path'] = $this->upload_document_path;
	        $config['allowed_types'] = 'pdf|docx|doc|txt';
	        $config['max_size'] = 10240;
	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('file_upload'))
	        {
				$flashdata = array("type"=>"error","message"=>$this->upload->display_errors());
				$this->session->set_flashdata('flash_message',$flashdata);
	        }
	        else
	        {
	            $data = array('upload_data' => $this->upload->data());
				$flashdata = array("type"=>"success","message"=>"Document Uploaded.");
				$this->session->set_flashdata('flash_message',$flashdata);
	        }
		}
		
		public function _get_date_data()
		{
			$selected_time = $this->input->post('selectedTime');
			$selected_time_array = explode(',',$selected_time);
			return $selected_time_array;
		}

		public function redirect_back()
		{
			if(isset($_SERVER['HTTP_REFERER']))
		        {
		            header('Location: '.$_SERVER['HTTP_REFERER']);
		        }
		        else
		        {
		            header('Location: http://'.$_SERVER['SERVER_NAME']);
		        }
		}
		
		public function pending_agenda($id,$page = 0)
		{	
			$order = 'DESC';
			$per_page = 5;
			if($this->session->userdata('order'))
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
			if($this->input->post('per_page'))
			{
				$per_page = $this->input->post('per_page');
				$data['per_page'] = $per_page;
				$this->session->set_userdata('per_page',$per_page);
			}
			if($this->input->post('order'))
			{
				$order = $this->input->post('order');
				$data['order'] = $order;
				$this->session->set_userdata('order',$order);
			}
			$config['base_url'] = base_url()."member/trainer_agenda/pending_agenda/".$id;
			$config['total_rows'] = $this->trainer_agenda_model->count_agendas($id);
			$config['per_page'] = $per_page;
			$config['num_links'] = 4;

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
			
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			
			
			$count = 0;
			$data['page'] = 'member/profile/notifications';
			$data['header'] = 'Notifications';
			$data['general_user_id'] = $this->session->userdata('general_id');
			$data['userdata'] = $this->user_model->get_user_info($id)->row_array();
			$data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($data['userdata']['id'])->row_array();
			$data['agendas'] = $this->trainer_agenda_model->get_trainer_agenda($id,$order,$per_page,$page)->result_array();
			$data['pagination'] = $this->pagination->create_links();
			foreach($data['agendas'] as $agenda)
			{
				$data['agendas'][$count]['company_detail'] = $this->user_model->get_user_info($agenda['appointed_by'])->row_array();
				$count++;
			}
			if($id == $data['general_user_id'])
				$data['same_user'] = 1;
			else
				$data['same_user'] = 0;
			$general_info_id = $this->session->userdata('general_id');
			$data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
			$this->load->view($this->_container,$data);
		}
		
		public function ajax_agenda()
		{
			$id = $this->input->post('id');
			$data['agenda'] = $this->trainer_agenda_model->get_agenda($id)->row_array();
			$data['document'] = $this->trainer_agenda_model->get_document_data($id)->row_array();
			echo json_encode($data);
		}
		
		
		public function reject($id)
		{
			$data["is_appointed"] = 0;
			$this->trainer_agenda_model->update('trainer_agenda',$data,array('id'=>$id));
			$this->trainer_agenda_time_model->delete('trainer_agenda_time',array('trainer_agenda_id'=>$id));
			$flashdata = array("type"=>"error","message"=>"Agenda Rejected!!!");
			$this->session->set_flashdata('flash_message',$flashdata);
			$this->redirect_back();
		}
		
		public function accept($id)
		{
			$data["is_confirm"] = 1; 
			$this->trainer_agenda_model->update('trainer_agenda',$data,array('id'=>$id));
			$flashdata = array("type"=>"success","message"=>"Agenda Accepted.");
			$this->session->set_flashdata('flash_message',$flashdata);
			$this->redirect_back();
		}
	}