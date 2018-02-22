<?php
	class Field_type_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_field_types()
		{
			$this->db->select("*");
			$this->db->from('field_type');
			return $this->db->get();
		}
		
	}
?>