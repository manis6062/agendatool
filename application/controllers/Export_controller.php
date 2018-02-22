<?php
	class Export_controller extends CI_Controller
	{
		public function __construct() {
			parent::__construct();
			$this->load->model('trainer_agenda_model');
			$this->load->model('user_model');
			$this->load->model('trainer_info_model');
		}
		public function _createCSV($setMainHeader,$setData) {
			//This Header is used to make data download instead of display the data
			header("Content-type: application/octet-stream");

			header("Content-Disposition: attachment; filename=Appointment_".time().".csv");

			header("Pragma: no-cache");
			header("Expires: 0");

			//It will print all the Table row as Excel file row with selected column name as header.
			echo ucwords(str_replace('_', ' ', $setMainHeader))."\n".$setData."\n";
		}
		public function export_company_appointments($general_id) {
			$no_of_agendas = $this->trainer_agenda_model->getTrainerAcceptedAppointments($general_id,0,0);
			$setMainHeader = "Name,Specialities,Appointment Reservation";
			$setData = "";
			foreach($no_of_agendas as $agenda) {
				$trainer_detail = $this->user_model->get_user_by_trainer_id($agenda['trainer_info_id'])->row_array();
				$user_specialities_arr = getUserSpecialities($agenda['general_id']);
				$user_specialites = "";
				foreach($user_specialities_arr as $us) {
					$user_specialites .= $us->category_name.",";
				}
				$user_specialites = rtrim($user_specialites,",");
				$appointment_reservation = $agenda['appoint_date']." ".$agenda['start_event_time']." to ".$agenda['end_event_time'];
				$setData .= $trainer_detail['name'].",\"".$user_specialites."\",".$appointment_reservation."\n";
			}
			$this->_createCSV($setMainHeader,$setData);
		}
		public function export_trainer_appointments($general_id) {
			$data['trainerdata'] = $this->trainer_info_model->get_specific_trainer($general_id)->row_array();
			$no_of_agendas = $this->trainer_agenda_model->getAcceptedAppointments($data['trainerdata']['id'],0,0);
			$setMainHeader = "Name,Specialities,Appointment Reservation";
			$setData = "";
			foreach($no_of_agendas as $agenda) {
				$company_detail = $this->user_model->get_user_info($agenda['appointed_by'])->row_array();
				$user_specialities_arr = getUserSpecialities($agenda['appointed_by']);
				$user_specialites = "";
				foreach($user_specialities_arr as $us) {
					$user_specialites .= $us->category_name.",";
				}
				$user_specialites = rtrim($user_specialites,",");
				$appointment_reservation = $agenda['appoint_date']." ".$agenda['start_event_time']." to ".$agenda['end_event_time'];
				$setData .= $company_detail['name'].",\"".$user_specialites."\",".$appointment_reservation."\n";
			}
			$this->_createCSV($setMainHeader,$setData);
		}
	}