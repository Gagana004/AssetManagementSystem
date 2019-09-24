<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'ADD')
	{
		$query = "
					INSERT INTO category (cat_name, cat_desc, cat_status) 
					VALUES (:cat_name, :cat_desc, :cat_status)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_name'			=>	$_POST["cat_name"],
				':cat_desc'			=>	$_POST["cat_desc"],
				':cat_status'		=>	'active'
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
		$query = "
					SELECT 	* 
					FROM 	category 
					WHERE 	cat_id = :cat_id
				";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_id'	=>	$_POST["cat_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['cat_name'] = $row['cat_name'];
			$output['cat_desc'] = $row['cat_desc'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'EDIT')
	{
		$query = "
					UPDATE 	category 
					SET 	cat_name = :cat_name, cat_desc = :cat_desc  
					WHERE 	cat_id = :cat_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_name'		=>	$_POST["cat_name"],
				':cat_desc'		=>	$_POST["cat_desc"],
				':cat_id'		=>	$_POST["cat_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category Edited';
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
					UPDATE 	category 
					SET 	cat_status = :cat_status 
					WHERE 	cat_id = :cat_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_status'	=>	$status,
				':cat_id'		=>	$_POST["cat_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category status change to ' . $status;
		}
	}
}

?>