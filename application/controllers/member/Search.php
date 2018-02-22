<?php
class Search extends Member_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('speciality_model');
        $this->load->model('favorite_model');
        $this->load->model('trainer_agenda_model');
        $this->load->model('trainer_info_model');
        $this->load->model('email_model');
        $this->load->model('language_model');
        $this->load->model('email_log_model');
        $this->load->library('pagination');

    }

    public function index()
    {
        $data['same_user'] = 1;
        $data['page'] = 'member/profile/search';
        $data['words'] = $this->_language;
        $data['header'] = 'Search';
        $data['general_user_id'] = $this->session->userdata('general_id');
        $data['userdata'] = $this->user_model->get_user_info($data['general_user_id'])->row_array();
        $data['main_specialities'] = $this->speciality_model->get_main_specialities()->result_array();
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
    }

    public function search_user()
    {
        $data = $this->_get_search_parameters();
        $count = 0;
        $speciality_count = 0;
        if ($data['search_time'] != null) {
            $whole_time = explode(' ', $data['search_time']);
            $time_frame = $whole_time[1];
            $only_time = explode(':', $whole_time[0]);
            $required_time = $only_time[0];
            if ($time_frame == "AM" && $required_time == 12)
                $required_time = 00;
            elseif ($time_frame == "PM" && $required_time == 12)
                $required_time = 12;
            elseif ($time_frame == "PM")
                $required_time += 12;
        } elseif ($this->session->userdata('search_time') != '') {
            $required_time = $this->session->userdata('search_time');
        }

        if ($data['location'] != null) {
            if ($data['latitude'] == null || $data['longitude'] == null) {
                $flashdata = array("type" => "error", "message" => $this->_language['DTL_0307']);
                $this->session->set_flashdata('flash_message', $flashdata);
                redirect(site_url('member/search'));
            }
        }
        if ($this->input->post()) {
            $this->session->unset_userdata('search_name');
            $this->session->unset_userdata('search_speciality_names');
            $this->session->unset_userdata('availability');
            $this->session->unset_userdata('search_time');
            $this->session->unset_userdata('search_speciality_id');
        }

        $data['same_user'] = 1;
        $data['page'] = 'member/profile/result';
        $data['words'] = $this->_language;
        $data['general_user_id'] = $this->session->userdata('general_id');
        $data['userdata'] = $this->user_model->get_user_info($data['general_user_id'])->row_array();

        if ($data['speciality_id'] != null || $this->session->userdata('search_speciality_id') != '') {
            if ($data['speciality_id'] == null)
                $data['speciality_id'] = $this->session->userdata('search_speciality_id');
            foreach ($data['speciality_id'] as $speciality_search_id) {
                $data['search_results'] = $this->speciality_detail_model->search_trainer($data['name'], $speciality_search_id, $data['availability'], null)->result_array();
                $data['speciality_names'][$speciality_count] = $this->speciality_model->get_speciality_name($speciality_search_id)->row_array();
                $speciality_count++;
                $this->session->set_userdata('search_name', $data['name']);
                $this->session->set_userdata('search_speciality_id', $data['speciality_id']);
                $this->session->set_userdata('search_speciality_names', $data['speciality_names']);
                $this->session->set_userdata('availability', $data['availability']);
                $this->session->set_userdata('search_time', $data['search_time']);
            }
        } elseif ($data['name'] == null && $data['speciality_id'] == null && $data['availability'] == null && ($data['search_time'] != null || $this->session->userdata('search_time') != '')) {
            $data['search_results'] = $this->user_model->available_trainer($required_time)->result_array();
            $this->session->set_userdata('search_time', $data['search_time']);
        } elseif (($data['availability'] != null || $this->session->userdata('availability') != '') && $data['name'] == null && $data['search_time'] == null) {
            $data['search_results'] = $this->speciality_detail_model->search_trainer()->result_array();
            if ($data['availability'] != null)
                $this->session->set_userdata('availability', $data['availability']);
            else
                $data['availability'] = $this->session->userdata('availability');
            $count = 0;
            $results = $data['search_results'];
            $data['search_results'] = null;
            foreach ($results as $result) {
                $flag = $this->trainer_agenda_model->check_if_free($result['id'], $data['availability'], null);
                if ($flag == TRUE) {
                    $data['search_results'][$count] = $result;
                    $count++;
                }
            }
        } elseif ($data['name'] != null || $this->session->userdata('search_name') != '' || $data['availability'] != null || $this->session->userdata('availability') != '' || $data['search_time'] != null || $this->session->userdata('search_time') != '') {
            if ($data['name'] == null)
                $data['name'] = $this->session->userdata('search_name');
            if ($data['availability'] == null)
                $data['availability'] = $this->session->userdata('availability');
            if ($data['search_time'] == null)
                $data['search_time'] = $this->session->userdata('search_time');
            $data['search_results'] = $this->speciality_detail_model->search_trainer($data['name'])->result_array();
            if ($data['availability'] != null || $data['search_time'] != null) {
                $count = 0;
                $results = $data['search_results'];
                $data['search_results'] = null;
                foreach ($results as $result) {
                    $flag = $this->trainer_agenda_model->check_if_free($result['id'], $data['availability'], $required_time);
                    if ($flag == TRUE) {
                        $data['search_results'][$count] = $result;
                        $count++;
                    }
                }
            }
            $this->session->set_userdata('search_name', $data['name']);
            $this->session->set_userdata('availability', $data['availability']);
            $this->session->set_userdata('search_time', $data['search_time']);
        } else {
            $data['search_results'] = $this->user_model->get_all_trainers()->result_array();
        }

        if ($data['search_results'] != null) {
            $count = 0;
            foreach ($data['search_results'] as $results) {
                $general_info_id = $this->session->userdata('general_id');
                $is_favorite_id = $results['general_info_id'];
                $data['search_results'][$count]['trainerdata'] = $this->user_model->get_user_info($is_favorite_id)->row_array();
                $data['search_results'][$count]['is_favorite'] = $this->check_if_favorite($general_info_id, $is_favorite_id);
                $count++;
            }

        }
        $data['header'] = "Result";
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
        if ($data['location'] || $data['range']) {
            $data['check_flag'] = 1;
        } else {
            $data['check_flag'] = null;
            $data['longitude'] = $data['userdata']['longitude'];
            $data['latitude'] = $data['userdata']['latitude'];
        }
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function check_if_favorite($general_info_id, $id)
    {
        $result = $this->favorite_model->get_favorite($general_info_id, $id)->row_array();
        if ($result == null)
            return 0;
        else
            return 1;
    }

    private function _get_search_parameters()
    {
        $data['name'] = $this->input->post('name');
        $data['speciality_id'] = $this->input->post('speciality_id');
        $data['location'] = $this->input->post('location');
        $data['range'] = $this->input->post('range');
        $data['availability'] = $this->input->post('availability');
        $data['search_time'] = $this->input->post('search_time');
        $data['latitude'] = $this->input->post('address_latitude');
        $data['longitude'] = $this->input->post('address_longitude');
        return $data;
    }

    public function search_by_speciality_name()
    {
        $name = $this->input->post('search_speciality');
        $speciality_array = array();
        $datas = $this->speciality_model->search_speciality_by_name($name)->result_array();
        foreach ($datas as $data) {
            $speciality_bit = $this->speciality_model->check_speciality($data['parent_id']);
            if ($speciality_bit) {
                $speciality_array[] = $data;
            }
        }
        echo json_encode($speciality_array);
        return $speciality_array;
    }


    public function ajax_search_results()
    {
        $count = 1;
        $limit = 10;
        $offset = $this->input->post('offset');
        $sort_search = $this->input->post('sort_search');		
        $words = $this->_language;
        if ($this->input->post('id_array') != '') {
			$id_array = explode(",", $this->input->post('id_array'));
			$general_id = $this->session->userdata('general_id');
            $data = $this->trainer_info_model->get_trainer_by_general_id_with_pagination_search($general_id,$id_array,$sort_search, $limit, $offset)->result_array();
            $total_rows = $this->trainer_info_model->get_trainer_by_general_id_with_pagination_search($general_id,$id_array,$sort_search, null, null)->result_array();
            $total_rows = count($total_rows);
            if ($data != null) {
                foreach ($data as $result) {
					$userdata = $this->user_model->get_user_info($result["general_info_id"])->row_array();
					$user_profile_image = "";
					if ($userdata['photo_logo'] == null) {
						$user_profile_image = '<img class="img-responsive" src="'.base_url().'assets/img/default.jpg" alt="Profile Picture">';
					}
					elseif ($userdata['linkedin_image_status'] == 0) {
						$user_profile_image = '<img class="img-responsive" src="'.base_url().'uploads/userimage/'.$userdata['photo_logo'].'" alt="Profile Picture">';
					}
					elseif ($userdata['linkedin_image_status'] == 1) {
						$user_profile_image = '<img class="img-responsive" src="'.$userdata['linkedin_image'].'" alt="Profile Picture">';
					}
					echo '<div class="search_result">';
						echo '<div class="info_section">';
							echo '<div class="userpic">';
								echo '<a target="_blank" href="' . site_url("member/profile/other_profile") . '/' . $result["general_info_id"] . '"	 class="img-responsive">'.$user_profile_image.'</a>';
							echo '</div>';
							echo '<div class="userinfo">';
								echo '<p class="name"><a target="_blank" href="' . site_url("member/profile/other_profile") . '/' . $result["general_info_id"] . '">'.$result["name"].'</a></p>
											<p class="location"><label>Location:</label><span>'.$result["address"].'</span></p>
											<p class="cost"><label>Cost per hour:</label><span>'.$result["cost_per_hour"].'</span></p>';
							echo '</div>';
						echo '</div>';
						echo '<div class="option_section clearfix">';
						if ($result['is_favorite_id'] != NULL) {
							$favorite_icon = '<i class="fa fa-star"></i>Favorite';
						} else {
							$favorite_icon = '<i class="fa fa-star-o"></i>Add to favorite';
						}
							echo '<div class="col-xs-12 option_section_wrapper">';
								echo '<p>
										<a class="add_to_favorite" result="' . $result["general_info_id"] . '" href="#">
											'.$favorite_icon.'
										</a>
									</p>
									<p>
										<a class="contact_trainer_search_button" href="" id="contact" result="' . $result["general_info_id"] . '" result_mail="' . $result["email_address"] . '"><i class="fa fa-comments-o"></i> Contact</a>
									</p>
									<p>
										<a target="_blank" href="' . site_url("member/profile/other_profile") . '/' . $result["general_info_id"] . '" class="btn btn-primary">Visit Profile</a>
									</p>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
                }
                $ids_str = "\"";
                foreach ($id_array as $id) {
                    $ids_str .= $id . ",";
                }
                $ids_str = rtrim($ids_str, ",");
                $ids_str .= "\"";
                echo create_ajax_paging('ajax_search_results', $total_rows, $offset, array($ids_str,$sort_search), $limit);
            } else {
                echo $words['DTL_0358'];

            }
        } else {
            echo '	<div class="alert alert-danger">
        			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>';
            echo $words['DTL_0358'];
            echo '</div>';
        }
    }

    public function ajax_search_results_map()
    {
        $id = $this->input->post('id');
        $count = 0;

        if ($id != null) {
            $general_info_id = $this->session->userdata('general_id');
            $data = $this->trainer_info_model->get_trainer_by_general_id($id)->row_array();
            $data['is_favorite'] = $this->check_if_favorite($general_info_id, $id);
            $data['specialities'] = $this->speciality_model->get_speciality_by_general_id($id)->result_array();
            echo json_encode($data);
        } else {
            echo '	<div class="alert alert-danger">
        			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>';
            echo "No Result";
            echo '	</div>';
        }
    }


    public function get_pagination()
    {
        $id_array = '';
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');
        $data = $this->trainer_info_model->get_trainer_by_general_id_with_pagination($id_array, $limit, $offset)->result_array();
        $total_rows = $this->trainer_info_model->get_trainer_by_general_id_with_pagination($id_array, null, null)->result_array();
        $total_rows = count($total_rows);
        $words = $this->_language;
        $count = ($offset * $limit) + 1;
        if (count($data) > 0) {
            foreach ($data as $result) {
                if ($result['general_info_id'] == $result['is_favorite_id']) {
                    foreach ($data as $result) {

                        echo '<tr>';
                        echo '<td>';
                        echo $count++;
                        echo '</td>';
                        echo '<td>' . $result["name"] . '</td>';
                        echo '<td>' . $result["address"] . '</td>';
                        echo '<td>' . $result["cost_per_hour"];
                        echo '</td>';
                        echo '<td class="double_icons">';
                        echo '<a target="_blank" href="' . site_url("member/profile/other_profile") . '/' . $result["general_info_id"] . '"><button class="btn btn-xs btn-secondary "><i class="fa fa-eye"></i></button></a>';
                        echo '<button class="btn btn-xs btn-tertiary contact_trainer_search_button" id="contact" result="' . $result["general_info_id"] . '" result_mail="' . $result["email_address"] . '"><i class="fa fa-comments-o"></i></button>';
                        echo '<button class="btn btn-xs btn-warning add_to_favorite" result="' . $result["general_info_id"] . '">';
                        if ($result['general_info_id'] == $result['is_favorite_id']) {
                            echo '<i class="fa fa-star"></i>';
                        } else {
                            echo '<i class="fa fa-star-o"></i>';
                        }
                        echo '</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td colspan="3">';
                    echo create_ajax_paging('ajax_search_results', $total_rows, $offset, array($id_array), $limit);
                    echo '</td>';
                    echo '</tr>';
                } else {
                    echo 'No Result Found!!!';

                }
                echo '</tbody>';

                echo '</table>';
            }
        } else {
            echo '	<div class="alert alert-danger">
        			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>';
            echo "No Results!";
            echo '</div>';
        }
    }


}
