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
		<link rel="stylesheet" href="includes/index.css"/>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<!-- <h2 align="center">Asset Management System </h2> -->	
			<div class="login-panel">
				<div class="panel-container panel panel-default col-md-4" id='login'>
					<div class="login-header">
						<h2>Asset Management System</h2>
					</div>
					<div class="login-body">
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
								<input type="checkbox"><label>Remember Me</label>
							</div>
							<div class="form-group">
								<button type="submit" name="login" class="btn btn-login">
									LOGIN
								</button>								
							</div>
						</form>
					</div>
				</div>
			</div>	
		</div>
	</body>
</html>	