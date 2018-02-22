<?php
	class Option_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_options($question_id)
		{
			$this->db->select("*");
			$this->db->from("option");
			$this->db->where("question_id",$question_id);
			return $this->db->get();
		}
		
		public function get_specific_option($id)
		{
			$this->db->select("*");
			$this->db->from("option");
			$this->db->where("id",$id);
			return $this->db->get();
		}
		
		public function get_option_value($id)
		{
			$this->db->select("value");
			$this->db->from("option");
			$this->db->where("id",$id);
			return $this->db->get();
		}
		
	}
?>