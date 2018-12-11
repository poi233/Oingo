<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/8
 * Time: 13:36
 */

class Filter_note_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
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

	public function get_note_by_user()
	{
		$user_id = $this->session->userdata("user_id");
		$state_id = $this->session->userdata("state_id");
		$lat = $this->session->userdata("latitude");
		$lng = $this->session->userdata("longitude");
		list($date, $time) = explode("T", $this->session->userdata("current_time"));
		$day = $this->get_week($date);

		$candidate_note_sql = "select Note.note_id, Note_Tag.tag_id, Note.user_id, Location.latitude, Location.longitude
		from Note join Location using(location_id) join Schedule using(schedule_id) join User using(user_id) left join Note_Tag using (note_id)
		where
		  Schedule.start_date <= " . $this->db->escape($date) . " and Schedule.end_date >= " . $this->db->escape($date) . " 
		  and Schedule.start_time <= " . $this->db->escape($time) . " and Schedule.end_time >= " . $this->db->escape($time) . "
		  and Schedule.repetition like '%" . $this->db->escape_like_str($day) . "%'
		  and (Note.permission = 0 or (Note.permission = 1 and (Note.user_id in (
																select (case when user1_id = " . $this->db->escape_str($user_id) . " then user2_id else user1_id end) user_id													
																from Friend                  
												  				where (user1_id = " . $this->db->escape_str($user_id) . " 
												  				or user2_id = " . $this->db->escape_str($user_id) . ") and status=1) 
												  				or Note.user_id = " . $this->db->escape_str($user_id) . "
																)))
		  and (6371000 * acos(
					cos( radians(Location.latitude) ) 
				  * cos( radians( " . $this->db->escape($lat) . " ) ) 
				  * cos( radians( " . $this->db->escape($lng) . " ) - radians(Location.longitude) ) 
				  + sin( radians(Location.latitude) ) 
				  * sin( radians( " . $this->db->escape($lat) . " ) )
					)) < Note.radius";

		$candidate_filter_sql = "select Filter.tag_id, Filter.from_who, Filter.latitude, Filter.longitude, Filter.radius
		from Filter left join Tag using (tag_id) left join State using(state_id)
 		where (Filter.state_id = -1 or Filter.state_id = 0 or (Filter.state_id = ".$this->db->escape($state_id)."))
		  and (Filter.start_date is NULL or Filter.start_date <= " . $this->db->escape($date) . ") and (Filter.end_date is NULL or Filter.end_date >= " . $this->db->escape($date) . ") 
		  and (Filter.start_time is NULL or Filter.start_time <= " . $this->db->escape($time) . ") and (Filter.end_time is NULL or Filter.end_time >= " . $this->db->escape($time) . ")
		  and (Filter.repetition is NULL or Filter.repetition like '%" . $this->db->escape_like_str($day) . "%')
		  and Filter.active = 1";

		$this->db->trans_start();
		if ($this->db->query($candidate_filter_sql)->num_rows() == 0) {
			$note_id = "select distinct n.note_id from (" . $candidate_note_sql . ") n";
		} else {
			$note_id = "select distinct note_id
		from (" . $candidate_note_sql . ") n, (" . $candidate_filter_sql . ") f
		where (n.tag_id is null or f.tag_id = -1 or n.tag_id = f.tag_id)
		and (n.user_id = " . $this->db->escape_str($user_id) . " or f.from_who = 0 or (f.from_who = 1 and " . $this->db->escape_str($user_id) . " in (
							select (case when user1_id = n.user_id then user2_id else user1_id end) user_id                      
			from Friend                  
			where (user1_id = n.user_id or user2_id = n.user_id) and status=1)))
		and (6371000 * acos(
					cos( radians(n.latitude) ) 
				  * cos( radians(f.latitude) ) 
				  * cos( radians(f.longitude) - radians(n.longitude) ) 
				  + sin( radians(n.latitude) ) 
				  * sin( radians(f.latitude) )
					)) < f.radius";
		}
		$notes = $this->db->query("select *
			from Note join Schedule using(schedule_id) join Location using(location_id)
			where note_id in (" . $note_id . ")");
		$this->db->trans_complete();
		return $notes;
	}
}
