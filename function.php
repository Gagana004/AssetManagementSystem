<?php 
//function.php

// fill dropdowns
// fill category list dropdown
function fill_category_list($connect)
{
	$query = "
				SELECT 	* 
				FROM 	category 
				WHERE 	cat_status = 'active' 
				ORDER BY cat_name ASC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["cat_id"].'">'.$row["cat_name"].'</option>';
	}
	return $output;
}
 

// fill branch list dropdown
function fill_branch_list($connect)
{
	$query = "
			SELECT * FROM branch 
			ORDER BY br_id ASC
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["br_id"].'">'.$row["br_name"].'</option>';
	}
	return $output;
}

// fill Item list dropdown for selected category
function fill_item_list($connect, $cat_id)
{
	$query = "
				SELECT 	* 
				FROM 	item 
				WHERE 	it_status = 'active' AND 
						cat_id = '".$cat_id."'
				ORDER BY it_name ASC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '<option value="">Select Item</option>';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["it_id"].'">'.$row["it_name"].'</option>';
	}
	return $output;
}

// fill Item list dropdown
function fill_item_list_two($connect)
{
	$query = "
				SELECT 	* 
				FROM 	item
				WHERE 	it_status = 'active'
				ORDER BY it_name ASC";

	$statement = $connect-> prepare($query);
	$statement -> execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach ($result as $row) 
	{
		$output .= '<option value="'.$row["it_id"].'">'.$row["it_name"].'</option>';
	}
	return $output;
}

// fill Vendor list dropdown
function fill_vendor_list($connect)
{
	$query = "
			SELECT 	* 
			FROM 	vendor 
			WHERE 	v_status = 'active' 
			ORDER BY v_name ASC
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["v_id"].'">'.$row["v_name"].'</option>';
	}
	return $output;
}

// fill area list dropdown
function fill_area_list($connect)
{
	$query= "	SELECT 	* 
				FROM 	area 
				ORDER BY ar_id";

	$statement = $connect-> prepare($query);
	$statement -> execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach ($result as $row) 
	{
		$output .= '<option value="' .$row["ar_id"].'">'.$row["ar_code"].'</option>';
	}
	return $output;
}

// counts display in index page
// count all users in the system
function count_total_user($connect)
{
	$query = "
				SELECT 	* 
				FROM 	user 
				WHERE 	u_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount(); //only return row count
}

// count all categories in the system
function count_total_category($connect)
{
	$query = "
				SELECT 	* 
				FROM 	category 
				WHERE 	cat_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// count all Items in the system
function count_total_item($connect)
{
	$query = "
				SELECT 	* 
				FROM 	item 
				WHERE 	it_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// count all Assets in the system
function count_total_asset($connect)
{
	$query = "
				SELECT 	* 
				FROM 	asset 
				WHERE 	ast_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// count all vendors in the system
function count_total_vendor($connect)
{
	$query = "
				SELECT 	* 
				FROM 	vendor 
				WHERE 	v_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// count all purchase records in the system
function count_total_purchase($connect)
{
	$query = "
				SELECT 	* 
				FROM 	purchase 
				WHERE 	p_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// count all transfers in the system
function count_total_transfer($connect)
{
	$query = "
				SELECT 	t.* 
				FROM 	transfer AS t, dispatch AS d 
				WHERE 	t.tr_dis_id = d.dis_id AND
						d.dis_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// count all urepairs in the system
function count_total_repair($connect)
{
	$query = "
				SELECT 	r.* 
				FROM 	repair AS r, dispatch AS d 
				WHERE 	r.r_dis_id = d.dis_id AND
						d.dis_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount(); 
}

// count all disposal in the system
function count_total_disposal($connect)
{
	$query = "
				SELECT 	di.* 
				FROM 	disposal AS di, dispatch AS d 
				WHERE 	di.d_dis_id = d.dis_id AND
						d.dis_status='active'
			";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
?>