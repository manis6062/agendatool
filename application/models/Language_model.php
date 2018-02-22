<?php
	class Language_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function select_specific_language($where = null)
		{
			$this->db->select('*');
			$this->db->from('language');
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}
		
		public function get_default_language($limit = null,$offset = null,$search_parameter = null)
		{
			$this->db->select('l.*,k.*,t.*');
			$this->db->from('language l');
			$this->db->join('translation t','t.language_type_id = l.id');
			$this->db->join('keywords k','t.keywords_id = k.id');
			if($limit && $offset)
				$this->db->limit($limit,$offset*$limit);
			elseif($limit)
				$this->db->limit($limit);
			if($search_parameter!=null)
			{
				$this->db->like('t.text',$search_parameter,'both');
			}
			$this->db->where('l.id',1);
			$result = $this->db->get();
			return $result;
		}
	}

		