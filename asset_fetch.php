<?php

//asset_fetch.php

include('database_connection.php');
include('function.php');

$query = '';

$output = array();
$query .= "
					SELECT 	a.*, i.it_name, ar.ar_code, u.fname, c.cat_name
					FROM 	asset AS a, item AS i, area as ar, user as u, category as c 
					WHERE 	i.it_id = a.it_id AND 
					 		ar.ar_id = a.ar_id AND
				 			u.u_id = a.u_id AND
				 			c.cat_id = a.cat_id AND
 	";

if(isset($_POST["search"]["value"]))
{
	$query .= '( i.it_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR ar.ar_code LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR a.ast_sn LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR a.ast_code LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR u.fname LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR a.ast_id LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY ast_id DESC ';
}

if($_POST['length'] != -1)
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
	if($row['ast_status'] == 'active')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['ast_id'];
	$sub_array[] = $row['cat_name'];
	$sub_array[] = $row['it_name'];
	$sub_array[] = $row['ar_code'];
	$sub_array[] = $row['ast_sn'];
	$sub_array[] = $row['ast_code'];
	$sub_array[] = $row['fname'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="view" id="'.$row["ast_id"].'" class="btn btn-xs view"><i class="fa fa-eye"></i></button>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["ast_id"].'" class="btn btn-xs update"><i class="fa fa-edit"></i></button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["ast_id"].'" class="btn btn-xs delete" data-status="'.$row["ast_status"].'"><i class="fa fa-trash"></i></button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare('SELECT * FROM asset');
	$statement->execute();
	return $statement->rowCount();
}

$output = array(
	"draw"    			=> 	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);

echo json_encode($output);
?>





