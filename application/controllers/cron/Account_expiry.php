<?php 
	Class Account_expiry extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('email_model');
			$this->load->model('trial_period_model');
			$this->load->model('user_subscription_model');
			$this->load->library('email_library');
		}
		public function index() {
			$all_users = $this->user_model->getAllActiveUsers()->result();
			foreach($all_users as $user) {
				$trial_period_time = $this->trial_period_model->get_trial_periods(array('user_type_id'=>$user->user_type_id))->row_array();
                $time = strtotime($user->created_on);
                $time_with_trial = date("Y-m-d H:i:s", strtotime("+" . $trial_period_time['trial_period_time'] . " days", $time));
                $current_date = date("Y-m-d H:i:s");
				$subscription_detail = $this->user_subscription_model->get_user_subscription($user->general_id)->row_array();
				if ($user->user_type_id == 2) {//company
					$email_data = $this->email_model->get_email(2, 1)->row_array();
				}
				elseif ($user->user_type_id == 3) {//trainer
					$email_data = $this->email_model->get_email(3, 1)->row_array();
				}
				$to = $user->email_address;
				$subject = $email_data['subject'];
				$message = $email_data['email'];
				if (empty($subscription_detail) && $time_with_trial < $current_date) {
					if($email_data['flag_bit'] == 1)
						$this->email_library->sendEmail($to, $subject, $message);
				}
				elseif ($time_with_trial >= $current_date || $subscription_detail['to_date'] >= $current_date) {}//do nothing
				else {
					if($email_data['flag_bit'] == 1)
						$this->email_library->sendEmail($to, $subject, $message);
				}
			}
		}
	}
