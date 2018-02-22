<?php
	class Notification_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_notification($where = null)
		{
			$this->db->select('*');
			$this->db->from('notifications');
			if($where)
				$this->db->where($where);
			$this->db->order_by('created_date','DESC');
			$this->db->order_by('id','DESC');
			return $this->db->get();
		}
	}
?>