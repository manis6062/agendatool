<?php 
// Load ICS parser
require_once(dirname(__FILE__).'/ics-parser/class.iCalReader.php');
require_once(dirname(__FILE__).'/ics-parser/class.iCloudCalendar.class.php');
Class Icloud {
	private $_url = 'https://p01-caldav.icloud.com';
	private $_icloud_server = 'p01';
	private $_icloud_calendar;
	function doRequest($user, $pw, $xml) {
		//Init cURL
		$c=curl_init($this->_url);
		//Set headers
		curl_setopt($c, CURLOPT_HTTPHEADER, array("Depth: 1", "Content-Type: text/xml; charset='UTF-8'", "User-Agent: DAVKit/4.0.1 (730); CalendarStore/4.0.1 (973); iCal/4.0.1 (1374); Mac OS X/10.6.2 (10C540)"));
		curl_setopt($c, CURLOPT_HEADER, 0);
		//Set SSL
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		//Set HTTP Auth
		curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($c, CURLOPT_USERPWD, $user.":".$pw);
		//Set request and XML
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PROPFIND");
		curl_setopt($c, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		//Execute
		$data=curl_exec($c);
		//Close cURL
		curl_close($c);
		
		return $data;
	}
	public function makePrincipalRequest($user, $pw) {
		$result_data['status'] = 'success';
		$principal_request = "<A:propfind xmlns:A='DAV:'>
										<A:prop>
											<A:current-user-principal/>
										</A:prop>
									</A:propfind>";
		$response =	$this->doRequest($user, $pw, $principal_request);
		$response = simplexml_load_string($response);
		if(isset($response->response->propstat->prop->{'current-user-principal'}->href)) {
			$userID=explode("/",$response->response->propstat->prop->{'current-user-principal'}->href);
			$userID=$userID[1];
			$result_data['userID'] = $userID;
		}
		else {
			$result_data['status'] = 'error`';
		}
		return $result_data;
	}
	public function makeCalendarRequest($userId,$user,$pw) {
		$calendars_request="<A:propfind xmlns:A='DAV:'>
								<A:prop>
									<A:displayname/>
								</A:prop>
							</A:propfind>";
		$this->_url = $this->_url."/".$userId."/calendars/";
		$response =	$this->doRequest($user, $pw, $calendars_request);
		$response=simplexml_load_string($response);
		//To array
		$calendars = array();
		foreach($response->response as $cal){
			$entry["href"]=$cal->href;
			//$entry["name"]=$cal->propstat[0]->prop[0]->displayname;
			if(isset($cal->propstat->prop->displayname))
				$entry["name"]=$cal->propstat->prop->displayname;
			$calendars[]=$entry;
		}
		return $calendars;
	}
	private function _getIcloudCalendar($icloud_details) {
		date_default_timezone_set("GMT");
		$user_id = $icloud_details['user_id'];
		$my_calendar_id = $icloud_details['default_calendar_id'];
		$my_icloud_username = $icloud_details['apple_id'];
		$my_icloud_password = $icloud_details['password'];
		$icloud_calendar = new php_icloud_calendar($this->_icloud_server, $user_id, $my_calendar_id, $my_icloud_username, $my_icloud_password);
		return $icloud_calendar;
	}
	public function addEvent($appointment_details,$icloud_details) {
		$icloud_calendar = $this->_getIcloudCalendar($icloud_details);
		$start_time = $appointment_details['start_time'];
		$end_time = $appointment_details['end_time'];
		$appointment_name = $appointment_details['appointment_name'];
		$appointment_description = $appointment_details['appointment_description'];
		$location = $appointment_details['location'];
		$event_id = $icloud_calendar->add_event(date($start_time),date($end_time),$appointment_name,$appointment_description,$location);
		return $event_id;
	}
	public function getEvents($icloud_details) {
		$icloud_calendar = $this->_getIcloudCalendar($icloud_details);
		$my_range_date_time_from = date("Y-m-d H:i:s", strtotime("-10 year"));
		$my_range_date_time_to = date("Y-m-d H:i:s", strtotime("+10 year"));
		$my_events = $icloud_calendar->get_events($my_range_date_time_from, $my_range_date_time_to);
		return $my_events;
	}
}