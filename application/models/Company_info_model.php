<?php
	class Company_info_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		

		public function get_specific_company($id)
		{
			$this->db->select("*");
			$this->db->from('company_info');
			$this->db->where("general_info_id",$id);
			return $this->db->get();
		}

		public function get_companies()
		{
			$this->db->select("*");
			$this->db->form("company_info");
			return $this->db->get();
		}

		public function get_specific_company_from_name($where)
		{
			$this->db->select("*");
			$this->db->from("general_reg_info");
			$this->db->where($where);
			return $this->db->get();
		}

		public function search_company($name,$already_selected_companies)
		{
			$this->db->select("*");
			$this->db->from("general_reg_info");
			$this->db->like('name',$name,'both');
			if($already_selected_companies != "") {
				$this->db->where('general_reg_info.id NOT IN('.$already_selected_companies.')');
			}
			$this->db->where('is_company',1);
			$result = $this->db->get();
			return $result;
		}
	}
?>