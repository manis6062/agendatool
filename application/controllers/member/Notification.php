<?php
class Notification extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('api_model');
		$this->load->model('trial_period_model');
		$this->load->model('user_subscription_model');
		$this->load->model('email_log_model');
		$this->load->model('trainer_agenda_model');
		$this->load->model('favorite_model');
		$this->load->model('notification_model');
		$this->load->model('language_model');
		$this->load->library('form_validation');
		$this->load->library('icloud');
		$this->load->model('icloud_model');
	}
	public function index(){
		$data['page'] = 'member/profile/notification';
		$data['header'] = 'Notifications';
		$data['today'] = date('Y-m-d');
		$data['yesterday'] = date('Y-m-d',strtotime("-1 days"));
		$data['older'] = date('Y-m-d',strtotime("-2 days"));
		$data['words'] = $this->_language;
		$general_info_id = $this->session->userdata('general_id');

		$notifications = $this->notification_model->get_notification(array('sent_to_id'=>$general_info_id))->result_array();
		$today_index = 0;
		$yesterday_index = 0;
		$older_index = 0;
		foreach($notifications as $notification)
		{
			if($notification['created_date'] == $data['today'])
			{
				$data['today_events'][$today_index] = $notification;
				$data['today_events'][$today_index]['other_user'] = $this->user_model->get_user_info($notification['sent_by_id'])->row_array();
				$today_index++;
			}
			elseif($notification['created_date'] == $data['yesterday'])
			{
				$data['yesterday_events'][$yesterday_index] = $notification;
				$data['yesterday_events'][$yesterday_index]['other_user'] = $this->user_model->get_user_info($notification['sent_by_id'])->row_array();
				$yesterday_index++;
			}
			else
			{
				$data['older_events'][$notification['created_date']][$older_index] = $notification;
				$data['older_events'][$notification['created_date']][$older_index]['other_user'] = $this->user_model->get_user_info($notification['sent_by_id'])->row_array();
				$older_index++;
			}
		}

		$data['user_photo'] = $this->user_model->get_user_pic($general_info_id)->row_array();
		$data['userdata'] = $this->user_model->get_user_info($general_info_id)->row_array();
		$data['same_user'] = 1;
		$data['language'] = $this->language_model->select_specific_language()->result_array();
		$this->load->view($this->_container,$data);
	}
	public function getIphoneEvents() {
		$login_id = $this->session->userdata('general_id');
		$icloud_details = $this->icloud_model->getUserIcloudDetails($login_id);
		if(!empty($icloud_details)) {
			$events = $this->icloud->getEvents($icloud_details);
			if(empty($events)) {
				$add_event_sent = $this->icloud_model->getUserIcloudAddEventSent($login_id,$icloud_details['default_calendar_id']);
				if(empty($add_event_sent)) {
					$appointment_details['appointment_name'] = "AgendaTool";
					$appointment_details['appointment_description'] = "AgendaTool";
					$appointment_details['location'] = "AgendaTool";
					$appointment_details['start_time'] = "1880-01-10 12:00:00";
					$appointment_details['end_time'] = "1880-01-10 04:00:00";
					$this->icloud->addEvent($appointment_details,$icloud_details);
					$events = $this->icloud->getEvents($icloud_details);
					$this->icloud_model->addUserIcloudAddEventSent($login_id,$icloud_details['default_calendar_id']);
				}
			}
		}
		print_r($events);
	}
	public function getGoogleEvents() {
		if($this->session->userdata('general_id')==null || $this->session->userdata('is_company') == 1)
		{
			redirect(site_url('auth'));
			exit;
		}
		
		$client_id = $this->api_model->get_api(array('id'=>5))->row_array();
		$client_secret = $this->api_model->get_api(array('id'=>6))->row_array();
		$api_key = $this->api_model->get_api(array('id'=>7))->row_array();
		
		require_once 'src/Google_Client.php';
		require_once 'src/contrib/Google_CalendarService.php';
		require_once 'src/contrib/Google_AnalyticsService.php';
		$scriptUri = $this->api_model->get_api(array('id'=>8))->row_array();
		//$scriptUri = "http://agendatool.podamibe.net/index.php/google/googleapi";
		$client = new Google_Client();
		$client->setAccessType('online'); // default: offline
		$client->setApplicationName('Agenda Tool');
		$client->setClientId('1037618522304-cs8u1dqj0pt64dvg0ht8au9jhjdv632m.apps.googleusercontent.com');
		$client->setClientSecret('T3DIk4kEjJKLAORNRTeqIB9T');
		$client->setRedirectUri('http://agendatool.servernepal.org/google/googleapi');
		$client->setDeveloperKey('AIzaSyCTk5Sil3ApM1QF4Msrrfd0ZnW2f0bIUI0'); // API key

		// $service implements the client interface, has to be set before auth call
		//$service = new Google_AnalyticsService($client);
		$service = new Google_CalendarService($client);


		if (isset($_GET['logout'])) { // logout: destroy token
			//unset($_SESSION['token']);
			$this->session->unset_userdata('token');
			redirect(site_url('member/profile'));
		}
		
		if($this->session->userdata('token')==''){
			if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
				$client->authenticate();
				$this->session->set_userdata('token',$client->getAccessToken());
			}
		}

		if (isset($_SESSION['token'])) { // extract token from session and configure client
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
		$events = $service->events->listEvents('primary');
		echo "<pre>";
		print_r($events);
		echo "</pre>";	
	}
}