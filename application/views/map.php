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
				<button type="button" id="btn_modal_ok" onclick="change_state()" class="btn btn-primary"
						data-dismiss="modal" aria-hidden="true">OK
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		noteMap.initialize();
		googleMap.initialize(<?= $this->session->userdata("latitude") ?>, <?= $this->session->userdata("longitude") ?>);
		var myLatlng = new google.maps.LatLng(<?= $this->session->userdata("latitude") ?>, <?= $this->session->userdata('longitude') ?>);
		noteMap.addMyMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
		$("#dateText").val("<?= $this->session->userdata("current_time")?>");
		var noteLatLng;
		<?php foreach($notes as $note_row): ?>
		noteLatLng = new google.maps.LatLng(<?= $note_row['latitude'] ?>,<?= $note_row['longitude'] ?>);
		noteMap.addNoteMarker(noteLatLng, <?= $note_row['note_id'] ?>, "<?= $note_row['content'] ?>");
		<?php endforeach; ?>
	});

	function change_state() {
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
	}

</script>

<script>
	$("#google_maps_api").on("shown.bs.modal", function () {
		googleMap.delMarker();
		var myLatlng = new google.maps.LatLng(<?= $this->session->userdata("latitude") ?>, <?= $this->session->userdata('longitude') ?>);
		googleMap.addMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
		$("#user_date").val("<?= $this->session->userdata("current_time")?>");
	});
</script>

<script src="<?= base_url("assets/js/google_map.js") ?>"></script>
<script src="<?= base_url("assets/js/note_map.js") ?>"></script>

