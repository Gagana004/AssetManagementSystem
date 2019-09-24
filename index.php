<?php
//index.php
include('database_connection.php');
include('function.php');

if(!isset($_SESSION["type"]))
{
	header("location:login.php");
}

include('header.php');

?>
	<br />
	<div class="row">

		<!-- only master user can seee this -->
	<?php
	if($_SESSION['type'] == 'master')
	{
	?>

		<!-- labels  -->
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total User</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_user($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Category</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_category($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Items</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_item($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Asset</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_asset($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Vendors</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_vendor($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Purchase Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_purchase($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Transfer Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_transfer($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Repair Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_repair($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Disposal Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_disposal($connect); ?></h1>
				</div>
			</div>
		</div>
	

	<?php
	}
	?>

	<!-- user and master can see this -->		
	<?php
		if($_SESSION['type']!== 'master')
		{
	?>
	<!-- lables -->
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Category</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_category($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Item</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_item($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Asset</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_asset($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Vendors</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_vendor($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Purchase Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_purchase($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Transfer Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_transfer($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Repair Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_repair($connect); ?></h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Total Disposal Records</strong></div>
				<div class="panel-body">
					<h1><?php echo count_total_disposal($connect); ?></h1>
				</div>
			</div>
		</div>	
		<?php
		}
		?>
	</div>

<?php
include("footer.php");
?>