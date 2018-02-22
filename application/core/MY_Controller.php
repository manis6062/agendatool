<?php
	class MY_Controller extends CI_Controller
	{
		var $_container;
		protected $_language;
		public function __construct()
		{
			parent::__construct();
			$this->load->model('language_model');
			$this->load->model('translation_model');
			$this->load->model('keyword_model');
			$this->main_language();
		}
		
		public function set_language($id=null)
		{
			$keywords = $this->keyword_model->get_all_keywords()->result_array();
			$language_id = 1;
			if($id)
			{
				$language_id = $id;
				$this->session->set_userdata('language_id',$id);
			}
			if($this->session->userdata('language_id') == '')
				$language_id = 1;
			else
				$language_id = $this->session->userdata('language_id');
			foreach($keywords as $keyword)
			{
				$data = $this->translation_model->select_specific_translation(array('keywords_id'=>$keyword['id'],'language_type_id'=>$language_id))->row_array();
				$this->_language[$keyword['keyword']] = $data['text'];
				if($this->_language[$keyword['keyword']] == '')
				{
					$default_translation = $this->translation_model->select_specific_translation(array('keywords_id'=>$keyword['id'],'language_type_id'=>1))->row_array();
					$this->_language[$keyword['keyword']] = $default_translation['text'];
				}
			}
			$language_data = $this->language_model->select_specific_language(array('id'=>$language_id))->row_array();
			$this->session->set_userdata('flag_icon',$language_data['flag']);
			$this->redirect_back();

		}
		
		public function main_language()
		{
			if($this->session->userdata('language_id')=='')
			{
				$keywords = $this->keyword_model->get_all_keywords()->result_array();
				foreach($keywords as $keyword)
				{
					$data = $this->translation_model->select_specific_translation(array('keywords_id'=>$keyword['id'],'language_type_id'=>1))->row_array();
					$this->_language[$keyword['keyword']] = $data['text'];
				}
				$language_data = $this->language_model->select_specific_language(array('id'=>1))->row_array();
			
			
				$this->session->set_userdata('flag_icon',$language_data['flag']);
			}
			else
			{
				$keywords = $this->keyword_model->get_all_keywords()->result_array();
				foreach($keywords as $keyword)
				{
					$data = $this->translation_model->select_specific_translation(array('keywords_id'=>$keyword['id'],'language_type_id'=>$this->session->userdata('language_id')))->row_array();
					$this->_language[$keyword['keyword']] = $data['text'];
					if($this->_language[$keyword['keyword']] == '')
					{
						$default_translation = $this->translation_model->select_specific_translation(array('keywords_id'=>$keyword['id'],'language_type_id'=>1))->row_array();
						$this->_language[$keyword['keyword']] = $default_translation['text'];
					}
				}
				
				$language_data = $this->language_model->select_specific_language(array('id'=>$this->session->userdata('language_id')))->row_array();
			
			
				$this->session->set_userdata('flag_icon',$language_data['flag']);
			}
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
	}
include_once('Member_Controller.php');
include_once('Admin_Controller.php');
include_once('Frontend_Controller.php');
?>