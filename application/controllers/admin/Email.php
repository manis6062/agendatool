<?php
	class Email extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('email_model');
			$this->load->model('language_model');
            $this->load->library('pagination');

        }

        public function index($page = 0)
        {
            $per_page = 10;
            $config['base_url'] = base_url() . "admin/email/index/";
            $config['total_rows'] = count($this->email_model->get_all_email_with_pagination(null, null)->result_array());
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
            $data['emails'] = $this->email_model->get_all_email_with_pagination($per_page, $page)->result_array();
            $data['page'] = 'member/email/index';
            $data['pagination'] = $this->pagination->create_links();
            $data['words'] = $this->_language;
            $data['header'] = 'Emails';
            $data['photo_logo'] = $this->session->userdata('photo');
            $id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		public function update_email() {
			$id = $this->input->post('email_id');
			$data['subject'] = $this->input->post('subject');
			$data['email'] = $this->input->post('email');
			$data['email_date'] = date('Y-m-d');
			$this->email_model->update('email',$data,array('id'=>$id));
			redirect(site_url('admin/email'));
		}
		public function changeStatus($id,$status = 1) {
			$referer = $_SERVER['HTTP_REFERER'];
			$result = $this->email_model->update('email',array('flag_bit'=>$status),array('id'=>$id));
			if($status == 1){
				$success_message = $this->_language['DTL_0362'];
				$error_message = $this->_language['DTL_0364'];
			}
			else 
			{
				$success_message = $this->_language['DTL_0363'];
				$error_message = $this->_language['DTL_0365'];
			}
			if($result)
				$flashdata = array("type" => "success", "message" => $success_message);
			else
				$flashdata = array("type" => "error", "message" => $error_message);
            $this->session->set_flashdata('flash_message', $flashdata);
			redirect($referer);
		}
	}
	
?>