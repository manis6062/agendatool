<?php
	class Invite_company_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
		}


        public function get_all_invited_companies($general_info_id)
        {
            $this->db->select('t.*,i.*');
            $this->db->from('trainer_info t');
            $this->db->join('invite_company i', 't.general_info_id = i.trainer_info_id');
            $this->db->where('i.trainer_info_id', $general_info_id);
            $this->db->where('t.visibility_id', 3);
            $result = $this->db->get();
            return $result;
        }
    }


