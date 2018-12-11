<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/3
 * Time: 02:04
 */

class User extends CI_Controller
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
			$data['title'] = 'Profile';
			$data['states'] = $this->Note_model->get_states();
			$data['user_info'] = $this->User_model->get_by_id($this->session->userdata("user_id"))->row();
			$data['friends'] = $this->Friend_model->get_my_friends();
			$this->load->view('header', $data);
			$this->load->view('profile', $data);
			$this->load->view('footer');
		}
	}

	public function update()
	{
		$data = array('sex' => $_POST['sex'],
			'state_id' => $_POST['state_id'],
			'name' => $_POST['name'],
			'detail' => $_POST['detail'],
			'birth' => $_POST['birth']);
		$this->User_model->update_user_info($data);
		$this->session->set_userdata("state_id", $_POST['state_id']);
		redirect('User');
	}
}
