<?php

//repair_action.php

include('database_connection.php');
include('function.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'load_asset_code') //load asset code to the dropdown menu in repair.php
	{
		echo fill_ast_code_list($connect, $_POST['it_id']); //pass selected item_id to the function 
	}
	if($_POST['btn_action'] == 'ADD')
	{
		//insert data in to Dipatch table
		$query = "
					INSERT INTO dispatch (dis_date, frm_area, dis_status, u_id, ast_id, it_id) 
					VALUES (:dis_date, :frm_area, :dis_status, :u_id, :ast_id, :it_id)				
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':dis_date'		=>	date("Y-m-d"),
				':frm_area'		=>	$_POST["frm_area"],
				':dis_status'	=>	'active',
				':u_id'			=>	$_SESSION['u_id'],
				':ast_id'		=>	$_POST['ast_id'],
				':it_id'		=>	$_POST['it_id']
			)
		); 

		$lastInsertIDQry = "SELECT last_insert_id() as max";  // select last insert id from Dispatch table

		$statement1 = $connect->prepare($lastInsertIDQry);
		$statement1->execute();
		$lastInsertIDQ = $statement1->fetch();

		//insert data into Repair table using  key as dispatch table's last insert_id 
		$query = "  INSERT INTO repair (r_dis_id, return_date) 
					VALUES (:r_dis_id, :return_date)";

		$statement = $connect->prepare($query);
		$statement->execute(
			array(	':r_dis_id' => $lastInsertIDQ['max'],
					':return_date'	=> $_POST["return_date"]
				)
		);

		if(isset($result))
		{
			echo 'Repair Added';
		}
	}
	
	// if($_POST['btn_action'] == 'fetch_single')
	// {
	// 	$query = "	SELECT 	d.* 
	// 				FROM 	dispatch AS d, repair AS r 
	// 				WHERE 	d.dis_id = :dis_id AND
	// 						d.dis_id = r.r_dis_id
	// 			";
	// 	$statement = $connect->prepare($query);
	// 	$statement->execute(
	// 		array(
	// 			':dis_id'	=>	$_POST["dis_id"]
	// 		)
	// 	);
	// 	$result = $statement->fetchAll();
	// 	foreach($result as $row)
	// 	{
	// 		$output['it_id'] 	= $row['it_id'];
	// 		$output['ast_id'] 	= $row['ast_id'];
	// 		$output['frm_area']	= $row['frm_area'];
	// 	}
	// 	echo json_encode($output);
	// }

	// if($_POST['btn_action'] == 'Edit')
	// {
	// 	$query = "
	// 				UPDATE 	dispatch 
	// 				SET		it_id = :it_id, ast_id = :ast_id, frm_area = :frm_area  
	// 				WHERE 	dis_id = :dis_id
	// 	";
	// 	$statement = $connect->prepare($query);
	// 	$statement->execute(
	// 		array(
	// 			':it_id'		=>	$_POST["it_id"],
	// 			':ast_id'		=>	$_POST["ast_id"],
	// 			':frm_area'		=>	$_POST["frm_area"],
	// 			':dis_id'		=>	$_POST["dis_id"]
	// 		)
	// 	);
	// 	$result = $statement->fetchAll();
	// 	if(isset($result))
	// 	{
	// 		echo 'Repair Edited';
	// 	}
	// }
	 
	// set status of record active or inactive
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';	
		}
		$query = "
				UPDATE 	dispatch 
				SET 	dis_status = :dis_status 
				WHERE 	dis_id = :dis_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':dis_status'	=>	$status,
				':dis_id'		=>	$_POST["dis_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Repair status change to ' . $status; // show message to user weather active or inactive
		}
	}
}

?>