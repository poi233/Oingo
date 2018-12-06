
<div class="container-fluid">
	<div class="row justify-content-md-center">
		<div class="col-lg-4 ">
			<h3>My Profile</h3>
			<form method="post" action="<?= base_url('index.php/User/update') ?>">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="inputName">Name</label>
						<input type="text" class="form-control" id="inputName" name="name" placeholder="Name" value="<?= $user_info->name ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputGender">Gender</label>
					<select id="inputGender" name="sex" class="form-control">
						<option value="0" <?php if($user_info->sex == 0): ?> selected="selected" <?php endif; ?>>Not show</option>
						<option value="1" <?php if($user_info->sex == 1): ?> selected="selected" <?php endif; ?>>Male</option>
						<option value="2" <?php if($user_info->sex == 2): ?> selected="selected" <?php endif; ?>>Female</option>
					</select>
				</div>
				<div class="form-group">
					<label for="inputDate">Birth</label>
					<input type="date" class="form-control" id="inputDate" name="birth" placeholder="Birth" value="<?= $user_info->birth ?>">
				</div>
				<div class="form-group">
					<label for="inputDetail">Detail</label>
					<textarea class="form-control" id="inputDetail" name="detail" placeholder="Detail"
							  rows="3"><?= $user_info->detail ?></textarea>
				</div>
				<div class="form-group">
					<label for="inputState">State</label>
					<select id="inputState" name="state_id" class="form-control">
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
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>
	</div>
</div>
