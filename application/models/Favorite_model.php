<?php
	class Favorite_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_favorite($general_reg_info_id,$is_favorite_id)
		{
			$this->db->select("*");
			$this->db->from("favorite");
			$this->db->where(array("general_reg_info_id"=>$general_reg_info_id,"is_favorite_id"=>$is_favorite_id));
			return $this->db->get();
		}

		public function get_favorites($general_reg_info_id)
		{
			$this->db->select("is_favorite_id");
			$this->db->from("favorite");
			$this->db->where(array("general_reg_info_id"=>$general_reg_info_id));
			return $this->db->get();
		}
		
		public function check_if_favorite($general_reg_info_id,$is_favorite_id)
		{
			$this->db->where('general_reg_info_id',$general_reg_info_id);
			$this->db->where('is_favorite_id',$is_favorite_id);
			$this->db->from('favorite');
			if($this->db->count_all_results() > 0)
				return true;
			else
				return false;
		}

		public function favorite_of_other($general_id)
		{
			$this->db->select("*");
			$this->db->from("favorite");
			$this->db->where('is_favorite_id',$general_id);
			return $this->db->get();
		}
	}