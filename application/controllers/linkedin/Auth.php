<?php
	
	class Auth extends Frontend_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->model('api_model');
			//session_start();
		}
		
		public function index()
		{
			$api_details = $this->api_model->get_api()->result_array();
			$base_url = $api_details[4]['api_key'];
			$callback_url = $api_details[3]['api_key'];
			$linkedin_access = $api_details[0]['api_key'];
			$linkedin_secret = $api_details[1]['api_key'];
			$linkedin_config['base_url']             =   $base_url;
			$linkedin_config['callback_url']         =   $callback_url;
			$linkedin_config['linkedin_access']      =   $linkedin_access;
			$linkedin_config['linkedin_secret']      =   $linkedin_secret;

			include_once "linkedin.php";

			# First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
			$linkedin = new LinkedIn($linkedin_config['linkedin_access'], $linkedin_config['linkedin_secret'], $linkedin_config['callback_url'] );
			//$linkedin->debug = true;

			# Now we retrieve a request token. It will be set as $linkedin->request_token
			$linkedin->getRequestToken();
			//print_r($linkedin->request_token);
			//$this->session->set_userdata('requestToken',serialize($linkedin->request_token));
			$_SESSION['requestToken'] = serialize($linkedin->request_token);
			//print_r($_SESSION);
		  
			# With a request token in hand, we can generate an authorization URL, which we'll direct the user to
			//echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
			header("Location: " . $linkedin->generateAuthorizeUrl());
		}
	}