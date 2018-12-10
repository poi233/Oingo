<?php

/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/11/29
 * Time: 22:08
 */
class User_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	public function get_user()
	{
		$query = $this->db->get('User');
		return $query->result_array();
	}

	public function get_user_by_account_and_password($account, $password)
	{
		return $result = $this->db->get_where('User', array('account' => $account,
			'password' => $password));
	}

	public function get_by_id($user_id)
	{
		$result = $this->db->get_where('User', array('user_id' => $user_id));
		return $result;
	}


	public function get_by_account($account)
	{
		$result = $this->db->get_where('User', array('account' => $account));
		return $result;
	}

	public function set_state($data)
	{
		$this->db->update('User', array('state_id' => $data['state_id']), array('user_id' => $data['user_id']));
		$this->session->set_userdata("state_id", $data['state_id']);
		$this->session->set_userdata("longitude", $data['longitude']);
		$this->session->set_userdata("latitude", $data['latitude']);
		$this->session->set_userdata("current_time", $data['current_time']);
	}

	public function update_user_info($data)
	{
		$this->db->update('User', $data, array('user_id' => $this->session->userdata("user_id")));
	}


	public function insert_record($data){
		return $this->db->insert('Record',
			array("user_id" => $data['user_id'],
			"longitude" => $data['longitude'],
			"latitude" => $data['latitude'],
			"save_time" => date("Y:m:d H:i:s"),
			));
	}

	public function to_login($user_account)
	{
		$row = $this->User_model->get_by_account($user_account)->row();
		$this->session->set_userdata('user_id', $row->user_id);
		$this->session->set_userdata('state_id', $row->state_id);
		$this->session->set_userdata('account', $row->account);
		$this->session->set_userdata('current_time', date("Y-m-d")."T".date("H:i:s"));
		$this->session->set_userdata('latitude', 40.69289);
		$this->session->set_userdata('longitude', -73.98488);
		$this->session->set_userdata('lastActiveTime', time());
	}

	public function login_authorize()
	{
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			return FALSE;
		}
		$current = time();
		$lastActiveTime = $this->session->userdata('lastActiveTime');
		$timeSpan = $current - $lastActiveTime;
		if ($timeSpan > 3000) {
			$this->logout();
			return FALSE;
		}
		$this->session->set_userdata('lastActiveTime', $current);
		return $user_id;
	}

	public function logout()
	{
		$this->session->sess_destroy();
	}

	public function register($data)
	{
		return $this->db->insert('User', array("account" => $data['register_account'],
			"name" => $data['register_name'],
			"password" => sha1($data['register_password'])));
	}

}
