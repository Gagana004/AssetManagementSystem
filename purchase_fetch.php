<?php

//category_fetch.php

include('database_connection.php');

$query = '';

$output = array();

$query = "
			SELECT 	p.*, v.v_name, i.it_name
			FROM 	purchase AS p, vendor AS v, item AS i
			WHERE 	v.v_id = p.v_id AND
					i.it_id = p.it_id AND
";

if(isset($_POST["search"]["value"]))
{
	$query .= '( p.invoice_no LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR p.p_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR v.v_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR i.it_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR p.p_status LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY p_id DESC ';
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
	if($row['p_status'] == 'active')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['p_id'];
	$sub_array[] = $row['invoice_no'];
	$sub_array[] = $row['p_date'];
	$sub_array[] = $row['v_name'];
	$sub_array[] = $row['it_name'];
	$sub_array[] = $row['p_qty'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["p_id"].'" class="btn btn-warning btn-xs update"><i class="fa fa-edit"></i></button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["p_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["p_status"].'"><i class="fa fa-trash"></i></button>';
	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"				=>	$data
);

function get_total_all_records($connect)
{ 
	$statement = $connect->prepare('SELECT * FROM purchase');
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>