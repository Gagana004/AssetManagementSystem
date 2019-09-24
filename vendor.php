<?php
//vendor.php

include('database_connection.php');

if(!isset($_SESSION['type']))
{
	header('location:login.php');
}

include('header.php');

?>

	<span id="alert_action"></span>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        <div class="row">
                            <h3 class="panel-title">Vendor List</h3>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                        <div class="row" align="right">
                             <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#vendorModal" class="btn btn-success btn-xs">ADD</button>   		
                        </div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-sm-12 table-responsive">
                    		<table id="vendor_data" class="table">
                    			<thead><tr>
									<th>ID</th>
									<th>Vendor Name</th>
									<th>Email</th>
									<th>Tel.</th>
									<th>Status</th>
									<th></th>
									<th></th>
								</tr></thead>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="vendorModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="vendor_form"> 			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Add Vendor</h4>
				</div>
				<div class="modal-content">
    				<div class="modal-body">
    					<label>Vendor Name</label>
						<input type="text" name="v_name" id="v_name" class="form-control" required />
						<label>Vendor Email </label>
						<input type="email" name="v_email" id="v_email" class="form-control" required/>
						<label>Vendor Tel </label>
						<input type="text" name="v_tel" id="v_tel" class="form-control" />
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="v_id" id="v_id"/>
    					<input type="hidden" name="btn_action" id="btn_action"/>
    					<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
						<input type="submit" name="action" id="action" class="btn btn-submit"/>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
    
<script>	
$(document).ready(function(){

	$('#add_button').click(function(){
		$('#vendor_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add Vendor");
		$('#action').val('ADD');
		$('#btn_action').val('ADD');
	});

	$(document).on('submit','#vendor_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"vendor_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#vendor_form')[0].reset();
				$('#vendorModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#action').attr('disabled', false);
				vendordataTable.ajax.reload();
			}
		})
	});

	$(document).on('click', '.update', function(){
		var v_id = $(this).attr("id");
		var btn_action = 'fetch_single';
		$.ajax({
			url:"vendor_action.php",
			method:"POST",
			data:{v_id:v_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#vendorModal').modal('show');
				$('#v_name').val(data.v_name);
				$('#v_email').val(data.v_email);
				$('#v_tel').val(data.v_tel);
				$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit vendor");
				$('#v_id').val(v_id);
				$('#action').val('EDIT');
				$('#btn_action').val("EDIT");
			}
		})
	});

	var vendordataTable = $('#vendor_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"vendor_fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[1,2,3,5,6],
				"orderable":false,
			},
		],
		"pageLength": 25
	});

	$(document).on('click', '.delete', function(){
		var v_id = $(this).attr('id');
		var status = $(this).data("status");
		var btn_action = 'delete';
		if(confirm("Are you sure you want to change status?"))
		{
			$.ajax({
				url:"vendor_action.php",
				method:"POST",
				data:{v_id:v_id, status:status, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
					vendordataTable.ajax.reload();
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
				