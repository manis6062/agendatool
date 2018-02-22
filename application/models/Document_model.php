<?php
	class Document_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_document($id)
		{
			$this->db->select("*");
			$this->db->from("document");
			$this->db->where("trainer_info_id",$id);
			return $this->db->get();
		}
		
		public function get_agenda_document($agenda_id)
		{
			$this->db->select("*");
			$this->db->from("document");
			$this->db->where("trainer_agenda_id",$agenda_id);
			return $this->db->get();
		}
	}