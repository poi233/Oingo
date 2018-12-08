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
		return $filter;
	}

	public function delete_filter($filter_id)
	{
		$this->db->query("delete from Filter where filter_id=?", array($filter_id));
	}


	public function get_my_active_filter() {
		$filter_sql = "select * from Filter left join Tag using (tag_id) left join State using(state_id) where user_id = ? and state_id = ?";
		$filters = $this->db->query($filter_sql, array($this->session->userdata("user_id"), $this->session->userdata("state_id")));
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

}
