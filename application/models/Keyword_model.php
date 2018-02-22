<?php
	class Keyword_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_all_keywords()
		{
			$this->db->select('*');
			$this->db->from('keywords');
			return $this->db->get();
		}
	}
?>