<?php

//repair_fetch.php

include('database_connection.php');

$query = '';

$output = array();

$query .= "	
			SELECT 	d.dis_id, d.dis_date, d.dis_status,i.it_name, u.fname, a.ast_code,r.return_date,
					(	SELECT 	ar.ar_code 
						FROM 	area AS ar 
						WHERE 	ar.ar_id = d.frm_area) AS `from`
			FROM 	dispatch as d, repair as r, item as i, user as u, asset as a
			WHERE 	d.dis_id = r.r_dis_id AND
					d.it_id = i.it_id AND
        			d.u_id = u.u_id AND
        			d.ast_id = a.ast_id AND
";

if(isset($_POST["search"]["value"]))
{
	$query .= '( d.dis_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR i.it_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR u.fname LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (	SELECT 	ar.ar_code 
					FROM 	area AS ar 
					WHERE 	ar.ar_id = d.frm_area) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR r.return_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR a.ast_code LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY dis_id DESC ';
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
	if($row['dis_status'] == 'active')
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['dis_id'];
	$sub_array[] = $row['it_name'];
	$sub_array[] = $row['ast_code'];
	$sub_array[] = $row['from'];
	$sub_array[] = $row['fname'];
	$sub_array[] = $row['dis_date'];
	$sub_array[] = $row['return_date'];
	$sub_array[] = $status;
	// $sub_array[] = '<button type="button" name="update" id="'.$row["dis_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["dis_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["dis_status"].'"><i class="fa fa-trash"></i></button>';
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
	$statement = $connect->prepare("SELECT 	* 
									FROM 	dispatch AS d, repair AS r
									WHERE 	d.dis_id = r.r_dis_id ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

