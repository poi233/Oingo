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
						open_modal("<?= base_url("index.php/Friend/add_friend/") ?>" + $("#inputSearch").val(), "Add Friend" ,true);
						break;
					case 2:
						open_modal("#", "Friend invitation already submitted.", false);
						break;
					case 3:
						open_modal("#", "Already Friends.", false);
						break;
					case 4:
						open_modal("<?= base_url("index.php/Friend/readd_friend/") ?>" + $("#inputSearch").val(), "Resubmit add friend invitation." ,true);
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
	function urlSubmit(){
		var url=$.trim($("#url").val());//获取会话中的隐藏属性URL
		window.location.href=url;
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
						<ul class="list-group">
							<?php foreach ($friends->result() as $friend_row): ?>
								<li class="list-group-item">
									<?= $friend_row->user_name ?>
								</li>
							<?php endforeach; ?>
						</ul>
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
					<ul class="list-group">
						<li class="list-group-item">
							<span>[someone] wants to be your friend.</span>
							<button style="float:right" class="btn btn-success" value="accept">Accept</button>
							<button style="float:right;margin-right: 10px;" class="btn btn-danger" value="accept">
								Decline
							</button>
						</li>
						<li class="list-group-item">
							<span>You invited [someone] as your friend</span>
							<button style="float:right" class="btn btn-warning" value="accept">Cancel</button>
						</li>
						<li class="list-group-item">
							123
						</li>
						<li class="list-group-item">
							123
						</li>
					</ul>

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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<p id="modal_info">confirm</p>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="url"/>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="btn_modal_submit" onclick="urlSubmit()" class="btn btn-success" data-dismiss="modal">Confirm</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
