<?php
class Icloud_controller extends Member_Controller
{
    var $data;
    var $upload_video_path = 'uploads/video';
    var $upload_path = 'uploads/userimage';
	public function __construct() {
        parent::__construct();
		$this->load->library('icloud');
		$this->load->model('icloud_model');
    }
	private function _checkIcloudId($input_data) {
		$result = array();
		$result['flag_bit'] = 1;
		$apple_id_exist = $this->icloud->makePrincipalRequest($input_data['apple_id'],$input_data['password']);
		if($apple_id_exist['status'] == 'success') {
			$result['calendars'] = array();
			$calendars = $this->icloud->makeCalendarRequest($apple_id_exist['userID'],$input_data['apple_id'],$input_data['password']);
			$result['status'] = 'success';
			foreach($calendars as $calendar) {
				$name = $calendar['name'];
				$href = $calendar['href'][0];
				$href_arr = explode("/",$href);
				if($name[0] != '' && count($href_arr) == 5) {
					$result['calendars'][] = array('href'=>str_replace("/","|",$href),'name'=>"'".$name[0]."'",'calendar_id'=>$href_arr[3]);
				}
			}
		}
		else {
			$result['status'] = 'error';
			$result['message'] = 'You are not authorized to access this resource.';
		}
		echo json_encode($result);
	}
	private function _validateIcloud($input_data){
		$result = array();
		$result['error_bit'] = false;
		if($input_data['apple_id'] == '') {
			$result['message'] = 'Enter Apple Id';
			$result['error_bit'] = true;
		}
		else if($input_data['password'] == '') {
			$result['error_bit'] = true;
			$result['message'] = 'Enter Apple Password';
		}
		else if(!isset($input_data['calendar'])) {
			$result['error_bit'] = true;
			$result['message'] = 'Select Any One Calendar';
		}
		return $result;
	}
	private function _saveIcloudId($input_data) {
		$result = array();
		$result['flag_bit'] = 2;
		$insert_data['user_id'] = $this->session->userdata('id');
		$validate_result = $this->_validateIcloud($input_data);
		if($validate_result['error_bit']) {
			$result['status'] = 'error';
		}
		else {
			$calendar = $input_data['calendar'];
			$calendar_arr = explode("|",$calendar);
			$insert_data['login_id'] = $this->session->userdata('general_id');
			$insert_data['apple_id'] = $input_data['apple_id'];
			$insert_data['password'] = $input_data['password'];
			$insert_data['default_calendar_id'] = $calendar_arr[3];
			$insert_data['user_id'] = $calendar_arr[1];
			$insert_result = $this->icloud_model->saveIcloudDetails($insert_data);
			if($result) {
				$result['status'] = 'successs';
			}
			else {
				$result['status'] = 'error';
				$result['message'] = 'Failed To save Icloud Credentials';
			}
		}
		echo json_encode($result);
	}
	private function _updateICloud($input_data) {
		$result = array();
		$result['flag_bit'] = 2;
		$validate_result = $this->_validateIcloud($input_data);
		if($validate_result['error_bit']) {
			$result['status'] = 'error';
		}
		else {
			$calendar = $input_data['calendar'];
			$calendar_arr = explode("|",$calendar);
			$login_id = $this->session->userdata('general_id');
			$insert_data['apple_id'] = $input_data['apple_id'];
			$insert_data['password'] = $input_data['password'];
			$insert_data['default_calendar_id'] = $calendar_arr[3];
			$insert_data['user_id'] = $calendar_arr[1];
			$insert_result = $this->icloud_model->updateIcloudDetails($insert_data,array('login_id'=>$login_id));
			if($result) {
				$result['status'] = 'successs';
			}
			else {
				$result['status'] = 'error';
				$result['message'] = 'Failed To update Icloud Credentials';
			}
		}
		echo json_encode($result);
	}
	public function save_iCloud_Credentials() {
		$input_data = $this->input->post();
		$flag_bit = $input_data['flag_bit'];
		$edit_bit = $input_data['edit_bit'];
		$login_id = $this->session->userdata('general_id');
		$icloud_details = $this->icloud_model->getUserIcloudDetails($login_id);
		if($flag_bit == 1 || $edit_bit == 1) {
			$this->_checkIcloudId($input_data);
		}
		else {
			if(empty($icloud_details))
				$this->_saveIcloudId($input_data);
			else
				$this->_updateICloud($input_data);
		}
	}
	public function getUserIcloudDetails() {
		$result = array();
		$result['calendars'] = array();
		$login_id = $this->session->userdata('general_id');
		$icloud_details = $this->icloud_model->getUserIcloudDetails($login_id);
		$result = $icloud_details;
		if(!empty($icloud_details)) {
			$apple_id_exist = $this->icloud->makePrincipalRequest($icloud_details['apple_id'],$icloud_details['password']);
			if($apple_id_exist['status'] == 'success') {
				$calendars = $this->icloud->makeCalendarRequest($icloud_details['user_id'],$icloud_details['apple_id'],$icloud_details['password']);
				$calendars = json_encode($calendars);
				$calendars = json_decode($calendars,true);
				foreach($calendars as $calendar) {
					$name = $calendar['name'];
					$href = $calendar['href'][0];
					$href_arr = explode("/",$href);
					if(isset($name[0]) && count($href_arr) == 5) {
						$result['calendars'][] = array('href'=>str_replace("/","|",$href),'name'=>"'".$name[0]."'",'calendar_id'=>$href_arr[3]);
					}
				}
			}
		}
		echo json_encode($result);
	}
}