<?php
	class Field extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('question_model');
			$this->load->model('section_model');
			$this->load->model('option_model');
			$this->load->model('answer_model');
			$this->load->model('language_model');
			$this->load->model('field_type_model');
			$this->load->library('form_validation');
			$this->load->library('pagination');
		}


		public function index()
		{
			$per_page = 10;
			if($this->input->post())
			{
				if($this->input->post('searchByQuestion'))
				{
					$searchByQuestion = $this->input->post('searchByQuestion');
					$this->session->set_userdata('searchByQuestion',$searchByQuestion);
				}
				else
					$this->session->unset_userdata('searchByQuestion');
				if($this->input->post('searchByType'))
				{
					$searchByType = $this->input->post('searchByType');
					$this->session->set_userdata('searchByType',$searchByType);
				}
				else
					$this->session->unset_userdata('searchByType');
				if($this->input->post('searchBySection'))
				{
					$searchBySection = $this->input->post('searchBySection');
					$this->session->set_userdata('searchBySection',$searchBySection);
				}
				else
					$this->session->unset_userdata('searchBySection');
				if($this->input->post('searchByRequired'))
				{
					$searchByRequired = $this->input->post('searchByRequired');
					$this->session->set_userdata('searchByRequired',$searchByRequired);
				}
				else
					$this->session->unset_userdata('searchByRequired');
				if($this->input->post('per_page'))
				{
					$per_page = $this->input->post('per_page');
					$this->session->set_userdata('per_page',$per_page);
				}
				else
					$this->session->unset_userdata('per_page');
				if($this->session->userdata('searchByQuestion')!='')
					$search_parameters['searchByQuestion'] = $this->session->userdata('searchByQuestion');
				if($this->session->userdata('searchByType')!='')
					$search_parameters['searchByType'] = $this->session->userdata('searchByType');
				if($this->session->userdata('searchBySection')!='')
					$search_parameters['searchBySection'] = $this->session->userdata('searchBySection');
				if($this->session->userdata('searchByRequired')!='')
					$search_parameters['searchByRequired'] = $this->session->userdata('searchByRequired');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
			}
			else
			{

				if($this->session->userdata('searchByQuestion')!='')
					$search_parameters['searchByQuestion'] = $this->session->userdata('searchByQuestion');
				if($this->session->userdata('searchByType')!='')
					$search_parameters['searchByType'] = $this->session->userdata('searchByType');
				if($this->session->userdata('searchBySection')!='')
					$search_parameters['searchBySection'] = $this->session->userdata('searchBySection');
				if($this->session->userdata('searchByRequired')!='')
					$search_parameters['searchByRequired'] = $this->session->userdata('searchByRequired');
				if($this->session->userdata('per_page')!='')
					$per_page = $this->session->userdata('per_page');
			}

			$config['base_url'] = base_url()."admin/field/index/";

			if(!empty($search_parameters))
			{
					$config['total_rows'] = count($this->question_model->get_all_questions(null,null,$search_parameters)->result_array());
			}
			else
			{
					$config['total_rows'] = count($this->question_model->get_all_questions(null,null,null)->result_array());
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

			if(!empty($search_parameters))
			{
					$data['questions'] = ($this->question_model->get_all_questions($per_page,$page,$search_parameters)->result_array());
			}
			else
			{
					$data['questions'] = ($this->question_model->get_all_questions($per_page,$page,null)->result_array());
			}

			$data['page'] = 'member/field/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Fields';
			$data['field_types'] = $this->field_type_model->get_field_types()->result_array();
			$data['sections'] = $this->section_model->get_sections()->result_array();
			$data['photo_logo'] = $this->session->userdata('photo');
			$this->check_options();
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$data['pagination'] = $this->pagination->create_links();
			$this->load->view($this->_container,$data);
		}
		
		public function add_question($question_id = null)
		{
			$data['page'] = 'member/field/add_question';
			$data['words'] = $this->_language;
			$data['header'] = 'Questions';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['field_types'] = $this->field_type_model->get_field_types()->result_array();
			$data['sections'] = $this->section_model->get_sections()->result_array();
			$data['question_id'] = $question_id;
			if($question_id)
			{
				$data['question'] = $this->question_model->get_specific_question($question_id)->row_array();
			}
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function option($question_id)
		{
			$data['page'] = 'member/field/option';
			$data['words'] = $this->_language;
			$data['header'] = 'Options';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['options'] = $this->option_model->get_options($question_id)->result_array();
			$data['option_count'] = count($this->option_model->get_options($question_id)->result_array());
			$data['question_id'] = $question_id;
			$data['question_name'] = $this->question_model->get_question_name($question_id)->row_array();
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function add_option($question_id)
		{
			$data['page'] = 'member/field/add_option';
			$data['words'] = $this->_language;
			$data['header'] = 'Options';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['question_id'] = $question_id;
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function save_question()
		{
			$data = $this->_get_posted_question_data();
			$data['is_deleted'] = 0;
			$this->form_validation->set_rules('question', 'Question', 'required');
			$this->form_validation->set_rules('field_type', 'Type', 'required');
			$this->form_validation->set_rules('section', 'Section', 'required');
			$this->form_validation->set_message('required','%s '.$this->_language['DTL_0301']);
			if($this->form_validation->run()!=FALSE)
			{
				if($data['id']==null)
				{
					$this->question_model->insert('question',$data);
					$question_id = $this->question_model->get_last_inserted_id();
					if($data['type_id']!=1)
						$this->add_option($question_id);
					else
						redirect(site_url('admin/field'));
					$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
					$this->session->set_flashdata('flash_message',$flashdata);
				}
				else
				{
					$success = $this->question_model->update('question',$data,array('id'=>$data['id']));
					if($data['type_id']==1)
					{
						$this->option_model->delete('option',array("question_id"=>$data['id']));
						$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0324']);
						$this->session->set_flashdata('flash_message',$flashdata);
						redirect(site_url('admin/field'));
					}
					elseif($data['type_id']!=1)
					{
						$this->add_option($data['id']);
					}

				}
			}
			else
			{
				if($data['id']!=null)
					$this->add_question($data['id']);
				else
					$this->add_question();
			}
			//$this->question_model->trans_complete();
		}
		public function save_option()
		{
			$optiondata = $this->_get_posted_option_data();
			$this->form_validation->set_rules('options[]', 'Option', 'required');
			$this->form_validation->set_message('required','%s '.$this->_language['DTL_0301']);
			if($this->form_validation->run()!=FALSE)
			{
				foreach($optiondata['values'] as $data['value'])
				{
					$data['question_id'] = $this->input->post('question_id');
					$data['is_deleted'] = 0;
					$this->option_model->insert('option',$data);
				}
				$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
				$this->session->set_flashdata('flash_message',$flashdata);
				$question_id = $this->input->post('question_id');
				redirect(base_url()."admin/field/option/".$question_id);
				//$this->option($question_id);
			}
			else
			{
				$this->add_option($this->input->post('question_id'));
			}
		}
		
		private function _get_posted_question_data()
		{
			$data['id'] = $this->input->post('question_id');
			$data['type_id'] = $this->input->post('field_type');
			$data['section_id'] = $this->input->post('section');
			$data['name'] = $this->input->post('question');
			if($this->input->post('is_required')==1)
				$data['is_required'] = 1;
			else
				$data['is_required'] = 0;
			return $data;
		}
		
		private function _get_posted_option_data()
		{
			$data['values'] = $this->input->post('options');
			return $data;
		}
		
		
		public function delete_question($question_id)
		{
			$this->question_model->delete('question',array('id'=>$question_id));
			$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0326']);
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect(site_url('admin/field'));
		}
		
		public function edit_question_page($question_id)
		{
			$this->add_question($question_id);
		}
		
		public function delete_option($option_id)
		{
			$result = $this->option_model->get_specific_option($option_id)->row_array();
			$question_id = $result['question_id'];
			$this->option_model->delete('option',array('id'=>$option_id));
			$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0326']);
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect(site_url('admin/field/option').'/'.$question_id);
		}
		
		public function edit_option_page($option_id)
		{
			$data['page'] = 'member/field/edit_option';
			$data['words'] = $this->_language;
			$data['header'] = 'Edit Options';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['option'] = $this->option_model->get_specific_option($option_id)->row_array();
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$this->load->view($this->_container,$data);
		}
		
		public function edit_option()
		{
			$data = $this->_get_edit_option_data();
			$this->form_validation->set_rules('value', 'Option', 'required');
			$this->form_validation->set_message('required','%s '.$this->_language['DTL_0301']);
			if($this->form_validation->run()!=FALSE)
			{
				$this->add_option($this->input->post('question_id'));
				$this->option_model->update('option',$data,array('id'=>$data['id']));
				$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0324']);
				$this->session->set_flashdata('flash_message',$flashdata);
				redirect(site_url('admin/field/option').'/'.$data['question_id']);
			}
			else
			{
				$this->edit_option_page($data['id']);
			}
		}
		
		private function _get_edit_option_data()
		{
			$data['id'] = $this->input->post('id');
			$data['value'] = $this->input->post('value');
			$data['question_id'] = $this->input->post('question_id');
			$data['is_deleted'] = $this->input->post('is_deleted');
			return $data;
		}
		
		public function check_options()
		{
			$questions = $this->question_model->get_questions(array('type_id'=>2,'type_id'=>3))->result_array();
			foreach($questions as $question)
			{
				$result = $this->option_model->get_options($question['id'])->result_array();
				if(empty($result))
				{
					$this->add_option($question['id']);
				}
			}
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
		
	}