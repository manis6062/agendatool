<?php
	class Trainer_video_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_videos($id)
		{
			$this->db->select("*");
			$this->db->from('trainer_video');
			$this->db->where("trainer_info_id",$id);
			return $this->db->get();
		}

		public function get_specific_video($id)
		{
			$this->db->select("*");
			$this->db->from('trainer_video');
			$this->db->where("id",$id);
			return $this->db->get();
		}

	}
?>