<?php
//item.php
include('database_connection.php');

include('function.php');

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
                	<div class="row">
                		<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                			<h3 class="panel-title">Item List</h3>
                		</div>
                		<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                			<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">ADD</button>
						</div>
                	</div>
                </div>
                <div class="panel-body">
                	<table id="item_data" class="table">
                		<thead>
							<tr>
								<th>ID</th>
								<th>Category</th>
								<th>Item Name</th>
								<th>Qty</th>
								<th>Status</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
                	</table>
                </div>
            </div>
        </div>
    </div>

    <div id="itemModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="item_form">			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Add Item </h4>
				</div>
				<div class="modal-content">
    				<div class="modal-body">
    					<div class="form-group">
    						<label>Select Category</label>
    						<select name="cat_id" id="cat_id" class="form-control" required>
								<option value="">Select Category</option>
								<?php echo fill_category_list($connect); ?>
							</select>
    					</div>
    					<div class="form-group">
							<label>Enter Item Name</label>
							<input type="text" name="it_name" id="it_name" class="form-control" required />
						</div>
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="it_id" id="it_id" />
    					<input type="hidden" name="btn_action" id="btn_action" />
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
		$('#itemModal').modal('show');
		$('#item_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add Item");
		$('#action').val('ADD');
		$('#btn_action').val('ADD');
	});

	$(document).on('submit','#item_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"item_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#item_form')[0].reset();
				$('#itemModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#action').attr('disabled', false);
				itemdataTable.ajax.reload();
			}
		})
	});

	$(document).on('click', '.update', function(){
		var it_id = $(this).attr("id");
		var btn_action = 'fetch_single';
		$.ajax({
			url:'item_action.php',
			method:"POST",
			data:{it_id:it_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#itemModal').modal('show');
				$('#cat_id').val(data.cat_id);
				$('#it_name').val(data.it_name);
				$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Item");
				$('#it_id').val(it_id);
				$('#action').val('EDIT');
				$('#btn_action').val('EDIT');
			}
		})
	});

	$(document).on('click','.delete', function(){
		var it_id = $(this).attr("id");
		var status  = $(this).data('status');
		var btn_action = 'delete';
		if(confirm("Are you sure you want to change status?"))
		{
			$.ajax({
				url:"item_action.php",
				method:"POST",
				data:{it_id:it_id, status:status, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
					itemdataTable.ajax.reload();
				}
			})
		}
		else
		{
			return false;
		}
	});


	var itemdataTable = $('#item_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"item_fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[2,3,5,6],
				"orderable":false,
			},
		],
		"pageLength": 25
	});

});
</script>


<?php
include('footer.php');
?>