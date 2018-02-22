<?php
class Speciality_Model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('speciality_detail_model');
    }

    public function get_by_parent_id($id)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->where("parent_id", $id);
        return $this->db->get();
    }

    public function get_main_specialities($limit = null, $offset = null, $search_parameter = null)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->where("parent_id", 0);
        $this->db->order_by('id', 'DESC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset * $limit);
        elseif ($limit)
            $this->db->limit($limit);
        if ($search_parameter != null) {
            $this->db->like('speciality.category_name', $search_parameter, 'both');
        }
        $result = $this->db->get();
        return $result;
    }


    public function get_speciality($id)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->where("id", $id);
        $this->db->order_by('id', 'DESC');
        return $this->db->get();
    }


    public function search_speciality_by_name($name)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->like("category_name", $name, 'both');
        $this->db->where('parent_id != 0');
        return $this->db->get();
    }

    public function check_speciality($id)
    {
        $this->db->select("parent_id");
        $this->db->from("speciality");
        $this->db->where("id", $id);
        $data = $this->db->get()->row();
        if ($data->parent_id != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_if_speciality($id)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->where("id", $id);
        $this->db->where("parent_id != 0");
        $data = $this->db->get()->row_array();
        if ($this->check_speciality($data['id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function get_sub_category($limit = null, $offset = null, $search_parameter = null)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->where("parent_id != 0");
        $this->db->order_by('id', 'DESC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset * $limit);
        elseif ($limit)
            $this->db->limit($limit);
        if ($search_parameter != null) {
            $this->db->like('speciality.category_name', $search_parameter, 'both');
        }
        $all_non_main_categories = $this->db->get()->result_array();


        $index = 0;
        $data = null;
        foreach ($all_non_main_categories as $c) {
            $parent = $this->get_speciality_name($c['parent_id'])->row_array();
            if (!empty($parent))
                if ($parent['parent_id'] == 0)
                    $data[$index] = $c;
            $index++;
        }

        return $data;
    }

    public function get_all_speciality($limit = null, $offset = null, $search_parameter = null)
    {
        $this->db->select("*");
        $this->db->from("speciality");
        $this->db->where("parent_id != 0");
        $this->db->order_by('id', 'DESC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset * $limit);
        elseif ($limit)
            $this->db->limit($limit);
        if ($search_parameter != null) {
            $this->db->like('speciality.category_name', $search_parameter, 'both');
        }
        $all_non_main_categories = $this->db->get()->result_array();
        $index = 0;
        $data = null;
        foreach ($all_non_main_categories as $c) {
            $parent = $this->get_speciality_name($c['parent_id'])->row_array();
            if (!empty($parent))
                if ($parent['parent_id'] != 0)
                    $data[$index] = $c;
            $index++;
        }
        return $data;
    }

    public function get_speciality_name($id)
    {
        $this->db->select("category_name,parent_id");
        $this->db->from("speciality");
        $this->db->where("id", $id);
        return $this->db->get();
    }

    public function get_speciality_by_general_id($id)
    {
        $this->db->select("s.category_name");
        $this->db->from("speciality s");
        $this->db->join("speciality_detail sd", "sd.speciality_id = s.id");
        $this->db->join("general_reg_info g", "sd.general_reg_info_id = g.id");
        $this->db->where("g.id", $id);
        return $this->db->get();
    }

    public function get_all_subcategories($limit = null, $offset = null, $search_parameter = null)
    {
        if($limit != null && $offset != null) {
            $limit_query = "limit " . ($offset * $limit) . ",{$limit}";
        }
        elseif ($limit != null) {
            $limit_query = "limit " . $limit;
        }
        else{
            $limit_query = '';
        }
        $search_query = "";
        if($search_parameter != null) {
            $limit_query = null;
            $search_query = " AND tmp.category_name LIKE '%".$search_parameter."%' ";
        }

        $query = $this->db->query(
                    "SELECT tmp. * , speciality.category_name AS parent_category_name, speciality.parent_id AS parent_parent_id FROM ( SELECT * FROM `speciality` WHERE `parent_id` !=0 ) AS tmp INNER JOIN speciality ON speciality.id = tmp.parent_id WHERE speciality.parent_id =0 ".$search_query." ". $limit_query);
        return $query->result_array();
    }


    public function get_all_specialities($limit = null, $offset = null, $search_parameter = null)
    {
        if($limit != null && $offset != null) {
            $limit_query = "limit " . ($offset * $limit) . ",{$limit}";
        }
        elseif ($limit != null) {
            $limit_query = "limit " . $limit;
        }
        else{
            $limit_query = '';
        }
        $search_query = "";
        if($search_parameter != null) {
            $limit_query = null;
            $search_query = " AND tmp.category_name LIKE '%".$search_parameter."%' ";
        }

        $query = $this->db->query(
            "SELECT tmp. * , speciality.category_name AS parent_category_name, speciality.parent_id AS parent_parent_id FROM ( SELECT * FROM `speciality` WHERE `parent_id` !=0 ) AS tmp INNER JOIN speciality ON speciality.id = tmp.parent_id WHERE speciality.parent_id !=0 ".$search_query." ". $limit_query);
        return $query->result_array();
    }

        }