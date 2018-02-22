<?php
class Profile extends Member_Controller
{
    var $data;
    var $upload_video_path = 'uploads/video';
    var $upload_path = 'uploads/userimage';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('company_info_model');
        $this->load->model('trainer_info_model');
        $this->load->model('trial_period_model');
        $this->load->model('speciality_model');
        $this->load->model('speciality_detail_model');
        $this->load->model('token_model');
        $this->load->model('favorite_model');
        $this->load->model('invite_company_model');
        $this->load->model('trainer_video_model');
        $this->load->model('standard_availability_model');
        $this->load->model('question_model');
        $this->load->model('option_model');
        $this->load->model('email_log_model');
        $this->load->model('email_model');
        $this->load->model('trainer_agenda_model');
        $this->load->model('language_model');
        $this->load->model('notification_model');
        $this->load->model('answer_model');
        $this->load->model('connect_agenda_model');
        $this->load->model('trainer_agenda_time_model');
        $this->load->model('day_model');
        $this->load->model('time_model');
        $this->load->model('icloud_model');
        $this->load->library('form_validation');
        $this->load->library('icloud');
        $this->load->library('email_library');
    }

    public function index()
    {
        $this->view_profile($this->session->userdata('general_id'));
    }

    public function other_profile($id) //by general id
    {
        if ($id == $this->session->userdata('general_id'))
            redirect(site_url('member/profile/view_profile') . "/" . $id);
        $count = 0;
        $data = array();
        $data['userdata'] = $this->user_model->get_user_info($id)->row_array();
        if ($data['userdata'] == null)
            redirect(site_url('member/profile/view_profile') . "/" . $this->session->userdata('general_id'));
        $data['same_user'] = 0;
        $specialities = $this->speciality_detail_model->get_speciality_id($data['userdata']['id'])->result_array();
        foreach ($specialities as $speciality) {
            $data['specialities']['speciality_name' . $count] = $this->speciality_model->get_speciality($speciality['speciality_id'])->row('category_name');
            $count++;
        }
        if ($data['userdata']['is_company'] == 1) {
            $data['companydata'] = $this->company_info_model->get_specific_company($data['userdata']['id'])->row_array();

        } else {
            $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($data['userdata']['id'])->row_array();
            $data['videos'] = $this->trainer_video_model->get_videos($data['trainerdata']['id'])->result_array();
        }

        $general_info_id = $this->session->userdata('general_id');
        $messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
        if (!empty($messages)) {
            foreach ($messages as $message) {
                if ($message['email_to_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                } elseif ($message['email_by_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                }
                if (!empty($message_specific))
                    $data['messages'][] = $message_specific;
            }
            $count = 0;
            if (!empty($data['messages']))
                foreach ($data['messages'] as $d) {
                    $data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                    $data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                    $count++;
                }
        }
        $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
        $is_favorite_id = $id;
        $data['is_favorite'] = $this->check_if_favorite($general_info_id, $id);
        $data['header'] = 'Profile';
        $data['page'] = 'member/profile/index';
        $data['words'] = $this->_language;
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function check_if_favorite($general_info_id, $id)
    {
        $result = $this->favorite_model->get_favorite($general_info_id, $id)->row_array();
        if ($result == null)
            return null;
        else
            return $result;
    }

    public function view_profile($id) //by general id
    {
        if ($id == $this->session->userdata('general_id') || $this->session->userdata('is_admin') == 1) {
            $count = 0;
            $data = array();
            $data['userdata'] = $this->user_model->get_user_info($id)->row_array();
            if ($id = $this->session->userdata('general_id'))
                $data['same_user'] = 1;
            $specialities = $this->speciality_detail_model->get_speciality_id($data['userdata']['id'])->result_array();
            foreach ($specialities as $speciality) {
                $data['specialities']['speciality_name' . $count] = $this->speciality_model->get_speciality($speciality['speciality_id'])->row('category_name');
                $count++;
            }
            if ($data['userdata']['is_company'] == 1) {
                $data['companydata'] = $this->company_info_model->get_specific_company($data['userdata']['id'])->row_array();

            } else {
                $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($data['userdata']['id'])->row_array();
                $data['videos'] = $this->trainer_video_model->get_videos($data['trainerdata']['id'])->result_array();
            }
            $data['header'] = 'Profile';
            $data['page'] = 'member/profile/index';
            $data['words'] = $this->_language;
            $general_info_id = $this->session->userdata('general_id');
            $messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
            if (!empty($messages)) {
                foreach ($messages as $message) {
                    if ($message['email_to_id'] == $general_info_id) {
                        $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                    } elseif ($message['email_by_id'] == $general_info_id) {
                        $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                    }
                    if (!empty($message_specific))
                        $data['messages'][] = $message_specific;
                }
                $count = 0;
                if (!empty($data['messages']))
                    foreach ($data['messages'] as $d) {
                        $data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                        $data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                        $count++;
                    }
            }
            $data['language'] = $this->language_model->select_specific_language()->result_array();
            $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
            $this->load->view($this->_container, $data);
        } else {
            redirect(site_url('member/profile/other_profile') . "/" . $id);
        }
    }

    public function edit_profile_index()
    {
        redirect(site_url('member/profile/edit_profile') . "/" . $this->session->userdata('general_id'));
    }

    public function edit_profile($general_info_id)
    {
        if ($this->session->userdata('general_id') == $general_info_id || $this->session->userdata('is_admin') == 1) {
            $count = 0;
            $i = 0;
            $data['userdata'] = $this->user_model->get_user_info($general_info_id)->row_array();
            if ($this->session->userdata('general_id') == $general_info_id)
                $data['same_user'] = 1;
            $specialities = $this->speciality_detail_model->get_speciality_id($data['userdata']['id'])->result_array();
            $data['main_specialities'] = $this->speciality_model->get_main_specialities()->result_array();
            foreach ($specialities as $speciality) {
                $data['specialities'][$count]['speciality_id'] = $speciality['speciality_id'];
                $data['specialities'][$count]['speciality_name'] = $this->speciality_model->get_speciality($speciality['speciality_id'])->row('category_name');
                $count++;
            }
            if ($data['userdata']['is_company'] == 1) {
                $data['companydata'] = $this->company_info_model->get_specific_company($data['userdata']['id'])->row_array();
                $data['dynamic_fields'] = $this->question_model->get_dynamic_fields(array('section_id' => 1))->result_array();
                $count = 0;
                foreach ($data['dynamic_fields'] as $dynamic_fields) {
                    $data['dynamic_fields'][$count]['options'] = $this->option_model->get_options($dynamic_fields['id'])->result_array();
                    $data['dynamic_fields'][$count]['answers'] = $this->answer_model->get_answer_by_general_id($this->session->userdata('general_id'), $dynamic_fields['id'])->result_array();
                    $count++;
                }
                $data['dynamic_fields_edit'] = $this->question_model->get_dynamic_fields(array('section_id' => 2))->result_array();
                $count = 0;
                foreach ($data['dynamic_fields_edit'] as $dynamic_fields) {
                    $data['dynamic_fields_edit'][$count]['options'] = $this->option_model->get_options($dynamic_fields['id'])->result_array();
                    $data['dynamic_fields_edit'][$count]['answers'] = $this->answer_model->get_answer_by_general_id($this->session->userdata('general_id'), $dynamic_fields['id'])->result_array();
                    $count++;
                }
                array_merge($data['dynamic_fields'], $data['dynamic_fields_edit']);
            } else {
                $removed_days = array();
                $removed_times = array();
                $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($data['userdata']['id'])->row_array();
                $removed_days = unserialize($data['trainerdata']['removed_days']);
                if (!empty($removed_days)) {
                    foreach ($removed_days as $rd) {
                        $data['days'][] = $rd['day'];
                    }
                }
                $removed_times = unserialize($data['trainerdata']['removed_times']);
                if (!empty($removed_times)) {
                    foreach ($removed_times as $rd) {
                        $data['times'][] = $rd['time'];
                    }
                }


                $data['available_date'] = $this->day_model->get_available_days()->result_array();

                $data['available_time'] = $this->time_model->get_available_times()->result_array();




                $data['standard_availability'] = $this->standard_availability_model->get_standard_availability($data['trainerdata']['id'])->row_array();
                $data['videos'] = $this->trainer_video_model->get_videos($data['trainerdata']['id'])->result_array();
                $data['favorites'] = $this->favorite_model->get_favorites($this->session->userdata('general_id'))->result_array();
                $count = 0;
                foreach ($data['favorites'] as $f) {
                    $user_data = $this->user_model->get_user_info($f['is_favorite_id'])->row_array();
                    if ($user_data['is_company'] != 0)
                        $data['favorites'][$count]['userdata'] = $user_data;
                    $count++;
                }
                $data['dynamic_fields'] = $this->question_model->get_dynamic_fields(array('section_id' => 3))->result_array();
                $count = 0;
                foreach ($data['dynamic_fields'] as $dynamic_fields) {
                    $data['dynamic_fields'][$count]['options'] = $this->option_model->get_options($dynamic_fields['id'])->result_array();
                    $data['dynamic_fields'][$count]['answers'] = $this->answer_model->get_answer_by_general_id($this->session->userdata('general_id'), $dynamic_fields['id'])->result_array();
                    $count++;
                }
                $data['dynamic_fields_edit'] = $this->question_model->get_dynamic_fields(array('section_id' => 2))->result_array();
                $count = 0;
                foreach ($data['dynamic_fields_edit'] as $dynamic_fields) {
                    $data['dynamic_fields_edit'][$count]['options'] = $this->option_model->get_options($dynamic_fields['id'])->result_array();
                    $data['dynamic_fields_edit'][$count]['answers'] = $this->answer_model->get_answer_by_general_id($this->session->userdata('general_id'), $dynamic_fields['id'])->result_array();
                    $count++;
                }
                array_merge($data['dynamic_fields'], $data['dynamic_fields_edit']);
            }

            $data['trial_period'] = $this->trial_period_model->get_trial_method($data['userdata']['user_type_id'])->row_array();
            $trial_period = $data['trial_period'];
            $trial_days = $trial_period['trial_period_time'];

            $date_added_with_trial = date('Y-m-d H:i:s', strtotime($data['userdata']['created_on']) + (86400 * $trial_days));
            $current_date = date('Y-m-d H:i:s');
            $datediff = floor(strtotime($date_added_with_trial) / (60 * 60 * 24)) - floor(strtotime($current_date) / (60 * 60 * 24));
            if ($current_date < $date_added_with_trial) {
                $data['left_days'] = $datediff;
            }
            $data['page'] = 'member/profile/edit';
            $data['words'] = $this->_language;
            $general_info_id = $this->session->userdata('general_id');
            $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
            $messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
            if (!empty($messages)) {
                foreach ($messages as $message) {
                    if ($message['email_to_id'] == $general_info_id) {
                        $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                    } elseif ($message['email_by_id'] == $general_info_id) {
                        $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                    }
                    if (!empty($message_specific))
                        $data['messages'][] = $message_specific;
                }
                $count = 0;
                if (!empty($data['messages']))
                    foreach ($data['messages'] as $d) {
                        $data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                        $data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                        $count++;
                    }
            }

//            //Invited Company
//            $this->load->model('Invite_company_model');
//            $data['invited_company'] = $this->Invite_company_model->get_all_invited_companies($general_info_id);
//            var_dump($data['invited_company']);

            $data['header'] = 'Edit Profile';
            $data['language'] = $this->language_model->select_specific_language()->result_array();

            $this->load->view($this->_container, $data);
        } else {
            redirect(site_url('member/profile/other_profile') . "/" . $general_info_id);
        }
    }


    /* User Changed To Inactive */
    public function delete_user($id)
    {
        $inactive_value = 0;
        $user_data = $this->user_model->get_user_info($id)->row_array();
        $this->user_model->update('user_login', array('is_active' => $inactive_value), array('id' => $user_data['user_login_id']));
        if ($this->session->userdata('is_admin') == 0)
            $this->session->sess_destroy();
        $flashdata = array("type" => "error", "message" => $this->_language['DTL_0326']);
        $this->session->set_flashdata('flash_message', $flashdata);
        redirect(site_url('auth'));
    }

    public function edit()
    {
        $this->user_model->trans_start();
        $registerdata = $this->_get_general_edit_data();
        $userdata = $this->_get_user_edit_data();
        $standard_availability_data = null;
        $video_data = null;
        $specialities = null;
        $trainerdata = null;
        if ($this->session->userdata('is_admin') != 1) {
            $trainerdata = $this->_get_trainer_edit_data();
            $standard_availability_data = $this->_get_standard_availability_data();
        }
        $specialities = $this->input->post('speciality_ids');//before this was inside above if condition due to this admin will not be able to change specialities of user
        if ($registerdata['is_company'] != 1 && $this->session->userdata('is_admin') != 1) {
            $video_data = $this->_get_video_data();
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('education', 'Education', 'required');
            $this->form_validation->set_rules('cost_per_hour', 'Cost Per Hour', 'numeric|required');
            $this->form_validation->set_rules('work_experience', 'Work Experience', 'integer|required');
//            $this->form_validation->set_rules('from_day', 'From Day', 'callback_is_required_day');
//            $this->form_validation->set_rules('to_day', 'To Day', 'callback_is_required_day|callback_check_to_day[' . $this->input->post('from_day') . ']');
//            $this->form_validation->set_rules('from_time', 'From Time', 'callback_is_required_time');
//            $this->form_validation->set_rules('to_time', 'To Time', 'callback_is_required_time|callback_check_to_time[' . $this->input->post('from_time') . ']');
            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
            $this->form_validation->set_message('numeric', '%s ' . $this->_language['DTL_0303']);
            $this->form_validation->set_message('integer', '%s ' . $this->_language['DTL_0303']);
            $this->validate_dynamic_fields_trainer();
        } 
		elseif ($registerdata['is_company'] == 1)
            $this->validate_dynamic_fields_company();
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone Number', 'required|integer');

        $this->form_validation->set_rules('photo_logo', 'Photo', 'callback_check_photo');

        if ($this->session->userdata('is_admin') != 1) {
            $this->form_validation->set_rules('iban_number', 'IBAN Number', 'required');
            $this->form_validation->set_rules('bic_number', 'BIC Number', 'required');
        }
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
        $this->form_validation->set_message('numeric', '%s ' . $this->_language['DTL_0303']);
        $this->form_validation->set_message('integer', '%s ' . $this->_language['DTL_0303']);
        //if ($this->session->userdata('is_admin') != 1)
            $this->form_validation->set_rules('speciality_ids', 'Speciality', 'callback_check_speciality');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_profile($registerdata['id']);
        } else {
            if ($standard_availability_data != null)
                $this->standard_availability_model->update('standard_availability', $standard_availability_data, array('trainer_info_id' => $trainerdata['id']));
            if ($video_data['video_link'] != null) {
                if ($video_data['id'])
                    $this->trainer_video_model->update('trainer_video', $video_data, array('id' => $video_data['id']));
                else
                    $this->trainer_video_model->insert('trainer_video', $video_data);
            }
            $this->change_image();
            $this->change_password();
			$general_id = $this->input->post('general_info_id');
            $this->remove_specialities($general_id);
            if ($this->session->userdata('is_admin') != 1) {
                $general_id = $this->session->userdata('general_id');
                $this->remove_dynamic_answers($general_id);
                $this->save_text_box_answer($general_id);
                $this->save_check_box_answer($general_id);
                $this->save_drop_down_answer($general_id);
            }
            if ($specialities != null) {
                for ($i = 0; $i < count($specialities); $i++) {
                    $specialitydata['general_reg_info_id'] = $general_id;
                    //$specialitydata['general_reg_info_id'] = $this->input->post('general_info_id');
                    $specialitydata['speciality_id'] = $specialities[$i];
                    $specialitydata['display_order'] = 1;
                    $specialitydata['deleted'] = 0;
                    $this->speciality_model->insert('speciality_detail', $specialitydata);
                }
            }
            $this->user_model->update('general_reg_info', $registerdata, array('id' => $registerdata['id']));
            $this->user_model->update('user_login', $userdata, array('id' => $userdata['id']));
            if ($registerdata['is_company'] == 0 && $this->session->userdata('is_admin') != 1)
                $this->user_model->update('trainer_info', $trainerdata, array('general_info_id' => $registerdata['id']));
            $success = $this->user_model->trans_complete();
			if ($this->session->userdata('is_admin') == 1) {
				$this->_sendEmails();
			}
            $flashdata = array("type" => "success", "message" => "Updated Successfully");
            $this->session->set_flashdata('flash_message', $flashdata);
            redirect(site_url('member/profile/view_profile') . '/' . $registerdata['id']);
        }
    }
	private function _sendEmails() {
		$to = $this->input->post('email_address');
		if($this->input->post('is_company'))
			$email = $this->email_model->get_email(2,11)->row();
		else 
			$email = $this->email_model->get_email(3,11)->row();
		if($email->flag_bit == 1) {
			$subject = $email->subject;
			$message = $email->email;
			$this->email_library->sendEmail($to,$subject,$message);
		}
	}
    public function check_photo() {
        $path = $_FILES['photo_logo']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($path != null) {
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif' && $ext != 'jpeg') {
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0199']);
                $this->session->set_flashdata('flash_message', $flashdata);
                return false;
            } else
                return true;
        }
    }

    public function validate_dynamic_fields_company()
    {
        $dynamic_fields = $this->question_model->get_dynamic_fields(array('section_id' => 1))->result_array();
        if (!empty($dynamic_fields)) {
            foreach ($dynamic_fields as $dynamic_field) {
                if ($dynamic_field['is_required'] == 1) {
                    if ($dynamic_field['type_id'] == 1) {
                        foreach ($this->input->post('input_text') as $input_text) {
                            $this->form_validation->set_rules('input_text[' . $dynamic_field['id'] . ']', 'Text', 'required');
                            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
                        }
                    } elseif ($dynamic_field['type_id'] == 2) {
                        $options = $this->option_model->get_options($dynamic_field['id'])->result_array();
                        if (!empty($options))
                            $this->form_validation->set_rules('option[' . $dynamic_field['id'] . ']', 'Check Box', 'callback_checkbox_required');
                    } elseif ($dynamic_field['type_id'] == 3) {
                        $options = $this->option_model->get_options($dynamic_field['id'])->result_array();
                        if (!empty($options)) {
                            foreach ($this->input->post('select_option') as $select_option) {
                                $this->form_validation->set_rules('select_option[' . $dynamic_field['id'] . ']', 'Select', 'required');
                                $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
                            }
                        }
                    }
                } else {
                    if ($dynamic_field['type_id'] == 1)
                        $this->form_validation->set_rules('input_text', 'Text', 'trim');
                    elseif ($dynamic_field['type_id'] == 2)
                        $this->form_validation->set_rules('option', 'Check Box', 'trim');
                    elseif ($dynamic_field['type_id'] == 3)
                        $this->form_validation->set_rules('select_option', 'Select', 'trim');
                }
            }
        }
    }

    public function checkbox_required($value)
    {
        if (count($value) == 0) {
            $this->form_validation->set_message('checkbox_required', 'The Check Box field is required');
            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
            return false;
        } else
            return true;
    }

    public function validate_dynamic_fields_trainer()
    {
        $dynamic_fields = $this->question_model->get_dynamic_fields(array('section_id' => 3))->result_array();

        if (!empty($dynamic_fields)) {
            foreach ($dynamic_fields as $dynamic_field) {
                if ($dynamic_field['is_required'] == 1) {
                    if ($dynamic_field['type_id'] == 1) {
                        foreach ($this->input->post('input_text') as $input_text) {
                            $this->form_validation->set_rules('input_text[' . $dynamic_field['id'] . ']', 'Text', 'required');
                            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
                        }
                    } elseif ($dynamic_field['type_id'] == 2) {
                        $options = $this->option_model->get_options($dynamic_field['id'])->result_array();
                        if (!empty($options))
                            $this->form_validation->set_rules('option[' . $dynamic_field['id'] . ']', 'Check Box', 'callback_checkbox_required');
                    } elseif ($dynamic_field['type_id'] == 3) {
                        $options = $this->option_model->get_options($dynamic_field['id'])->result_array();
                        if (!empty($options)) {
                            foreach ($this->input->post('select_option') as $select_option) {
                                $this->form_validation->set_rules('select_option[' . $dynamic_field['id'] . ']', 'Select', 'required');
                                $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
                            }
                        }
                    }
                } else {
                    if ($dynamic_field['type_id'] == 1)
                        $this->form_validation->set_rules('input_text', 'Text', 'trim');
                    elseif ($dynamic_field['type_id'] == 2)
                        $this->form_validation->set_rules('option', 'Check Box', 'trim');
                    elseif ($dynamic_field['type_id'] == 3)
                        $this->form_validation->set_rules('select_option', 'Select', 'trim');
                }
            }
        }
    }

    private function remove_specialities($general_id)
    {
        $this->speciality_detail_model->delete('speciality_detail', array('general_reg_info_id' => $general_id));
    }

    private function remove_dynamic_answers($general_id)
    {
        $this->answer_model->delete('answer', array('general_reg_info_id' => $general_id));
    }

    private function save_check_box_answer($general_reg_info_id)
    {
        $insertarray = array();
        $data['general_reg_info_id'] = $general_reg_info_id;
        $data['is_deleted'] = 0;
        $option = $this->input->post('option');
        if ($option != null) {
            foreach ($option as $question_id => $optionarray) {
                $data['question_id'] = $question_id;
                foreach ($optionarray as $options) {
                    $data['option_id'] = $options;
                    $data['value'] = $this->option_model->get_option_value($options)->row_array();
                    $temp_array = array('general_reg_info_id' => $data['general_reg_info_id'], 'is_deleted' => $data['is_deleted'], 'question_id' => $data['question_id'], 'option_id' => $data['option_id'], 'value' => $data['value']['value']);
                    array_push($insertarray, $temp_array);
                }
            }
            if (!empty($insertarray)) {
                $this->answer_model->insert_batch($insertarray);
            }
        }
    }

    private function save_text_box_answer($general_reg_info_id)
    {
        $insertarray = array();
        $data['general_reg_info_id'] = $general_reg_info_id;
        $data['is_deleted'] = 0;
        $data['option'] = null;
        $input_text = $this->input->post('input_text');
        if ($input_text != null) {
            foreach ($input_text as $question_id => $input) {
                $data['question_id'] = $question_id;
                $data['value'] = $input;
                $temp_array = array('general_reg_info_id' => $data['general_reg_info_id'], 'is_deleted' => $data['is_deleted'], 'question_id' => $data['question_id'], 'option_id' => $data['option'], 'value' => $data['value']);
                array_push($insertarray, $temp_array);
            }
            if (!empty($insertarray)) {
                $this->answer_model->insert_batch($insertarray);
            }
        }
    }

    private function save_drop_down_answer($general_reg_info_id)
    {
        $insertarray = array();
        $data['general_reg_info_id'] = $general_reg_info_id;
        $data['is_deleted'] = 0;
        $select_options = $this->input->post('select_option');
        if ($select_options != null) {
            foreach ($select_options as $question_id => $select_option) {
                $data['question_id'] = $question_id;
                $data['option_id'] = $select_option;
                $data['value'] = $this->option_model->get_option_value($select_option)->row_array();
                $temp_array = array('general_reg_info_id' => $data['general_reg_info_id'], 'is_deleted' => $data['is_deleted'], 'question_id' => $data['question_id'], 'option_id' => $data['option_id'], 'value' => $data['value']['value']);
                array_push($insertarray, $temp_array);
            }
            if (!empty($insertarray)) {
                $this->answer_model->insert_batch($insertarray);
            }
        }
    }

    private function _get_video_data()
    {
        $data['id'] = $this->input->post('video_id');
        $data['video_link'] = $this->input->post('video_link');
        $data['trainer_info_id'] = $this->input->post('trainer_info_id_for_video');
        $data['uploaded_date'] = date("Y-m-d H:i:s");
        $data['is_publish'] = 1;
        $data['display_order'] = 1;
        $data['is_deleted'] = 0;
        return $data;
    }

    private function _get_standard_availability_data()
    {

        $data = null;
        if ($this->input->post('from_day')) {

            $data['from_new_day'] = $this->input->post('from_day');
            $posted_days = array();
            $posted_days = $data['from_new_day'];

            if (!empty($posted_days)) {
                $data['from_new_day'] = implode(',', $posted_days);

            }


        }
        if ($this->input->post('choosen_time'))


            $data['from_new_time'] = $this->input->post('choosen_time');
        $posted_times = array();
        $posted_times = $data['from_new_time'];

        if (!empty($posted_times)) {
            $data['from_new_time'] = implode(',', $posted_times);
        }

        return $data;
    }

    public function check_speciality()
    {
        $value = $this->input->post('speciality_ids');
        if (count($value) == 0) {
            $this->form_validation->set_message('check_speciality', $this->_language['DTL_0054']);
            return false;
        } elseif (count($value) > 3) {
            $this->form_validation->set_message('check_speciality', $this->_language['DTL_0336']);
        } else {
            return true;
        }
    }

    public function change_image()
    {
        $this->upload_image();
        $imagename = $this->upload->data();
        $id = $this->input->post('id_for_photo');
        $userdata = $this->user_model->get_user_info($id)->row_array();
        $old_photo = $this->input->post('old_photo');
        if ($imagename['file_name'] != null) {
            $this->delete_image($old_photo);
            $data['photo_logo'] = $imagename['file_name'];
            $this->session->set_userdata('photo', $data['photo_logo']);
            $this->user_model->update('general_reg_info', $data, array('id' => $id));
            $logindata['linkedin_image_status'] = 0;
            $this->user_model->update('user_login', $logindata, array('id' => $userdata['user_login_id']));
        }

    }

    public function delete_image($filename)
    {
        @unlink($this->upload_path . '/' . $filename);
    }

    public function upload_image()
    {
        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 102400;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo_logo')) {
            $error_message = $this->upload->display_errors();
            if ($error_message = "The filetype you are attempting to upload is not allowed.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0199']);
            elseif ($error_message = "You did not select a file to upload.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0201']);
            elseif ($error_message = "The file you are attempting to upload is larger than the permitted size.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0316']);
            $this->session->set_flashdata('flash_message', $flashdata);
        } else {
            $upload_data = $this->upload->data();

            //Image Resize:

            $config['image_library'] = 'gd2';
            $config['source_image'] = $upload_data['full_path'];
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 180;
            $config['height'] = 180;

            $this->load->library('image_lib', $config);

            $this->image_lib->resize();

            $data = array('upload_data' => $this->upload->data());

        }
    }

    private function _get_general_edit_data()
    {
        $data['id'] = $this->input->post('general_info_id');
        $data['name'] = $this->input->post('name');
        $data['address'] = $this->input->post('address');
        $data['zip_code'] = $this->input->post('zip_code');
        $data['phone_no'] = $this->input->post('phone_no');
        $data['is_company'] = $this->input->post('is_company');
        $data['iban_number'] = $this->input->post('iban_number');
        $data['bic_number'] = $this->input->post('bic_number');
        return $data;
    }

    private function _get_user_edit_data()
    {
        $data['id'] = $this->input->post('user_login_id_for_password');
        $data['email_address'] = $this->input->post('email_address');
        return $data;
    }

    private function _get_trainer_edit_data()
    {
        $data['id'] = $this->input->post('trainer_info_id');
        $data['education'] = $this->input->post('education');
        $data['cost_per_hour'] = $this->input->post('cost_per_hour');
        $data['visibility_id'] = $this->input->post('visibility');
        return $data;
    }

    public function change_password()
    {
        $id = $this->input->post('user_login_id_for_password');
        $general_info_id = $this->input->post('general_info_id');
        $userdata = $this->user_model->get_specific_user($id)->row_array();
        $current_password = md5($this->input->post('current_password'));
        $new_password = $this->input->post('new_password');
        $enc_password = md5($this->input->post('new_password'));
        $confirm_password = md5($this->input->post('confirm_password'));
        $data['password'] = $enc_password;
        if ($this->input->post('current_password')) {
            if (($new_password) != null) {
                if ($current_password == $userdata['password']) {
                    if ($enc_password != $confirm_password) {
                        $flashdata = array("type" => "error", "message" => $this->_language['DTL_0317']);
                        $this->session->set_flashdata('flash_message', $flashdata);
                        redirect(site_url('member/profile/edit_profile') . '/' . $general_info_id);
                        exit;
                    } else {
                        $this->user_model->update('user_login', $data, array('id' => $id));
                        $flashdata = array("type" => "success", "message" => $this->_language['DTL_0318']);
                        $this->session->set_flashdata('flash_message', $flashdata);
                    }
                } else {
                    $flashdata = array("type" => "error", "message" => $this->_language['DTL_0321']);
                    $this->session->set_flashdata('flash_message', $flashdata);
                    redirect(site_url('member/profile/edit_profile') . '/' . $general_info_id);
                    exit;
                }
            } else {
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0319']);
                $this->session->set_flashdata('flash_message', $flashdata);
                redirect(site_url('member/profile/edit_profile') . '/' . $general_info_id);
                exit;
            }
        }
    }

    public function redirect_back()
    {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME']);
        }
    }

    public function get_company()
    {
        $value = $this->input->post('company_detail');
        //$company_details_array = explode("-",$value);
        $data = $this->company_info_model->get_specific_company_from_name(array('name' => $value))->row_array();
        echo json_encode($data);
    }

    public function searchCompany($name)
    {
		$already_selected_companies = $this->input->get('already_selected_companies');
		$already_selected_companies = rtrim($already_selected_companies,",");
        $name = urldecode($name);
        $company_array = array();
        $companies = $this->company_info_model->search_company($name,$already_selected_companies)->result_array();
        foreach ($companies as $company) {
            $company_array[] = $company['name'];
        }
        echo json_encode($company_array);
    }

    public function invite_company()
    {
        $count = 0;
        $data['companies'] = $this->input->post('companies');
        $data['company_name'] = $this->input->post('company_name');
        $data['company_email'] = $this->input->post('company_email');
        $data['company_message'] = $this->input->post('company_message');
        $data['user_email'] = $this->input->post('user_email');
        $data['username'] = $this->input->post('username');
        $data['general_reg_info_id'] = $this->input->post('user_id');
        $trainer_id = $this->input->post('trainer_info_id');
        $trainer_data['visibility_id'] = 3;
        $this->trainer_info_model->update('trainer_info', $trainer_data, array('id' => $trainer_id));
        if ($data['companies'] != null) {
            foreach ($data['companies'] as $company) {
                $company_details[$count] = $this->user_model->get_user_info($company)->row_array();
                $count++;
            }
            foreach ($company_details as $company_detail) {
                $data['token'] = $this->generateRandomString(15);
                $invitedata['token_id'] = $this->_insert_token($data['token'], $data['general_reg_info_id']);
                $maildata = $this->email_model->get_email(2, 9)->row_array();
                $message = $maildata['email'] . "<br/>";
                $message .= "<a href='" . site_url('auth/valid_token') . "/" . $data['token'] . "'>" . $data['token'] . "</a>";
				if($maildata['flag_bit'] == 1)
					$this->mail_send($data['user_email'], $data['username'], $company_detail['email_address'], $message, $maildata['subject']);
                $invitedata['trainer_info_id'] = $data['general_reg_info_id'];
                $invitedata['company_name'] = $company_detail['username'];
                $invitedata['email_address'] = $company_detail['email_address'];
                $invitedata['is_visible'] = 1;
                $invitedata['message'] = $message;
                $invitedata['is_deleted'] = 1;
                $this->invite_company_model->insert('invite_company', $invitedata);
            }
        }
		elseif ($data['company_email'] != '' || $data['company_name'] != '') {
            $data['token'] = $this->generateRandomString(15);
            $this->_insert_token($data['token'], $this->session->userdata('general_id'));
            $maildata = $this->email_model->get_email(2, 9)->row_array();
            $message = $maildata['email'] . "<br/>";
            $message .= "<a href='" . site_url('auth/valid_token') . "/" . $data['token'] . "'>" . $data['token'] . "</a>";
            $this->mail_send($data['user_email'], $data['username'], $data['company_email'], $message, $maildata['subject']);
        } else {
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0332']);
            $this->session->set_flashdata('flash_message', $flashdata);
        }
        $this->redirect_back();
    }


    public function mail_send($from, $name, $to, $message, $subject)
    {
        $this->load->library('email');
        $this->email->from($from, $name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_mailtype('html');
        $this->email->send();
        $flashdata = array("type" => "success", "message" => $this->_language['DTL_0315']);
        $this->session->set_flashdata('flash_message', $flashdata);
    }

    private function _insert_token($token, $general_user_id)
    {
        $data['token'] = $token;
        $data['duration'] = 10;
        $data['is_deleted'] = 1;
        $data['general_reg_info_id'] = $general_user_id;
        $this->token_model->insert('token', $data);
        return $this->token_model->get_last_inserted_id();
    }

    private function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }


    public function add_to_favorite()
    {
        $response_arr = array();
        $data['is_favorite_id'] = $this->input->post("general_id");
        $data['general_reg_info_id'] = $this->session->userdata('general_id');
        $data['created_date'] = date('Y-m-d H:i:s');
        $info = $this->favorite_model->get_favorite($data['general_reg_info_id'], $data['is_favorite_id'])->row_array();
        if ($info == null) {
            $data['is_deleted'] = 0;
            $this->favorite_model->insert('favorite', $data);
            $response_arr['status'] = 1;
            $notification_data['created_date'] = date('Y-m-d');
            $notification_data['sent_by_id'] = $data['general_reg_info_id'];
            $notification_data['sent_to_id'] = $data['is_favorite_id'];
            $notification_data['notification_type'] = 3;
            $this->notification_model->insert('notifications', $notification_data);
            $flashdata = array("type" => "success", "message" => $this->_language['DTL_0325']);
            $this->session->set_flashdata('flash_message', $flashdata);
        } else {
            $this->favorite_model->delete('favorite', array('id' => $info['id']));
            $response_arr['status'] = 0;
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0333']);
            $this->session->set_flashdata('flash_message', $flashdata);
        }
        echo json_encode($response_arr);
    }

    public function get_date()
    {
        $id = $this->input->post("id");
        $data['standard_availability'] = $this->standard_availability_model->get_standard_availability($id)->row_array();
//        $data['confirmed'] = $this->trainer_agenda_model->get_trainer_agenda_confirmed($id)->row_array();
        $count = 0;
        if (($this->trainer_agenda_model->get_all_agendas($id)->result_array()) != null) {
            $data['trainer_agenda'] = $this->trainer_agenda_model->get_all_agendas($id)->result_array();
            foreach ($data['trainer_agenda'] as $trainer) {
                $data['trainer_agenda'][$count]['reserved_time'] = $this->trainer_agenda_time_model->get_trainer_time($trainer['agenda_id'])->result_array();
                $count++;
            }
        }

        $removed_days = array();
        $removed_times = array();
        $trainerdata = $this->user_model->get_user_by_trainer_id($id)->row_array();
        $removed_days = unserialize($trainerdata['removed_days']);
        if (!empty($removed_days)) {
            foreach ($removed_days as $rd) {
                $day = $rd['day'];
                if ($day == 'sunday')
                    $data['days'][] = 0;
                if ($day == 'monday')
                    $data['days'][] = 1;
                if ($day == 'tuesday')
                    $data['days'][] = 2;
                if ($day == 'wednesday')
                    $data['days'][] = 3;
                if ($day == 'thursday')
                    $data['days'][] = 4;
                if ($day == 'friday')
                    $data['days'][] = 5;
                if ($day == 'saturday')
                    $data['days'][] = 6;
            }
        }
        $removed_times = unserialize($trainerdata['removed_times']);
        if (!empty($removed_times)) {
            foreach ($removed_times as $rd) {
                $data['times'][] = $rd['time'];
            }
        }

        echo json_encode($data);
    }



    public function send_contact_mail()
    {
        $this->load->library('email');
        $to = $this->input->post('send_to');
        $message = $this->input->post('message');
        $id = $this->input->post('user_id');
        $general_id = $this->session->userdata('general_id');
        $data = $this->user_model->get_user_info($general_id)->row_array();
        if ($data['is_company'] == 1) {
            $email = $this->email_model->get_email(2, 8)->row_array();
        } else {
            $email = $this->email_model->get_email(3, 8)->row_array();
        }
        $email_log_data['email_to_id'] = $id;
        $email_log_data['email_by_id'] = $general_id;
        $email_log_data['created_date'] = date("Y-m-d H:i:s");
        $email_log_data['message'] = $message;
        $this->email_log_model->insert('email_log', $email_log_data);

        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');;
        $notification_data['sent_to_id'] = $id;
        $notification_data['notification_type'] = 1;
        $this->notification_model->insert('notifications', $notification_data);
		if($email['flag_bit'] == 1) {
			$from = "noreply@agendatool.com";
			$name = "Admin";
			$this->email->from($from, $name);
			$this->email->to($to);
			$subject = $email['subject'];
			$this->email->subject($subject);
			$mail = $email['email'];
			$mail .= "<br/><a href='" . site_url('auth') . "'>Link to agendatool web application.</a>";
			$this->email->message($mail);
			$this->email->set_mailtype("html");
			$this->email->send();
		}
    }

    public function send_contact_mail_page()
    {
        $parent_id = null;
        $sent_by_id = $this->session->userdata('general_id');
        if ($this->input->post('parent_id'))
            $parent_id = $this->input->post('parent_id');
        $this->load->library('email');
        $message = $this->input->post('message');
        if ($this->input->post('send_to'))
            $to = $this->input->post('send_to');
        if ($this->input->post('user_id'))
            $id = $this->input->post('user_id');
        if ($this->input->post('sent_by_id'))
            $sent_by_id = $this->input->post('sent_by_id');
        if ($this->input->post('sent_to_id'))
            $sent_to_id = $this->input->post('sent_to_id');
        else
            $sent_to_id = $id;
        $general_id = $this->session->userdata('general_id');
        if ($sent_by_id != $general_id)
            $id = $sent_by_id;
        else
            $id = $sent_to_id;
        $other_user = $this->user_model->get_user_info($id)->row_array();
        if (!empty($other_user))
            $to = $other_user['email_address'];
        $data = $this->user_model->get_user_info($general_id)->row_array();
        if ($data['is_company'] == 1) {
            $email = $this->email_model->get_email(2, 8)->row_array();
        } else {
            $email = $this->email_model->get_email(3, 8)->row_array();
        }
        $email_log_data['email_to_id'] = $id;
        $email_log_data['email_by_id'] = $general_id;
        $email_log_data['created_date'] = date("Y-m-d H:i:s");
        $email_log_data['message'] = $message;
        if ($parent_id)
            $email_log_data['parent_id'] = $parent_id;
        else
            $email_log_data['parent_id'] = 0;
        $this->email_log_model->insert('email_log', $email_log_data);

        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');;
        $notification_data['sent_to_id'] = $id;
        $notification_data['notification_type'] = 1;
        $this->notification_model->insert('notifications', $notification_data);
		if($email['flag_bit'] == 1) {
			$from = "noreply@agendatool.com";
			$name = "Admin";
			$this->email->from($from, $name);
			$this->email->to($to);
			$subject = $email['subject'];
			$this->email->subject($subject);
			$mail = $email['email'];
			$mail .= "<br/><a href='" . site_url('auth') . "'>Link to agendatool web application.</a>";
			$this->email->message($mail);
			$this->email->send();
		}
        $flashdata = array("type" => "success", "message" => $this->_language['DTL_0315']);
        $this->session->set_flashdata('flash_message', $flashdata);
        redirect(site_url('member/profile/other_profile') . "/" . $id);
    }


    public function check_to_day($to_day, $from_day)
    {
        if ($to_day <= $from_day) {
            $this->form_validation->set_message('check_to_day', $this->_language['DTL_0305']);
            return false;
        } else {
            return true;
        }
    }

    public function check_to_time($to_time, $from_time)
    {
        if ($to_time <= $from_time) {
            $this->form_validation->set_message('check_to_time', $this->_language['DTL_0306']);
            return false;
        } else {
            return true;
        }
    }


    public function is_required_day($value)
    {
        if ($value == null) {
            $this->form_validation->set_message('is_required_day', 'Day ' . $this->_language['DTL_0301']);
            return false;
        } else {
            return true;
        }
    }

    public function is_required_time($value)
    {
        if ($value == null) {
            $this->form_validation->set_message('is_required_time', 'Time ' . $this->_language['DTL_0301']);
            return false;
        } else {
            return true;
        }
    }


    public function inbox()
    {
        $data['header'] = "Message";
        $data['page'] = "member/profile/inbox_page";
        $data['words'] = $this->_language;
        $general_info_id = $this->session->userdata('general_id');
        $messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
        if (!empty($messages)) {
            foreach ($messages as $message) {
                if ($message['email_to_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                } elseif ($message['email_by_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                }
                if (!empty($message_specific))
                    $data['messages'][] = $message_specific;
            }
            $count = 0;
            if (!empty($data['messages']))
                foreach ($data['messages'] as $d) {
                    $data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                    $data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                    $count++;
                }
        }
        $messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
        if (!empty($messages)) {
            foreach ($messages as $message) {
                if ($message['email_to_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                } elseif ($message['email_by_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                }
                if (!empty($message_specific))
                    $data['mails'][] = $message_specific;
            }
            $count = 0;
            if (!empty($data['mails']))
                foreach ($data['mails'] as $d) {
                    $data['mails'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                    $data['mails'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                    $count++;
                }
        }
        $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
        $data['userdata'] = $this->user_model->get_user_info($general_info_id)->row_array();
        $data['same_user'] = 1;
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function inbox_detail($message_id)
    {
        if ($message_id == null)
            redirect(site_url('member/profile/inbox'));
        $data['header'] = "Message Detail";
        $data['page'] = "member/profile/inbox_detail_page";
        $data['words'] = $this->_language;
        $general_info_id = $this->session->userdata('general_id');
        $messages = $this->email_log_model->get_email_by_general_id($general_info_id)->result_array();
        if (!empty($messages)) {
            foreach ($messages as $message) {
                if ($message['email_to_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                } elseif ($message['email_by_id'] == $general_info_id) {
                    $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                }
                if (!empty($message_specific))
                    $data['messages'][] = $message_specific;
            }
            $count = 0;
            if (!empty($data['messages']))
                foreach ($data['messages'] as $d) {
                    $data['messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                    $data['messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                    $count++;
                }
        }
        $specific_message = $this->email_log_model->get_specific_mail($message_id)->row_array();
        if ($specific_message['parent_id'] != 0)
            $specific_message = $this->email_log_model->get_specific_mail($specific_message['parent_id'])->row_array();
        if ($specific_message['email_to_id'] == $general_info_id)
            $data['specific_message'] = $this->email_log_model->get_specific_mail($specific_message['id'], array('delete_by_receiver' => 0))->row_array();
        elseif ($specific_message['email_by_id'] == $general_info_id)
            $data['specific_message'] = $this->email_log_model->get_specific_mail($specific_message['id'], array('delete_by_sender' => 0))->row_array();
        else
            $data['specific_message'] = null;
        if (!empty($data['specific_message'])) {
            if ($data['specific_message']['parent_id'] != 0) {
                $data['specific_message'] = $this->email_log_model->get_specific_mail($data['specific_message']['parent_id'])->row_array();
            }
            $data['specific_message']['sent_by'] = $this->user_model->get_user_info($data['specific_message']['email_by_id'])->row_array();
            $data['specific_message']['sent_to'] = $this->user_model->get_user_info($data['specific_message']['email_to_id'])->row_array();
            if ($data['specific_message']['parent_id'] == 0) {
                $child_messages = $this->email_log_model->get_child_message($data['specific_message']['id'])->result_array();
                if (!empty($child_messages)) {
                    foreach ($child_messages as $message) {
                        if ($message['email_to_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                        } elseif ($message['email_by_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                        }
                        if (!empty($message_specific))
                            $data['child_messages'][] = $message_specific;
                    }
                    $count = 0;
                    if (!empty($data['child_messages']))
                        foreach ($data['child_messages'] as $d) {
                            $data['child_messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                            $data['child_messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                            $count++;
                        }
                }
            } else {
                $child_messages = $this->email_log_model->get_child_message($data['specific_message']['parent_id'])->result_array();
                if (!empty($child_messages)) {
                    foreach ($child_messages as $message) {
                        if ($message['email_to_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                        } elseif ($message['email_by_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                        }
                        if (!empty($message_specific))
                            $data['child_messages'][] = $message_specific;
                    }
                    $count = 0;
                    if (!empty($data['child_messages']))
                        foreach ($data['child_messages'] as $d) {
                            $data['child_messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                            $data['child_messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                            $count++;
                        }
                }
            }
        } else {
            if ($specific_message['parent_id'] != 0) {
                $specific_message = $this->email_log_model->get_specific_mail($specific_message['parent_id'])->row_array();
            }
            if ($specific_message['parent_id'] == 0) {
                $child_messages = $this->email_log_model->get_child_message($specific_message['id'])->result_array();
                if (!empty($child_messages)) {
                    foreach ($child_messages as $message) {
                        if ($message['email_to_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                        } elseif ($message['email_by_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                        }
                        if (!empty($message_specific))
                            $data['child_messages'][] = $message_specific;
                    }
                    $count = 0;
                    if (!empty($data['child_messages']))
                        foreach ($data['child_messages'] as $d) {
                            $data['child_messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                            $data['child_messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                            $count++;
                        }
                }
            } else {
                $child_messages = $this->email_log_model->get_child_message($specific_message['parent_id'])->result_array();
                if (!empty($child_messages)) {
                    foreach ($child_messages as $message) {
                        if ($message['email_to_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_receiver' => 0))->row_array();
                        } elseif ($message['email_by_id'] == $general_info_id) {
                            $message_specific = $this->email_log_model->get_specific_mail($message['id'], array('delete_by_sender' => 0))->row_array();
                        }
                        if (!empty($message_specific))
                            $data['child_messages'][] = $message_specific;
                    }
                    $count = 0;
                    if (!empty($data['child_messages']))
                        foreach ($data['child_messages'] as $d) {
                            $data['child_messages'][$count]['sent_by'] = $this->user_model->get_user_info($d['email_by_id'])->row_array();
                            $data['child_messages'][$count]['sent_to'] = $this->user_model->get_user_info($d['email_to_id'])->row_array();
                            $count++;
                        }
                }
            }
        }
        $this->session->set_userdata('message_id', $message_id);
        $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
        $data['userdata'] = $this->user_model->get_user_info($general_info_id)->row_array();
        $data['same_user'] = 1;
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function delete_mail_by_receiver($mail_id)
    {
        $message = $this->email_log_model->get_specific_mail($mail_id)->row_array();
        if ($message['delete_by_sender'] == 1) {
            $this->email_log_model->delete('email_log', array('id' => $mail_id));
        } elseif ($message['delete_by_sender'] == 0) {
            $data['delete_by_receiver'] = 1;
            $this->email_log_model->update('email_log', $data, array('id' => $mail_id));
        }
        redirect(site_url('member/profile/inbox'));
    }

    public function delete_mail_by_sender($mail_id)
    {
        $message = $this->email_log_model->get_specific_mail($mail_id)->row_array();
        if ($message['delete_by_receiver'] == 1) {
            $this->email_log_model->delete('email_log', array('id' => $mail_id));
        } elseif ($message['delete_by_receiver'] == 0) {
            $data['delete_by_sender'] = 1;
            $this->email_log_model->update('email_log', $data, array('id' => $mail_id));
        }
        redirect(site_url('member/profile/inbox'));
    }

    function send_outlook_mail($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location)
    {
        $domain = 'agendatool.com';
        $mime_boundary = "----Meeting Booking----" . MD5(TIME());

        $headers = "From: " . $from_name . " <" . $from_address . ">\n";
        $headers .= "Reply-To: " . $from_name . " <" . $from_address . ">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";

        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<body>\n";
        $message .= '<p>Dear ' . $to_name . ',</p>';
        $message .= '<p>' . $description . '</p>';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";

        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
            'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
            'VERSION:2.0' . "\r\n" .
            'METHOD:REQUEST' . "\r\n" .
            'BEGIN:VTIMEZONE' . "\r\n" .
            'TZID:Eastern Time' . "\r\n" .
            'BEGIN:STANDARD' . "\r\n" .
            'DTSTART:20091101T020000' . "\r\n" .
            'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
            'TZOFFSETFROM:-0400' . "\r\n" .
            'TZOFFSETTO:-0500' . "\r\n" .
            'TZNAME:EST' . "\r\n" .
            'END:STANDARD' . "\r\n" .
            'BEGIN:DAYLIGHT' . "\r\n" .
            'DTSTART:20090301T020000' . "\r\n" .
            'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
            'TZOFFSETFROM:-0500' . "\r\n" .
            'TZOFFSETTO:-0400' . "\r\n" .
            'TZNAME:EDST' . "\r\n" .
            'END:DAYLIGHT' . "\r\n" .
            'END:VTIMEZONE' . "\r\n" .
            'BEGIN:VEVENT' . "\r\n" .
            'ORGANIZER;CN="' . $from_name . '":MAILTO:' . $from_address . "\r\n" .
            'ATTENDEE;CN="' . $to_name . '";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:' . $to_address . "\r\n" .
            'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
            'UID:' . date("Ymd\TGis", strtotime($startTime)) . rand() . "@" . $domain . "\r\n" .
            'DTSTAMP:' . date("Ymd\TGis") . "\r\n" .
            'DTSTART;TZID="Eastern Time":' . date("Ymd\THis", strtotime($startTime)) . "\r\n" .
            'DTEND;TZID="Eastern Time":' . date("Ymd\THis", strtotime($endTime)) . "\r\n" .
            'TRANSP:OPAQUE' . "\r\n" .
            'SEQUENCE:1' . "\r\n" .
            'SUMMARY:' . $subject . "\r\n" .
            'LOCATION:' . $location . "\r\n" .
            'CLASS:PUBLIC' . "\r\n" .
            'PRIORITY:5' . "\r\n" .
            'BEGIN:VALARM' . "\r\n" .
            'TRIGGER:-PT15M' . "\r\n" .
            'ACTION:DISPLAY' . "\r\n" .
            'DESCRIPTION:Reminder' . "\r\n" .
            'END:VALARM' . "\r\n" .
            'END:VEVENT' . "\r\n" .
            'END:VCALENDAR' . "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST' . "\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;

        $mailsent = mail($to_address, $subject, $message, $headers);

        //echo ($mailsent)?(true):(false);
    }

    public function check_outlook_mail($called_flag_bit = 1)
    {
        $result = $this->connect_agenda_model->check_outlook_email($this->session->userdata('general_id'));
        if ($result == 1) {
            $this->set_outlook_mail();
			if($called_flag_bit == 1) {
				echo 1;
			}
		}
		else {
			if($called_flag_bit == 1) {
				echo 0;
			}
		}
    }

    /*
     *This function will get all the agendas that are needed to be sent to outlook and icloud
    */
    private function _getAgendasToSend()
    {
		$return_arr = array();
        $general_id = $this->session->userdata('general_id');
        $trainer_data = $this->user_model->get_trainer_by_general_id($general_id)->row_array();
        $all_agendas = $this->trainer_agenda_model->get_trainer_agenda($trainer_data['trainer_info_id'])->result_array();
		$return_arr['trainer_data'] = $trainer_data;
		$return_arr['all_agendas'] = $all_agendas;
        return $return_arr;
    }

    public function set_outlook_mail($all_agendas = array())
    {
        set_time_limit(0);
        if (empty($all_agendas)) {
            $all_agendas_arr = $this->_getAgendasToSend();
			$all_agendas = $all_agendas_arr['all_agendas'];
			$trainer_data = $all_agendas_arr['trainer_data'];
		}
		else {
			$general_id = $this->session->userdata('general_id');
			$trainer_data = $this->user_model->get_trainer_by_general_id($general_id)->row_array();
		}
        foreach ($all_agendas as $agenda) {
            if ($agenda['is_edited'] == 1 || $agenda['outlook_status'] == 0)
                if ($agenda['is_confirm'] == 1 || $agenda['is_appointed'] == 1)
                    $agendas[] = $agenda;
        }
        if (!empty($agendas)) {
            foreach ($agendas as $agenda) {
                $agenda['agenda_time'] = $this->trainer_agenda_time_model->get_trainer_time($agenda['id'])->result_array();
                $sentby = $this->user_model->get_user_info($agenda['appointed_by'])->row_array();
                if (!empty($agenda['agenda_time'])) {
                    $first_time = $agenda['agenda_time'][0]['work_time'] . ":00:00";
                    $last_item = count($agenda['agenda_time']) - 1;
                    $last_time = $agenda['agenda_time'][$last_item]['work_time'] . ":00:00";
                } else {
                    $first_time = "00:00:00";
                    $last_time = "23:00:00";
                }
                $start_time = $agenda['appoint_date'] . 'T' . $first_time;
                $end_time = $agenda['appoint_date'] . 'T' . $last_time;
                $outlook_email = $this->connect_agenda_model->get_data_by_general_id($this->session->userdata('general_id'))->row_array();
                $this->send_outlook_mail($sentby['name'], $sentby['email_address'], $trainer_data['name'], $outlook_email['outlook_email'], $start_time, $end_time, $agenda['appointment_name'], $agenda['appointment_description'], $agenda['location']);
                $agenda_data['outlook_status'] = 1;
                $this->trainer_agenda_model->update('trainer_agenda', $agenda_data, array('id' => $agenda['id']));
            }
        }
    }

    public function save_outlook_mail()
    {
        $data['general_id'] = $this->session->userdata('general_id');
        $data['outlook_email'] = $this->input->post('outlook_email');
        $userdata = $this->connect_agenda_model->get_data_by_general_id($data['general_id'])->row_array();
        if (!empty($userdata)) {
            $this->connect_agenda_model->update('connect_agenda', $data, array('id' => $userdata['id']));
        } else {
            $this->connect_agenda_model->insert('connect_agenda', $data);
        }
        $this->check_outlook_mail(2);
        redirect(site_url('edit'));
    }


    public function sync_calendar()
    {
        $result['status'] = 0;
        $data = $this->connect_agenda_model->get_data_by_general_id($this->session->userdata('general_id'))->row_array();
        $icloud_details = $this->icloud_model->getUserIcloudDetails($this->session->userdata('general_id'));
        $all_agendas_arr = $this->_getAgendasToSend();
		$all_agendas = $all_agendas_arr['all_agendas'];
		$trainer_data = $all_agendas_arr['trainer_data'];
        if (!empty($data['outlook_email'])) {
            $this->set_outlook_mail($all_agendas);
            $result['status'] = 1;
        }
        if (!empty($data['gmail_status'])) {
            $result['gmail_status'] = 1;
            $result['status'] = 1;
        }
        if (!empty($icloud_details)) {
            $this->_sendEventsToiCloud($all_agendas, $icloud_details);
            $result['status'] = 1;
        }
        echo json_encode($result);
    }

    private function _sendEventsToiCloud($all_agendas, $icloud_details)
    {
        $agendas_to_send = array();
        $sent_agendas = array();
        foreach ($all_agendas as $agenda) {
            if ($agenda['is_confirm'] == 1 AND $agenda['icloud_status'] == 0)
                $agendas_to_send[] = $agenda;
        }
        //print_r($agendas_to_send);
        if (!empty($agendas_to_send)) {
            foreach ($agendas_to_send as $agenda) {
                $agenda['agenda_time'] = $this->trainer_agenda_time_model->get_trainer_time($agenda['agenda_id'])->result_array();
                //echo $this->db->last_query();
                if (!empty($agenda['agenda_time'])) {
                    $first_time = $agenda['agenda_time'][0]['work_time'];
                    $last_item = count($agenda['agenda_time']) - 1;
                    $last_time = $agenda['agenda_time'][$last_item]['work_time'];
                } else {
                    $first_time = "00:00:00";
                    $last_time = "23:00:00";
                }
                $start_time = $agenda['appoint_date'] . ' ' . $first_time;
                $end_time = $agenda['appoint_date'] . ' ' . $last_time;
                $appointment_details['appointment_name'] = $agenda['appointment_name'];
                $appointment_details['appointment_description'] = $agenda['appointment_description'];
                $appointment_details['location'] = $agenda['location'];
                $appointment_details['start_time'] = $start_time;
                $appointment_details['end_time'] = $end_time;
                $this->icloud->addEvent($appointment_details, $icloud_details);
                $agenda_data['icloud_status'] = 1;
                array_push($sent_agendas, array('id' => $agenda['id'], 'icloud_status' => 1));
                $this->trainer_agenda_model->update('trainer_agenda', $agenda_data, array('id' => $agenda['agenda_id']));
            }
        }
    }

    public function edit_appointment()
    {
        $events = array();
        date_default_timezone_set("Asia/Kathmandu");
        $json = $_POST['event_details'];
        $event = $json;
        $general_info_id = $this->session->userdata('general_id');
        $data['general_info_id'] = $general_info_id;
        $event['start'] = date("Y-m-d H:i:s",($event['starttimestamp']/1000));
        $event['end'] = date("Y-m-d H:i:s",($event['endtimestamp']/1000));
        $events[] = $event;
        $this->session->set_userdata('json_data', $events);
    }


    public function new_create_appointment()
    {
        date_default_timezone_set("Asia/Kathmandu");
        $json = $_POST['event_details'];
        $event_details = json_decode($json, true);
        $events = $event_details['events'];

        $appointments = array();
        $index = 0;
        foreach($events as $event){
            $appointments[$index]['start_date'] = $event['start_date'];
            $appointments[$index]['start_time']= $event['start_time'];
            $appointments[$index]['end_date']= $event['end_date'];
            $appointments[$index]['end_time'] = $event['end_time'];
            $index++;
        }



        $general_info_id = $this->session->userdata('general_id');
        $data['general_info_id'] = $general_info_id;
        $this->session->set_userdata('json_data', $appointments);

    }



    public function appointment_page($id)
    {

        $data['general_user_id'] = $this->session->userdata('general_id');
        if ($id == $data['general_user_id']){
            $data['same_user'] = 1;
            $data['trainer_data'] = $this->trainer_info_model->get_specific_trainer($data['general_user_id'])->row_array();

        }
    else{
        $data['same_user'] = 0;
        $data['trainer_data'] = $this->trainer_info_model->get_specific_trainer($id)->row_array();
    }
        $data['trainer_info_data'] = $this->user_model->get_user_by_trainer_id($data['trainer_data']['id'])->row_array();


        $data['header'] = "Trainer Agenda";
        $data['words'] = $this->_language;
        $data['user_data'] = $this->user_model->get_user_info($id)->row_array(); //general info id
        $data['language'] = $this->language_model->select_specific_language()->result_array();
//        $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();


        if(!(empty($this->session->userdata('json_data')))){
            $data['json_data'] = $this->session->userdata('json_data');

        }
        else{
            $flashdata = array("type" => "error", "message" => 'Please choose atleast one appointment');
            $this->session->set_flashdata('flash_message', $flashdata);
            redirect(base_url('member/trainer_agenda/get_trainer_agenda') . '/' . $this->session->userdata('general_id'));

        }



        $data['page'] = "member/profile/appointment";
        $this->load->view($this->_container, $data);

    }


}