<?php
	class Favorite extends Member_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('favorite_model');
			$this->load->model('user_model');
			$this->load->model('language_model');
			$this->load->model('email_log_model');
		}

		public function index()
		{
			$data['same_user'] = 1;
			$count = 0;
			$data['page'] = 'member/profile/favorite';
			$data['words'] = $this->_language;
			$data['header'] = 'Favorite';
			$data['id'] = $this->session->userdata('id');
			$data['general_user_id'] = $this->session->userdata('general_id');
			$data['favorite_ids'] = $this->favorite_model->get_favorites($data['general_user_id'])->result_array();
			foreach($data['favorite_ids'] as $favorite_id)
			{
				$data['favorites'][$count] = $this->user_model->get_user_info($favorite_id['is_favorite_id'])->row_array();
				$count++;
			}
			$data['userdata'] = $this->user_model->get_user_info($data['general_user_id'])->row_array();
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
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
	}
?>