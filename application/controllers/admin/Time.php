<?php
	class Time extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('question_model');
			$this->load->model('section_model');
			$this->load->model('option_model');
			$this->load->model('answer_model');
			$this->load->model('language_model');
			$this->load->model('field_type_model');
		}
	}

	public function index()
	{

	}