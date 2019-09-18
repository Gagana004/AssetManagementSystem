<?php
// index.php
include('database_connection.php');
include('function.php');

include("header.php");
?>

<!-- Lables -->
<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Category</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_category($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Item</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_item($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Asset</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_asset($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Vendors</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_vendor($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Purchase Records</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_purchase($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Transfer Records</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_transfer($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Repair Records</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_repair($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Disposal Records</strong></div>
				<div class="panel-body" align="center">
					<h1><?php echo count_total_disposal($connect); ?></h1>
				</div>
			</div>
		</div>	


<?php
include("footer.php");
?>