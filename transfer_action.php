<?php

//transfer_action.php

include('database_connection.php');
include('function.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'load_asset_code')
	{
		echo fill_ast_code_list($connect, $_POST['it_id']);
	}
	if($_POST['btn_action'] == 'Add')
	{
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

		$lastInsertIDQry = "SELECT last_insert_id() as max";

		$statement1 = $connect->prepare($lastInsertIDQry);
		$statement1->execute();
		$lastInsertIDQ = $statement1->fetch();

		$query = "  INSERT INTO transfer (tr_dis_id, tr_to_area) 
					VALUES (:tr_dis_id, :to_area)";

		$statement = $connect->prepare($query);
		$statement->execute(
			array(	':tr_dis_id' 	=> 	$lastInsertIDQ['max'],
					':to_area'		=> 	$_POST["to_area"]
				) 
		);

		$result = $statement->fetchAll();

		//Update Area of Asset Table 
		$to_area  = $_POST['to_area'];
		$ast_id = $_POST['ast_id'];	
		$query1 = "	UPDATE 	asset
 					SET 	ar_id  = $to_area
 					WHERE 	ast_id = $ast_id
		";
		$statement1 = $connect->prepare($query1);
		$statement1->execute();

		if(isset($result))
		{
			echo 'Dispatch Added ID';
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "	SELECT 	d.* 
					FROM 	dispatch AS d, transfer AS t
					WHERE 	d.dis_id = :dis_id AND
							d.dis_id = t.tr_dis_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':dis_id'	=>	$_POST["dis_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['it_id'] 	= $row['it_id'];
			$output['ast_id'] 	= $row['ast_id'];
			$output['frm_area']	= $row['frm_area'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
					UPDATE 	dispatch 
					SET		it_id = :it_id, ast_id = :ast_id, frm_area = :frm_area  
					WHERE 	dis_id = :dis_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':it_id'		=>	$_POST["it_id"],
				':ast_id'		=>	$_POST["ast_id"],
				':frm_area'		=>	$_POST["frm_area"],
				':dis_id'		=>	$_POST["dis_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Repair Edited';
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
			echo 'repair status change to ' . $status;
		}
	}
}

?>