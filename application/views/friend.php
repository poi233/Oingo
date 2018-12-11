<script>
	function search_friend() {
		var search_url = "<?= base_url("index.php/Friend/search") ?>";
		$.ajax({
			url: search_url,
			type: 'POST',
			data: {
				account: $("#inputSearch").val(),
			},
			error: function () {
				alert("ajax error");
			},  //错误执行方法
			success: function (data) {
				switch (parseInt(data)) {
					case 0:
						open_modal("#", "User doesn't exist", false);
						break;
					case 1:
						open_modal("<?= base_url("index.php/Friend/add_friend/") ?>" + $("#inputSearch").val(), "Add Friend", true);
						break;
					case 2:
						open_modal("#", "Friend invitation already submitted.", false);
						break;
					case 3:
						open_modal("#", "Already Friends.", false);
						break;
					case 4:
						open_modal("<?= base_url("index.php/Friend/readd_friend/") ?>" + $("#inputSearch").val(), "Resubmit add friend invitation.", true);
						break;
					case 5:
						open_modal("#", "Friend blocked.", false);
						break;
					default:
						alert("error");
						break;

				}
			} //成功执行方法
		});
	}

	function open_modal(url, text, show_confirm) {
		$('#url').val(url);//给会话中的隐藏属性URL赋值
		$("#modal_info").text(text);
		if (show_confirm) {
			$("#btn_modal_submit").show();
		} else {
			$("#btn_modal_submit").hide();
		}
		$('#delcfmModel').modal();
	}

	function urlSubmit() {
		var url = $.trim($("#url").val());//获取会话中的隐藏属性URL
		window.location.href = url;
	}

	function accept_friend(id) {
		window.location.href = "<?= base_url("index.php/Friend/accept_friend/")?>" + id;
	}

	function decline_friend(id) {
		window.location.href = "<?= base_url("index.php/Friend/decline_friend/")?>" + id;
	}

	function delete_friend(id) {
		window.location.href = "<?= base_url("index.php/Friend/delete_friend/")?>" + id;
	}

	function confirm_delete(id) {
		var r = confirm("Are you sure to delete?");
		if (r) {
			decline_friend(id);
		}
	}

</script>
<div class="container-fluid">
	<div class="row justify-content-md-center">
		<div class="col-md-3">
			<div class="card" id="friend_card">
				<div class="card-header">
					My Friends
				</div>
				<div class="card-body">
					<div>
						<label><b>Search New Friends:</b></label>
						<div class="form-row">
							<div class="col-md-9">
								<input class="form-control" id="inputSearch" placeholder="search new friends">
							</div>
							<div class="col-md-3">
								<button class="btn btn-primary" onclick="search_friend()">Add</button>
							</div>
						</div>
					</div>
					<div style="margin-top:10px;">
						<label><b>My Friends:</b></label>
						<?php if($friends->num_rows() == 0): ?>
							<h5>No Friends.</h5>
						<?php else: ?>
							<ul class="list-group">
								<?php foreach ($friends->result() as $friend_row): ?>
									<li class="list-group-item">
										<?= $friend_row->user_name ?>
										<button class="btn btn-danger" style="float:right;" onclick="confirm_delete(<?= $friend_row->user_id ?>)">delete</button>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="card" id="friend_message_card">
				<div class="card-header">
					Invitation Message
				</div>
				<div class="card-body">
					<?php if ($invite_message->num_rows() == 0): ?>
						<h3>No invitation message.</h3>
					<?php else: ?>
						<ul class="list-group">
							<?php foreach ($invite_message->result() as $invite_message_row): ?>
								<li class="list-group-item">
									<span><?= $invite_message_row->user_name ?> wants to be your friend.</span>
									<button style="float:right" class="btn btn-success" value="accept"
											onclick="accept_friend(<?= $invite_message_row->user_id ?>)">Accept
									</button>
									<button style="float:right;margin-right: 10px;" class="btn btn-danger"
											value="decline"
											onclick="decline_friend(<?= $invite_message_row->user_id ?>)">
										Decline
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="delcfmModel">
	<div class="modal-dialog">
		<div class="modal-content message_align">
			<div class="modal-header">
				<h4 class="modal-title">Message</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<p id="modal_info">confirm</p>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="url"/>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="btn_modal_submit" onclick="urlSubmit()" class="btn btn-success" data-dismiss="modal">
					Confirm
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
