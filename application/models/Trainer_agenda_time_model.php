<?php
	class Trainer_agenda_time_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
		}


		public function get_trainer_time($id)
		{
			$this->db->select("work_time");
			$this->db->from("trainer_agenda_time");
			$this->db->where("trainer_agenda_id",$id);
			return $this->db->get();
		}

		public function get_confirmed_time_for_date($id,$date)
		{
			$this->db->select("tat.work_time");
			$this->db->from("trainer_agenda_time tat");
			$this->db->join("trainer_agenda ta","ta.id = tat.trainer_agenda_id");
			$this->db->where("ta.id",$id);
			$this->db->where("ta.appoint_date",$date);
			$this->db->where("ta.is_confirm",1);
			$this->db->where("ta.is_appointed",1);
			return $this->db->get();
		}

        public function updateby_desc($time,$is_deleted,$id)
        {
            $data = array(
                'work_time' => $time,
                'is_deleted' => $is_deleted,
            );
            $this->db->where('trainer_agenda_id',$id);
            $this->db->update('trainer_agenda_time', $data);
            $this->db->limit(1);


        }
    }
?>