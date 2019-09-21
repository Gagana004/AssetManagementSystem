<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'ADD')
	{
		$query = "
		INSERT INTO purchase (invoice_no, p_date, p_qty, v_id, it_id, p_status) 
		VALUES (:invoice_no, :p_date, :p_qty, :v_id, :it_id, :p_status)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':invoice_no'	=>	$_POST["invoice_no"],
				':p_date'		=>	$_POST["p_date"],
				':p_qty'		=>	$_POST["p_qty"],
				':v_id'			=>	$_POST["v_id"],
				':it_id'		=>	$_POST["it_id"],
				':p_status'		=>	'active'
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Purchase Added';
		}

		#update qty of asset
		
		$p_qty  = $_POST['p_qty'];
		$it_id 	= $_POST['it_id'];	
		$query  = "	UPDATE 	item
 					SET 	it_qty  = it_qty + $p_qty
 					WHERE 	it_id = $it_id
				";
		$statement = $connect->prepare($query);
		$statement->execute();
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM purchase WHERE p_id = :p_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':p_id'	=>	$_POST["p_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['invoice_no'] 	= 	$row['invoice_no'];
			$output['p_date'] 		= 	$row['p_date'];
			$output['p_qty'] 		= 	$row['p_qty'];
			$output['v_id'] 		= 	$row['v_id'];
			$output['it_id'] 		= 	$row['it_id'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'EDIT')
	{
		$query = "
					UPDATE 	purchase 
					SET 	invoice_no = :invoice_no, p_date = :p_date, p_qty = :p_qty, v_id = :v_id, it_id = :it_id  
					WHERE 	p_id = :p_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':invoice_no'	=>	$_POST["invoice_no"],
				':p_date'		=>	$_POST["p_date"],
				':p_qty'		=>	$_POST["p_qty"],
				':v_id'			=>	$_POST["v_id"],
				':it_id'		=>	$_POST["it_id"],
				':p_id'			=>	$_POST["p_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Purchase Edited';
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
					UPDATE 	purchase 
					SET 	p_status = :p_status 
					WHERE 	p_id = :p_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':p_status'	=>	$status,
				':p_id'		=>	$_POST["p_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Purchase status change to ' . $status;
		}
	}
}

?>