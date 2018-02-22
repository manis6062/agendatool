<?php
	class Token_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_token($token)
		{
			$this->db->select("*");
			$this->db->from('token');
			$this->db->where('token',$token);
			return $this->db->get();
		}
		
	}
?>