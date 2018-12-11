<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/7
 * Time: 20:29
 */

class Filter_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function add_new_filter($data)
	{
		$repetition = null;
		if ($data['repetition'] != null) {
			$repetition = "";
			foreach ($data['repetition'] as $repeat) {
				$repetition .= $repeat;
			}
		}
		$this->db->insert(
			'Filter',
			array(
				"longitude" => $data['longitude'],
				"latitude" => $data['latitude'],
				"start_time" => $data['start_time'],
				"end_time" => $data['end_time'],
				"start_date" => $data['start_date'],
				"end_date" => $data['end_date'],
				"repetition" => $repetition,
				"user_id" => $this->session->userdata('user_id'),
				"radius" => $data['radius'],
				"tag_id" => $data['tag_id'],
				"state_id" => $data['state_id'],
				"from_who" => $data['from_who'],
			)
		);
	}

	public function modify_filter($data)
	{
		$repetition = null;
		if ($data['repetition'] != null) {
			$repetition = "";
			foreach ($data['repetition'] as $repeat) {
				$repetition .= $repeat;
			}
		}
		$this->db->update('Filter', array(
			"radius" => $data['radius'],
			"start_time" => $data["start_time"],
			"end_time" => $data["end_time"],
			"start_date" => $data["start_date"],
			"end_date" => $data["end_date"],
			"repetition" => $repetition,
			"latitude" => $data["latitude"],
			"longitude" => $data["longitude"],
			"tag_id" => $data["tag_id"],
			"state_id" => $data["state_id"],
			"from_who" => $data["from_who"]), array('filter_id' => $data['filter_id']));

	}

	public function get_my_filter()
	{
		$filter_sql = "select * from Filter left join Tag using (tag_id) left join State using(state_id) where user_id = ?";
		$filters = $this->db->query($filter_sql, array($this->session->userdata("user_id")));
		$data = array();
		foreach ($filters->result() as $item) {
			$filter['filter_id'] = $item->filter_id;
			$filter['radius'] = $item->radius;
			$filter['start_time'] = $item->start_time;
			$filter['end_time'] = $item->end_time;
			$filter['start_date'] = $item->start_date;
			$filter['end_date'] = $item->end_date;
			$filter['latitude'] = $item->latitude;
			$filter['longitude'] = $item->longitude;
			$filter['repetition'] = $item->repetition;
			$filter['tag_id'] = $item->tag_id;
			$filter['tag'] = $item->tag_name;
			$filter['state_id'] = $item->state_id;
			$filter['state'] = $item->state_name;
			$filter['from_who'] = $item->from_who;
			$filter['active'] = $item->active;
			array_push($data, $filter);
		};
		return $data;
	}

	public function get_all_filter()
	{
		$filter_sql = "select * from Filter join Tag using (tag_id) join State using(state_id)";
		$filters = $this->db->query($filter_sql);
		$data = array();
		foreach ($filters->result() as $item) {
			$filter['filter_id'] = $item->filter_id;
			$filter['radius'] = $item->radius;
			$filter['start_time'] = $item->start_time;
			$filter['end_time'] = $item->end_time;
			$filter['start_date'] = $item->start_date;
			$filter['end_date'] = $item->end_date;
			$filter['latitude'] = $item->latitude;
			$filter['longitude'] = $item->longitude;
			$filter['repetition'] = $item->repetition;
			$filter['tag_id'] = $item->tag_id;
			$filter['tag'] = $item->tag_name;
			$filter['state_id'] = $item->state_id;
			$filter['state'] = $item->state_name;
			$filter['from_who'] = $item->from_who;
			array_push($data, $filter);
		};
		return $data;
	}

	public function get_filter_by_id($filter_id)
	{
		$filter_search = $this->db->query("select *
			from Filter where filter_id=?", array($filter_id));
		$item = $filter_search->row();
		$filter['filter_id'] = $item->filter_id;
		$filter['radius'] = $item->radius;
		$filter['start_time'] = $item->start_time;
		$filter['end_time'] = $item->end_time;
		$filter['start_date'] = $item->start_date;
		$filter['end_date'] = $item->end_date;
		$filter['latitude'] = $item->latitude;
		$filter['longitude'] = $item->longitude;
		$filter['repetition'] = $item->repetition;
		$filter['tag_id'] = $item->tag_id;
		$filter['state_id'] = $item->state_id;
		$filter['from_who'] = $item->from_who;
		$filter['active'] = $item->active;
		return $filter;
	}

	public function delete_filter($filter_id)
	{
		$this->db->query("delete from Filter where filter_id=?", array($filter_id));
	}

	function get_week($date)
	{
		$date_str = date('Y-m-d', strtotime($date));
		$arr = explode("-", $date_str);
		$year = $arr[0];
		$month = sprintf('%02d', $arr[1]);
		$day = sprintf('%02d', $arr[2]);
		$hour = $minute = $second = 0;
		$strap = mktime($hour, $minute, $second, $month, $day, $year);
		$number_wk = date("w", $strap);
		return $number_wk == 0 ? 7 : $number_wk;
	}


	public function get_my_active_filter() {
		$user_id = $this->session->userdata("user_id");
		$state_id = $this->session->userdata("state_id");
		$lat = $this->session->userdata("latitude");
		$lng = $this->session->userdata("longitude");
		list($date, $time) = explode("T", $this->session->userdata("current_time"));
		$day = $this->get_week($date);

		$candidate_filter_sql = "select *
		from Filter left join Tag using (tag_id) left join State using(state_id)
 		where (Filter.state_id = -1 or Filter.state_id = 0 or (Filter.state_id = ".$this->db->escape($state_id)."))
		  and (Filter.start_date is NULL or Filter.start_date <= " . $this->db->escape($date) . ") and (Filter.end_date is NULL or Filter.end_date >= " . $this->db->escape($date) . ") 
		  and (Filter.start_time is NULL or Filter.start_time <= " . $this->db->escape($time) . ") and (Filter.end_time is NULL or Filter.end_time >= " . $this->db->escape($time) . ")
		  and (Filter.repetition is NULL or Filter.repetition like '%" . $this->db->escape_like_str($day) . "%')
		  and Filter.active = 1
		  and Filter.user_id =".$this->db->escape($user_id);



//		$filter_sql = "select * from Filter left join Tag using (tag_id) left join State using(state_id)
//						where user_id = ? and (state_id = -1 or state_id = ?) and active = 1";
//		$filters = $this->db->query($filter_sql, array($this->session->userdata("user_id"), $this->session->userdata("state_id")));
		$filters = $this->db->query($candidate_filter_sql);
		$data = array();
		foreach ($filters->result() as $item) {
			$filter['filter_id'] = $item->filter_id;
			$filter['radius'] = $item->radius;
			$filter['start_time'] = $item->start_time;
			$filter['end_time'] = $item->end_time;
			$filter['start_date'] = $item->start_date;
			$filter['end_date'] = $item->end_date;
			$filter['latitude'] = $item->latitude;
			$filter['longitude'] = $item->longitude;
			$filter['repetition'] = $item->repetition;
			$filter['tag_id'] = $item->tag_id;
			$filter['tag'] = $item->tag_name;
			$filter['state_id'] = $item->state_id;
			$filter['state'] = $item->state_name;
			$filter['from_who'] = $item->from_who;
			$filter['active'] = $item->active;
			array_push($data, $filter);
		};
		return $data;
	}

	public function toggle_filter($id)
	{
		$toggle_sql = "update Filter set active=(
						case when (active=1) then 0 
						when (active=0) then 1 
						else 0 end) 
						where filter_id = ?";
		$this->db->query($toggle_sql, array($id));
	}

}
