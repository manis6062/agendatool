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
        $this->load->model('favorite_model');
        $this->load->model('notification_model');
        $this->load->model('language_model');
        $this->load->model('email_model');
        $this->load->model('email_log_model');
        $this->load->model('standard_availability_model');
        $this->load->model('company_info_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');

    }

    public function get_trainer_agenda($id, $data = null)
    {

        $data['total_agendas'] = $this->trainer_agenda_model->get_all_total_agendas($id);
        $data['events'] = array();
        $index = 0;
        foreach ($data['total_agendas'] as $result) {
            //print_r($result);
            $data['events'][$index]['id'] = $index;
            $data['events'][$index]['appointment_id'] = $result['id'];
            $data['events'][$index]['appoint_date'] = $result['appoint_date'];
            $data['events'][$index]['start_date'] = $result['start_date'];
            $data['events'][$index]['end_date'] = $result['end_date'];
            $data['events'][$index]['appointment_name'] = $result['appointment_name'];
            $data['events'][$index]['is_appointed'] = $result['is_appointed'];


            $check_is_company = $this->user_model->get_company($result['appointed_by'])->row_array();

            if($check_is_company['is_company'] == 0){
                $data['events'][$index]['is_company'] = 0;

            }
            else{
                $data['events'][$index]['is_company'] = 1;
            }

            $data['events'][$index]['is_confirm'] = $result['is_confirm'];
            $data['events'][$index]['appointed_by'] = $result['appointed_by'];

            $index++;
        }

        $data['agendas'] = $this->trainer_agenda_model->get_trainer_agenda($id)->result_array();

        $data['header'] = "Trainer Agenda";
        $data['words'] = $this->_language;
        $data['general_user_id'] = $this->session->userdata('general_id');
        $data['userdata'] = $this->user_model->get_user_info($id)->row_array(); //general info id
        $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($data['userdata']['id'])->row_array();
        $data['standard_availability'] = $this->standard_availability_model->get_standard_availability($data['trainerdata']['id'])->row_array();

        if ($id == $data['general_user_id'])
            $data['same_user'] = 1;
        else
            $data['same_user'] = 0;
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
        $data['page'] = "member/profile/trainer_agenda";
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
        $this->load->view($this->_container, $data);
    }



    public function check_document()
    {

        $path = $_FILES['file_upload']['name'];

        foreach ($path as $paths) {
            $ext = pathinfo($paths, PATHINFO_EXTENSION);
            if ($paths != null) {
                if ($ext != 'pdf' && $ext != 'doc' && $ext != 'docx' && $ext != 'txt') {
                    $this->form_validation->set_message('check_document', $this->_language['DTL_0327']);
                    return false;
                } else
                    return true;
            }
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

    private function _get_posted_data()
    {
        $data['id'] = $this->input->post('agenda_id');
        $data['trainer_info_id'] = $this->input->post('trainer_id');
        $data['appointment_name'] = $this->input->post('appointmentName');
        $data['location'] = $this->input->post('location');
        $data['appointed_by'] = $this->input->post('appointed_by');
        $data['appointment_description'] = $this->input->post('description');
        $data['appoint_date'] = $this->input->post('date');
        $data['longitude'] = $this->input->post('longitude');
        $data['latitude'] = $this->input->post('latitude');
        return $data;
    }

    public function save_date($id , $selected_time)
    {
        $selected_time_array = $this->_get_date_data_time($selected_time);
        foreach ($selected_time_array as $s) {
            $data['trainer_agenda_id'] = $id;
            $data['work_time'] = $s;
            $data['is_deleted'] = 0;
            $this->trainer_agenda_time_model->insert('trainer_agenda_time', $data);
        }
    }

    public function save_edited_date($id, $selected_time)
    {
        $selected_time_array = $this->_get_date_data_time($selected_time);
        $index = 0;
           foreach ($selected_time_array as $s) {
            $data['work_time'][$index] = $s;
            $data['is_deleted'] = 0;
//            $this->trainer_agenda_time_model->update('trainer_agenda_time',$data, array('trainer_agenda_id' => $id));
               $this->trainer_agenda_time_model->updateby_desc($data['work_time'][$index],$data['is_deleted'],$id);
               $index++;

        }


    }

    public function save_document($trainer_agenda_id)
    {
        $this->upload_document();
        $document_name = $this->upload->data();
        if ($document_name['file_name'] != null) {
            $documentdata['document_name'] = $document_name['file_name'];
            $documentdata['trainer_info_id'] = $this->input->post('trainer_id');
            $documentdata['trainer_agenda_id'] = $trainer_agenda_id;
            $documentdata['uploaded_date'] = date("Y-m-d H:i:s");
            $documentdata['display_order'] = 1;
            $documentdata['is_deleted'] = 0;
            $this->document_model->insert('document', $documentdata);
        }

    }

    public function edit_document($i, $trainer_agenda_id)
    {

        $this->remove_document($i, $trainer_agenda_id);
//        $this->multiple_upload_document($i, $trainer_agenda_id);

//        $this->save_document($trainer_agenda_id);
    }


    public function remove_date($trainer_agenda_id)
    {
        $this->trainer_agenda_time_model->delete('trainer_agenda_time', array('trainer_agenda_id' => $trainer_agenda_id));
    }

    public function remove_document($i, $trainer_agenda_id)
    {
        $document_data = $this->document_model->get_agenda_document($trainer_agenda_id)->row_array();
        $this->delete_document($document_data['document_name']);

        $config['upload_path'] = $this->upload_document_path;
        $config['allowed_types'] = 'pdf|docx|doc|txt';
        $config['max_size'] = 0;
        $files = $_FILES;
        $this->load->library('upload', $config);
        $_FILES['userfile']['name'] = $files['file_upload']['name'][$i];
        $_FILES['userfile']['type'] = $files['file_upload']['type'][$i];
        $_FILES['userfile']['tmp_name'] = $files['file_upload']['tmp_name'][$i];
        $_FILES['userfile']['error'] = $files['file_upload']['error'][$i];
        $_FILES['userfile']['size'] = $files['file_upload']['size'][$i];
        if (!$this->upload->do_upload('userfile')) {
            $error_message = $this->upload->display_errors();
            if ($error_message = "The filetype you are attempting to upload is not allowed.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0327']);
            elseif ($error_message = "You did not select a file to upload.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0329']);
            elseif ($error_message = "The file you are attempting to upload is larger than the permitted size.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0316']);
            $this->session->set_flashdata('flash_message', $flashdata);
        }
        $document_name = $this->upload->data();
        if ($document_name['file_name'] != null) {
            $documentdata['document_name'] = $document_name['file_name'];
            $documentdata['trainer_info_id'] = $this->input->post('trainer_id');
            $documentdata['trainer_agenda_id'] = $trainer_agenda_id;
            $documentdata['uploaded_date'] = date("Y-m-d H:i:s");
            $documentdata['display_order'] = 1;
            $documentdata['is_deleted'] = 0;
            $this->document_model->update('document', $documentdata, array('trainer_agenda_id' => $trainer_agenda_id));
        }
    }

    public function delete_document($filename)
    {
        @unlink($this->upload_document_path . '/' . $filename);
    }


    public function upload_document()
    {
        $config['upload_path'] = $this->upload_document_path;
        $config['allowed_types'] = 'pdf|docx|doc|txt';
        $config['max_size'] = 10240;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_upload')) {
            $error_message = $this->upload->display_errors();
            if ($error_message = "The filetype you are attempting to upload is not allowed.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0327']);
            elseif ($error_message = "You did not select a file to upload.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0329']);
            elseif ($error_message = "The file you are attempting to upload is larger than the permitted size.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0316']);
            $this->session->set_flashdata('flash_message', $flashdata);
        } else {
            $data = array('upload_data' => $this->upload->data());
        }
    }


    public function _get_date_data()
    {
        $selected_time = $this->input->post('selectedTime');
        foreach ($selected_time as $select) {
            $selected_time_array = explode(',', $select);
            return $selected_time_array;

        }

    }


    public function _get_date_data_time($selected_date)
    {
        $selected_time = $selected_date;
        $selected_time_array = explode(',', $selected_time);
        return $selected_time_array;
    }


    public function redirect_back()
    {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME']);
        }
    }

    public function pending_agenda($id, $page = null)
    {
        //$this->load->library('pagination');
        $order = 'DESC';
        $per_page = 5;
        $filter = 'All';
        $search_parameters = null;
        $from_date = null;
        $to_date = null;
        if ($this->input->post()) {
            if ($this->input->post('searchByAppointment')) {
                $searchByAppointment = $this->input->post('searchByAppointment');
                $this->session->set_userdata('searchByAppointment', $searchByAppointment);
            } else
                $this->session->unset_userdata('searchByAppointment');
            if ($this->input->post('searchByCompany')) {
                $searchByCompany = $this->input->post('searchByCompany');
                $this->session->set_userdata('searchByCompany', $searchByCompany);
            } else
                $this->session->unset_userdata('searchByCompany');
            if ($this->input->post('searchByLocation')) {
                $searchByLocation = $this->input->post('searchByLocation');
                $this->session->set_userdata('searchByLocation', $searchByLocation);
            } else
                $this->session->unset_userdata('searchByLocation');
            if ($this->input->post('from_date')) {
                $from_date = $this->input->post('from_date');
                $this->session->set_userdata('from_date', $from_date);
            } else
                $this->session->unset_userdata('from_date');
            if ($this->input->post('to_date')) {
                $to_date = $this->input->post('to_date');
                $this->session->set_userdata('to_date', $to_date);
            } else
                $this->session->unset_userdata('to_date');
            if ($this->session->userdata('searchByAppointment'))
                $search_parameters['searchByAppointment'] = $this->session->userdata('searchByAppointment');
            if ($this->session->userdata('searchByCompany'))
                $search_parameters['searchByCompany'] = $this->session->userdata('searchByCompany');
            if ($this->session->userdata('searchByLocation'))
                $search_parameters['searchByLocation'] = $this->session->userdata('searchByLocation');
            if ($this->session->userdata('from_date'))
                $from_date = $this->session->userdata('from_date');
            if ($this->session->userdata('to_date'))
                $to_date = $this->session->userdata('to_date');
        } else {
            if ($this->session->userdata('searchByAppointment'))
                $search_parameters['searchByAppointment'] = $this->session->userdata('searchByAppointment');
            if ($this->session->userdata('searchByCompany'))
                $search_parameters['searchByCompany'] = $this->session->userdata('searchByCompany');
            if ($this->session->userdata('searchByLocation'))
                $search_parameters['searchByLocation'] = $this->session->userdata('searchByLocation');
            if ($this->session->userdata('from_date'))
                $from_date = $this->session->userdata('from_date');
            if ($this->session->userdata('to_date'))
                $to_date = $this->session->userdata('to_date');
        }
        if ($this->session->userdata('order')) {
            $data['order'] = $this->session->userdata('order');
            $order = $data['order'];
        } else
            $data['order'] = $order;
        if ($this->session->userdata('per_page')) {
            $data['per_page'] = $this->session->userdata('per_page');
            $per_page = $data['per_page'];
        } else
            $data['per_page'] = $per_page;
        if ($this->session->userdata('filter')) {
            $data['filter'] = $this->session->userdata('filter');
            $filter = $data['filter'];
        } else
            $data['filter'] = $filter;
        if ($this->input->post('per_page')) {
            $per_page = $this->input->post('per_page');
            $data['per_page'] = $per_page;
            $this->session->set_userdata('per_page', $per_page);
        }
        if ($this->input->post('filter')) {
            $filter = $this->input->post('filter');
            $data['filter'] = $filter;
            $this->session->set_userdata('filter', $filter);
        }
        if ($this->input->post('order')) {
            $order = $this->input->post('order');
            $data['order'] = $order;
            $this->session->set_userdata('order', $order);
        }
        $config['base_url'] = base_url() . "member/trainer_agenda/pending_agenda/" . $id;
        $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($id)->row_array();
        if ($filter == 'All')
            $config['total_rows'] = count($this->trainer_agenda_model->get_trainer_agenda($data['trainerdata']['id'], $order, null, null, $search_parameters, $from_date, $to_date)->result_array());
        elseif ($filter == 'Confirmed')
            $config['total_rows'] = count($this->trainer_agenda_model->get_trainer_agenda_confirmed($data['trainerdata']['id'], $order, null, null, $search_parameters, $from_date, $to_date)->result_array());

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
        $data['userdata'] = $this->user_model->get_user_info($id)->row_array();
        $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($id)->row_array();
        if ($filter == 'All')
            $data['agendas'] = $this->trainer_agenda_model->get_trainer_agenda($data['trainerdata']['id'], $order, $per_page, $page, $search_parameters, $from_date, $to_date)->result_array();
        elseif ($filter == 'Confirmed')
            $data['agendas'] = $this->trainer_agenda_model->get_trainer_agenda_confirmed($data['trainerdata']['id'], $order, $per_page, $page, $search_parameters, $from_date, $to_date)->result_array();
        if (!empty($data['agendas'])) {
            foreach ($data['agendas'] as $agenda) {
                $data['agendas'][$count]['company_detail'] = $this->user_model->get_user_info($agenda['appointed_by'])->row_array();
                $data['agendas'][$count]['trainer_agenda_time'] = $this->trainer_agenda_time_model->get_trainer_time($agenda['id'])->result_array();
                $count++;
            }
        }
        if ($id == $this->session->userdata('general_id'))
            $data['same_user'] = 1;
        else
            $data['same_user'] = 0;
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
        $data['page'] = 'member/profile/notifications';
        $data['words'] = $this->_language;
        $data['header'] = 'Agenda Notifications';
        $data['general_user_id'] = $this->session->userdata('general_id');
        $data['pagination'] = $this->pagination->create_links();
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
        $this->load->view($this->_container, $data);
    }
	public function your_appointments($general_id,$offset = 0) {
		$limit = 5;
        $data['words'] = $this->_language;
        $data['general_id'] = $general_id;
		$data['userdata'] = $this->user_model->get_user_info($general_id)->row_array();
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $data['header'] = 'Your Appointments';
		$data['page'] = 'member/profile/your_appointments';
        $data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($general_id)->row_array();
		$no_of_agendas = count($this->trainer_agenda_model->getAcceptedAppointments($data['trainerdata']['id'],0,0));
		$data['pagination'] = createPagination(base_url()."member/trainer_agenda/your_appointments/".$general_id,$no_of_agendas,$limit,5);
		$data['agendas'] = $this->trainer_agenda_model->getAcceptedAppointments($data['trainerdata']['id'],$limit,$offset);
		$count = 0;
		if (!empty($data['agendas'])) {
            foreach ($data['agendas'] as $agenda) {
                $data['agendas'][$count]['company_detail'] = $this->user_model->get_user_info($agenda['appointed_by'])->row_array();
                $count++;
            }
        }
		$this->load->view($this->_container, $data);
	}
    public function ajax_agenda()
    {
        $id = $this->input->post('id');
        $data['agenda'] = $this->trainer_agenda_model->get_agenda($id)->row_array();
        $agenda_times = $this->trainer_agenda_time_model->get_trainer_time($id)->result_array();
        $data['all_time'] = null;
        $numItems = count($agenda_times);
        $i = 0;
        foreach ($agenda_times as $agenda_time) {
            if (count($agenda_times) == 1) {
                $data['all_time'] = (string)($agenda_time['work_time']) . ":00";
            } elseif (++$i === $numItems) {
                $data['all_time'] = (string)($data['all_time']) . (string)($agenda_time['work_time']) . ":00";
            } else {
                $data['all_time'] = (string)($data['all_time']) . (string)($agenda_time['work_time']) . ":00, ";
            }
        }
        $data['document'] = $this->trainer_agenda_model->get_document_data($id)->row_array();
        echo json_encode($data);
    }


    public function reject($id)
    {
        $result = $this->trainer_agenda_model->get_agenda_by_agenda_id($id)->row_array();
        $data["is_appointed"] = 0;
        $data["is_edited"] = 0;
        $data['edited_date'] = date('Y-m-d H:i:s');
        $this->trainer_agenda_model->update('trainer_agenda', $data, array('id' => $id));
        $this->trainer_agenda_time_model->delete('trainer_agenda_time', array('trainer_agenda_id' => $id));
        $flashdata = array("type" => "error", "message" => $this->_language['DTL_0330']);
        $this->session->set_flashdata('flash_message', $flashdata);

        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');
        $notification_data['sent_to_id'] = $result['appointed_by'];
        $notification_data['notification_type'] = 5;
        $notification_data['agenda_id'] = $id;
        $this->notification_model->insert('notifications', $notification_data);

        //Send mail start
        $maildata = $this->email_model->get_email(2, 7)->row_array();
        $companydata = $this->user_model->get_user_info($result['appointed_by'])->row_array();
		if($maildata['flag_bit'] == 1) {
			$this->mail_send("noreply@agendatool.com", "Agendatool", $companydata['email_address'], $maildata['subject'], $maildata['email']); //send mail to trainer
		}
        //Send mail end

        $this->redirect_back();
    }

    public function accept($id)
    {
        $result = $this->trainer_agenda_model->get_agenda_by_agenda_id($id)->row_array();
        $data["is_confirm"] = 1;
        $data["is_edited"] = 0;
        $data['edited_date'] = date('Y-m-d H:i:s');
        $this->trainer_agenda_model->update('trainer_agenda', $data, array('id' => $id));
        $flashdata = array("type" => "success", "message" => $this->_language['DTL_0331']);
        $this->session->set_flashdata('flash_message', $flashdata);

        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');
        $notification_data['sent_to_id'] = $result['appointed_by'];
        $notification_data['notification_type'] = 4;
        $notification_data['agenda_id'] = $id;
        $this->notification_model->insert('notifications', $notification_data);

        //Send mail start
        $maildata = $this->email_model->get_email(2, 6)->row_array();
        $companydata = $this->user_model->get_user_info($result['appointed_by'])->row_array();
		if($maildata['flag_bit'] == 1) {
			$this->mail_send("noreply@agendatool.com", "Agendatool", $companydata['email_address'], $maildata['subject'], $maildata['email']); //send mail to trainer
		}
        //Send mail end

        $this->redirect_back();
    }

    public function company_reject($id)
    {
        $result = $this->trainer_agenda_model->get_agenda_by_agenda_id($id)->row_array();
        $data["is_appointed"] = 0;
        $data["is_edited"] = 0;
        $data['edited_date'] = date('Y-m-d H:i:s');
        $this->trainer_agenda_model->update('trainer_agenda', $data, array('id' => $id));
        $this->trainer_agenda_time_model->delete('trainer_agenda_time', array('trainer_agenda_id' => $id));
        $flashdata = array("type" => "error", "message" => $this->_language['DTL_0330']);
        $this->session->set_flashdata('flash_message', $flashdata);

        //Send mail start
        $maildata = $this->email_model->get_email(3, 7)->row_array();
        $trainerdata = $this->user_model->get_user_by_trainer_id($result['trainer_info_id'])->row_array();
		if($maildata['flag_bit'] == 1) {
			$this->mail_send("noreply@agendatool.com", "Agendatool", $trainerdata['email_address'], $maildata['subject'], $maildata['email']); //send mail to trainer
		}
        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');
        $notification_data['sent_to_id'] = $trainerdata['general_id'];
        $notification_data['notification_type'] = 5;
        $notification_data['agenda_id'] = $id;
        $this->notification_model->insert('notifications', $notification_data);
        //Send mail end

        $this->redirect_back();
    }

    public function company_accept($id)
    {
        $result = $this->trainer_agenda_model->get_agenda_by_agenda_id($id)->row_array();
        $data["is_confirm"] = 1;
        $data["is_edited"] = 0;
        $data['edited_date'] = date('Y-m-d H:i:s');
        $this->trainer_agenda_model->update('trainer_agenda', $data, array('id' => $id));
        $flashdata = array("type" => "success", "message" => $this->_language['DTL_0331']);
        $this->session->set_flashdata('flash_message', $flashdata);


        //Send mail start
        $maildata = $this->email_model->get_email(3, 6)->row_array();
        $trainerdata = $this->user_model->get_user_by_trainer_id($result['trainer_info_id'])->row_array();
		if($maildata['flag_bit'] == 1) {
			$this->mail_send("noreply@agendatool.com", "Agendatool", $trainerdata['email_address'], $maildata['subject'], $maildata['email']); //send mail to trainer
		}
        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');
        $notification_data['sent_to_id'] = $trainerdata['general_id'];
        $notification_data['notification_type'] = 4;
        $notification_data['agenda_id'] = $id;
        $this->notification_model->insert('notifications', $notification_data);
        //Send mail end

        $this->redirect_back();
    }


    public function edit_agenda($id, $agenda_id)
    {
        $this->remove_date($agenda_id);
        $data['edit_agenda_data'] = $this->trainer_agenda_model->get_agenda_by_agenda_id($agenda_id)->row_array();
        $data['edit_agenda_data']['document'] = $this->document_model->get_agenda_document($agenda_id)->row_array();
        $this->get_trainer_agenda($id, $data);
    }





    public function new_save_agenda()
    {
//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
//
//        exit;


        $appointments = $this->input->post('appointmentName');
        $number_of_appointments = count($appointments);
        for ($i = 0; $i < $number_of_appointments; $i++) {
            $agenda_id = $_POST['agenda_id'][$i];
            $trainer_id = $_POST['trainer_id'];
            $appointment_name = $_POST['appointmentName'][$i];
            $date_time = date('Y-m-d H:i:s');
//            if($_POST['block']){
//                $block = 'true';
//            }
//            else{
//                $block = 'false';
//
//           }
//            $block = '';
            $trainer_info_id = $_POST['trainer_id'];
            $appointed_by = $_POST['appointed_by'][$i];
            $location = $_POST['location'][$i];
            $longitude = $_POST['longitude'][$i];
            $selected_time = $_POST['selectedTime'][$i];
            $latitude = $_POST['latitude'][$i];
            $appointment_description = $_POST['description'][$i];
//            $appoint_date = $_POST['selected_date'][$i];
            $appoint_date = $_POST['selected_date_date'][$i];

            if ($agenda_id != '') {
                $this->update_agenda($agenda_id, $appointment_name, $date_time, $trainer_info_id, $location, $longitude, $latitude, $appointment_description, $appoint_date, $appointed_by, $i, $selected_time);


            } else {
                $this->insert_agenda($appointment_name, $date_time, $trainer_info_id, $location, $longitude, $latitude, $appointment_description, $appoint_date, $i , $selected_time );
            }

        }

        $flashdata = array("type" => "success", "message" => $this->_language['DTL_0328']);
        $this->session->set_flashdata('flash_message', $flashdata);
        $trainerdata = $this->user_model->get_user_by_trainer_id($trainer_id)->row_array();
        redirect(site_url('member/trainer_agenda/get_trainer_agenda') . '/' . $trainerdata['general_info_id']);

    }


    public function insert_agenda($appointment_name, $date_time, $trainer_info_id, $location, $longitude, $latitude, $appointment_description, $appoint_date, $i ,$selected_time)
    {
        $data['is_edited'] = 0;
        $data['appointment_name'] = $appointment_name;
        $data['date_time'] = $date_time;
        $data['trainer_info_id'] = $trainer_info_id;
        $trainerdata = $this->user_model->get_user_by_trainer_id($trainer_info_id)->row_array();
        if($trainerdata['general_id'] != $this->session->userdata('general_id')){
            $data['is_confirm'] = 0;
        }
        else{
            $data['is_confirm'] = 1;
        }
        $data['location'] = $location;
//        $data['block'] = $block;
        $data['is_appointed'] = 1;
        $data['longitude'] = $longitude;
        $data['latitude'] = $latitude;
        $data['appointment_description'] = $appointment_description;
        $data['appoint_date'] = $appoint_date;
        $data['id'] = '';
        $data['appointed_by'] = $this->session->userdata('general_id');
        $this->trainer_agenda_model->insert('trainer_agenda', $data);
        $trainer_agenda_id = $this->user_model->get_last_inserted_id();
        $this->multiple_upload_document($i, $trainer_agenda_id);
        $this->save_date($trainer_agenda_id ,$selected_time);

        //Mail send start
        $maildata = $this->email_model->get_email(3, 3)->row_array();
        if ($trainerdata['general_id'] != $this->session->userdata('general_id')) {
            if($maildata['flag_bit'] == 1) {
                $this->mail_send("noreply@agendatool.com", "Agendatool", $trainerdata['email_address'], $maildata['subject'], $maildata['email']);
            }
        }

        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');
        $notification_data['sent_to_id'] = $trainerdata['general_id'];
        $notification_data['agenda_id'] = $trainer_agenda_id;
        $notification_data['notification_type'] = 2;
        $this->notification_model->insert('notifications', $notification_data);



        //Add to Favorite
        $company_general_id = $this->session->userdata('general_id'); //general id of company
        $trainer_general_id = $trainerdata['general_info_id']; //general id of trainer
        $result = $this->favorite_model->check_if_favorite($company_general_id, $trainer_general_id);
        if ($result == false && $company_general_id != $trainer_general_id) {
            $favoritedata['general_reg_info_id'] = $company_general_id;
            $favoritedata['is_favorite_id'] = $trainer_general_id;
            $favoritedata['is_deleted'] = 0;
            $this->favorite_model->insert('favorite', $favoritedata);
            $notification_data['created_date'] = date('Y-m-d');
            $notification_data['sent_by_id'] = $trainer_general_id;
            $notification_data['sent_to_id'] = $company_general_id;
            $notification_data['notification_type'] = 3;
            $this->notification_model->insert('notifications', $notification_data);
        }

    }


    public function update_agenda($agenda_id, $appointment_name, $date_time, $trainer_info_id, $location, $longitude, $latitude, $appointment_description, $appoint_date, $appointed_by, $i, $selected_time)
    {

        $data['is_edited'] = 1;
        $data['id'] = $agenda_id;
        $data['appointment_name'] = $appointment_name;
        $data['date_time'] = $date_time;
        $data['trainer_info_id'] = $trainer_info_id;
        $data['location'] = $location;
        $data['longitude'] = $longitude;
        $data['latitude'] = $latitude;
        $data['latitude'] = $latitude;
        $data['appointment_description'] = $appointment_description;
        $data['appoint_date'] = $appoint_date;
        $data['appointed_by'] = $appointed_by;
        $this->trainer_agenda_model->update('trainer_agenda', $data, array('id' => $data['id']));
        $this->edit_document($i, $agenda_id);
        $this->save_edited_date($agenda_id, $selected_time);
        $notification_data['created_date'] = date('Y-m-d');
        $notification_data['sent_by_id'] = $this->session->userdata('general_id');;
        $notification_data['agenda_id'] = $data['id'];
        $notification_data['notification_type'] = 6;

        if (($data['appointed_by']) == 0) {
            $data['appointed_by'] = $this->session->userdata('general_id');
            $company_data = $this->user_model->get_user_info($data['appointed_by'])->row_array();
            $data['trainer_id'] = $data['trainer_info_id'];
            $trainerdata = $this->user_model->get_user_by_trainer_id($data['trainer_info_id'])->row_array();
        } else {

            $company_data = $this->user_model->get_user_info($data['appointed_by'])->row_array();

            $trainerdata = $this->user_model->get_user_by_trainer_id($data['trainer_info_id'])->row_array();
        }

        $data['is_required'] = 1;
        $data['is_deleted'] = 0;
        if ($trainerdata['general_info_id'] == $data['appointed_by'])
            $data['is_confirm'] = 1;
        //Mail send start
        if ($trainerdata['general_info_id'] == $this->session->userdata('general_id')) {
            $maildata = $this->email_model->get_email(2, 10)->row_array();
            if($maildata['flag_bit'] == 1) {
                $this->mail_send("noreply@agendatool.com", "Agendatool", $company_data['email_address'], $maildata['subject'], $maildata['email']); //send mail to company
            }
            $notification_data['sent_to_id'] = $data['appointed_by'];
        } elseif ($data['appointed_by'] == $this->session->userdata('general_id')) {
            $maildata = $this->email_model->get_email(3, 10)->row_array();
            if($maildata['flag_bit'] == 1) {
                $this->mail_send("noreply@agendatool.com", "Agendatool", $trainerdata['email_address'], $maildata['subject'], $maildata['email']); //send mail to trainer
            }
            $notification_data['sent_to_id'] = $trainerdata['general_info_id'];
        }
        //Mail send end

        $this->notification_model->insert('notifications', $notification_data);

    }


    public function multiple_upload_document($i, $trainer_agenda_id)
    {

        $config['upload_path'] = $this->upload_document_path;
        $config['allowed_types'] = 'pdf|docx|doc|txt';
        $config['max_size'] = 0;
        $files = $_FILES;
        $this->load->library('upload', $config);
        $_FILES['userfile']['name'] = $files['file_upload']['name'][$i];
        $_FILES['userfile']['type'] = $files['file_upload']['type'][$i];
        $_FILES['userfile']['tmp_name'] = $files['file_upload']['tmp_name'][$i];
        $_FILES['userfile']['error'] = $files['file_upload']['error'][$i];
        $_FILES['userfile']['size'] = $files['file_upload']['size'][$i];
        if (!$this->upload->do_upload('userfile')) {
            $error_message = $this->upload->display_errors();
            if ($error_message = "The filetype you are attempting to upload is not allowed.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0327']);
            elseif ($error_message = "You did not select a file to upload.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0329']);
            elseif ($error_message = "The file you are attempting to upload is larger than the permitted size.")
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0316']);
            $this->session->set_flashdata('flash_message', $flashdata);
        }
        $document_name = $this->upload->data();
        if ($document_name['file_name'] != null) {
            $documentdata['document_name'] = $document_name['file_name'];
            $documentdata['trainer_info_id'] = $this->input->post('trainer_id');
            $documentdata['trainer_agenda_id'] = $trainer_agenda_id;
            $documentdata['uploaded_date'] = date("Y-m-d H:i:s");
            $documentdata['display_order'] = 1;
            $documentdata['is_deleted'] = 0;
            $this->document_model->insert('document', $documentdata);

        }

    }

            public function check_trainer_agenda()
            {
                $agenda_id = $_POST['app_id'];
                $this->delete_previous_agenda($agenda_id);
                $result = $this->trainer_agenda_model->get_agenda_by_agenda_id($agenda_id)->row_array();
                echo json_encode($result);
                exit;
            }

            public function delete_previous_agenda($agenda_id)
            {
                $this->trainer_agenda_model->delete('trainer_agenda', array('id' => $agenda_id));
                $this->trainer_agenda_time_model->delete('trainer_agenda_time', array('trainer_agenda_id' => $agenda_id));
                $this->document_model->delete('document', array('trainer_agenda_id' => $agenda_id));

            }


    public function get_new_date(){


    }


}