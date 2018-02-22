<?php
class User_model extends My_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_login_info($where)
    {
        $this->db->select("id, username, email_address, password, user_type_id,is_active");
        $this->db->from("user_login");
        if ($where)
            $this->db->where($where);
        return $this->db->get();
    }

    public function get_user_pic($id)
    {
        $user = $this->get_user_info($id)->row_array();
        if ($user['linkedin_image_status'] == 1) {
            $this->db->select('linkedin_image');
            $this->db->from('user_login');
            $this->db->where('id', $user['user_login_id']);
        } else {
            $this->db->select("photo_logo");
            $this->db->from("general_reg_info");
            $this->db->where("id", $id);
        }
        return $this->db->get();
    }

    public function get_user($id) //By user_id
    {
        $this->db->select("u.*,g.*");
        $this->db->from("user_login u");
        $this->db->where("u.id", $id);
        $this->db->join("general_reg_info g", "u.id=g.user_login_id");
        return $this->db->get();
    }

    public function get_user_info($id) //By general_info_id
    {
        $this->db->select("u.*,g.*,g.id as general_id,u.id as user_id");
        $this->db->from("general_reg_info g");
        $this->db->where("g.id", $id);
        $this->db->join("user_login u", "u.id=g.user_login_id");
        return $this->db->get();
    }

    public function get_users($user_type_id = null, $limit = null, $offset = null, $search_parameters = null, $is_active = null)
    {
        $this->db->select("u.*,g.*,ut.*,g.id as general_id");
        $this->db->from("user_login u");
        $this->db->join("general_reg_info g", "u.id=g.user_login_id");
        $this->db->join("user_type ut", "ut.id = u.user_type_id");
        if ($user_type_id)
            $this->db->where("u.user_type_id", $user_type_id);
        $this->db->order_by('g.created_on', 'DESC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        elseif ($limit)
            $this->db->limit($limit);

        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByName') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByUserName') {
                    $this->db->like("u.username", $search_parameter, 'both');
                }

                if ($key == 'searchByEmailAddress') {
                    $this->db->like("u.email_address", $search_parameter, 'both');
                }
            }
        }
        if ($is_active) {
            if ($is_active != 'All') {
                if ($is_active == 'Active') {
                    $this->db->where('u.is_active', 1);
                } elseif ($is_active == 'Inactive') {
                    $this->db->where('u.is_active', 0);
                }
            }
        }

        $this->db->where("u.user_type_id!=1");
        return $this->db->get();
    }
	public function getAllActiveUsers() {
		$all_users = $this->db->select("user_login.*,general_reg_info.id as general_id,general_reg_info.created_on as created_on")
		->from("user_login")
		->join("general_reg_info","general_reg_info.user_login_id = user_login.id")
		->where("user_login.is_active",1)
		->where("user_login.user_type_id != 1")
		->get();
		return $all_users;
	}
    public function get_specific_user($id)
    {
        $this->db->select("*");
        $this->db->from("user_login");
        $this->db->where("id", $id);
        return $this->db->get();
    }


    public function search_user_by_name($name)
    {
        $this->db->select("g.*,t.*");
        $this->db->from("general_reg_info g");
        $this->db->like("name", $name, 'both');
        $this->db->join('trainer_info t', 'g.id=t.general_info_id');
        $this->db->where('is_company', 0);
        return $this->db->get();
    }

    public function get_user_by_trainer_id($trainer_id)
    {
        $this->db->select("g.*,t.*,u.*,g.id as general_id");
        $this->db->from("general_reg_info g");
        $this->db->join('trainer_info t', 'g.id=t.general_info_id');
        $this->db->join("user_login u", "u.id = g.user_login_id");
        $this->db->where('t.id', $trainer_id);
        return $this->db->get();
    }

    public function get_all_trainers()
    {
        $this->db->select("g.*,t.*");
        $this->db->from("general_reg_info g");
        $this->db->join("trainer_info t", "g.id = t.general_info_id");
        $this->db->where("is_company", 0);
        return $this->db->get();
    }


    public function get_company($general_id)
    {
        $this->db->select("is_company");
        $this->db->from("general_reg_info");
        $this->db->where("id", $general_id);
        return $this->db->get();
    }

    public function count_trainers()
    {
        return $this->db->count_all_results('trainer_info');
    }

    public function available_trainer($time)
    {
        $this->db->select("g.*,t.*");
        $this->db->from("general_reg_info g");
        $this->db->join("trainer_info t", "g.id = t.general_info_id");
        $this->db->join("standard_availability sa", "sa.trainer_info_id = t.id");
        $this->db->where("sa.from_time <=", $time);
        $this->db->where("sa.to_time >=", $time);
        return $this->db->get();
    }


    public function get_active_users()
    {
        $this->db->select("g.*,u.*,g.id as general_id");
        $this->db->from("user_login u");
        $this->db->join("general_reg_info g", "u.id = g.user_login_id");
        $this->db->where("u.is_active", 1);
        return $this->db->get();
    }

    public function get_trainer_data($where = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select('t.education,t.work_experience,t.cost_per_hour,u.username,u.email_address,g.name,g.address,g.zip_code,g.phone_no,g.iban_number,g.bic_number');
        $this->db->from('trainer_info t');
        $this->db->join('general_reg_info g', 'g.id = t.general_info_id');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        $this->db->join("user_type ut", "ut.id = u.user_type_id");
        if ($where)
            $this->db->where($where);
        if ($from_date && $to_date) {
            $this->db->where('g.created_on >= ', $from_date);
            $this->db->where('g.created_on <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('g.created_on >= ', $from_date);
            $this->db->where('g.created_on <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('g.created_on >= ', date('Y-m-d'));
            $this->db->where('g.created_on <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByName') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByUserName') {
                    $this->db->like("u.username", $search_parameter, 'both');
                }

                if ($key == 'searchByEmailAddress') {
                    $this->db->like("u.email_address", $search_parameter, 'both');
                }
            }
        }

        $this->db->where("u.user_type_id!=1");
        return $this->db->get();
    }

    public function get_company_data($where = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select('c.contact_person,u.username,u.email_address,g.name,g.address,g.zip_code,g.phone_no,g.iban_number,g.bic_number');
        $this->db->from('company_info c');
        $this->db->join('general_reg_info g', 'g.id = c.general_info_id');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        $this->db->join("user_type ut", "ut.id = u.user_type_id");
        if ($where)
            $this->db->where($where);
        if ($from_date && $to_date) {
            $this->db->where('g.created_on >= ', $from_date);
            $this->db->where('g.created_on <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('g.created_on >= ', $from_date);
            $this->db->where('g.created_on <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('g.created_on >= ', date('Y-m-d'));
            $this->db->where('g.created_on <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByName') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByUserName') {
                    $this->db->like("u.username", $search_parameter, 'both');
                }

                if ($key == 'searchByEmailAddress') {
                    $this->db->like("u.email_address", $search_parameter, 'both');
                }
            }
        }

        $this->db->where("u.user_type_id!=1");
        return $this->db->get();
    }

    public function get_company_by_agenda_id($agenda_id)
    {
        $this->db->select('g.*');
        $this->db->from('general_reg_info g');
        $this->db->join('trainer_agenda t', 't.appointed_by = g.id');
        $this->db->where('t.id', $agenda_id);
        return $this->db->get();
    }

    public function get_general_id_by_email($email_address)
    {
        $this->db->select('g.id');
        $this->db->from('general_reg_info g');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        $this->db->where('u.email_address', $email_address);
        return $this->db->get();
    }

    public function search_user_subscription($param)
    {
        $this->db->select('g.*,u.*,g.id as general_id');
        $this->db->from('user_login u');
        $this->db->join('general_reg_info g', 'u.id = g.user_login_id');
        $this->db->where('u.username', $param);
        $this->db->or_where('u.email_address', $param);
        return $this->db->get();
    }


    public function check_name($name)
    {
        $query = $this->db->get_where('general_reg_info', array('name' => $name));
        $count = $query->num_rows();
        if ($count === 0)
            return false;
        else
            return true;
    }

    public function check_username($username)
    {
        $query = $this->db->get_where('user_login', array('username' => $username));
        $count = $query->num_rows();
        if ($count === 0)
            return false;
        else
            return true;
    }

    public function check_email_address($email_address)
    {
        $query = $this->db->get_where('user_login', array('email_address' => $email_address));
        $count = $query->num_rows();
        if ($count === 0)
            return false;
        else
            return true;
    }

    public function get_user_by_linkedin_id($linkedin_id)
    {
        $this->db->select('*');
        $this->db->from('user_login');
        $this->db->where('linkedin_id', $linkedin_id);
        return $this->db->get();
    }

    public function count_users($user_type_id = null)
    {
        if ($user_type_id)
            $this->db->where("user_type_id", $user_type_id);
        $this->db->where("user_type_id!=1");
        $this->db->from("user_login");
        return $this->db->count_all_results();
    }

    public function get_trainer_by_general_id($id)
    {
        $this->db->select('g.*,t.*,u.*,t.id as trainer_info_id');
        $this->db->from('general_reg_info g');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        $this->db->join('trainer_info t', 'g.id = t.general_info_id');
        $this->db->where('g.id', $id);
        return $this->db->get();
    }

    public function search_users($where = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select("u.*,g.*,ut.*,g.id as general_id");
        $this->db->from("user_login u");
        $this->db->join("general_reg_info g", "u.id=g.user_login_id");
        $this->db->join("user_type ut", "ut.id = u.user_type_id");
        if ($where)
            $this->db->where($where);
        if ($from_date && $to_date) {
            $this->db->where('g.created_on >= ', $from_date);
            $this->db->where('g.created_on <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('g.created_on >= ', $from_date);
            $this->db->where('g.created_on <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('g.created_on >= ', date('Y-m-d'));
            $this->db->where('g.created_on <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByName') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByUserName') {
                    $this->db->like("u.username", $search_parameter, 'both');
                }

                if ($key == 'searchByEmailAddress') {
                    $this->db->like("u.email_address", $search_parameter, 'both');
                }
            }
        }

        $this->db->where("u.user_type_id!=1");
        return $this->db->get();
    }

    public function get_user_by_email($email_address)
    {
        $this->db->select('u.*');
        $this->db->from('user_login u');
        $this->db->where('u.email_address', $email_address);
        return $this->db->get();
    }


    public function get_all_trainers_with_pagination($limit = null, $offset = null)
    {
        $this->db->select("g.*,t.*");
        $this->db->from("general_reg_info g");
        $this->db->join("trainer_info t", "g.id = t.general_info_id");
        $this->db->where("is_company", 0);
        if ($limit && $offset)
            $this->db->limit($limit, $offset * $limit);
        elseif ($limit)
            $this->db->limit($limit);
        $result = $this->db->get();
        return $result;
    }
	public function checkGoogleEventId($google_event_id) {
		$google_event_exist = $this->db->select("*")
		->from("trainer_agenda")
		->where("google_event_id",$google_event_id)
		->get()
		->row();
		return $google_event_exist;
	}
	public function getTrainerInfoIdFromGeneral($general_info_id) {
		$trainer_info_id = $this->db->select("id")
		->from("trainer_info")
		->where("general_info_id",$general_info_id)
		->get()
		->row();
		return $trainer_info_id;
	}
	public function addEventFromGoogle($event_data) {
		$this->db->insert_batch('mytable', $data); 
	}
	public function getUserGeneralRegInfoId($general_id) {
		$result = $this->db->select("general_reg_info.*,user_login.email_address")
		->from("general_reg_info")
		->join("user_login","user_login.id = general_reg_info.user_login_id")
		->where("general_reg_info.id",$general_id)
		->get();
		return $result;
	}
}