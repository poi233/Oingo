<?php
/**
 * Created by PhpStorm.
 * User: puyihao
 * Date: 2018/12/10
 * Time: 10:19
 */

class Comment_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function make_comment($data)
	{
		$this->db->query("insert into Comment(note_id, user_id, content) value (?,?,?)",
			array($data['note_id'], $data['user_id'], $data['content']));
		$comment = $this->db->query("select account, content, post_time from Comment join User using (user_id) where comment_id = (select max(comment_id) from Comment)");
		$comment_data['account'] = $comment->row()->account;
		$comment_data['content'] = $comment->row()->content;
		$comment_data['post_time'] = $comment->row()->post_time;
		return $comment_data;
	}

	public function delete_comment($comment_id)
	{
		$this->db->query("delete from Comment where comment_id = ?", array($comment_id));
	}
}
