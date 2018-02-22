<?php
	class Speciality_detail_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('speciality_model');
		}

		public function get_speciality_id($id)
		{
			$this->db->select("speciality_id");
			$this->db->from("speciality_detail");
			$this->db->where("general_reg_info_id",$id);
			return $this->db->get();
		}

		public function check_speciality_with_user($general_id,$speciality_id)
		{
			$this->db->select("id");
			$this->db->from("speciality_detail");
			$this->db->where(array("general_reg_info_id"=>$general_id,"speciality_id"=>$speciality_id));
			return $this->db->get();
		}

		public function get_general_id($id)
		{
			$this->db->select("general_reg_info_id");
			$this->db->from("speciality_detail");
			$this->db->where("speciality_id",$id);
			return $this->db->get();
		}

		public function search_trainer($name=null,$speciality_id=null)
		{
			$this->db->select("g.*,t.*");
			$this->db->from("general_reg_info g");
			if($speciality_id)
			{
				$this->db->join("speciality_detail sd","g.id = sd.general_reg_info_id");
				$this->db->join("speciality s","sd.speciality_id = s.id");
			}
			$this->db->join("trainer_info t","g.id = t.general_info_id");
			if($name)
				$this->db->like("g.name",$name,"both");
			if($speciality_id)
				$this->db->where("s.id",$speciality_id);
			return $this->db->get();
		}
		
	}