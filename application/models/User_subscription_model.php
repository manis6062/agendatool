<?php
	class User_subscription_Model extends MY_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_user_subscription($general_id)
		{
			$this->db->select_max('to_date');
			$this->db->from('user_subscriptions');
			$this->db->where('general_reg_info_id',$general_id);
			return $this->db->get();
		}
		
		public function get_subscription_detail($where = null)
		{
			$this->db->select('*');
			$this->db->from('trial_period');
			$this->db->where('id',3);
			$this->db->or_where('id',4);
			if($where)
				$this->db->where($where);
			return $this->db->get();
		}
		
		public function get_all_subscription($limit = null, $offset = null, $search_parameters = null)
		{
			$this->db->select('us.*,g.*,us.id as subscription_id');
			$this->db->from('user_subscriptions us');
			$this->db->join('general_reg_info g','us.general_reg_info_id = g.id');
			$this->db->order_by('from_date','DESC');
			if($limit && $offset)
				$this->db->limit($limit,$offset);
			elseif($limit)
				$this->db->limit($limit);
			if($search_parameters)
			{
				foreach($search_parameters as $key=>$search_parameter)
				{
					if($key == 'searchByName')
					{
						$this->db->like("g.name",$search_parameter,'both');
					}

					if($key == 'searchByAmount')
					{
						$this->db->like("us.amount",$search_parameter,'both');
					}

					if($key == 'searchByStartDate')
					{
						$this->db->like("us.from_date",$search_parameter,'both');
					}

					if($key == 'searchByExpiryDate')
					{
						$this->db->like("us.to_date",$search_parameter,'both');
					}
					
				}
			}

			return $this->db->get();
		}
		
		
		public function search_user($name = null)
		{
			$this->db->select("g.*,u.*");
			$this->db->from("general_reg_info g");
			$this->db->join("user_login u","u.id=g.user_login_id");
			$this->db->like('name',$name,'both');
			return $this->db->get();
		}
		
		public function get_specific_user_from_name($where = null)
		{
			$this->db->select("g.*,u.*,g.id as general_id");
			$this->db->from("general_reg_info g");
			$this->db->join("user_login u","u.id=g.user_login_id");
			$this->db->where($where);
			return $this->db->get();
		}
	}
?>