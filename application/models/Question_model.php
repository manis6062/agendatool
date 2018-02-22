<?php
	class Question_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}

		public function get_questions($where=null)
		{
			$this->db->select('q.*,s.section_name,t.type_name');
			$this->db->from('question q');
			$this->db->join('section s','q.section_id = s.id');
			$this->db->join('field_type t','q.type_id = t.id');
			$this->db->order_by('q.id','DESC');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}
		
		public function get_question_name($id)
		{
			$this->db->select("name");
			$this->db->from("question");
			$this->db->where("id",$id);
			return $this->db->get();
		}
		
		public function get_specific_question($id)
		{
			$this->db->select("*");
			$this->db->from("question");
			$this->db->where("id",$id);
			return $this->db->get();
		}
		
		public function get_dynamic_fields($where)
		{
			$this->db->select("q.*");
			$this->db->from("question q");
			if($where!=null)
				$this->db->where($where);
			return $this->db->get();
		}

		public function get_all_questions($limit = null, $offset = null, $search_parameters = null)
		{
			$this->db->select('q.*,s.*,t.*,q.id as question_id,s.id as section_id,t.id as type_id');
			$this->db->from('question q');
			$this->db->join('section s','q.section_id = s.id');
			$this->db->join('field_type t','q.type_id = t.id');
			$this->db->order_by('q.id','DESC');

			if($limit && $offset)
				$this->db->limit($limit,$offset);
			elseif($limit)
				$this->db->limit($limit);
			if($search_parameters)
			{
				foreach($search_parameters as $key=>$search_parameter)
				{
					if($key == 'searchByQuestion')
					{
						$this->db->like("q.name",$search_parameter,'both');
					}

					if($key == 'searchByType')
					{
						$this->db->where("t.id",$search_parameter);
					}

					if($key == 'searchBySection')
					{
						$this->db->where("s.id",$search_parameter);
					}

					if($key == 'searchByRequired')
					{
						$this->db->where("q.is_required",$search_parameter);
					}
					
				}
			}

			return $this->db->get();
		}
		
	}
?>