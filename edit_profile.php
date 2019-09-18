<?php

//edit_profile.php

include('database_connection.php');

if(isset($_POST['u_name']))
{
	if($_POST["user_new_password"] != '')
	{
		//if user set new password
		$query = "
		UPDATE user SET 
			fname = '".$_POST["fname"]."', 
			u_name = '".$_POST["u_name"]."', 
			password = '".$_POST["user_new_password"]."' 
			WHERE u_id = '".$_SESSION["u_id"]."'
		";
	}
	else
	{
		//if user didn't set new password
		$query = "
		UPDATE user SET 
			fname = '".$_POST["fname"]."', 
			u_name = '".$_POST["u_name"]."'
			WHERE u_id = '".$_SESSION["u_id"]."'
		";
	}
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	if(isset($result))
	{
		echo '<div class="alert alert-success">Profile Edited</div>';
	}
}

?>