<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/7
 * Time: 12:48
 */

class Filter extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['title'] = 'Filter';
		$data['tags'] = $this->Note_model->get_tags();
		$data['friends'] = $this->Friend_model->get_my_friends();
		$data['states'] = $this->Note_model->get_states();
		$data['filters'] = $this->Filter_model->get_my_filter();
		$this->load->view('header', $data);
		$this->load->view('filter', $data);
		$this->load->view('footer');
	}

	public function add_new_filter()
	{
		$data = array(
			'start_date' => $_POST['start_date'] != "" ? $_POST['start_date'] : null,
			'end_date' => $_POST['end_date'] != "" ? $_POST['end_date'] : NULL,
			'start_time' => $_POST['start_time'] != "" ? $_POST['start_time'] : NULL,
			'end_time' => $_POST['end_time'] != "" ? $_POST['end_time'] : NULL,
			'tag_id' => $_POST['tag_id'],
			'state_id' => $_POST['state_id'],
			'repetition' => array_key_exists('repetition', $_POST) ? $_POST['repetition'] : null,
			'latitude' => $_POST['latitude'],
			'longitude' => $_POST['longitude'],
			'radius' => array_key_exists('radius', $_POST) ? null : $_POST['radius'],
			'from_who' => $_POST['from_who'],);
		$this->Filter_model->add_new_filter($data);
		redirect("Filter");
	}

	public
	function delete_filter($filter_id)
	{
		$this->Filter_model->delete_filter($filter_id);
		redirect("Filter");
	}

	public
	function get_filter_info()
	{
		$filter = $this->Filter_model->get_filter_by_id($_POST['filter_id']);
		echo json_encode($filter);
	}

	public
	function modify_filter()
	{
		$data = array(
			'filter_id' => $_POST['filter_id'],
			'start_date' => $_POST['start_date'],
			'end_date' => $_POST['end_date'],
			'start_time' => $_POST['start_time'],
			'end_time' => $_POST['end_time'],
			'tag_id' => $_POST['tag_id'],
			'repetition' => $_POST['repetition'],
			'latitude' => $_POST['latitude'],
			'longitude' => $_POST['longitude'],
			'content' => $_POST['content'],
			'location_name' => $_POST['location_name'],
			'radius' => $_POST['radius'],
			'permission' => $_POST['permission'],
			'allow_comment' => $_POST['allow_comment']);
		$this->Filter_model->modify_filter($data);
		redirect("Filter");
	}


}
