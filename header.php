<?php
//header.php
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Asset Management System</title>
		<script src="js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!-- <link rel="stylesheet" href="css/fontawesome.css"> -->
		<link rel="stylesheet" href="includes/customCSS.css"/>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>		
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<br />
		<div class="container">
			<h2 align="center">Asset Management System</h2>

			<nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<a href="index.php" class="navbar-brand">Home</a>
					</div>
					<ul class="nav navbar-nav">
					<?php
					if($_SESSION['type'] == 'master') 
					{
					?>
						<li><a href="user.php">User</a></li> <!-- only master-user can manage users -->
						
					<?php
					}
					?>	
						<li><a href="vendor.php">Vendor</a></li>
						<li><a href="category.php">Category</a></li>
						<li><a href="item.php">Items </a></li>
						<li><a href="purchase.php">Purchase</a></li>
						<li><a href="asset.php">Assets</a></li>
						<li><a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Asset Transfer </a>
       						 <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					          <a class="dropdown-item" href="transfer.php">Transfer</a>
					          <a class="dropdown-item" href="repair.php">Repair</a>
					          <a class="dropdown-item" href="disposal.php">Disposal</a>
					        </div>
        				</li>												
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> <?php echo $_SESSION["fname"]; ?></a>
							<ul class="dropdown-menu">
								<li><a href="profile.php">Profile</a></li>
								<li><a href="logout.php">Logout</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			 