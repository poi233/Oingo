<script>
	$(function () {
		$("#btn_modify_note").hide();
		clear_error();
	});

	function get_note_info(note_id) {
		clear_error();
		$("#btn_add_note").hide();
		$("#btn_modify_note").show();
		$("#note_header").text("Modify New Note");
		$("#reset").trigger("click");
		var modify_url = "<?= base_url('index.php/MyNote/get_note_info') ?>";
		$.ajax({
			url: modify_url,
			type: 'POST',
			data: {
				note_id: note_id,
			},
			dataType: 'json',
			error: function () {
				alert("ajax error");
			},  //错误执行方法
			success: function (data) {
				var data = eval(data);
				$("#inputNoteId").val(data['note_id']);
				$("#inputStartDate").val(data['start_date']);
				$("#inputEndDate").val(data['end_date']);
				$("#inputStartTime").val(data['start_time']);
				$("#inputEndTime").val(data['end_time']);
				$("#inputContent").val(data['content']);
				$("#inputLocationName").val(data['location_name']);
				$("#inputRadius").val(data['radius']);
				$("#inputPermission").val(data['permission']);
				$("#inputAllowComment").val(data['allow_comment']);
				$("#lat").val(data['latitude']);
				$("#lng").val(data['longitude']);
				console.log(data);
				console.log(data['tag_id']);
				for (j = 0, len = data['tag_id'].length; j < len; j++) {
					$("#inputTag option[value='" + data['tag_id'][j]['tag_id'] + "']").prop("selected", true);
				}
				for (j = 0, len = data['repetition'].length; j < len; j++) {
					console.log(data['repetition'].indexOf(j));
					$("#inputRepeat option[value='" + data['repetition'][j] + "']").prop("selected", true);
				}
				// $("#inputTag").val(data['tag_id']);
				var myLatlng = new google.maps.LatLng(data['latitude'], data['longitude']);
				addNoteMap.addMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
					myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5))
			} //成功执行方法
		});
	}

	function delete_note(note_id) {
		window.location.href = "<?= base_url("index.php/MyNote/delete_note/") ?>" + note_id;
	}

	function confirm_delete_note(note_id) {
		if (confirm("Are you sure to delete?")) {
			delete_note(note_id);
		}
	}

	function add_note() {
		if (check_validation()) {
			clear_error();
			$("#note_form").attr("action", "<?= base_url('index.php/MyNote/add_new_note') ?>");
			$("#note_form").submit();
		}
	}

	function modify_note() {
		if (check_validation()) {
			clear_error();
			$("#note_form").attr("action", "<?= base_url('index.php/MyNote/modify_note') ?>");
			$("#note_form").submit();
		}
	}

	function show_add_note() {
		clear_error();
		$("#reset").trigger("click");
		var myLatlng = new google.maps.LatLng(40.69289, -73.98488);
		addNoteMap.addMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5))
		$("#btn_add_note").show();
		$("#btn_modify_note").hide();
		$("#note_header").text("Add New Note");
	}

	function delete_comment(id) {
		$.ajax({
			url: "<?= base_url("index.php/Comment/delete_comment") ?>",
			type: "POST",
			data: {comment_id: id},
			dataType: 'json',
			error: function () {
				alert("ajax error");
			},
			success: function (data) {
				$("#comment_" + id).remove();
			},
		});
	}

	function confirm_delete_comment(id) {
		if (confirm("Are you sure to delete?")) {
			delete_comment(id);
		}
	}

	function check_validation() {
		var valid = true;
		if ($("#inputStartDate").val() == "") {
			$("#inputStartDateError").show();
			valid = false;
		}

		if ($("#inputEndDate").val() == "") {
			$("#inputEndDateError").show();
			valid = false;
		}

		var startDate = new Date($("#inputStartDate").val());
		var endDate = new Date($("#inputEndDate").val());
		if (startDate != "" && endDate != "" && startDate > endDate) {
			$("#inputDateError").show();
			valid = false;
		}

		if ($("#inputStartTime").val() == "") {
			$("#inputStartTimeError").show();
			valid = false;
		}

		if ($("#inputEndTime").val() == "") {
			$("#inputEndTimeError").show();
			valid = false;
		}

		var startTime = Date.parse('20 Aug 2000 ' + $("#inputStartTime").val());
		var endTime = Date.parse('20 Aug 2000 ' + $("#inputEndTime").val());
		if (startTime != "" && endTime != "" && startTime > endTime) {
			$("#inputTimeError").show();
			valid = false;
		}

		if ($("#inputTag").val() == null) {
			$("#inputTagError").show();
			valid = false;
		}

		if ($("#inputRepeat").val() == null) {
			$("#inputRepeatError").show();
			valid = false;
		}

		if ($("#inputContent").val() == "") {
			$("#inputContentError").show();
			valid = false;
		}

		if ($("#inputLocationName").val() == "") {
			$("#inputLocationNameError").show();
			valid = false;
		}

		if ($("#inputRadius").val() == "") {
			$("#inputRadiusError").show();
			valid = false;
		}


		return valid;
	}

	function clear_error() {
		$("#inputDateError").hide();
		$("#inputTimeError").hide();
		$("#inputStartDateError").hide();
		$("#inputEndDateError").hide();
		$("#inputStartTimeError").hide();
		$("#inputEndTimeError").hide();
		$("#inputTagError").hide();
		$("#inputRepeatError").hide();
		$("#inputContentError").hide();
		$("#inputLocationNameError").hide();
		$("#inputRadiusError").hide();
	}


