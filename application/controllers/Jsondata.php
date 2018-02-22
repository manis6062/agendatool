<?php
	class Jsondata extends Frontend_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('speciality_model');
		}

		public function get_sub_category()
		{
			$ids=$this->input->post("ids");
			$data = array();
			foreach($ids as $id) {
				$options = $this->speciality_model->get_by_parent_id($id)->result_array();
				$data = array_merge($data,$options);
			}
			echo json_encode($data);
		}

		public function get_sub_category_by_name()
		{
			$search_speciality = $this->input->post("search_speciality");
			echo json_encode($this->speciality_model->search_speciality_by_name($search_speciality)->result_array());
		}
		
		public function get_category()
		{
			$ids = $this->input->post("ids");
			$data = array();
			foreach($ids as $id)
			{
				$result = $this->speciality_model->get_speciality($id)->result_array();
				$data = array_merge($data,$result);
			}
			echo json_encode($data);
		}
	}	