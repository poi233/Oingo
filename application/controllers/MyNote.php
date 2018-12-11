<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/3
 * Time: 22:27
 */

class MyNote extends CI_Controller
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
			$data['title'] = 'My Note';
			$data['tags'] = $this->Note_model->get_tags();
			$data['notes'] = $this->Note_model->get_my_Note();
			$data['friends'] = $this->Friend_model->get_my_friends();
			$this->load->view('header', $data);
			$this->load->view('my_note', $data);
			$this->load->view('footer');
		}

	}

	public function add_new_note()
	{
		$data = array(
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
		$this->Note_model->add_new_note($data);
		redirect("MyNote");
	}

	public function delete_note($note_id)
	{
		$this->Note_model->delete_note($note_id);
		redirect("MyNote");
	}

	public function get_note_info()
	{
		$note = $this->Note_model->get_note_by_id($_POST['note_id']);
		echo json_encode($note);
	}

	public function modify_note()
	{
		$data = array(
			'note_id' => $_POST['note_id'],
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
		$this->Note_model->modify_note($data);
		redirect("MyNote");
	}


}
