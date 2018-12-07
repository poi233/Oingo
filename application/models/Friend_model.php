<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/5
 * Time: 22:43
 */

class Friend_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function get_my_friends()
	{
		$user_id = $this->session->userdata("user_id");
		$sql = "
		  	select (case when user1_id = ? then user2_id else user1_id end) user_id,                        
			(case when user1_id = ? then user2_name else user1_name end) user_name                     
			from Friend                  
			where (user1_id = ? or user2_id = ?) and status=1";
		$friends = $this->db->query($sql, array($user_id, $user_id, $user_id, $user_id));
		return $friends;
	}

	public function is_friend($user1_id, $user2_id)
	{
		if ($user1_id > $user2_id) {
			return $this->is_friend($user2_id, $user1_id);
		}
		$sql = "select * from Friend where user1_id = ? and user2_id = ?";
		$relation = $this->db->query($sql, array($user1_id, $user2_id));
		if ($relation->num_rows() == 0) {
			return 1;//not friend
		} else {
			$status = $relation->row()->status;
			if ($status == 0) {
				return 2; //already applied;
			} else if ($status == 1) {
				return 3; //already friend
			} else if ($status == 2) {
				return 4; //declined;
			} else if ($status == 3) {
				return 5; //blocked;
			} else {
				return false;
			}
		}
	}

	public function add_friend($user1_id, $user2_id)
	{
		if ($user1_id > $user2_id) {
			$this->add_friend($user2_id, $user1_id);
		}
		$this->db->trans_start();
		$get_name_sql = "select account from User where user_id=?";
		$user1_name = $this->db->query($get_name_sql, array($user1_id))->row()->account;
		$user2_name = $this->db->query($get_name_sql, array($user2_id))->row()->account;
		$sql = "insert into Friend values (?,?,0,?,?,?)";
		$this->db->query($sql, array($user1_id, $user2_id, $this->session->userdata("user_id"), $user1_name, $user2_name));
		$this->db->trans_complete();
	}

	public function readd_friend($user1_id, $user2_id)
	{
		if ($user1_id > $user2_id) {
			$this->readd_friend($user2_id, $user1_id);
		}
		$sql = "update Friend set status=0, action_user_id=? where user1_id=? and user2_id=?";
		$this->db->query($sql, array($this->session->userdata("user_id"), $user1_id, $user2_id));
	}

}
