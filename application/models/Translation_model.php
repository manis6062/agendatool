<?php
	class Translation_Model extends MY_Model{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function select_specific_translation($where = null)
		{
			$this->db->select('*');
			$this->db->from('translation');
			if($where);
				$this->db->where($where);
			return $this->db->get();
		}
		
		public function check_if_exists($language_type_id,$keywords_id)
		{
			$this->db->select('*');
			$this->db->from('translation');
			$this->db->where('language_type_id',$language_type_id);
			$this->db->where('keywords_id',$keywords_id);
			if(count($this->db->get()->row_array())>0)
				return 1;
			else
				return 0;
		}
		
	}
?>