<?php
	class MY_Model extends CI_Model
	{
		var $TABLE;
		public function __construct()
		{
			parent::__construct();
		}

		public function insert($table,$data)
		{
			$this->db->insert($table,$data);
		}

		public function update($table,$data,$where)
		{
			$this->db->update($table,$data,$where);
		}

		public function delete($table,$where)
		{
			$this->db->delete($table,$where);
		}

		public function fetch($cols,$table,$where=NULL)
		{
			$this->db->select($cols);
			$this->db->from($table);
			
			if(!is_null($where)){
				$this->db->where($where);
			}
			
			return $this->db->get();
		}

		public function trans_start()
		{
			$this->db->trans_start();
		}

		public function trans_complete()
		{
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
			    $this->db->trans_rollback();
			    return FALSE;
			}
			else
			{
			    $this->db->trans_commit();
			    return TRUE;
			}
		}

		public function get_last_inserted_id()
		{
			return $this->db->insert_id();
		}
	}