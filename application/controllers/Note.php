<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Note extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	public function index()
	{
		$data['title'] = 'Map';
		$data['states'] = $this->Note_model->get_states();
		$data['current_state'] = $this->Note_model->get_current_state();
		$data['notes'] = $this->Note_model->get_all_note();
		$data['friends'] = $this->Friend_model->get_my_friends();
		$this->load->view('header', $data);
		$this->load->view('map', $data);
		$this->load->view('footer');

	}

	public function get_state()
	{
		return $this->db->get('State');
	}

	public function set_state()
	{
		$data['user_id'] = $_POST['user_id'];
		$data['state_id'] = $_POST['state_id'];
		$data['latitude'] = $_POST['latitude'];
		$data['longitude'] = $_POST['longitude'];
		$this->User_model->set_state($data);
		$this->User_model->insert_record($data);
		echo TRUE;
	}


}
