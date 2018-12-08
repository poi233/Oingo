<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	/**
	 * Welcome constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['user'] = $this->User_model->login_authorize();
		if ($data['user'] != false) {
			redirect( 'Note');
		} else {
			$this->load->view('login');
		}
	}

	public function login()
	{
		$find_user = $this->User_model->get_user_by_account_and_password($_POST['login_account'], sha1($_POST['login_password']));
		if ($find_user->num_rows() == 0){
			echo $find_user->num_rows();
		} else {
			$this->User_model->to_login($_POST['login_account']);
			echo TRUE;
		}
	}

	public function register() {
		$find_user = $this->User_model->get_by_account($_POST['register_account']);
		if ($find_user->num_rows() > 0) {
			echo json_encode("duplicate_account");
		} else {
			if ($_POST['register_password'] != $_POST['register_confirm_password']) {
				echo json_encode("different_password");
			} else {
				$this->User_model->register($_POST);
				echo json_encode("ok");
			}
		}
	}

	public function logout()
	{
		$this->User_model->logout();
		redirect('');
	}
}
