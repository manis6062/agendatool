<?php
	class Email_model extends MY_Model{
		public function __construct(){
			parent::__construct();
		}
		public function get_email($user_type_id,$email_type_id){
			$this->db->select("e.*,et.*");
			$this->db->from('email e');
			$this->db->join('email_type et','e.email_type_id = et.id');
			$this->db->where('e.user_type_id',$user_type_id);
			$this->db->where('et.id',$email_type_id);
			return $this->db->get();
		}
		public function get_all_email()
		{
			$this->db->select("e.*,et.*,ut.*,e.id as email_id");
			$this->db->from("email e");
			$this->db->join('email_type et','e.email_type_id = et.id');
			$this->db->join('user_type ut','ut.id = e.user_type_id');
			return $this->db->get();
		}
        public function get_all_email_with_pagination($limit = null, $offset = null){
            $this->db->select("e.*,et.*,ut.*,e.id as email_id");
            $this->db->from("email e");
            $this->db->join('email_type et', 'e.email_type_id = et.id');
            $this->db->join('user_type ut', 'ut.id = e.user_type_id');
            $this->db->limit($limit, $offset);
            return $this->db->get();
        }
		public function update($table_name,$data,$where_arr) {
			$result = $this->db->update($table_name,$data,$where_arr);
			return $result;
		}
    }
?>