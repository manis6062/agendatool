<?php
	class Time_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_times($where = null)
		{
			$this->db->select("*");
			$this->db->from('times');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}

		public function get_removed_times()
		{
			$this->db->select("time");
			$this->db->from('times');
			$this->db->where('status',0);
			return $this->db->get();
		}

        public function get_available_times()
        {
            $this->db->select("*");
            $this->db->from('times');
            $this->db->where('status',1);
            return $this->db->get();
        }


	}
?>