<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/2
 * Time: 01:12
 */

class Note_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function get_states()
	{
		return $this->db
			->select('*')
			->from('State')
			->get();
	}

	public function get_tags()
	{
		return $this->db
			->select('*')
			->from('Tag')
			->get();
	}


	public function get_current_state()
	{
		$state_name = $this->db
			->select('state_name')
			->from('State')
			->join('User', 'User.state_id=State.state_id')
			->where('State.state_id=' . $this->session->userdata("state_id"))
			->get()
			->row()
			->state_name;
		return $state_name;
	}

	public function add_new_note($data)
	{
		$this->db->trans_start();
		$this->db->insert(
			'Location',
			array(
				"longitude" => $data['longitude'],
				"latitude" => $data['latitude'],
				"location_name" => $data['location_name'],
			));
		$location_id = $this->db
			->query('select max(location_id) as latest_location from Location')
			->row()->latest_location;
		$repetition = "";
		foreach ($data['repetition'] as $repeat) {
			$repetition .= $repeat;
		}
		$this->db->insert(
			'Schedule',
			array(
				"start_time" => $data['start_time'],
				"end_time" => $data['end_time'],
				"start_date" => $data['start_date'],
				"end_date" => $data['end_date'],
				"repetition" => $repetition,
			)
		);
		$schedule_id = $this->db
			->query('select max(schedule_id) as latest_schedule from Schedule')
			->row()->latest_schedule;
		$this->db->insert(
			'Note',
			array(
				"location_id" => $location_id,
				"schedule_id" => $schedule_id,
				"user_id" => $this->session->userdata('user_id'),
				"radius" => $data['radius'],
				"content" => $data['content'],
				"post_time" => date("Y:m:d H:i:s"),
				"permission" => $data['permission'],
				"allow_comment" => $data['allow_comment'],
			)
		);
		$note_id = $this->db
			->query('select max(note_id) as latest_note from Note')
			->row()->latest_note;
		foreach ($data['tag_id'] as $tag_id) {
			$this->db->insert(
				'Note_Tag',
				array(
					"note_id" => $note_id,
					"tag_id" => $tag_id,
				)
			);
		}
		$this->db->trans_complete();
	}

	public function modify_note($data)
	{
		$this->db->trans_start();
		$to_modify = $this->db->query("select schedule_id, location_id 
									from Note where note_id=" . $data["note_id"]);
		$schedule_id = $to_modify->row()->schedule_id;
		$location_id = $to_modify->row()->location_id;
		$repetition = "";
		foreach ($data['repetition'] as $repeat) {
			$repetition .= $repeat;
		}
		$this->db->update('Schedule', array("start_time" => $data["start_time"],
			"end_time" => $data["end_time"],
			"start_date" => $data["start_date"],
			"end_date" => $data["end_date"],
			"repetition" => $repetition), array('schedule_id' => $schedule_id));
		$this->db->update('Location', array("location_name" => $data["location_name"],
			"latitude" => $data["latitude"],
			"longitude" => $data["longitude"]), array('location_id' => $location_id));
		$this->db->update('Note', array(
			"radius" => $data['radius'],
			"content" => $data['content'],
			"post_time" => date("Y:m:d H:i:s"),
			"permission" => $data['permission'],
			"allow_comment" => $data['allow_comment'],), array('note_id' => $data['note_id']));
		$this->db->query("delete from Note_Tag where note_id=" . $data['note_id']);
		foreach ($data['tag_id'] as $tag_id) {
			$this->db->insert(
				'Note_Tag',
				array(
					"note_id" => $data["note_id"],
					"tag_id" => $tag_id,
				)
			);
		}
		$this->db->trans_complete();

	}

	public function get_my_Note()
	{
		$notes = $this->db->query("select *
			from Note join Schedule using(schedule_id) join Location using (location_id)
			where user_id = " . $this->session->userdata("user_id"));
		$data = array();
		foreach ($notes->result() as $item) {
			$note['note_id'] = $item->note_id;
			$note['radius'] = $item->radius;
			$note['start_time'] = $item->start_time;
			$note['end_time'] = $item->end_time;
			$note['start_date'] = $item->start_date;
			$note['end_date'] = $item->end_date;
			$note['latitude'] = $item->latitude;
			$note['longitude'] = $item->longitude;
			$note['location_name'] = $item->location_name;
			$note['content'] = $item->content;
			$note['permission'] = $item->permission;
			$note['allow_comment'] = $item->allow_comment;
			$note['repetition'] = $item->repetition;
			$note['tag'] = $this->db->query("select tag_name from Note_Tag join Tag using(tag_id) where note_id=" . $item->note_id);
			array_push($data, $note);
		};
		return $data;
	}

	public function get_all_note()
	{
		$notes = $this->db->query("select *
			from Note join Schedule using(schedule_id) join Location using(location_id)");
		$data = array();
		foreach ($notes->result() as $item) {
			$note['note_id'] = $item->note_id;
			$note['radius'] = $item->radius;
			$note['start_time'] = $item->start_time;
			$note['end_time'] = $item->end_time;
			$note['start_date'] = $item->start_date;
			$note['end_date'] = $item->end_date;
			$note['latitude'] = $item->latitude;
			$note['longitude'] = $item->longitude;
			$note['location_name'] = $item->location_name;
			$note['content'] = $item->content;
			$note['permission'] = $item->permission;
			$note['allow_comment'] = $item->allow_comment;
			$note['repetition'] = $item->repetition;
			$note['tag'] = $this->db->query("select tag_name from Note_Tag join Tag using(tag_id) where note_id=" . $item->note_id);
			array_push($data, $note);
		};
		return $data;
	}

	public function get_note_by_id($note_id)
	{
		$note_search = $this->db->query("select *
			from Note join Schedule using(schedule_id) join Location using(location_id)
			where note_id=" . $note_id);
		$item = $note_search->row();
		$note['note_id'] = $item->note_id;
		$note['radius'] = $item->radius;
		$note['start_time'] = $item->start_time;
		$note['end_time'] = $item->end_time;
		$note['start_date'] = $item->start_date;
		$note['end_date'] = $item->end_date;
		$note['latitude'] = $item->latitude;
		$note['longitude'] = $item->longitude;
		$note['location_name'] = $item->location_name;
		$note['content'] = $item->content;
		$note['permission'] = $item->permission;
		$note['allow_comment'] = $item->allow_comment;
		$note['repetition'] = $item->repetition;
		$note['tag_id'] = $this->db->query("select tag_id from Note_Tag join Tag using(tag_id) where note_id=" . $item->note_id)->result_array();
		return $note;
	}

	public function delete_note($note_id)
	{
		$this->db->trans_start();
		$to_delete = $this->db->query("select schedule_id, location_id 
									from Note join Schedule using (schedule_id) join Location using (location_id)
									where note_id=" . $note_id);
		$schedule_id = $to_delete->row()->schedule_id;
		$location_id = $to_delete->row()->location_id;
		$this->db->query("delete from Location where location_id=" . $location_id);
		$this->db->query("delete from Schedule where schedule_id=" . $schedule_id);
		$this->db->query("delete from Note where note_id=" . $note_id);
		$this->db->query("delete from Note_Tag where note_id=" . $note_id);
		$this->db->trans_complete();
	}

}
