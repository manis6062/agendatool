<?php
	class Date_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_date($where)
		{
			$this->db->select("*");
			$this->db->from('date');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}
		
	}
?>