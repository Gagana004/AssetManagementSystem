<?php

//user_action.php

include('database_connection.php');
// include('function.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add') 
	{
		//insert new user details in to user table
		$query = "
		INSERT INTO user (fname, mint, lname, u_name, password, u_type, u_status, br_id) 
		VALUES (:fname, :mint, :lname, :u_name, :password, :u_type, :u_status, :br_id)
		";	
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':fname'			=>	$_POST["fname"],
				':mint'				=>	$_POST["mint"],
				':lname'			=>	$_POST["lname"],
				':u_name'			=>	$_POST["u_name"],
				':password'			=>	md5($_POST["password"]),
				':u_type'			=>	$_POST["u_type"],
				':u_status'			=>	'active',
				':br_id'			=>	$_POST["br_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'New User Added';
		}
	}

	
	if($_POST['btn_action'] == 'fetch_single')
	{
		//select curent user details for recived u_id
		$query = "
				SELECT 	u.*, b.br_name
				FROM	user as u, branch as b
				WHERE 	u.br_id = b.br_id AND 
						u.u_id = :u_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':u_id'	=>	$_POST["u_id"]
			)
		);
		$result = $statement->fetchAll();

		//json_encode the output & send details to user.php
		foreach($result as $row)
		{
			$output['u_name'] = $row['u_name'];
			$output['fname'] = $row['fname'];
			$output['mint'] = $row['mint'];
			$output['lname'] = $row['lname'];
			$output['u_type'] = $row['u_type'];
			$output['br_id'] = $row['br_id'];
		}
		echo json_encode($output); 
	}

	if($_POST['btn_action'] == 'Edit')
	{
		//update user-table if password has changed
		if($_POST['password'] != '')
		{
			$query = "
			UPDATE user SET 
				fname = '".$_POST["fname"]."', 
				mint = '".$_POST["mint"]."',
				lname = '".$_POST["lname"]."',
				u_name = '".$_POST["u_name"]."',
				u_type = '".$_POST["u_type"]."',
				br_id = '".$_POST["br_id"]."',
				password = '".$_POST["password"]."'
				WHERE u_id = '".$_POST["u_id"]."'
			";
		}
		else
		{
			//update user-table if password hasn't changed
			$query = "
			UPDATE user SET 
				fname = '".$_POST["fname"]."', 
				mint = '".$_POST["mint"]."',
				lname = '".$_POST["lname"]."',
				u_name = '".$_POST["u_name"]."',
				u_type = '".$_POST["u_type"]."',
				br_id = '".$_POST["br_id"]."'
				WHERE u_id = '".$_POST["u_id"]."'
			";
		}
		$statement = $connect->prepare($query);
		$statement->execute();	
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'User Details Edited';
		}
	}

	//set user status to "Inactive" or "Active"
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'Active';
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';
		}
		$query = "
		UPDATE user 
		SET u_status = :u_status 
		WHERE u_id = :u_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':u_status'	=>	$status,
				':u_id'		=>	$_POST["u_id"]
			)
		);	
		$result = $statement->fetchAll();	
		if(isset($result))
		{
			echo 'User Status change to ' . $status;
		}
	}
}

?>