</script>
<div class="container-fluid">
	<div class="row justify-content-md-center">
		<div class="col-md-4">
			<div class="card" id="note_card">
				<div class="card-header">
					My Notes
					<button class="btn btn-outline-primary" onclick="show_add_note()">+</button>
				</div>
				<div class="card-body">
					<ul class="list-group">
						<?php foreach ($notes as $note_row): ?>
							<li class="list-group-item" style="margin-bottom: 10px;">
								<p>Start Date: <?= $note_row['start_date'] ?>, End
									Date: <?= $note_row['end_date'] ?></p>
								<p>Start Time: <?= $note_row['start_time'] ?>, End
									Time: <?= $note_row['end_time'] ?></p>
								<p>Repetition: <?= $note_row['repetition'] ?></p>
								<p>Location Name: <?= $note_row['location_name'] ?>, Coordinate:
									(<?= $note_row['latitude'] ?>
									,<?= $note_row['longitude'] ?>), Radius: within <?= $note_row['radius'] ?>
									meters</p>
								<p>Content: <?= $note_row['content'] ?></p>
								<p>tags:
									<?php foreach ($note_row['tag']->result() as $tag): ?>
										<?= $tag->tag_name ?>
									<?php endforeach; ?>
								</p>
								<p>permission:
									<?php if ($note_row['permission'] == 0) {
										echo "All";
									} else if ($note_row['permission'] == 1) {
										echo "Friends";
									} else {
										echo "Nobody";
									} ?>
									, allow_comment:<?= $note_row['allow_comment'] == 1 ? "True" : "False" ?></p>
								<ul class="list-group list-group-flush" id="noteModalComment">
									<?php foreach ($note_row['comment']->result() as $comment): ?>
										<li class="list-group-item" id="comment_<?= $comment->comment_id ?>">
											<?= $comment->content ?> || <?= $comment->account ?>
											|| <?= $comment->post_time ?>
											<button type="button" class="btn-sm btn-danger"
													onclick="confirm_delete_comment(<?= $comment->comment_id ?>)">Delete
											</button>
										</li>
									<? endforeach; ?>
								</ul>
								<button class="btn btn-warning" onclick="get_note_info(<?= $note_row['note_id'] ?>)">
									Modify
								</button>
								<button class="btn btn-danger" onclick="confirm_delete_note(<?= $note_row['note_id'] ?>)">
									Delete
								</button>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="card">
				<div class="card-header">
					<span id="note_header">Add New Note</span>
				</div>
				<div class="card-body">
					<form method="post" id="note_form">
						<input id="inputNoteId" name="note_id" hidden="hidden"/>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputStartDate">Start Date</label>
								<input type="date" class="form-control" id="inputStartDate" name="start_date"
									   placeholder="Start Date">
								<div class="invalid-feedback" id="inputStartDateError">
									Start Date can't be empty.
								</div>
								<div class="invalid-feedback" id="inputDateError">
									Please set the right date.
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputEndDate">End Date</label>
								<input type="date" class="form-control" id="inputEndDate" name="end_date"
									   placeholder="End Date">
								<div class="invalid-feedback" id="inputEndDateError">
									End date can't be empty.
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputStartTime">Start Time</label>
								<input type="time" class="form-control" id="inputStartTime" name="start_time"
									   placeholder="Start Time">
								<div class="invalid-feedback" id="inputStartTimeError">
									Start time can't be empty.
								</div>
								<div class="invalid-feedback" id="inputTimeError">
									Please set a right time.
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputEndTime">End Time</label>
								<input type="time" class="form-control" id="inputEndTime" name="end_time"
									   placeholder="End Time">
								<div class="invalid-feedback" id="inputEndTimeError">
									End time can't be empty.
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputTag">Tag</label>
								<select multiple id="inputTag" name="tag_id[]" class="form-control">
									<?php foreach ($tags->result() as $tag_row): ?>
										<option value="<?= $tag_row->tag_id ?>"><?= $tag_row->tag_name ?></option>
									<?php endforeach; ?>
								</select>
								<div class="invalid-feedback" id="inputTagError">
									Tag can't be empty.
								</div>

							</div>
							<div class="form-group col-md-6">
								<label for="inputRepeat">Repeat</label>
								<select multiple id="inputRepeat" name="repetition[]" class="form-control">
									<option value="1">Monday</option>
									<option value="2">Tuesday</option>
									<option value="3">Wednesday</option>
									<option value="4">Thursday</option>
									<option value="5">Friday</option>
									<option value="6">Saturday</option>
									<option value="7">Sunday</option>
								</select>
								<div class="invalid-feedback" id="inputRepeatError">
									Repeat can't be empty.
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Location</label>
								<div id="add_note_map" style="height: 250px;width: 100%;"></div>
								<input type="text" id="lat" name="latitude" hidden="hidden"/>
								<input type="text" id="lng" name="longitude" hidden="hidden"/>
							</div>
							<div class="form-group col-md-6">
								<label>Content</label>
								<textarea class="form-control" id="inputContent" name="content" rows="10"></textarea>
								<div class="invalid-feedback" id="inputContentError">
									Content can't be empty.
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputLocationName">Location Name</label>
								<input type="text" class="form-control" id="inputLocationName" name="location_name"
									   placeholder="Location Name">
								<div class="invalid-feedback" id="inputLocationNameError">
									Location name can't be empty.
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputRadius">Radius</label>
								<input type="text" class="form-control" id="inputRadius" name="radius"
									   placeholder="Radius">
								<div class="invalid-feedback" id="inputRadiusError">
									Radius can't be empty.
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputPermission">Permission</label>
								<select id="inputPermission" name="permission" class="form-control">
									<option value="0">All</option>
									<option value="1">Friends Only</option>
									<option value="2">Nobody</option>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="inputAllowComment">Allow Comment</label>
								<select id="inputAllowComment" name="allow_comment" class="form-control">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<input type="reset" name="reset" id="reset" style="display: none;"/>
							<button type="button" class="btn btn-primary" id="btn_add_note" onclick="add_note()">Add New
								Note
							</button>
							<button type="button" class="btn btn-primary" id="btn_modify_note" onclick="modify_note()">
								Modify Note
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		addNoteMap.initialize();
	});
</script>
<script src="<?= base_url("assets/js/add_note_map.js") ?>"></script>

