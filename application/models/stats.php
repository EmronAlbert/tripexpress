<?php
Class Stats extends CI_Model
{
	function total_tickets($from, $to)
	{
		$this->db->select('COUNT(booking_id) as total_tickets');
		$this->db->from('bookings');
		$this->db->where('created_time >=', $from);
		$this->db->where('created_time <=', $to);
		$query = $this->db->get();
	 	if ($query->num_rows() > 0)
		{
		   $row = $query->row();
		   if(!empty($row->total_tickets))  return $row->total_tickets;
		   else return '0';
		}
	}

	function total_tickets_per_day($from, $to)
	{
		$this->db->select("date_format(created_time,'%Y-%m-%d') as month, COUNT(booking_id) as value", false);
		$this->db->from('bookings');
		$this->db->where('created_time >=', $from);
		$this->db->where('created_time <=', $to);
		$this->db->group_by("month");
		$query = $this->db->get();
		return $query->result();
		// 	if ($query->num_rows() > 0)
		// {
		//    $row = $query->row();
		//    if(!empty($row->total_tickets))  return $row->total_tickets;
		//    else return '0';
		// }
	}

	function total_income_one_way($from, $to)
	{
		$this->db->select('SUM(tours.start_price) as price');
		$this->db->from('bookings');
		$this->db->join('tours', 'bookings.tour_id = tours.tour_id');
		$this->db->where('bookings.created_time >=', $from);
		$this->db->where('bookings.created_time <=', $to);
		$query = $this->db->get();
	 	if ($query->num_rows() > 0)
		{
		   $row = $query->row();
		   if(!empty($row->price))  return $row->price;
		   else return '0.00';
		}
	}

	function total_income_round_trip($from, $to)
	{
		$this->db->select('SUM(tours.start_price) as price');
		$this->db->from('bookings');
		$this->db->join('tours', 'bookings.tour_back_id = tours.tour_id');
		$this->db->where('bookings.created_time >=', $from);
		$this->db->where('bookings.created_time <=', $to);
		$query = $this->db->get();
	 	if ($query->num_rows() > 0)
		{
		   $row = $query->row();
		   if(!empty($row->price))  return $row->price;
		   else return '0.00';
		}
	}
	function get_company_info()
	{
		$this->db->from('settings');
	 	$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row;
		}
	}
	function show_symbol($id){
		$this->db->select('symbol');
		$this->db->where('currency_id', $id);
		$query = $this->db->get('currency');
		if ($query->num_rows() > 0)
		{
		   $row = $query->row();
		   return $row->symbol;
		}
	}


}
?>
