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
		$friends = $this->db->query("
			select (case when user1_id = ".$user_id." then user2_id else user1_id end) user_id,                        
			(case when user1_id = ".$user_id." then user2_name else user1_name end) user_name                     
			from Friend                  
			where (user1_id = ".$user_id." or user2_id = ".$user_id.") and status=1");
		return $friends;
	}
}
