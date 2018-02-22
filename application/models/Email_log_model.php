<?php
	class Email_log_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_email_by_general_id($general_id,$where = null)
		{
			$this->db->select("*");
			$this->db->from('email_log');
			$this->db->where('email_to_id',$general_id);
			$this->db->or_where('email_by_id',$general_id);
			$this->db->order_by('created_date','DESC');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}
		
		public function get_specific_mail($id,$where = null)
		{
			$this->db->select("*");
			$this->db->from("email_log");
			$this->db->where("id",$id);
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}
		
		public function get_child_message($parent_id)
		{
			$this->db->select("*");
			$this->db->from("email_log");
			$this->db->where("parent_id",$parent_id);
			return $this->db->get();
		}

		public function get_email($where = null)
		{
			$this->db->select('*');
			$this->db->from('email_log');
			if($where)
				$this->db->where($where);
			$this->db->order_by('created_date','DESC');
			return $this->db->get();
		}
	}
?>