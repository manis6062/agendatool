<?php
class Googleapi extends Frontend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('trainer_agenda_model');
		$this->load->model('api_model');
		$this->load->model('trainer_agenda_time_model');
		$this->load->model('user_model');
		$this->load->model('connect_agenda_model');
	}
	
	public function index()
	{
		set_time_limit(0);
		if($this->session->userdata('general_id')==null || $this->session->userdata('is_company') == 1)
		{
			redirect(site_url('auth'));
			exit;
		}
		$google_api_details = $this->api_model->get_api()->result_array();
		$client_id = $google_api_details[4]['api_key'];
		$client_secret = $google_api_details[5]['api_key'];
		$api_key =  $google_api_details[6]['api_key'];
		$call_back_url =  $google_api_details[7]['api_key'];
		require_once 'src/Google_Client.php';
		require_once 'src/contrib/Google_CalendarService.php';
		require_once 'src/contrib/Google_AnalyticsService.php';

		//$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
		//$scriptUri = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		//print_r($scriptUri);
		//exit;
		//$scriptUri = $this->api_model->get_api(array('id'=>8))->row_array();
		//$scriptUri = "http://agendatool.podamibe.net/index.php/google/googleapi";
		$client = new Google_Client();
		$client->setAccessType('online'); // default: offline
		$client->setApplicationName('Agenda Tool');
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($call_back_url);
		$client->setDeveloperKey($api_key); // API key

		// $service implements the client interface, has to be set before auth call
		//$service = new Google_AnalyticsService($client);
		$service = new Google_CalendarService($client);


		if (isset($_GET['logout'])) { // logout: destroy token
			//unset($_SESSION['token']);
			$this->session->unset_userdata('token');
			redirect(site_url('member/profile'));
		}
		
		if($this->session->userdata('token')=='')
		{
			if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
				$client->authenticate();
				$this->session->set_userdata('token',$client->getAccessToken());
				//$_SESSION['token'] = $client->getAccessToken();
			}
		}

		if (isset($_SESSION['token'])) { // extract token from session and configure client
			//$token = $_SESSION['token'];
			$token = $this->session->userdata('token');
			$client->setAccessToken($token);
		}

		if (!$client->getAccessToken()) { // auth call to google
			$authUrl = $client->createAuthUrl();
			header("Location: ".$authUrl);
			die;
		}
		
		if($client->isAccessTokenExpired()) {
			$this->session->unset_userdata('token');
			redirect(site_url('google/googleapi'));
		}
		
		$this->_set_google_calendar($service);
		$this->_getGoogleCalendarEvents($service);
		redirect(site_url('member/profile'));
		
	}
	
	private function _set_google_calendar($service)
	{
		set_time_limit(0);
		$connect_data['gmail_status'] = 1;
		$connect_agenda_details = $this->db->query("SELECT * FROM connect_agenda WHERE general_id = '".$this->session->userdata('general_id')."'")->row();
		if(empty($connect_agenda_details)){
			$this->connect_agenda_model->insert('connect_agenda',array('gmail_status'=>1,'general_id'=>$this->session->userdata('general_id')));
		}
		else {
			$this->connect_agenda_model->update('connect_agenda',$connect_data,array('general_id'=>$this->session->userdata('general_id')));
		}
		$general_id = $this->session->userdata('general_id');
		$trainer_data = $this->user_model->get_trainer_by_general_id($general_id)->row_array();
		$all_agendas = $this->trainer_agenda_model->get_trainer_agenda($trainer_data['trainer_info_id'])->result_array();
		foreach($all_agendas as $agenda)
		{
			if($agenda['is_edited']==1 || $agenda['google_event_id']=="")
				if($agenda['is_confirm'] == 1 || $agenda['is_appointed'] == 1)
					$agendas[] = $agenda;
		}
		if(!empty($agendas))
		{
			foreach($agendas as $agenda)
			{
				$agenda['agenda_time'] = $this->trainer_agenda_time_model->get_trainer_time($agenda['id'])->result_array();
				if(!empty($agenda['agenda_time']))
				{
					$first_time = $agenda['agenda_time'][0]['work_time'].":00:00";
					$last_item = count($agenda['agenda_time'])-1;
					$last_time = $agenda['agenda_time'][$last_item]['work_time'].":00:00";
				}
				else
				{
					$first_time = "00:00:00";
					$last_time = "23:00:00";
				}
				$start_time = $agenda['appoint_date'].'T'.$first_time;
				$end_time = $agenda['appoint_date'].'T'.$last_time;
				$event = new Google_Event(array(
					'summary' =>$agenda['appointment_name'],
					'location' => $agenda['location'],
					'description' => $agenda['appointment_description'],
					'start' => array(
						'dateTime' => $start_time,
						'timeZone' => 'America/Los_Angeles',
					),
					'end' => array(
						'dateTime' => $end_time,
						'timeZone' => 'America/Los_Angeles',
					),
				));
				
				
				
				$calendarId = 'primary';
				if($agenda['google_event_id']=="")
				{
					$event = $service->events->insert($calendarId, $event);
					$data['google_event_id'] = $event['id'];
					$this->trainer_agenda_model->update('trainer_agenda',$data,array('id'=>$agenda['agenda_id']));
				}
				elseif($agenda['is_edited'] == 1)
				{
					$event = $service->events->update($calendarId, $agenda['google_event_id'], $event);
				}
			}
		}
		//redirect(site_url('member/profile'));
	}
	private function _getGoogleCalendarEvents($service) {
		$general_id = $this->session->userdata('general_id');
		$trainer_info_id = $this->user_model->getTrainerInfoIdFromGeneral($general_id)->id;
		$events = $service->events->listEvents('primary');
		$items = $events['items'];
		foreach($items as $item) {
			$google_event_id = $item['id'];
			$event_times = $this->_getGoogleEventsTime($item);
			$google_event_exist = $this->user_model->checkGoogleEventId($google_event_id);
			if(empty($google_event_exist)) {//if google event exist is empty then there is no event in our table
				$google_event_data = array(
										'google_event_id'=>$google_event_id,
										'trainer_info_id'=>$trainer_info_id,
										'appointment_name'=>'Gmail Appointment',
										'location' => @$item['location'],
										'is_confirm' => 1,
										'appointed_by' => $general_id,
										'date_time' => $event_times['date_time'],
										'appoint_date' =>$event_times['appoint_date'],
										'appointment_description'=>'Gmail Appointment'
									);
				$this->db->insert("trainer_agenda",$google_event_data);
				$this->_insertTrainerAgendaTime($event_times);
			}
		}
	}
	private function _insertTrainerAgendaTime($event_times) {
		$trainer_agenda_id = $this->db->insert_id();
		$google_event_work_time_details = array(
												array(
														'trainer_agenda_id'=>$trainer_agenda_id,
														'work_time'=>$event_times['start_time']
													),
												array(
														'trainer_agenda_id'=>$trainer_agenda_id,
														'work_time'=>$event_times['end_time']
													)
											);
		$this->db->insert_batch('trainer_agenda_time', $google_event_work_time_details); 
	}
	private function _getGoogleEventsTime($item) {
		$created_date = $item['created'];
		$created_date_arr = explode("T",$created_date);
		$created_time = explode(".",$created_date_arr[1]);
		
		
		$start_date_time = $item['start']['dateTime'];
		$start_time_arr = explode("T",$start_date_time);
		$start_time = explode("+",$start_time_arr[1]);
		
		$end_date_time = $item['end']['dateTime'];
		$end_time_arr = explode("T",$end_date_time);
		$end_time = explode("+",$end_time_arr[1]);
		$result['date_time'] = $created_date_arr[0]." ".$created_time[0];
		$result['start_time'] = $start_time[0];
		$result['end_time'] = $end_time[0];
		$result['appoint_date'] = $start_time_arr[0];
		return $result;
	}
}
