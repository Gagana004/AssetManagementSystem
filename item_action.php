<?php

//item_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'ADD')
	{
		$query = "
					INSERT INTO 	item (cat_id, it_name, it_status) 
					VALUES 			(:cat_id,:it_name,:it_status)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_id'		=>	$_POST["cat_id"],
				':it_name'		=>	$_POST["it_name"],
				// ':it_qty'		=>	$_POST["it_qty"],
				':it_status'	=>	'active'
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'item Name Added';
		}
	}

	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
					SELECT 	* 
					FROM 	item 
					WHERE 	it_id = :it_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':it_id'	=>	$_POST["it_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['cat_id']	= $row['cat_id'];
			$output['it_name']	= $row['it_name'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'EDIT')
	{
		$query = "
					UPDATE 	item 
					SET 	cat_id = :cat_id, it_name = :it_name 
					WHERE 	it_id = :it_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_id'		=>	$_POST["cat_id"],
				':it_name'		=>	$_POST["it_name"],
				':it_id'		=>	$_POST["it_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'item Name Edited';
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
					UPDATE 	item 
					SET 	it_status = :it_status 
					WHERE 	it_id = :it_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':it_status'	=>	$status,
				':it_id'		=>	$_POST["it_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'item status change to ' . $status;
		}
	}
}

?>