<script>
	$(function () {
		$("#btn_modify_filter").hide();
		$("#inputDateError").hide();
		$("#inputTimeError").hide();
	});

	function get_filter_info(filter_id) {
		$("#btn_add_filter").hide();
		$("#btn_modify_filter").show();
		$("#filter_header").text("Modify New Filter");
		$("#reset").trigger("click");
		var modify_url = "<?= base_url('index.php/Filter/get_filter_info') ?>";
		$.ajax({
			url: modify_url,
			type: 'POST',
			data: {
				filter_id: filter_id,
			},
			dataType: 'json',
			error: function () {
				alert("ajax error");
			},  //错误执行方法
			success: function (data) {
				var data = eval(data);
				$("#inputFilterId").val(data['filter_id']);
				$("#inputStartDate").val(data['start_date']);
				$("#inputEndDate").val(data['end_date']);
				$("#inputStartTime").val(data['start_time']);
				$("#inputEndTime").val(data['end_time']);
				$("#inputRadius").val(data['radius']);
				$("#lat").val(data['latitude']);
				$("#lng").val(data['longitude']);
				$("#inputTag").val(data['tag_id']);
				$("#inputState").val(data['state_id']);
				if (data['repetition'] != null) {
					for (j = 0, len = data['repetition'].length; j < len; j++) {
						console.log(data['repetition'].indexOf(j));
						$("#inputRepeat option[value='" + data['repetition'][j] + "']").prop("selected", true);
					}
				}
				var myLatlng = new google.maps.LatLng(data['latitude'], data['longitude']);
				addFilterMap.addMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
					myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5))
			} //成功执行方法
		});
	}

	function delete_filter(filter_id) {
		window.location.href = "<?= base_url("index.php/Filter/delete_filter/") ?>" + filter_id;
	}

	function add_filter() {
		if (check_validation()) {
			clear_error();
			$("#filter_form").attr("action", "<?= base_url('index.php/Filter/add_new_filter') ?>");
			$("#filter_form").submit();
		}
	}

	function modify_filter() {
		if (check_validation()) {
			clear_error();
			$("#filter_form").attr("action", "<?= base_url('index.php/Filter/modify_filter') ?>");
			$("#filter_form").submit();
		}
	}

	function show_add_filter() {
		$("#reset").trigger("click");
		var myLatlng = new google.maps.LatLng(40.69289, -73.98488);
		addFilterMap.addMarker(myLatlng, "name", "<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5))
		$("#btn_add_filter").show();
		$("#btn_modify_filter").hide();
		$("#filter_header").text("Add New Filter");
	}

	function toggle_filter(id) {
		window.location.href = "<?= base_url("index.php/Filter/toggle_filter/") ?>" + id;
	}

	function check_validation() {
		var valid = true;
		var startDate = new Date($("#inputStartDate").val());
		var endDate =new Date($("#inputEndDate").val());
		if (startDate != "" && endDate != "" && startDate > endDate){
			$("#inputDateError").show();
			valid = false;
		}
		var startTime = Date.parse('20 Aug 2000 '+ $("#inputStartTime").val());
		var endTime = Date.parse('20 Aug 2000 '+$("#inputEndTime").val());
		if (startTime != "" && endTime != "" && startTime > endTime){
			$("#inputTimeError").show();
			valid = false;
		}
		return valid;
	}

	function clear_error() {
		$("#inputDateError").hide();
		$("#inputTimeError").hide();
	}
</script>
<div class="container-fluid">
	<div class="row justify-content-md-center">
		<div class="col-md-4">
			<div class="card" id="filter_card">
				<div class="card-header">
					My Filters
					<button class="btn btn-outline-primary" onclick="show_add_filter()">+</button>
				</div>
				<div class="card-body">
					<ul class="list-group">
						<?php foreach ($filters as $filter_row): ?>
							<li class="list-group-item" style="margin-bottom: 10px;">
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
								<button class="btn btn-warning"
										onclick="get_filter_info(<?= $filter_row['filter_id'] ?>)">
									Modify
								</button>
								<button class="btn btn-danger" onclick="delete_filter(<?= $filter_row['filter_id'] ?>)">
									Delete
								</button>
								<?php if ($filter_row['active'] == 1): ?>
									<button class="btn btn-danger"
											onclick="toggle_filter(<?= $filter_row['filter_id'] ?>)">Close
									</button>
								<?php else: ?>
									<button class="btn btn-success"
											onclick="toggle_filter(<?= $filter_row['filter_id'] ?>)">Open
									</button>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="card">
				<div class="card-header">
					<span id="filter_header">Add New Filter</span>
				</div>
				<div class="card-body">
					<form method="post" id="filter_form">
						<input id="inputFilterId" name="filter_id" hidden="hidden"/>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputStartDate">Start Date</label>
								<input type="date" class="form-control" id="inputStartDate" name="start_date"
									   placeholder="Start Date">
								<div class="invalid-feedback" id="inputDateError">
									Please set the right date.
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputEndDate">End Date</label>
								<input type="date" class="form-control" id="inputEndDate" name="end_date"
									   placeholder="End Date">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputStartTime">Start Time</label>
								<input type="time" class="form-control" id="inputStartTime" name="start_time"
									   placeholder="Start Date">
								<div class="invalid-feedback" id="inputTimeError">
									Please set the right time.
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputEndTime">End Time</label>
								<input type="time" class="form-control" id="inputEndTime" name="end_time"
									   placeholder="End Date">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>Location</label>
								<div id="add_filter_map" style="height: 200px;width: 100%;"></div>
								<input type="text" id="lat" name="latitude" hidden="hidden"/>
								<input type="text" id="lng" name="longitude" hidden="hidden"/>
							</div>
							<div class="form-group col-md-6">
								<label for="inputRepeat">Repeat</label>
								<select multiple id="inputRepeat" name="repetition[]" class="form-control"
										style="height: 200px;">
									<option value="1">Monday</option>
									<option value="2">Tuesday</option>
									<option value="3">Wednesday</option>
									<option value="4">Thursday</option>
									<option value="5">Friday</option>
									<option value="6">Saturday</option>
									<option value="7">Sunday</option>
								</select>
							</div>

						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputRadius">Radius</label>
								<input type="text" class="form-control" id="inputRadius" name="radius"
									   placeholder="Radius">
							</div>
							<div class="form-group col-md-6">
								<label for="inputFromWho">From Who</label>
								<select id="inputFromWho" name="from_who" class="form-control">
									<option value="0" selected="selected">All</option>
									<option value="1">Friend</option>
									<option value="2">Nobody</option>
								</select>
							</div>

						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputTag">Tag</label>
								<select id="inputTag" name="tag_id" class="form-control">
									<option value="-1" selected="selected">Don't care.</option>
									<?php foreach ($tags->result() as $tag_row): ?>
										<option value="<?= $tag_row->tag_id ?>"><?= $tag_row->tag_name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="inputState">State</label>
								<select id="inputState" name="state_id" class="form-control">
									<option value="-1" selected="selected">Don't care.</option>
									<?php foreach ($states->result() as $state_row): ?>
										<option
											value="<?= $state_row->state_id ?>"><?= $state_row->state_name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<input type="reset" name="reset" id="reset" style="display: none;"/>
							<button type="button" class="btn btn-primary" id="btn_add_filter" onclick="add_filter()">Add
								New
								Filter
							</button>
							<button type="button" class="btn btn-primary" id="btn_modify_filter"
									onclick="modify_filter()">
								Modify Filter
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
		addFilterMap.initialize();
	});
</script>
<script src="<?= base_url("assets/js/add_filter_map.js") ?>"></script>

