<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<title><?= $title ?></title>
</head>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXwsx8a4WKrw9Hsar2k-GTO6-09VGyMTs"
		type="text/javascript"></script>
<script src="<?= base_url("assets/js/jquery.min.js") ?>"></script>
<!--	<script src="--><? //= base_url("assets/js/snow.js") ?><!--"></script>-->
<script src="<?= base_url("assets/js/jquery.pure.tooltips.js") ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
		integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
		crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
		integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
		crossorigin="anonymous"></script>
<style>
	.nav-item {
		margin-left: 10px;
	}

	.card-header {
		background-color: #AFEEFF;
	}
</style>
<body>
<?php if ($this->session->userdata("user_id") == null) redirect("");  ?>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #AFEEFF;margin-bottom:20px;">
	<a class="navbar-brand" href="<?= base_url("index.php/Note") ?>"
	   style="color: #1E90FF;font-size: 25px;"><b>Oingo</b></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
			aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url("index.php/MyNote") ?>">Notes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url("index.php/MyNote") ?>">Filters</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
				   aria-haspopup="true" aria-expanded="false">Friend
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<?php foreach($friends->result() as $friend_row): ?>
					<a class="dropdown-item" href="#"><?= $friend_row->user_name ?></a>
					<?php endforeach; ?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?= base_url("index.php/Friend") ?>">Manage</a>
				</div>
			</li>

		</ul>
		<span class="nav-link">
			Hello, <a href="<?= base_url("index.php/User") ?>"><?= $this->session->userdata('account'); ?></a>
		</span>

		<button class="btn btn-outline-danger" onclick="logout()">logout</button>
	</div>
</nav>
<script>
	function logout() {
		window.location.href = '<?= base_url("index.php/Welcome/logout") ?>'
	}

</script>
