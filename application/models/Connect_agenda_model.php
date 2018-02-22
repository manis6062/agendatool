<?php
	class Connect_agenda_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function check_outlook_email($general_id)
		{
			$this->db->select('*');
			$this->db->from('connect_agenda');
			$this->db->where('general_id',$general_id);
			$this->db->where('outlook_email !=',"");
			$result = $this->db->get()->row_array();
			if($result)
				return 1;
			else
				return 0;
		}
		
		public function check_gmail_status($general_id)
		{
			$this->db->select('*');
			$this->db->from('connect_agenda');
			$this->db->where('general_id',$general_id);
			$this->db->where('gmail_status !=',0);
			$result = $this->db->get()->row_array();
			if($result)
				return 1;
			else
				return 0;
		}
		
		public function get_data_by_general_id($general_id)
		{
			$this->db->select('*');
			$this->db->from('connect_agenda');
			$this->db->where('general_id',$general_id);
			return $this->db->get();
		}
	}