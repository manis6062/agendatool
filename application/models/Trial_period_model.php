<?php
class Trial_period_Model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_trial_method($user_type_id)
    {
        $this->db->select('*');
        $this->db->from('trial_period');
        $this->db->where('user_type_id', $user_type_id);
        return $this->db->get();
    }

    public function get_trial_periods($where = null)
    {
        $this->db->select('*');
        $this->db->from('trial_period');
        $this->db->where('id', 1);
        $this->db->or_where('id', 2);
        if ($where)
            $this->db->where($where);
        return $this->db->get();
    }


    public function get_individual_trial_period($user_id)
    {
        $this->db->select('*');
        $this->db->from('trial_period');
        $this->db->where('user_type_id', $user_id);
        return $this->db->get();
    }
}

?>