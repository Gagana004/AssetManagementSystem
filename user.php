<?php
//user.php

include('database_connection.php');
include('function.php');

if(!isset($_SESSION["type"]))
{
	header('location:login.php');
}

//only master user can accesss this page
if($_SESSION["type"] != 'master')
{
	header("location:index.php");
}

include('header.php');


?>
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="row">
                        	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">User List</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                            	<button type="button" name="add" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-success btn-xs">Add</button>
                        	</div>
                        </div>
                       
                        <div class="clear:both"></div>
                   	</div>
                   	<div class="panel-body">
                   		<div class="row"><div class="col-sm-12 table-responsive">
                   			<table id="user_data" class="table table-bordered table-striped">
                   				<thead>
									<tr>
										<th>ID</th>
										<th>Branch</th>
										<th>Username</th>
										<th>Name</th>
										<th>Type</th>
										<th>Status</th>
										<th>Edit</th>
										<th>Delete</th>
									</tr>
								</thead>
                   			</table>
                   		</div>
                   	</div>
               	</div>
           	</div>
        </div>
        <div id="userModal" class="modal fade">
        	<div class="modal-dialog">
        		<form method="post" id="user_form">
        			<div class="modal-content">
	        			<div class="modal-header">
	        				<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"></h4>
	        			</div>
	        			<div class="modal-body">
	        				<div class="form-group">
								<label>Enter First Name</label>
								<input type="text" name="fname" id="fname" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Enter Mint</label>
								<input type="text" name="mint" id="mint" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Enter Last Name</label>
								<input type="text" name="lname" id="lname" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Enter Username</label>
								<input type="text" name="u_name" id="u_name" class="form-control" required />
							</div>
							<div class="form-group">
								  <label>Select User Type</label>
								<select name="u_type" id="u_type" class="form-control" required>
	                                    <option value="">Select User Type</option>
	                                    <option value="master">Master</option> 
	                                    <option value="user">User</option>  
	                         	</select>
	                     	</div>
	                     	<div class="form-group">
	    						<label>Select Branch</label>
	    						<select name="br_id" id="br_id" class="form-control" required>
									<option value="">Select Branch</option>
									<?php echo fill_branch_list($connect); ?>
								</select>
	    					</div>
							<div class="form-group">
								<label>Enter User Password</label>
								<input type="password" name="password" id="password" class="form-control" required />
							</div>
	        			</div>
	        			<div class="modal-footer">
	        				<input type="hidden" name="u_id" id="u_id" /> <!-- User id is hidden -->
	        				<input type="hidden" name="btn_action" id="btn_action" /> <!-- btn_action is hidden -->
	        				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        				<input type="submit" name="action" id="action" class="btn btn-info"/>
	        			</div>
        			</div>
        		</form>
        	</div>
        </div>
		
<script>
$(document).ready(function(){

	//add-user form loading
	$('#add_button').click(function(){
		$('#user_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add User");//Set model title -> "Add user" 
		$('#action').val("Add");//assign "Add" value to submit button in "model-footer"
		$('#btn_action').val("Add");//assign "Add" value to hidden "btn_action" in "model-footer"
	});

	//datatable sorting & Load data to DataTable 
	var userdataTable = $('#user_data').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],

		"ajax":{
			url:"user_fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[6,7],
				"orderable":false
			}
		],
		"pageLength": 25
	});

	//submit new user deatils and pass data to user_action.php
	$(document).on('submit', '#user_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"user_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#user_form')[0].reset();
				$('#userModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#action').attr('disabled', false);
				userdataTable.ajax.reload();
			}
		})
	});

	//load previous data to update-form and pass updated user-data to user-action.php 
	$(document).on('click', '.update', function(){
		var u_id = $(this).attr("id");  //pass u_id to the user_action.php
		var btn_action = 'fetch_single';
		$.ajax({
			url:"user_action.php",
			method:"POST",
			data:{u_id:u_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#userModal').modal('show'); //show update form 
				$('#fname').val(data.fname); //load previous user data to update form
				$('#mint').val(data.mint);
				$('#lname').val(data.lname);
				$('#u_name').val(data.u_name);
				$('#u_type').val(data.u_type);
				$('#br_id').val(data.br_id);
				$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit User"); //Add model title -> "Edit user" 
				$('#u_id').val(u_id);	//assign "u_id" value  to hidden "u_id" in "model-footer"
				$('#action').val('Edit'); 	//assign "Edit" value to submit button in "model-footer"
				$('#btn_action').val('Edit');	//assign "Edit" value to hidden "btn_action" in "model-footer"
				$('#password').attr('required', false);
			}
		})
	});

	//change user status when press delete button
	$(document).on('click', '.delete', function(){
		var u_id = $(this).attr("id");
		var status = $(this).data('status');
		var btn_action = "delete";
		if(confirm("Are you sure you want to change status?")) //confirm allert
		{
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:{u_id:u_id, status:status, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
					userdataTable.ajax.reload();
				}
			})
		}
		else
		{
			return false;
		}
	});
});
</script>

<?php
include('footer.php');
?>

