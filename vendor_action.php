<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
					INSERT INTO vendor (v_name, v_email, v_tel, v_status) 
					VALUES (:v_name, :v_email, :v_tel, :v_status)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':v_name'	=>	$_POST["v_name"],
				':v_email'	=>	$_POST["v_email"],
				':v_tel'	=>	$_POST["v_tel"],
				':v_status'	=>	$_POST["v_status"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category Added';
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "	SELECT 	* 
					FROM 	vendor 
					WHERE 	v_id = :v_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':v_id'	=>	$_POST["v_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['v_name'] 	= $row['v_name'];
			$output['v_email'] 	= $row['v_email'];
			$output['v_tel']	= $row['v_tel'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
					UPDATE 	vendor 
					SET		v_name = :v_name, v_email = :v_email, v_tel = :v_tel  
					WHERE 	v_id = :v_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':v_name'	=>	$_POST["v_name"],
				':v_email'	=>	$_POST["v_email"],
				':v_tel'	=>	$_POST["v_tel"],
				':v_id'		=>	$_POST["v_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Vendor Edited';
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';	
		}
		$query = "
				UPDATE 	vendor 
				SET 	v_status = :v_status 
				WHERE 	v_id = :v_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':v_status'	=>	$status,
				':v_id'		=>	$_POST["v_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'vendor status change to ' . $status;
		}
	}
}

?>