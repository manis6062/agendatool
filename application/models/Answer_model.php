<?php
	class Answer_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_answers()
		{
			$this->db->select("*");
			$this->db->from('answer');
			return $this->db->get();
		}
		
		public function insert_batch($data)
		{
			$this->db->insert_batch('answer',$data);
		}
		
		public function get_answer_by_general_id($id,$question_id)
		{
			$this->db->select("a.*,q.type_id");
			$this->db->from('answer a');
			$this->db->join("question q","a.question_id = q.id");
			$this->db->where('a.general_reg_info_id',$id);
			$this->db->where('a.question_id',$question_id);
			return $this->db->get();
		}
		
	}
?>