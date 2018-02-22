<?php
	class Section_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_sections()
		{
			$this->db->select("*");
			$this->db->from('section');
			return $this->db->get();
		}
		
	}
?>