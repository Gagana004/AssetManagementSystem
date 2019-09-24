<?php

//item_fetch.php
// print_r($_REQUEST);
include('database_connection.php');

$query = '';

$output = array();

$query .= "
			SELECT 	i.*, c.cat_name
			FROM	item AS i, category AS c
			WHERE	i.cat_id = c.cat_id AND
";

if(isset($_POST["search"]["value"]))
{	

	$query .= '(i.it_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR c.cat_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR i.it_status LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY i.it_id DESC ';
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
	if($row['it_status'] == 'active')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['it_id'];
	$sub_array[] = $row['cat_name'];
	$sub_array[] = $row['it_name'];
	$sub_array[] = $row['it_qty'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["it_id"].'" class="btn btn-warning btn-xs update"><i class="fa fa-edit"></i></button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["it_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["it_status"].'"><i class="fa fa-trash"></i></button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare('SELECT * FROM item');
	$statement->execute();
	return $statement->rowCount();
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=>	$filtered_rows,
	"recordsFiltered"	=>	get_total_all_records($connect),
	"data"				=>	$data
);

echo json_encode($output);

?>