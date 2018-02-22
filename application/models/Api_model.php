<?php
	class Api_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		

		public function get_api($where = null)
		{
			$this->db->select("*");
			$this->db->from('api');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}

	}
?>