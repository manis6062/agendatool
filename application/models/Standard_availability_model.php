<?php
	class Standard_availability_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_standard_availability($trainer_id)
		{
			$this->db->select("*");
			$this->db->from('standard_availability');
			$this->db->where('trainer_info_id',$trainer_id);
			return $this->db->get();
		}
		
	}
?>