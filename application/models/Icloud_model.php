<?php
class Icloud_model extends My_Model
{
	private $_tbl_name = 'user_icloud_credentials';
    public function __construct()
    {
        parent::__construct();
    }
	public function saveIcloudDetails($insert_data) 
	{
		$result = $this->db->insert($this->_tbl_name,$insert_data);
		return $result;
	}
	public function getUserIcloudDetails($login_id) {
		$icloud_details = $this->db->select("*")
		->from("user_icloud_credentials")
		->where("login_id",$login_id)
		->get()
		->row_array();
		return $icloud_details;
	}
	public function updateIcloudDetails($update_data,$where_arr) {
		$update_result =  $this->db->update($this->_tbl_name,$update_data,$where_arr);
		return $update_result;
	}
	public function getUserIcloudAddEventSent($login_id,$calendar_id) {
		$result = $this->db->select("*")
		->from("user_icloud_add_event_sent")
		->where("login_id",$login_id)
		->where("calendar_id",$calendar_id)
		->get()
		->result();
		return $result;
	}
	public function addUserIcloudAddEventSent($login_id,$calendar_id) {
		$result = $this->db->insert("user_icloud_add_event_sent",array('login_id'=>$login_id,'calendar_id'=>$calendar_id));
		return $result;
	}
}