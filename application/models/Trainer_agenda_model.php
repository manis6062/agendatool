<?php
class Trainer_agenda_Model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_trainer_agenda($id, $order_by = null, $limit = null, $offset = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select("t.*,g.*,t.id as agenda_id");
        $this->db->from("trainer_agenda t");
        $this->db->where("trainer_info_id", $id);
        //$this->db->where("is_confirm != 1");
        $this->db->join('general_reg_info g', 'g.id=t.appointed_by');
        if ($order_by)
            $this->db->order_by("date_time", $order_by);
        else
            $this->db->order_by("date_time", "DESC");
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        elseif ($limit)
            $this->db->limit($limit);
        if ($from_date && $to_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('t.appoint_date >= ', date('Y-m-d'));
            $this->db->where('t.appoint_date <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByAppointment') {
                    $this->db->like("t.appointment_name", $search_parameter, 'both');
                }

                if ($key == 'searchByCompany') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByLocation') {
                    $this->db->like("t.location", $search_parameter, 'both');
                }
            }
        }


        return $this->db->get();
    }

    public function get_trainer_agenda_confirmed($id, $order_by = null, $limit = null, $offset = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select("t.*,g.*");
        $this->db->from("trainer_agenda t");
        $this->db->where("trainer_info_id", $id);
        $this->db->where("is_confirm", 1);
        $this->db->join('general_reg_info g', 'g.id=t.appointed_by');
        if ($order_by)
            $this->db->order_by("date_time", $order_by);
        else
            $this->db->order_by("date_time", "DESC");
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        elseif ($limit)
            $this->db->limit($limit);
        if ($from_date && $to_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('t.appoint_date >= ', date('Y-m-d'));
            $this->db->where('t.appoint_date <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByAppointment') {
                    $this->db->like("t.appointment_name", $search_parameter, 'both');
                }

                if ($key == 'searchByCompany') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByLocation') {
                    $this->db->like("t.location", $search_parameter, 'both');
                }
            }
        }

        return $this->db->get();
    }

    public function count_agendas($id, $confirmed = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select("t.*,g.*");
        $this->db->from("trainer_agenda t");
        $this->db->where("trainer_info_id", $id);
        $this->db->join('general_reg_info g', 'g.id=t.appointed_by');
        if ($confirmed)
            $this->db->where("is_confirm", $confirmed);
        if ($from_date && $to_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('t.appoint_date >= ', date('Y-m-d'));
            $this->db->where('t.appoint_date <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByAppointment') {
                    $this->db->like("t.appointment_name", $search_parameter, 'both');
                }

                if ($key == 'searchByCompany') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByLocation') {
                    $this->db->like("t.location", $search_parameter, 'both');
                }
            }
        }
        return $this->db->count_all_results();
    }

    public function count_company_agendas($id, $reserved = null, $confirmed = null, $rejected = null)
    {
        $this->db->where("trainer_info_id", $id);
        $this->db->from("trainer_agenda");
        if ($reserved) {
            $this->db->where("is_confirm", 0);
            $this->db->where("is_appointed", 1);
        }
        if ($confirmed) {
            $this->db->where("is_confirm", 1);
            $this->db->where("is_appointed", 1);
        }
        if ($rejected) {
            $this->db->where("is_confirm", 0);
            $this->db->where("is_appointed", 0);
        }
        return $this->db->count_all_results();
    }

    public function get_company_agenda($id, $reserved = null, $confirmed = null, $rejected = null, $order_by = null, $limit = null, $offset = null, $search_parameters = null, $from_date = null, $to_date = null)
    {
        $this->db->select("t.*,g.*,t.id as agenda_id");
        $this->db->from("trainer_agenda t");
        $this->db->join('general_reg_info g', 'g.id=t.appointed_by');
        $this->db->join('trainer_info as ti', 't.trainer_info_id = ti.id');
        $this->db->join('general_reg_info gr', 'ti.general_info_id = gr.id');
        $this->db->where("is_confirm != 1");
        $this->db->where("appointed_by", $id);
        if ($order_by == 'ASC')
            $this->db->order_by('date_time', 'ASC');
        elseif ($order_by == 'DESC')
            $this->db->order_by('date_time', 'DESC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        elseif ($limit)
            $this->db->limit($limit);

        if ($reserved) {
            $this->db->where("is_confirm", 0);
            $this->db->where("is_appointed", 1);
        }
        if ($confirmed) {
            $this->db->where("is_confirm", 1);
            $this->db->where("is_appointed", 1);
        }
        if ($rejected) {
            $this->db->where("is_confirm", 0);
            $this->db->where("is_appointed", 0);
        }
        if ($from_date && $to_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', $to_date);
        } elseif ($from_date) {
            $this->db->where('t.appoint_date >= ', $from_date);
            $this->db->where('t.appoint_date <= ', date('Y-m-d'));
        } elseif ($to_date) {
            $this->db->where('t.appoint_date >= ', date('Y-m-d'));
            $this->db->where('t.appoint_date <= ', $to_date);
        }
        if ($search_parameters) {
            foreach ($search_parameters as $key => $search_parameter) {
                if ($key == 'searchByAppointment') {
                    $this->db->like("t.appointment_name", $search_parameter, 'both');
                }

                if ($key == 'searchByTrainerName') {
                    $this->db->like("gr.name", $search_parameter, 'both');
                }

                if ($key == 'searchByAppointmentName') {
                    $this->db->like("t.appointment_name", $search_parameter, 'both');
                }

                if ($key == 'searchByCompany') {
                    $this->db->like("g.name", $search_parameter, 'both');
                }

                if ($key == 'searchByLocation') {
                    $this->db->like("t.location", $search_parameter, 'both');
                }

                if ($key == 'searchByAppointmentDate') {
                    $this->db->like('t.appoint_date', $search_parameter, 'both');
                }
            }
        }

        return $this->db->get();
    }

    public function get_agenda($id)
    {
        $this->db->select("t.*,g.*,u.*");
        $this->db->from("trainer_agenda t");
        $this->db->join("general_reg_info g", "t.appointed_by = g.id");
        $this->db->join("user_login u", "u.id = g.user_login_id");
        $this->db->where("t.id", $id);
        return $this->db->get();
    }

    public function get_document_data($id)
    {
        $this->db->select("*");
        $this->db->from("document");
        $this->db->where("trainer_agenda_id", $id);
        return $this->db->get();
    }

    public function check_if_free($trainer_info_id, $appoint_date = null, $work_time = null)
    {
        if ($work_time && $appoint_date) {
            $this->db->select("tat.work_time,ta.appoint_date");
            $this->db->from("trainer_agenda ta");
            $this->db->join("trainer_agenda_time tat", "tat.trainer_agenda_id = ta.id");
            $this->db->where("ta.trainer_info_id", $trainer_info_id);
            $data = $this->db->count_all_results();
        } elseif ($appoint_date) {
            $this->db->select("ta.appoint_date");
            $this->db->from("trainer_agenda ta");
            $this->db->where("ta.trainer_info_id", $trainer_info_id);
            $data = $this->db->count_all_results();
        }
        if (isset($data)) {
            if ($data > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function get_agenda_by_agenda_id($id)
    {
        $this->db->select('ta.*');
        $this->db->from('trainer_agenda ta');
        $this->db->where('ta.id', $id);
        return $this->db->get();
    }

    public function get_edited_trainer_agenda($id, $order_by = null, $limit = null, $offset = null)
    {
        $this->db->select("*");
        $this->db->from("trainer_agenda");
        $this->db->where("trainer_info_id", $id);
        $this->db->where('edited_date !=', null);
        if ($order_by)
            $this->db->order_by("date_time", $order_by);
        else
            $this->db->order_by("date_time", "DESC");
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        elseif ($limit)
            $this->db->limit($limit);


        return $this->db->get();
    }

    public function get_all_agendas($id)
    {
        $this->db->select("t.*,g.*,t.id as agenda_id");
        $this->db->from("trainer_agenda t");
        $this->db->where("trainer_info_id", $id);
        $this->db->join('general_reg_info g', 'g.id=t.appointed_by');
        return $this->db->get();
    }

    public function get_all_total_agendas($id)
    {
        $query = $this->db->query("SELECT
                                            general_reg_info.id,
                                            general_reg_info.name,
                                            general_reg_info.is_company,
                                            general_reg_info.user_login_id,
                                            general_reg_info.created_on,
                                            general_reg_info.is_required,
                                            general_reg_info.is_deleted,
                                            trainer_info.id,
                                            trainer_info.general_info_id,
                                            trainer_info.removed_days,
                                            trainer_info.removed_times,
                                            trainer_info.is_deleted,
                                            trainer_agenda.id,
                                            trainer_agenda.trainer_info_id,
                                            trainer_agenda.appointment_name,
                                            trainer_agenda.location,
                                            trainer_agenda.longitude,
                                            trainer_agenda.latitude,
                                            trainer_agenda.date_time,
                                            trainer_agenda.appointment_description,
                                            trainer_agenda.is_confirm,
                                            trainer_agenda.is_edited,
                                            trainer_agenda.appoint_date,
                                            trainer_agenda.is_appointed,
                                            trainer_agenda.appointed_by,
                                            trainer_agenda.google_event_id,
                                            trainer_agenda.outlook_status,
                                            trainer_agenda.edited_date,
                                            trainer_agenda.is_deleted,
                                             trainer_agenda.block,
                                             (select trainer_agenda_time.work_time from trainer_agenda_time
 where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time limit 1 ) as start_date,(select trainer_agenda_time.work_time from trainer_agenda_time
 where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time DESC limit 1 ) as end_date
                                            FROM
                                            trainer_info
                                             JOIN general_reg_info ON trainer_info.general_info_id = general_reg_info.id
                                             JOIN trainer_agenda ON trainer_info.id = trainer_agenda.trainer_info_id
                                            WHERE trainer_agenda.is_appointed != 0
                                          AND trainer_info.general_info_id = " . $id );

        //INNER JOIN trainer_agenda_time ON trainer_agenda.id = trainer_agenda_time.trainer_agenda_id

        return $query->result_array();
    }


    public function update_icloud_status($agendas)
    {
        $result = $this->db->update_batch('trainer_agenda', $agendas, 'id');
        return $result;
    }

    public function get_all_total_agendas_with_appointment_id($id, $appointment_id)
    {
        if (!empty($id) AND !empty($appointment_id)) {
            $ids = "trainer_info.general_info_id = $id AND trainer_agenda.id = $appointment_id";
        }

        $query = $this->db->query("SELECT
                                            general_reg_info.id,
                                            general_reg_info.name,
                                            general_reg_info.is_company,
                                            general_reg_info.user_login_id,
                                            general_reg_info.created_on,
                                            general_reg_info.is_required,
                                            general_reg_info.is_deleted,
                                            trainer_info.id,
                                            trainer_info.general_info_id,
                                            trainer_info.removed_days,
                                            trainer_info.removed_times,
                                            trainer_info.is_deleted,
                                            trainer_agenda.id,
                                            trainer_agenda.trainer_info_id,
                                            trainer_agenda.appointment_name,
                                            trainer_agenda.location,
                                            trainer_agenda.longitude,
                                            trainer_agenda.latitude,
                                            trainer_agenda.date_time,
                                            trainer_agenda.appointment_description,
                                            trainer_agenda.is_confirm,
                                            trainer_agenda.is_edited,
                                            trainer_agenda.appoint_date,
                                            trainer_agenda.is_appointed,
                                            trainer_agenda.appointed_by,
                                            trainer_agenda.google_event_id,
                                            trainer_agenda.outlook_status,
                                            trainer_agenda.edited_date,
                                            trainer_agenda.is_deleted,
                                            (select trainer_agenda_time.work_time from trainer_agenda_time
 where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time limit 1 ) as start_date,
											(select trainer_agenda_time.work_time from trainer_agenda_time
 where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time DESC limit 1 ) as end_date,
 (select document_name from document where trainer_agenda_id  = trainer_agenda.id ) as document
                                            FROM
                                            trainer_info
                                            INNER JOIN general_reg_info ON trainer_info.general_info_id = general_reg_info.id
                                            INNER JOIN trainer_agenda ON trainer_info.id = trainer_agenda.trainer_info_id
                                            WHERE
                                            " . $ids);
        return $query->row_array();
    }
	public function getAcceptedAppointments($trainer_id,$limit = 0,$offset = 0) {
		if($limit == 0 && $offset == 0) {}
		else {
			$this->db->limit($limit,$offset);
		}
		$accepted_appointments = $this->db->select("*,(select trainer_agenda_time.work_time from trainer_agenda_time
 where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time limit 1 ) as start_event_time,
											(select trainer_agenda_time.work_time from trainer_agenda_time
 where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time DESC limit 1 ) as end_event_time")
		->from("trainer_agenda")
		->where("is_confirm",1)
		->where("trainer_info_id",$trainer_id)
		->get()
		->result_array();
		return $accepted_appointments;
	}
	public function getTrainerAcceptedAppointments($company_id,$limit = 0,$offset = 0) {
		if($limit == 0 && $offset == 0) {}
		else {
			$this->db->limit($limit,$offset);
		}
		$accepted_appointments = $this->db->select("*,
													(select trainer_agenda_time.work_time from trainer_agenda_time
													where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time limit 1 ) as start_event_time,
													(select trainer_agenda_time.work_time from trainer_agenda_time
													where trainer_agenda.id = trainer_agenda_time.trainer_agenda_id order by trainer_agenda_time.work_time DESC limit 1 ) as end_event_time,
													(SELECT general_info_id FROM trainer_info WHERE id = trainer_agenda.trainer_info_id) as general_id
													")
		->from("trainer_agenda")
		->where("is_confirm",1)
		->where("appointed_by",$company_id)
		->get()
		->result_array();
		return $accepted_appointments;
	}
}

?>