<style>
	.card-header {
		background-color: #AFEEFF;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-3">
			<div class="card" id="filter_card">
				<div class="card-header">
					Filter
				</div>
				<div class="card-body">
					<form>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputStartDate">Start Date</label>
								<input type="date" class="form-control" id="inputStartDate" placeholder="Start Date">
							</div>
							<div class="form-group col-md-6">
								<label for="inputEndDate">End Date</label>
								<input type="date" class="form-control" id="inputEndDate" placeholder="End Date">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputStartTime">Start Time</label>
								<input type="time" class="form-control" id="inputStartTime" placeholder="Start Date">
							</div>
							<div class="form-group col-md-6">
								<label for="inputEndTime">End Time</label>
								<input type="time" class="form-control" id="inputEndTime" placeholder="End Date">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="inputRadius">Radius</label>
								<input type="text" class="form-control" id="inputRadius">
							</div>
							<div class="form-group col-md-4">
								<label for="inputTag">Tag</label>
								<select id="inputTag" class="form-control">
									<option value="null">No Tag</option>
									<option>...</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheck">
								<label class="form-check-label" for="gridCheck">
									Save as my filter
								</label>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<button type="button" class="btn btn-primary">Select Location</button>
							</div>
							<div class="col-md-6">
								<button type="button" class="btn btn-primary	">Add Filter</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="card" style="margin-top: 10px;" id="active_filter_card">
				<div class="card-header">
					Active Filter
				</div>
				<div class="card-body">
					<ul class="list-group">
						<li class="list-group-item">Filter1</li>
						<li class="list-group-item">Filter2</li>
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
							<span id="locationText">My Location: (40.69289, -73.98488)</span>
						</div>
						<div>
							<span id="dateText"><?= date("Y-m-d") ?></span>
						</div>
						<div>
							<span id="timeText"><?= date("H:i:s") ?></span>
						</div>
						<div>
							<span id="stateText"><?= $current_state ?></span>
						</div>
						<button class="btn btn-primary" data-toggle="modal" data-target="#google_maps_api" style="margin-top: 5px;">Set My
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
						<button class="btn btn-primary">Show All Notes</button>
					</div>
					<div style="margin-top: 10px;">
						<button class="btn btn-primary">Show Filtered Notes</button>
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
				<input type="datetime-local" id="user_date" style="margin-top: 10px;" value=""/>
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
				<button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">OK</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		noteMap.initialize();
		var myLatlng;
		<?php foreach($notes as $note_row): ?>
		myLatlng = new google.maps.LatLng(<?= $note_row['latitude'] ?>,<?= $note_row['longitude'] ?>);
		noteMap.addNoteMarker(myLatlng, <?= $note_row['note_id'] ?>, "<?= $note_row['content'] ?>");
		<?php endforeach; ?>

	});

</script>

<script>
	$("#google_maps_api").on("shown.bs.modal", function () {
		googleMap.initialize();
		$("#user_date").val("<?= date('Y-m-d') . 'T' . date('H:i:s')?>");
		// googleMap.maps.event.trigger(map, "resize");
	}).on('hide.bs.modal', function () { //关闭模态框
		var res = 'My Location: (' + googleMap.markers[0].getPosition().lat().toFixed(5) + ' , ' + googleMap.markers[0].getPosition().lng().toFixed(5) + ')';
		$("#locationText").text(res);
		$datetime = $("#user_date").val().toString();
		$("#dateText").text($datetime.substr(0, $datetime.indexOf('T')));
		$("#timeText").text($datetime.substr($datetime.indexOf('T') + 1, $datetime.length));
		var url1 = "<?= base_url("index.php/Note/set_state") ?>";
		$.ajax({
			url: url1,
			type: 'POST',
			data: {
				user_id: <?= $this->session->userdata("user_id") ?>,
				state_id: $("#user_state").val(),
				latitude: googleMap.markers[0].getPosition().lat().toFixed(5),
				longitude: googleMap.markers[0].getPosition().lng().toFixed(5)
			},
			dataType: 'json',
			error: function () {
				alert("ajax error");
			},  //错误执行方法
			success: function (data) {
				if (data == true)
					$("#stateText").text($("#user_state").find("option:selected").text());
				else
					alert("set fail");
			} //成功执行方法
		});
		var myLatlng = googleMap.markers[0].getPosition();
		noteMap.addMyMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
	});
</script>

<script src="<?= base_url("assets/js/google_map.js") ?>"></script>
<script src="<?= base_url("assets/js/note_map.js") ?>"></script>

