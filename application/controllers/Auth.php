<?php
class Auth extends Frontend_Controller
{
    var $general_info_id;
    var $log_id;
    var $upload_path = 'uploads/userimage';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('token_model');
        $this->load->model('trial_period_model');
        $this->load->model('company_info_model');
        $this->load->model('trainer_info_model');
        $this->load->model('speciality_model');
        $this->load->model('question_model');
        $this->load->model('option_model');
        $this->load->model('answer_model');
        $this->load->model('email_model');
        $this->load->model('user_subscription_model');
        $this->load->model('language_model');
        $this->load->model('translation_model');
        $this->load->model('keyword_model');
        $this->load->model('standard_availability_model');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->model('day_model');
        $this->load->model('time_model');
		$this->load->model('api_model');
    }

    public function page_not_found()
    {
        $data['header'] = '404 Page Not Found';
        $data['words'] = $this->_language;
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $data['page'] = 'errors/page_not_found';
        $data['photo_logo'] = $this->session->userdata('photo');
        $id = $this->session->userdata('general_id');
        $data['userdata'] = $this->user_model->get_user_info($id)->row_array();
        $this->load->view($this->_container, $data);
    }

    public function index($flashdata = null)
    {
        $this->session->unset_userdata('speciality');
        if ($this->session->userdata('general_id') == null) {
            $data['page'] = 'member/login/index';
            $data['words'] = $this->_language;
            $data['header'] = 'Login';
            $data['language'] = $this->language_model->select_specific_language()->result_array();
            $this->load->view($this->_container, $data);
        } else
            $this->user_profile($this->session->userdata('general_id'));
        if ($flashdata)
            $this->session->set_flashdata('flash_message', $flashdata);
    }

    private function _get_login_data()
    {
        $data['id'] = $this->input->post('user_login_id');
        $data['email_address'] = ($this->input->post('email_address'));
        $data['username'] = ($this->input->post('username'));
        if ($data['email_address'] == null && $data['username'] == null)
            return null;
        $data['password'] = md5(($this->input->post('password')));
        return $data;
    }

    public function login()
    {

        if ($this->session->userdata('linkedin_id') != null) {
            $data['info'] = $this->user_model->get_user_by_linkedin_id($this->session->userdata('linkedin_id'))->row_array();
        } else {
            $data['info'] = $this->_get_login_data();
        }

        $this->form_validation->set_rules('email_address', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);


        if ($this->form_validation->run() == FALSE && $this->session->userdata('linkedin_id') == null) {
            $this->index();
        } else {
            $data['database_info'] = $this->user_model->get_login_info(array('username' => $data['info']['email_address']))->row_array();
            if (!isset($data['database_info']))
                $data['database_info'] = $this->user_model->get_login_info(array('email_address' => $data['info']['email_address']))->row_array();
            $trial_period_month = $this->trial_period_model->get_trial_method($data['database_info']['user_type_id'])->row_array();

            if ($data['database_info']['password'] == $data['info']['password'] && $data['database_info']['is_active'] == 1)
            {
                if ($data['database_info']['user_type_id'] == 1) {
                    $this->session->set_userdata($data['database_info']);
                    $userdata = $this->user_model->get_user($data['database_info']['id'])->row_array();
                    $this->session->set_userdata('photo', $userdata['photo_logo']);
                    $this->session->set_userdata('general_id', $userdata['id']);

                    //insert into user logs begin
                    $logdata['user_login_id'] = $userdata['user_login_id'];
                    $logdata['login_date'] = date('Y-m-d H:i:s');
                    $logdata['ip'] = $this->input->ip_address();
                    $logdata['remarks'] = "Any Text";
                    $logdata['is_deleted'] = 0;
                    $this->user_model->insert('user_login_logs', $logdata);
                    $this->session->set_userdata('log_id', $this->user_model->get_last_inserted_id());
                    $this->session->set_userdata('is_admin', 1);
                    //insert into user logs end


                    redirect(site_url('admin/home'));
                    exit;
                }
                $this->session->set_userdata($data['database_info']);
                $userdata = $this->user_model->get_user($data['database_info']['id'])->row_array();
                $this->session->set_userdata('photo', $userdata['photo_logo']);
                $this->session->set_userdata('general_id', $userdata['id']);

                $trial_period_time = $this->trial_period_model->get_trial_periods(array('user_type_id' => $userdata['user_type_id']))->row_array();
                $time = strtotime($userdata['created_on']);
                $time_with_trial = date("Y-m-d H:i:s", strtotime("+" . $trial_period_time['trial_period_time'] . " days", $time));
                $current_date = date("Y-m-d H:i:s");

                $subscription_detail = $this->user_subscription_model->get_user_subscription($userdata['id'])->row_array();


                if (empty($subscription_detail) && $time_with_trial < $current_date) {
                    if ($data['database_info']['user_type_id'] == 2) // company
                    {
                        $email_data = $this->email_model->get_email(2, 1)->row_array();
                    }
					elseif ($data['database_info']['user_type_id'] == 3) // trainer
                    {
                        $email_data = $this->email_model->get_email(3, 1)->row_array();
                    }
                    $admin_data = $this->user_model->get_user_info(1)->row_array();
                    $from = 'noreply@info.com';
                    $name = 'Agendatool';
                    $to = $userdata['email_address'];
                    $subject = $email_data['subject'];
                    $message = $email_data['email'];
					if($email_data['flag_bit'] == 1)
						$this->mail_send($from, $name, $to, $subject, $message);
                    $flashdata = array("type" => "error", "message" => $this->_language['DTL_0308']);
                    $this->session->set_flashdata('flash_message', $flashdata);
                    $this->expire_page();
                }
                elseif ($time_with_trial >= $current_date || $subscription_detail['to_date'] >= $current_date) {


                    if ($data['database_info']['user_type_id'] == 2) {
                        $this->session->set_userdata('is_admin', 0);
                        $this->session->set_userdata('is_company', 1);
                    }
                    if ($data['database_info']['user_type_id'] == 3) {
                        $this->session->set_userdata('is_admin', 0);
                        $this->session->set_userdata('is_company', 0);
                    }

                    $this->session->set_userdata('general_id', $userdata['id']);
                    $this->session->set_userdata('user_type_id', $userdata['user_type_id']);
                    $this->session->set_userdata('default_language_id', $userdata['default_language_id']);

                    //insert into user logs begin
                    $logdata['user_login_id'] = $userdata['user_login_id'];
                    $logdata['login_date'] = date('Y-m-d H:i:s');
                    $logdata['ip'] = $this->input->ip_address();
                    $logdata['remarks'] = "Any Text";
                    $logdata['is_deleted'] = 0;
                    $this->user_model->insert('user_login_logs', $logdata);
                    $this->session->set_userdata('log_id', $this->user_model->get_last_inserted_id());
                    //insert into user logs end
                    $this->user_profile($userdata['id']);

                    $flashdata = array("type" => "success", "message" => $this->_language['DTL_0309']);
                    $this->session->set_flashdata('flash_message', $flashdata);
                    exit;
                }
                else {


                    if ($data['database_info']['user_type_id'] == 2) // company
                    {
                        $email_data = $this->email_model->get_email(2, 1)->row_array();
                    } elseif ($data['database_info']['user_type_id'] == 3) // trainer
                    {
                        $email_data = $this->email_model->get_email(3, 1)->row_array();
                    }
                    $admin_data = $this->user_model->get_user_info(1)->row_array();
                    $from = 'noreply@agendatool.com';
                    $name = 'Agendatool';
                    if (!empty($email_data)) {
                        $to = $userdata['email_address'];
                        $subject = $email_data['subject'];
                        $message = $email_data['email'];
						if($email_data['flag_bit'] == 1)
							$this->mail_send($from, $name, $to, $subject, $message);
                        $this->expire_page();

                    }
                }

            } else {
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0310']);
                $this->session->set_flashdata('flash_message', $flashdata);
                $this->index();
            }
        }
    }


    public function expire_page()
    {
        $this->session->sess_destroy();
        $data['page'] = 'member/error/expiry_page';
        $data['words'] = $this->_language;
        $data['header'] = 'Expired';
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);

    }

    private function user_profile($id)
    {
        redirect(site_url('member/profile/view_profile/' . $id));
    }

    private function admin_profile()
    {
        $url = site_url('admin/home');
        header("Location: $url");
    }


    public function logout($flashdata = null)
    {

        $data['logout_date'] = date('Y-m-d H:i:s');
        $id = $this->session->userdata('log_id');
        $this->user_model->update('user_login_logs', $data, array('id' => $id));
        $this->session->sess_destroy();
        if ($flashdata == null)
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0311']);
        $this->session->set_flashdata('flash_message', $flashdata);
        $this->index($flashdata);
        exit;
    }

    public function register_company()
    {
        $count = 0;
        $data['main_specialities'] = $this->speciality_model->get_main_specialities()->result_array();
        $data['dynamic_fields'] = $this->question_model->get_dynamic_fields(array('section_id' => 1))->result_array();
        foreach ($data['dynamic_fields'] as $dynamic_fields) {
            $data['dynamic_fields'][$count]['options'] = $this->option_model->get_options($dynamic_fields['id'])->result_array();
            $count++;
        }
        $data['page'] = 'member/login/register_company';
        $data['words'] = $this->_language;
        $data['header'] = 'Register Company';
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function register_trainer()
    {
        $count = 0;
        $data['main_specialities'] = $this->speciality_model->get_main_specialities()->result_array();
        $data['dynamic_fields'] = $this->question_model->get_dynamic_fields(array('section_id' => 3))->result_array();
        foreach ($data['dynamic_fields'] as $dynamic_fields) {
            $data['dynamic_fields'][$count]['options'] = $this->option_model->get_options($dynamic_fields['id'])->result_array();
            $count++;
        }
        $data['page'] = 'member/login/register_trainer';
        $data['words'] = $this->_language;
        $data['header'] = 'Register Trainer';
        $days = $this->day_model->get_days(array('status' => 1))->result_array();
        foreach ($days as $value) {
            $data[$value['day']] = $value['status'];
        }
        for ($i = 0; $i <= 23; $i++) {
            $data['time'][$i] = null;
        }
        $times = $this->time_model->get_times()->result_array();
        foreach ($times as $time) {
            $data['time'][$time['time']] = $time['status'];
        }


        $data['available_date'] = $this->day_model->get_available_days()->result_array();
        $data['available_time'] = $this->time_model->get_available_times()->result_array();
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);

    }

    private function _get_company_data()
    {
        $data['contact_person'] = $this->input->post('contact_person');
        return $data;
    }

    public function redirect_back()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME']);
        }
    }

    public function speciality_check($data)
    {
        if (count($data) > 3) {
            $this->form_validation->set_message('speciality_check', 'Select 3 or less specialities');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function save()
    {
        $logindata = $this->_get_login_data();
        $registerdata = $this->_get_register_data();
        $specialities = $this->_get_speciality_data();

        $success = 1;
        //Set Validation Rules
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
        if ($registerdata['is_company'] == 1) {
            if ($this->session->userdata('linkedin_id') == null) {
                $this->form_validation->set_rules('name', 'Name', 'required|is_unique[general_reg_info.name]');
                $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
                $this->form_validation->set_message('is_unique', '%s ' . $this->_language['DTL_0302']);
            } else {
                $i = 1;
                while (true) {
                    $result = $this->user_model->check_name($registerdata['name']);
                    if ($result) {
                        $registerdata['name'] = $registerdata['name'] . $i;
                        $i++;
                    } else {
                        break;
                    }
                }
            }
            $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
            $this->validate_dynamic_fields_company();
        } else {
            $this->form_validation->set_rules('education', 'Education', 'required');
//            $this->form_validation->set_rules('from_day', 'From Day', 'callback_is_required_day');
//            $this->form_validation->set_rules('to_day', 'To Day', 'callback_is_required_day|callback_check_to_day[' . $this->input->post('from_day') . ']');
//            $this->form_validation->set_rules('from_time', 'From Time', 'callback_is_required_time');
//            $this->form_validation->set_rules('to_time', 'To Time', 'callback_is_required_time|callback_check_to_time[' . $this->input->post('from_time') . ']');
            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
            if ($this->session->userdata('linkedin_id') == null) {
                $this->form_validation->set_rules('username', 'User Name', 'is_unique[user_login.username]|required');
                $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
                $this->form_validation->set_message('is_unique', '%s ' . $this->_language['DTL_0302']);
            } else {
                $i = 1;
                while (true) {
                    $result = $this->user_model->check_username($logindata['username']);
                    if ($result) {
                        $logindata['username'] = $logindata['username'] . $i;
                        $i++;
                    } else {
                        break;
                    }
                }
            }
            $this->form_validation->set_rules('cost_per_hour', 'Cost Per Hour', 'numeric|required');
            $this->form_validation->set_rules('work_experience', 'Work Experience', 'required');
            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
            $this->form_validation->set_message('numeric', '%s ' . $this->_language['DTL_0303']);
            $this->validate_dynamic_fields_trainer();
        }

        if ($this->session->userdata('linkedin_id') == null) {
            $this->form_validation->set_rules('email_address', 'Email Address', 'required|is_unique[user_login.email_address]|valid_email');
            $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
            $this->form_validation->set_message('is_unique', '%s ' . $this->_language['DTL_0302']);
            $this->form_validation->set_message('valid_email', '%s ' . $this->_language['DTL_0304']);
        } else {
            $result = $this->user_model->check_email_address($logindata['email_address']);
            if ($result) {
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0312']);
                $this->session->set_flashdata('flash_message', $flashdata);
                redirect(site_url('auth'));
                exit;
            }
        }
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('address_longitude', 'Longitude', 'callback_check_address|trim');
        $this->form_validation->set_rules('address_latitude', 'Latitude', 'trim');
        $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|callback_integer_check');
        if ($this->session->userdata('linkedin_id') == null)
            $this->form_validation->set_rules('photo_logo', 'Photo', 'callback_check_photo');
        $this->form_validation->set_rules('iban_number', 'IBAN Number', 'required');
        $this->form_validation->set_rules('bic_number', 'BIC Number', 'required');
        $this->form_validation->set_rules('speciality_ids', 'Speciality', 'callback_check_speciality');
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
        if ($this->form_validation->run() == FALSE || $success == 0) {
            if ($registerdata['is_company'] == null) {
                $this->register_trainer();
            } else {
                $this->register_company();
            }
        } else {
            //insert login data begin
            $this->user_model->trans_start();
            if ($registerdata['is_company'] == 1) {
                $logindata['username'] = $registerdata['name'];
                $logindata['user_type_id'] = 2;
            }
            if ($registerdata['is_company'] == 0)
                $logindata['user_type_id'] = 3;
            $logindata['is_active'] = 1;
            $logindata['is_first_time_login'] = 1;
            $logindata['is_deleted'] = 0;

            if ($this->session->userdata('linkedin_id') != null) {
                $logindata['linkedin_id'] = $this->session->userdata('linkedin_id');
                $logindata['linkedin_status'] = 1;
                $logindata['linkedin_image'] = $this->session->userdata('linkedin_image_url');
                $logindata['linkedin_image_status'] = 1;
            }
            $this->user_model->insert('user_login', $logindata);
            //insert login data end


            //insert register data
            $success = $this->upload_image();
            $imagename = $this->upload->data();
            $registerdata['user_login_id'] = $this->db->insert_id();
            $registerdata['default_language_id'] = 1;
            $registerdata['created_on'] = date('Y-m-d H:i:s');
            $registerdata['is_deleted'] = 0;

            if ($registerdata['is_company'] == null)
                $registerdata['is_company'] = 0;
            $registerdata['trial_period_id'] = $this->user_model->get_last_inserted_id();
            if ($imagename['file_name'])
                $registerdata['photo_logo'] = $imagename['file_name'];
            else
                $registerdata['photo_logo'] = "default.jpg";
            $this->user_model->insert('general_reg_info', $registerdata);
            $register_inserted_id = $this->user_model->get_last_inserted_id();
            $this->_set_general_info_id($register_inserted_id);
            $this->save_text_box_answer($register_inserted_id);
            $this->save_check_box_answer($register_inserted_id);
            $this->save_drop_down_answer($register_inserted_id);
            $specialitygeneralid['general_info_id'] = $this->_get_general_info_id();
            //insert register data end

            //insert if trainer begin
            if ($registerdata['is_company'] == 0) {
                $trainerdata = $this->_get_trainer_data();
                $trainerdata['general_info_id'] = $this->_get_general_info_id();
                $trainerdata['visibility_id'] = 1;
                $trainerdata['invite_company_id'] = null;
                $removed_days = $this->day_model->get_removed_days()->result_array();
                $trainerdata['removed_days'] = serialize($removed_days);
                $removed_times = $this->time_model->get_removed_times()->result_array();
                $trainerdata['removed_times'] = serialize($removed_times);
                $trainerdata['is_deleted'] = 0;
                $this->trainer_info_model->insert('trainer_info', $trainerdata);
                $standard_availability_data = $this->_get_standard_availability_data_new();
                $standard_availability_data['trainer_info_id'] = $this->user_model->get_last_inserted_id();
                $standard_availability_data['is_deleted'] = 0;
                $this->standard_availability_model->insert('standard_availability', $standard_availability_data);
                $this->session->set_userdata('is_company', 0);

            } //insert if company begin
            elseif ($registerdata['is_company'] == 1) {
                $companydata = $this->_get_company_data();
                $companydata['general_info_id'] = $this->_get_general_info_id();
                $companydata['is_deleted'] = 0;
                $this->company_info_model->insert('company_info', $companydata);
                $this->session->set_userdata('is_company', 1);
            }
            //insert if company end


            //insert speciality begin
            if ($specialities != null) {
                for ($i = 0; $i < count($specialities); $i++) {
                    $specialitydata['general_reg_info_id'] = $specialitygeneralid['general_info_id'];
                    $specialitydata['speciality_id'] = $specialities[$i];
                    $specialitydata['display_order'] = 1;
                    $this->speciality_model->insert('speciality_detail', $specialitydata);
                }
            }
            $this->session->set_userdata('username', $logindata['username']);
            $success = $this->user_model->trans_complete();
            //insert speciality end

            if ($success) {
                //insert into user logs begin
                $userdata = $this->user_model->get_user_info($this->_get_general_info_id())->row_array();
                $logdata['user_login_id'] = $userdata['id'];
                $logdata['login_date'] = date('Y-m-d H:i:s');
                $logdata['ip'] = $this->input->ip_address();
                $logdata['remarks'] = "Any Text";
                $logdata['is_deleted'] = 0;
                $this->user_model->insert('user_login_logs', $logdata);
                $this->session->set_userdata('log_id', $this->user_model->get_last_inserted_id());
                $this->session->set_userdata('id', $userdata['user_login_id']);
                $this->session->set_userdata('username', $userdata['username']);
                $this->session->set_userdata('email_address', $userdata['email_address']);
                $this->session->set_userdata('password', $userdata['password']);
                $this->session->set_userdata('user_type_id', $userdata['user_type_id']);
                $this->session->set_userdata('photo', $registerdata['photo_logo']);
                //insert into user logs end

                //send message start
                if ($registerdata['is_company'] == 1) {
                    $data = $this->email_model->get_email(2, 2)->row_array();
                } elseif ($registerdata['is_company'] == 0) {
                    $data = $this->email_model->get_email(3, 2)->row_array();
                }
				if($data['flag_bit'] == 1)
					$this->mail_send("noreply@agendatool.com", "Agendatool", $userdata['email_address'], $data['subject'], $data['email']);
                $mail_data = $this->email_model->get_email(1, 2)->row_array();
                $admin_data = $this->user_model->get_user_info(1)->row_array();
				if($mail_data['flag_bit'] == 1)
					$this->mail_send("noreply@agendatool.com", "Agendatool", $admin_data['email_address'], $mail_data['subject'], $mail_data['email']);
                //send message end

                $this->session->set_userdata('general_id', $userdata['id']);
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0313']);
                $this->session->set_flashdata('flash_message', $flashdata);
                $this->user_profile($this->_get_general_info_id());
            } else {
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0314']);
                $this->session->set_flashdata('flash_message', $flashdata);
                $this->index();
            }

        }


    }

    public function check_photo()
    {
        $data = $_FILES;
        if (($data['photo_logo']['name']) == null) {
            $this->form_validation->set_message('check_photo', $this->_language['DTL_0201']);
            return false;
        } else
            return true;
    }

    public function integer_check($value)
    {
        if (is_numeric($value)) {
            return true;
        } else {
            $this->form_validation->set_message('integer_check', '%s ' . $this->_language['DTL_0303']);
            return false;
        }
    }

    public function check_address($value)
    {
        if ($value == null) {
            $this->form_validation->set_message('check_address', $this->_language['DTL_0307']);
            return false;
        } else {
            return true;
        }
    }

    public function check_speciality()
    {
        $value = $this->input->post('speciality_ids');
        if (sizeof($value) == 0) {
            $this->form_validation->set_message('check_speciality', $this->_language['DTL_0054']);
            return false;
        } elseif (sizeof($value) > 3) {
            $this->form_validation->set_message('check_speciality', $this->_language['DTL_0336']);
            return false;
        } else {
            return true;
        }
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
            $this->form_validation->set_message('is_required_day', '%s ' . $this->_language['DTL_0301']);
            return false;
        } else {
            return true;
        }
    }

    public function is_required_time($value)
    {
        if ($value == null) {
            $this->form_validation->set_message('is_required_time', '%s ' . $this->_language['DTL_0301']);
            return false;
        } else {
            return true;
        }
    }


    public function mail_send($from, $name, $to, $subject, $message)
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

    private function _get_register_data()
    {
        $data['name'] = $this->input->post('name');
        $data['address'] = $this->input->post('address');
        $data['zip_code'] = $this->input->post('zip_code');
        $data['phone_no'] = $this->input->post('phone_number');
        $data['is_company'] = $this->input->post('is_company');
        $data['iban_number'] = $this->input->post('iban_number');
        $data['bic_number'] = $this->input->post('bic_number');
        $data['longitude'] = $this->input->post('address_longitude');
        $data['latitude'] = $this->input->post('address_latitude');
        $data['is_required'] = 1;
        return $data;
    }

    private function _get_standard_availability_data()
    {
        $data['from_day'] = $this->input->post('from_day');
        $data['to_day'] = $this->input->post('to_day');
        $data['from_time'] = $this->input->post('from_time');
        $data['to_time'] = $this->input->post('to_time');
        return $data;
    }


    private function _get_standard_availability_data_new()
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





    private function _get_trainer_data()
    {
        $data['education'] = $this->input->post('education');
        $data['work_experience'] = $this->input->post('work_experience');
        $data['cost_per_hour'] = $this->input->post('cost_per_hour');
        return $data;
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
            return 0;
        } else {
            $data = array('upload_data' => $this->upload->data());
            return 1;
        }
    }

    private function _get_speciality_data()
    {
        $data = null;
        if ($this->input->post('speciality_ids'))
            $data = $this->input->post('speciality_ids');
        if (!empty($data)) {
            foreach ($data as $d) {
                $all_speciality_data[] = $this->speciality_model->get_speciality($d)->row_array();
            }
            $this->session->set_userdata('speciality', $all_speciality_data);
        } else {
            $this->session->unset_userdata('speciality');
        }
        return $data;
    }

    private function _set_general_info_id($id)
    {
        $this->general_info_id = $id;
    }

    private function _get_general_info_id()
    {
        return $this->general_info_id;
    }

    public function change_image()
    {
        $id = $this->input->post('id_for_photo');
        $old_photo = $this->input->post('old_photo');
        $this->delete_image($old_photo);
        $this->upload_image();
        $imagename = $this->upload->data();
        if ($imagename['file_name'] != null) {
            $data['photo_logo'] = $imagename['file_name'];
            $this->user_model->update('general_reg_info', $data, array('id' => $id));
        } else {
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0201']);
            $this->session->set_flashdata('flash_message', $flashdata);
        }
    }

    public function delete_image($filename)
    {
        @unlink($this->upload_path . '/' . $filename);
    }

    public function forgot_password()
    {
        $email_address = $this->input->post('email_address');
        $general_id = $this->user_model->get_general_id_by_email($email_address)->row_array();
        if (empty($email_address)) {
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0196']);
            $this->session->set_flashdata('flash_message', $flashdata);
            $this->forgot_password_page();
        } elseif (!empty($general_id)) {
            $data = $this->email_model->get_email(2, 4)->row_array();
            $token = $this->generateRandomString(15);
            $this->_insert_token($token, $general_id['id']);
            $message = $data['email'] . '<br/>';
            $message .= "<a href='" . site_url('auth/valid_token_forgot_password') . "/" . $token . "'>" . $token . "</a>";
			if($data['flag_bit'] == 1)
				$this->mail_send("noreply@agendatool.com", "Agendatool", $email_address, $data['subject'], $message);
            $flashdata = array("type" => "success", "message" => $this->_language['DTL_0315']);
            $this->session->set_flashdata('flash_message', $flashdata);
            $this->forgot_password_page();
        } else {
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0195']);
            $this->session->set_flashdata('flash_message', $flashdata);
            $this->forgot_password_page();
        }
    }

    private function _insert_token($token, $general_user_id)
    {
        $data['token'] = $token;
        $data['duration'] = 10;
        $data['is_deleted'] = 1;
        $data['general_reg_info_id'] = $general_user_id;
        $this->token_model->insert('token', $data);
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

    public function valid_token_forgot_password($token)
    {
        $valid_token = $this->token_model->get_token($token)->row_array();
        if ($valid_token == null) {
            redirect(site_url('auth'));
        } else {
            redirect(site_url('auth/forgot_password_login' . '/' . $valid_token['general_reg_info_id']));
        }
    }

    public function valid_token($token)
    {
        $valid_token = $this->token_model->get_token($token)->row_array();
        if ($valid_token == null) {
            redirect(site_url('auth'));
        } else {
            redirect(site_url('member/profile/other_profile' . '/' . $valid_token['general_reg_info_id']));
        }
    }


    public function forgot_password_page()
    {
        $data['page'] = 'member/login/forgot_password';
        $data['words'] = $this->_language;
        $data['header'] = 'Forgot Password';
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function change_password_function()
    {
        $id = $this->input->post('user_id');
        $new_password = $this->input->post('new_password');
        $enc_password = md5($this->input->post('new_password'));
        $confirm_password = md5($this->input->post('confirm_password'));
        $data['password'] = $enc_password;
        if (($new_password) != null) {
            if ($enc_password != $confirm_password) {
                $flashdata = array("type" => "error", "message" => "Password don't match");
                $this->session->set_flashdata('flash_message', $flashdata);
                $data['page'] = 'member/profile/forgot_password';
                $data['words'] = $this->_language;
                $data['header'] = 'Change Password';
                $data['language'] = $this->language_model->select_specific_language()->result_array();
                $this->load->view($this->_container, $data);
                exit;
            } else {
                $this->user_model->update('user_login', $data, array('id' => $id));
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0318']);
                $this->session->set_flashdata('flash_message', $flashdata);
                redirect(site_url('auth'));
            }
        } else {
            $flashdata = array("type" => "error", "message" => $this->_language['DTL_0319']);
            $this->session->set_flashdata('flash_message', $flashdata);
            $data['page'] = 'member/profile/forgot_password';
            $data['words'] = $this->_language;
            $data['header'] = 'Change Password';
            $data['language'] = $this->language_model->select_specific_language()->result_array();
            $this->load->view($this->_container, $data);
            exit;
        }

    }

    public function forgot_password_login($id)
    {
        $userdata = $this->user_model->get_user_info($id)->row_array();

        $data['user_id'] = $userdata['user_login_id'];
        $data['page'] = 'member/login/change_password';
        $data['words'] = $this->_language;
        $data['header'] = 'Change Password';
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
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
                            $this->form_validation->set_rules('options[' . $dynamic_field['id'] . ']', 'Check Box', 'callback_checkbox_required');
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
            $this->form_validation->set_message('checkbox_required', '%s ' . $this->_language['DTL_0301']);
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

    public function linkedin_register()
    {
		$data['api_details'] = $this->api_model->get_api()->result_array();
        $data['header'] = 'Register';
        $data['page'] = 'member/login/linkedin_register';
        $data['words'] = $this->_language;
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function linkedin_register_page()
    {

        if ($this->user_model->check_email_address($this->session->userdata('linkedin_email_address'))) {
            $userdata = $this->user_model->get_user_by_email($this->session->userdata('linkedin_email_address'))->row_array();
            if ($userdata['linkedin_id'] == null) {
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0320']);
                $this->session->set_flashdata('flash_message', $flashdata);
                redirect(site_url('auth'));
                exit;
            } else {
                $this->login();
            }
        }
        $data['page'] = 'member/login/linkedin_register_page';
        $data['words'] = $this->_language;
        $data['header'] = 'Register Through Linkedin';
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

}