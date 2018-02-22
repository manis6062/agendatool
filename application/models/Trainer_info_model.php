<?php
class Trainer_info_Model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_specific_trainer($id)
    {
        $this->db->select("*");
        $this->db->from('trainer_info');
        $this->db->where("general_info_id", $id);
        return $this->db->get();
    }

    public function get_trainer_by_general_id($id) //By general id
    {
        $this->db->select("t.*,g.*,u.*");
        $this->db->from('trainer_info t');
        $this->db->join('general_reg_info g', 'g.id = t.general_info_id');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        $this->db->where("t.general_info_id", $id);
        $this->db->where('g.is_company', 0);
        return $this->db->get();
    }


    public function get_trainer_by_general_id_with_pagination($id_array, $limit = null, $offset = null) //By general id
    {
        $ids = implode(',', $id_array);
        $this->db->select("t.*,g.*,u.*,f.*");
        $this->db->from('trainer_info t');
        $this->db->join('general_reg_info g', 'g.id = t.general_info_id');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        $this->db->join('favorite f', 'f.general_reg_info_id = g.id', 'left');
        $this->db->where("t.general_info_id IN (" . $ids . ")");
        $this->db->where('g.is_company', 0);
        if ($limit && $offset)
            $this->db->limit($limit, $offset * $limit);
        elseif ($limit)
            $this->db->limit($limit);
        return $this->db->get();
    }
	public function get_trainer_by_general_id_with_pagination_search($general_id,$id_array,$sort_search, $limit = null, $offset = null) //By general id
    {
        $ids = implode(',', $id_array);
        $this->db->select("t.*,g.*,u.*,(SELECT id FROM favorite WHERE general_reg_info_id = {$general_id} AND is_favorite_id = t.general_info_id) as is_favorite_id");
        $this->db->from('trainer_info t');
        $this->db->join('general_reg_info g', 'g.id = t.general_info_id');
        $this->db->join('user_login u', 'u.id = g.user_login_id');
        //$this->db->join('favorite f', 'f.general_reg_info_id = g.id', 'left');
		if($sort_search == 1) {
			$this->db->order_by("g.address ASC");
		}
		else if($sort_search == 2) {
			$this->db->order_by("t.cost_per_hour ASC");
		}
		else if($sort_search == 3) {
			$this->db->order_by("g.name ASC");
		}
        $this->db->where("t.general_info_id IN (" . $ids . ")");
        $this->db->where('g.is_company', 0);
        if ($limit && $offset)
            $this->db->limit($limit, $offset * $limit);
        elseif ($limit)
            $this->db->limit($limit);
        return $this->db->get();
    }

}

?>