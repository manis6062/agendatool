<?php
	class Day_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_days($where = null)
		{
			$this->db->select("*");
			$this->db->from('days');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}

		public function get_removed_days()
		{
			$this->db->select("day");
			$this->db->from('days');
			$this->db->where('status',0);
			return $this->db->get();
		}

        public function get_available_days()
        {
            $this->db->select("*");
            $this->db->from('days');
            $this->db->where('status',1);
            return $this->db->get();
        }
		
	}
?>