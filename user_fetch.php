<?php

//user_fetch.php

include('database_connection.php');

$query = '';

$output = array();

$query .= "
			SELECT 	u.*, b.br_name
			FROM	user as u, branch as b
			WHERE 	u.br_id = b.br_id AND
";

if(isset($_POST["search"]["value"]))
{
	$query .= '(u_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR fname LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR u_type LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR u_status LIKE "%'.$_POST["search"]["value"].'%") ';
}

if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY u_id DESC ';
}

if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	$status = '';
	if($row["u_status"] == 'Active')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['u_id'];
	$sub_array[] = $row['br_name'];
	$sub_array[] = $row['u_name'];
	$sub_array[] = $row['fname'];
	$sub_array[] = $row['u_type'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["u_id"].'" class="btn btn-xs update"><i class="fa fa-edit"></i></button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["u_id"].'" class="btn btn-xs delete" data-status="'.$row["u_status"].'"><i class="fa fa-trash"></i></button>';
	$data[] = $sub_array;
}

// set output to array and json_encode & send to user.php
$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);
echo json_encode($output);

// get number of records in user-table
function get_total_all_records($connect)
{
	$statement = $connect->prepare("SELECT * FROM user");
	$statement->execute();
	return $statement->rowCount();
}

?>