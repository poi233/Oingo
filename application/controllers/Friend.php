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
		$this->load->helper('url');
	}

	public function index()
	{
		$data['title'] = 'Friend';
		$data['friends'] = $this->Friend_model->get_my_friends();
		$this->load->view('header', $data);
		$this->load->view('friend', $data);
		$this->load->view('footer');

	}

}
