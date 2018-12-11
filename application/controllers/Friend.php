<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/5
 * Time: 10:57
 */

class Friend extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->session->userdata("user_id") == null) {
			redirect("");
		} else {
			$data['title'] = 'Friend';
			$data['friends'] = $this->Friend_model->get_my_friends();
			$data['invite_message'] = $this->Friend_model->get_invite_message();
			$this->load->view('header', $data);
			$this->load->view('friend', $data);
			$this->load->view('footer');
		}

	}

	public function search()
	{
		$account = $_POST['account'];
		$user = $this->User_model->get_by_account($account);
		if ($user->num_rows() == 0) {
			echo 0; //user not exist;
		} else {
			$user_id = $user->row()->user_id;
			echo $this->Friend_model->is_friend($user_id, $this->session->userdata('user_id'));
		}
	}

	public function add_friend($account)
	{
		$user = $this->User_model->get_by_account($account);
		assert($user->num_rows() == 1);
		$this->Friend_model->add_friend($user->row()->user_id, $this->session->userdata('user_id'));
		redirect("Friend");
	}

	public function readd_friend($account)
	{
		$user = $this->User_model->get_by_account($account);
		assert($user->num_rows() == 1);
		$this->Friend_model->readd_friend($user->row()->user_id, $this->session->userdata('user_id'));
		redirect("Friend");
	}

	public function accept_friend($user_id)
	{
		$this->Friend_model->accept_friend($user_id, $this->session->userdata("user_id"));
		redirect("Friend");
	}

	public function decline_friend($user_id)
	{
		$this->Friend_model->decline_friend($user_id, $this->session->userdata("user_id"));
		redirect("Friend");
	}

	public function delete_friend($user_id)
	{
		$this->Friend_model->delete_friend($user_id, $this->session->userdata("user_id"));
		redirect("Friend");
	}


}
