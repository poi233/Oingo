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
								<button class="btn btn-primary">Add</button>
							</div>
						</div>
					</div>
					<div style="margin-top:10px;">
						<label><b>My Friends:</b></label>
						<ul class="list-group">
							<?php foreach($friends->result() as $friend_row): ?>
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
