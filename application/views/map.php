<style>
	.card-header {
		background-color: #AFEEFF;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-3">
			<div class="card" id="active_filter_card">
				<div class="card-header">
					Active Filter
				</div>
				<div class="card-body">
					<ul class="list-group">
						<?php foreach ($active_filters as $filter_row): ?>
							<li class="list-group-item">
								<p>Start
									Date: <?= $filter_row['start_date'] == null ? "NULL" : $filter_row['start_date'] ?>,
									End
									Date: <?= $filter_row['end_date'] == null ? "NULL" : $filter_row['end_date'] ?></p>
								<p>Start
									Time: <?= $filter_row['start_time'] == null ? "NULL" : $filter_row['start_time'] ?>,
									End
									Time: <?= $filter_row['end_time'] == null ? "NULL" : $filter_row['end_time'] ?></p>
								<p>
									Repetition: <?= $filter_row['repetition'] == null ? "NULL" : $filter_row['repetition'] ?></p>
								<p>Coordinate: (<?= $filter_row['latitude'] ?>, <?= $filter_row['longitude'] ?>)</p>
								<p>Radius:
									within <?= $filter_row['radius'] == null ? "Unlimited" : $filter_row['radius'] ?>
									meters</p>
								<p>Tag: <?= $filter_row['tag_id'] == -1 ? "No tag" : $filter_row['tag'] ?></p>
								<p>State: <?= $filter_row['state_id'] == -1 ? "No state" : $filter_row['state'] ?></p>
								<p>From Who:
									<?php if ($filter_row['from_who'] == 0) {
										echo "All";
									} else if ($filter_row['from_who'] == 1) {
										echo "Friends";
									} else {
										echo "Nobody";
									} ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-lg-7">
			<div id="note_map" style="height: 600px; width: 100%;"></div>
		</div>
		<div class="col-lg-2">
			<div class="card" id="my_status_card">
				<div class="card-header">
					My Status
				</div>
				<div class="card-body">
					<div>
						<div>
							<span id="locationText">My Location: (<?= $this->session->userdata("latitude") ?>
								, <?= $this->session->userdata("longitude") ?>)</span>
						</div>
						<div>
							<span
								id="dateText"><?= $this->session->userdata("current_time") ?></span>
						</div>
						<div>
							<span id="stateText"><?= $current_state ?></span>
						</div>
						<button class="btn btn-primary" data-toggle="modal" data-target="#google_maps_api"
								style="margin-top: 5px;">Set My
							Status
						</button>
					</div>
				</div>
			</div>
			<div class="card" id="my_status_card" style="margin-top:10px;">
				<div class="card-header">
					Operations
				</div>
				<div class="card-body">
					<div>
						<button class="btn btn-primary" onclick="show_all_notes()">Show All Notes</button>
					</div>
					<div style="margin-top: 10px;">
						<button class="btn btn-primary" onclick="show_filtered_notes()">Show Filtered Notes</button>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="google_maps_api" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Set My Status</h4>
			</div>
			<div class="modal-body">
				<div id="map_canvas" style="width: 100%; height: 450px"></div>
				<input class="form-control" type="datetime-local" id="user_date" style="margin-top: 10px;" value=""/>
				<div class="invalid-feedback" id="user_date_error">
					Date and Time can't be empty.
				</div>
				<select class="form-control" id="user_state" style="margin-top: 10px;">
					<?php foreach ($states->result() as $state_row): ?>
						<option value="<?= $state_row->state_id ?>"
							<?php if ($this->session->userdata("state_id") == $state_row->state_id): ?>
								selected="selected"
							<?php endif; ?> >
							<?= $state_row->state_name ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn_modal_ok" onclick="change_state()" class="btn btn-primary">OK
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="note_modal" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="noteModalLabel">Note</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div hidden="hidden" id="noteModalNoteId"></div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<b>Location:</b><br/>
							<span id="noteModalLocation">Location Name</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Content:</b><br/>
							<span id="noteModalContent">This is the content of a note.</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Publisher:</b><br/>
							<span id="noteModalAccount">publisher_name</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Date:</b><br/>
							<span id="noteModalDate">Date</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Time:</b><br/>
							<span id="noteModalPeriod">Period</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Repeat:</b><br/>
							<span id="noteModalRepeat">Repeat</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Tags:</b><br/>
							<span id="noteModalTag">#tag1 #tag2</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<b>Comments:</b><br/>
							<ul class="list-group list-group-flush" id="noteModalComment">
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9">
							<input class="form-control" id="noteModalCommentInput" placeholder="Make comments">
							<div class="invalid-feedback" id="noteModalCommentInputError">
								Comment can't be empty.
							</div>
						</div>
						<div class="col-md-2">
							<button class="btn-sm btn-primary" id="noteModalCommentButton" onclick="make_comment()">
								Comment
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
					Close
				</button>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function () {
		$("#noteModalCommentInputError").hide();
		noteMap.initialize();
		googleMap.initialize(<?= $this->session->userdata("latitude") ?>, <?= $this->session->userdata("longitude") ?>);
		var myLatlng = new google.maps.LatLng(<?= $this->session->userdata("latitude") ?>, <?= $this->session->userdata('longitude') ?>);
		noteMap.addMyMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
		$("#dateText").val("<?= $this->session->userdata("current_time")?>");
		<?php foreach($notes as $note_row): ?>
		var noteLatLng = new google.maps.LatLng(<?= $note_row['latitude'] ?>,<?= $note_row['longitude'] ?>);
		var noteContent =
			"<div>" +
			"<b><?= $note_row['location_name'] ?></b><br/>" +
			"<span><?= $note_row['content'] ?></span><br/>" +
			"<a href='#' onclick='show_note_detail(<?= $note_row['note_id'] ?>)'>Show Details.</a>" +
			"</div>";
		noteMap.addNoteMarker(noteLatLng, <?= $note_row['note_id'] ?>, noteContent);
		<?php endforeach; ?>
	});

	function change_state() {
		if ($("#user_date").val() == null || $("#user_date").val() == "") {
			$("#user_date_error").show();
		} else {
			$("#user_date_error").hide();
			var res = 'My Location: (' + googleMap.markers[0].getPosition().lat().toFixed(5) + ' , ' + googleMap.markers[0].getPosition().lng().toFixed(5) + ')';
			$("#locationText").text(res);
			var url1 = "<?= base_url("index.php/Note/set_state") ?>";
			$.ajax({
				url: url1,
				type: 'POST',
				data: {
					user_id: <?= $this->session->userdata("user_id") ?>,
					state_id: $("#user_state").val(),
					latitude: googleMap.markers[0].getPosition().lat().toFixed(5),
					longitude: googleMap.markers[0].getPosition().lng().toFixed(5),
					current_time: $("#user_date").val()
				},
				dataType: 'json',
				error: function () {
					alert("ajax error");
				},
				success: function (data) {
					window.location.href = "<?= base_url("index.php/Note") ?>";
				}
			});
			var myLatlng = googleMap.markers[0].getPosition();
			noteMap.addMyMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
				myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
			$("#google_maps_api").modal("hide");
		}
	}

	function show_filtered_notes() {
		$.each(noteMap.note_markers, function (i, val) {
			val.setMap(null);
		});
		noteMap.note_markers = {};
		<?php foreach($filtered_notes->result() as $note_row): ?>
		var noteLatLng = new google.maps.LatLng(<?= $note_row->latitude ?>, <?= $note_row->longitude ?>);
		var noteContent =
			"<div>" +
			"<b><?= $note_row->location_name ?></b><br/>" +
			"<span><?= $note_row->content ?></span><br/>" +
			"<a href='#' onclick='show_note_detail(<?= $note_row->note_id ?>)'>Show Details.</a>" +
			"</div>";

		noteMap.addNoteMarker(noteLatLng, <?= $note_row->note_id ?>, noteContent);
		<?php endforeach; ?>
	}

	function show_all_notes() {
		$.each(noteMap.note_markers, function (i, val) {
			val.setMap(null);
		});
		noteMap.note_markers = {};
		<?php foreach($notes as $note_row): ?>
		var noteLatLng = new google.maps.LatLng(<?= $note_row['latitude'] ?>,<?= $note_row['longitude'] ?>);
		var noteContent =
			"<div>" +
			"<b><?= $note_row['location_name'] ?></b><br/>" +
			"<span><?= $note_row['content'] ?></span><br/>" +
			"<a href='#' onclick='show_note_detail(<?= $note_row['note_id'] ?>)'>Show Details.</a>" +
			"</div>";
		noteMap.addNoteMarker(noteLatLng, <?= $note_row['note_id'] ?>, noteContent);
		<?php endforeach; ?>
	}

	function show_note_detail(id) {
		var info_url = "<?= base_url('index.php/Note/get_note_info') ?>";
		$.ajax({
			url: info_url,
			type: 'POST',
			data: {
				note_id: id,
			},
			dataType: 'json',
			error: function () {
				alert("ajax error");
			},  //错误执行方法
			success: function (data) {
				var data = eval(data);
				$("#noteModalNoteId").text(data['note_id']);
				$("#noteModalLocation").text(data['location_name']);
				$("#noteModalContent").text(data['content']);
				$("#noteModalAccount").text(data['account']);
				var tags = "";
				for (j = 0, len = data['tag_id'].length; j < len; j++) {
					tags = tags + data['tag_name'][j]['tag_name'] + " ";
				}
				$("#noteModalTag").text(tags);
				$("#noteModalDate").text(data['start_date'] + " ~ " + data['end_date']);
				$("#noteModalPeriod").text(data['start_time'] + " ~ " + data['end_time']);
				$("#noteModalRepeat").text(data['repetition']);
				$("#noteModalComment").empty();
				for (i = 0, len = data['comment'].length; i < len; i++) {
					var content = "<li class='list-group-item'>" +
						data['comment'][i]['content'] + "<br/>" +
						data['comment'][i]['account'] + " " +
						data['comment'][i]['post_time'] +
						"</li>";
					$("#noteModalComment").append(content);
				}
				if (data['allow_comment'] == 1) {
					$("#noteModalCommentInput").removeAttr("disabled");
					$("#noteModalCommentButton").removeAttr("disabled");
				} else {
					$("#noteModalCommentInput").attr("disabled", "disabled");
					$("#noteModalCommentButton").attr("disabled", "disabled");

				}

				$("#note_modal").modal("show");

			} //成功执行方法
		});
	}

	function make_comment() {
		if ($("#noteModalCommentInput").val() == "") {
			$("#noteModalCommentInputError").show();
			return;
		}
		$("#noteModalCommentInputError").hide();
		var comment_url = "<?= base_url('index.php/Comment/make_comment') ?>";
		$.ajax({
			url: comment_url,
			type: 'POST',
			data: {
				note_id: $("#noteModalNoteId").text(),
				user_id: <?= $this->session->userdata("user_id") ?>,
				content: $("#noteModalCommentInput").val(),
			},
			dataType: 'json',
			error: function () {
				alert("ajax error");
			},  //错误执行方法
			success: function (data) {
				var data = eval(data);
				$("#noteModalCommentInput").val("");
				var content = "<li class='list-group-item'>" +
					data['content'] + "<br/>" +
					data['account'] + " " +
					data['post_time'] +
					"</li>";
				$("#noteModalComment").append(content);
			} //成功执行方法
		});

	}
</script>

<script>
	$("#google_maps_api").on("shown.bs.modal", function () {
		$("#user_date_error").hide();
		$("#user_date").val("<?= $this->session->userdata("current_time")?>");
		googleMap.delMarker();
		var myLatlng = new google.maps.LatLng(<?= $this->session->userdata("latitude") ?>, <?= $this->session->userdata('longitude') ?>);
		googleMap.addMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
	});

	$("#note_modal").on("hide.bs.modal", function () {
		$("#noteModalCommentInputError").hide();
	});
</script>

<script src="<?= base_url("assets/js/google_map.js") ?>"></script>
<script src="<?= base_url("assets/js/note_map.js") ?>"></script>

