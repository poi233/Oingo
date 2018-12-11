<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/10
 * Time: 10:20
 */

class Comment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function make_comment()
	{
		echo json_encode($this->Comment_model->make_comment($_POST));
	}

}
