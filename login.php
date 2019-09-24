<?php
//login.php
//user authontication & session handling 

include('database_connection.php');

if(isset($_SESSION['type']))
{
	header("location:index.php");
}

// assign error message
$message = '';

if(isset($_POST["login"]))
{
	$query = "
	SELECT * FROM user
		WHERE u_name = :u_name
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
				'u_name'	=>	$_POST["u_name"]
			)
	);
	$count = $statement->rowCount();
	if($count > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			if($row['u_status'] == 'Active')
			{
				if(md5($_POST["password"]) == $row["password"])
					
				// adding as a session variables
				{  
					$_SESSION['type'] = $row['u_type'];
					$_SESSION['u_id'] = $row['u_id'];
					$_SESSION['fname'] = $row['fname'];
					$_SESSION['br_id'] = $row['br_id'];
					header("location:index.php");
				}
				else
				{
					$message = "<label>Wrong Password</label>";
				}
			}
			else
			{
				$message = "<label>Your account is disabled, Please Contact Admin</label>";
			}
		}
	}
	else
	{
		$message = "<label>Wrong Username</labe>";
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Asset Management System </title>		
		<script src="js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="includes/customCSS.css"/>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<br />
		<div class="container">
			<!-- <h2 align="center">Asset Management System </h2> -->
			<br />
			<div class="login-panel" style=" width: 100%;
								  				/* Firefox */
											  display: -moz-box;
											  -moz-box-pack: center;
											  -moz-box-align: center;
											  /* Safari and Chrome */
											  display: -webkit-box;
											  -webkit-box-pack: center;
											  -webkit-box-align: center;
											  /* W3C */
											  display: box;
											  box-pack: center;
											  box-align: center;">
				<div class="login-header panel panel-default col-md-4" id='login'>
					<div class="panel-body" align="center"><h2>Asset Management System</h2></div>
					<div class="login-container panel-container">
						<form method="post">
							<?php echo $message; ?> <!-- display error message -->
							<div class="form-group">
								<!-- <label>Username</label> -->
								<input type="text" name="u_name" class="login-input form-control" placeholder="Username" required />
							</div>
							<br>
							<div class="form-group">
								<!-- <label>Password</label> -->
								<input type="password" name="password" class="login-input form-control" placeholder="Password" required />
							</div>
							<div class="remember-me form-group">
								<label><input type="checkbox" /> Remember Me</label>
							</div>
							<div class="form-group">
								<input type="submit" name="login" value="Login" class="btn btn-info btn-block login-button " />
							</div>
						</form>
					</div>
				</div>
			</div>	
		</div>
	</body>
</html>	