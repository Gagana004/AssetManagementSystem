<?php

//asset_action.php

include('database_connection.php');

include('function.php');


if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'load_item')
	{
		echo fill_item_list($connect, $_POST['cat_id']);
	}

	if($_POST['btn_action'] == 'Add')
	{
		$query = "
					INSERT INTO 	asset (ast_code, ast_sn, ast_desc, ast_status, ar_id, it_id, cat_id, u_id) 
					VALUES 			(:ast_code, :ast_sn, :ast_desc, :ast_status, :ar_id, :it_id, :cat_id, :u_id)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':ast_code'		=>	$_POST['ast_code'],
				':ast_sn'		=>	$_POST['ast_sn'],
				':ast_desc'		=>	$_POST['ast_desc'],
				':ast_status'	=>	'active',
				':ar_id'		=>	$_POST['ar_id'],
				':it_id'		=>	$_POST['it_id'],
				':cat_id'		=>	$_POST['cat_id'],
				':u_id'			=>	$_SESSION['u_id']
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Asset Added';
		}

		#update qty of asset
		
		$it_id = $_POST['it_id'];	
		$query = "	UPDATE 	item
 					SET 	it_qty  = (it_qty - 1)
 					WHERE 	it_id   = $it_id
 				";
		$statement = $connect->prepare($query);
		$statement->execute();


	}
	if($_POST['btn_action'] == 'asset_details')
	{	$query = "
					SELECT 	a.*, i.it_name, ar.ar_code, u.fname, c.cat_name
					FROM 	asset AS a, item AS i, area as ar, user as u, category as c
					WHERE 	i.it_id = a.it_id AND 
					 		ar.ar_id = a.ar_id AND
				 			u.u_id = a.u_id AND 
				 			c.cat_id = a.cat_id AND 
					 		a.ast_id = '".$_POST["ast_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '
		<div class="table-responsive">
			<table class="table table-boredered">
		';
		foreach($result as $row)
		{
			$status = '';
			if($row['ast_status'] == 'active')
			{
				$status = '<span class="label label-success">Active</span>';
			}
			else
			{
				$status = '<span class="label label-danger">Inactive</span>';
			}
			$output .= '
			<tr>
				<td>Area Code</td>
				<td>'.$row["ar_code"].'</td>
			</tr>
			<tr>
				<td>S/N</td>
				<td>'.$row["ast_sn"].'</td>
			</tr>
			<tr>
				<td>Asset Code</td>
				<td>'.$row["ast_code"].'</td>
			</tr>
			<tr>
				<td>Category</td>
				<td>'.$row["cat_name"].'</td>
			</tr>
			<tr>
				<td>Item</td>
				<td>'.$row["it_name"].'</td>
			</tr>
			<tr>
				<td>Item Description</td>
				<td>'.$row["ast_desc"].'</td>
			</tr>
			<tr>
				<td>Enter By</td>
				<td>'.$row["fname"].'</td>
			<tr>
				<td>Status</td>
				<td>'.$status.'</td>
			</tr>
			';
		}
		$output .= '
			</table>
		</div>
		';
		echo $output;
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
					SELECT 	* 
					FROM 	asset 
					WHERE 	ast_id = :ast_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':ast_id'	=>	$_POST["ast_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['cat_id'] = $row['cat_id'];
			$output['it_id'] = $row['it_id'];
			$output["item_select_box"] = fill_item_list($connect, $row["cat_id"]);
			$output['ar_id'] = $row['ar_id'];
			$output['ast_sn'] = $row['ast_sn'];
			$output['ast_code'] = $row['ast_code'];
			$output['ast_desc'] = $row['ast_desc'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
					UPDATE 	asset 
					SET  	it_id = :it_id,
							cat_id = :cat_id,
							ar_id = :ar_id,
							ast_sn = :ast_sn,
							ast_code = :ast_code,
							ast_desc = :ast_desc
					WHERE 	ast_id = :ast_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':cat_id'				=>	$_POST['cat_id'],
				':it_id'				=>	$_POST['it_id'],
				':ar_id'				=>	$_POST['ar_id'],
				':ast_sn'				=>	$_POST['ast_sn'],
				':ast_code'				=>	$_POST['ast_code'],
				':ast_desc'				=>	$_POST['ast_desc'],
				':ast_id'				=>	$_POST['ast_id']
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Asset Details Edited';
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
				UPDATE 		asset
				SET 		ast_status = :ast_status 
				WHERE 		ast_id = :ast_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':ast_status'	=>	$status,
				':ast_id'		=>	$_POST["ast_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Asset status change to ' . $status;
		}
	}
}


?>



