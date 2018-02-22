<?php
	class Language extends Admin_Controller
	{
		var $upload_path = 'uploads/flags';
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('api_model');
			$this->load->model('trial_period_model');
			$this->load->model('language_model');
			$this->load->model('keyword_model');
			$this->load->model('translation_model');
			$this->load->model('user_subscription_model');
			$this->load->library('form_validation');
		}
		
		public function index()
		{
			if($this->session->userdata('tab_index')!= 'language_list' && $this->session->userdata('tab_index')!= 'add_language' && $this->session->userdata('tab_index')!= 'add_translation' && $this->session->userdata('tab_index')!= 'add_keyword')
				$this->session->set_userdata('tab_index','language_list');
			$limit = 10;
			$offset = 0;
			$searchByEnglishTranslation = null;
			if($this->input->post())
			{
				if($this->input->post('limit'))
				{
					$limit = $this->input->post('limit');
					$this->session->set_userdata('limit',$limit);
				}
				else
					$this->session->unset_userdata('limit');
				if($this->input->post('offset'))
				{
					$offset = $this->input->post('offset');
					$this->session->set_userdata('offset',$offset);
				}
				else
					$this->session->unset_userdata('offset');
				if($this->input->post('searchByEnglishTranslation'))
				{
					$searchByEnglishTranslation = $this->input->post('searchByEnglishTranslation');
					$this->session->set_userdata('searchByEnglishTranslation',$searchByEnglishTranslation);
				}
				else
					$this->session->unset_userdata('searchByEnglishTranslation');

			}
			else
			{
				if($this->session->userdata('limit')!='')
					$limit = $this->session->unset_userdata('limit');
				if($this->session->userdata('offset')!='')
					$offset = $this->session->unset_userdata('offset');
				if($this->session->userdata('searchByEnglishTranslation')!='')
					$searchByEnglishTranslation = $this->session->unset_userdata('searchByEnglishTranslation');
			}
			
			$data['page'] = 'member/language/index';
			$data['words'] = $this->_language;
			$data['header'] = 'Languages';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['languages'] = $this->language_model->select_specific_language()->result_array();
			if($searchByEnglishTranslation==null)
				$data['keywords'] = $this->language_model->get_default_language($limit,$offset)->result_array();
			else
				$data['keywords'] = $this->language_model->get_default_language($limit,$offset,$searchByEnglishTranslation)->result_array();
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			if($searchByEnglishTranslation==null)
				$data['total_translation'] = count($this->language_model->get_default_language()->result_array());
			else
				$data['total_translation'] = count($this->language_model->get_default_language(null,null,$searchByEnglishTranslation)->result_array());
			$data['searchByEnglishTranslation'] = $this->session->userdata('searchByEnglishTranslation');
			$data['limit'] = $limit;
			$data['offset'] = $offset;
			$this->load->view($this->_container,$data);
		}
		
		public function add_language()
		{
			$data['lang_name'] = $this->input->post('lang_name');
			$data['short_name'] = $this->input->post('short_name');
			$this->upload_image();
			$imagename = $this->upload->data();
			$data['flag'] = $imagename['file_name'];
			$this->language_model->insert('language',$data);
			$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect('admin/language');
		}
		
		public function add_keyword()
		{
			$data['keyword'] = $this->input->post('keyword');
			$this->keyword_model->insert('keywords',$data);
			$this->add_default_translation($this->keyword_model->get_last_inserted_id());
			$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect('admin/language');
		}
		
		public function add_default_translation($keywords_id)
		{
			$data['text'] = $this->input->post('text');
			$data['keywords_id'] = $keywords_id;
			$data['language_type_id'] = 1;
			$this->translation_model->insert('translation',$data);
			$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
			$this->session->set_flashdata('flash_message',$flashdata);
		}

		public function show_php_info(){
			ini_set('mbstring.detect_order','pass');
			ini_set('mbstring.http_input','pass');
			ini_set('mysql.default-character-set','utf8');
			phpinfo();
						
		}
		public function add_translation(){
			ini_set('mbstring.http_input','pass');
			ini_set('mbstring.internal_encoding','EUC-JP');
			ini_set('mysql.default-character-set','utf8');
			$text_array = $this->input->post('text_array');
			//phpinfo();
			$data['keywords_id'] = ($this->input->post('keywords_id'));
			
			foreach($text_array as $language_type_id=>$text)
			{
				$data['language_type_id'] = $language_type_id;
				$data['text'] = ($text);
				//print_r($data);
				if($this->translation_model->check_if_exists($language_type_id,$data['keywords_id'])==0)
					$this->translation_model->insert('translation',$data);
				else
					$this->translation_model->update('translation',$data,array('language_type_id'=>$data['language_type_id'],'keywords_id'=>$data['keywords_id']));
			}
			//exit;
			$flashdata = array("type"=>"success","message"=>$this->_language['DTL_0325']);
			$this->session->set_flashdata('flash_message',$flashdata);
			redirect('admin/language');
		}
		
		public function get_data_from_keyword_id()
		{
			$keywords_id = $this->input->post('keywords_id');
			$data = $this->translation_model->select_specific_translation(array('keywords_id'=>$keywords_id))->result_array();
			echo json_encode($data);
		}
		
		public function delete_language($id)
		{
			$this->language_model->delete('language',array('id'=>$id));
			$this->translation_model->delete('translation',array('language_type_id'=>$id));
			redirect('admin/language');
		}
		
		public function upload_image()
		{
			$config['upload_path'] = $this->upload_path;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 102400;
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('photo_logo'))
			{
				$error_message = $this->upload->display_errors();
				if($error_message = "The filetype you are attempting to upload is not allowed.")
					$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0199']);
				elseif($error_message = "You did not select a file to upload.")
					$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0201']);
				elseif($error_message = "The file you are attempting to upload is larger than the permitted size.")
					$flashdata = array("type"=>"error","message"=>$this->_language['DTL_0316']);
				$this->session->set_flashdata('flash_message',$flashdata);
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
			}
		}
		
		public function edit_language_page($language_id)
		{
			$data['page'] = 'member/language/edit_language';
			$data['words'] = $this->_language;
			$data['header'] = 'Edit Languages';
			$data['photo_logo'] = $this->session->userdata('photo');
			$id = $this->session->userdata('general_id');
			$data['languages'] = $this->language_model->select_specific_language()->result_array();
			$data['keywords'] = $this->language_model->get_default_language()->result_array();
			$data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array();//general info id
			$data['language'] = $this->language_model->select_specific_language()->result_array();
			$data['edit_language'] = $this->language_model->select_specific_language(array('id'=>$language_id))->row_array();
            $flashdata = array("type" => "success", "message" => $this->_language['DTL_0324']);
            $this->session->set_flashdata('flash_message', $flashdata);
            $this->load->view($this->_container,$data);
		}

		public function edit_language()
		{
			$data['id'] = $this->input->post('id');
			$data['lang_name'] = $this->input->post('lang_name');
			$data['short_name'] = $this->input->post('short_name');
			if($_FILES['photo_logo']['name']!=null)
			{
				$this->upload_image_edit();
				$imagename = $this->upload->data();
				$data['flag'] = $imagename['file_name'];
			}
			$this->language_model->update('language',$data,array('id'=>$data['id']));
            $flashdata = array("type" => "success", "message" => $this->_language['DTL_0324']);
            $this->session->set_flashdata('flash_message', $flashdata);
			redirect('admin/language');
		}

		public function upload_image_edit()
		{
			$config['upload_path'] = $this->upload_path;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 102400;
			$this->load->library('upload', $config);
			
			if ( $this->upload->do_upload('photo_logo'))
			{
				$data = array('upload_data' => $this->upload->data());
			}
		}

		public function get_translation_pagination()
		{
			$offset = $this->input->post('offset');
			$limit = $this->input->post('limit');
			
			$searchByEnglishTranslation = null;
			if($this->input->post())
			{
				if($this->input->post('limit'))
				{
					$limit = $this->input->post('limit');
					$this->session->set_userdata('limit',$limit);
				}
				else
					$this->session->unset_userdata('limit');
				if($this->input->post('offset'))
				{
					$offset = $this->input->post('offset');
					$this->session->set_userdata('offset',$offset);
				}
				else
					$this->session->unset_userdata('offset');
				if($this->input->post('searchByEnglishTranslation'))
				{
					$searchByEnglishTranslation = $this->input->post('searchByEnglishTranslation');
					$this->session->set_userdata('searchByEnglishTranslation',$searchByEnglishTranslation);
				}
				else
					$this->session->unset_userdata('searchByEnglishTranslation');
				if($this->session->userdata('limit')!='')
					$limit = $this->session->userdata('limit');
				if($this->session->userdata('offset')!='')
					$offset = $this->session->userdata('offset');
				if($this->session->userdata('searchByEnglishTranslation')!='')
					$searchByEnglishTranslation = $this->session->userdata('searchByEnglishTranslation');
			}
			else
			{
				if($this->session->userdata('limit')!='')
					$limit = $this->session->userdata('limit');
				if($this->session->userdata('offset')!='')
					$offset = $this->session->userdata('offset');
				if($this->session->userdata('searchByEnglishTranslation')!='')
					$searchByEnglishTranslation = $this->session->userdata('searchByEnglishTranslation');
			}
			if($searchByEnglishTranslation==null)
				$keywords = $this->language_model->get_default_language($limit,$offset)->result_array();
			else
				$keywords = $this->language_model->get_default_language($limit,$offset,$searchByEnglishTranslation)->result_array();
			if($searchByEnglishTranslation==null)
				$total_translation = count($this->language_model->get_default_language()->result_array());
			else
				$total_translation = count($this->language_model->get_default_language(null,null,$searchByEnglishTranslation)->result_array());
			$count = ($offset * $limit) + 1;
			if(count($keywords) > 0){
				foreach($keywords as $keyword)
				{
					 	echo '<tr>';
						echo '<td>'.$count.'</td>';
						echo "<td class='keyword_column'>".$keyword['keyword']."</td>";
						echo "<td>".$keyword['text']."</td>";
						echo "<td>";
						echo '<button class="btn btn-xs btn-secondary edit_translation" result="'.$keyword['id'].'"><i class="fa fa-pencil"></i>&nbsp;<?php $words["DTL_0271"]?></button>';
						echo '</td>';
					  echo '</tr>';
					  $count++;
				}
				echo '<tr><td colspan="4">'.create_ajax_paging('get_pagination',$total_translation,$offset,array($limit),$limit).'</td></tr>';
			}
			else
				echo 'No more results.';
		}

	}
?>