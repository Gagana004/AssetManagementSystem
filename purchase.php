<?php
//purchase.php
//
include('database_connection.php');

if(!isset($_SESSION['type']))
{
	header('location:login.php');
}

include('header.php');
include('function.php');

?>


	<link rel="stylesheet" href="css/datepicker.css">
	<script src="js/bootstrap-datepicker1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

	<script>
	$(document).ready(function(){
		$('#p_date').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true
		});
	});
	</script>


	<span id="alert_action"></span>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        <div class="row">
                            <h3 class="panel-title">Purchase List</h3>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                        <div class="row" align="right">
                             <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#purchaseModal" class="btn btn-success btn-xs">Add</button>   		
                        </div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-sm-12 table-responsive">
                    		<table id="purchase_data" class="table table-bordered table-striped">
                    			<thead><tr>
									<th>ID</th>
									<th>Invoice No</th>
									<th>Date</th>
									<th>Vendor Name</th>
									<th>Item Name</th>
									<th>Qty</th>
									<th>Status</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr></thead>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="purchaseModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="purchase_form">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Add Purchase</h4>
    				</div>
    				<div class="modal-body">
    					<div class="form-group">
    						<label>Invoice No</label>
							<input type="text" name="invoice_no" id="invoice_no" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Date</label>
							<input type="text" name="p_date" id="p_date" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Vendor</label>
    						<select name="v_id" id="v_id" class="form-control" required>
								<option value="">Select Vendor</option>
								<?php echo fill_vendor_list($connect); ?>
							</select>
						</div>
						<div class="form-group">
							<label>Item</label>
    						<select name="it_id" id="it_id" class="form-control" required>
								<option value="">Select Item</option>
								<?php echo fill_item_list_two($connect); ?>
							</select>
						</div>
						<div class="form-group">
							<label>Qty</label>
							<input type="number" name="p_qty" id="p_qty" class="form-control" required />
						</div>
					</div>
    				<div class="modal-footer">
    					<input type="hidden" name="p_id" id="p_id"/>
    					<input type="hidden" name="btn_action" id="btn_action"/>
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    					<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
<script>

$(document).ready(function(){

	$('#add_button').click(function(){
		$('#purchaseModal').modal('show');
		$('#purchase_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add Purchase");
		$('#action').val('Add');
		$('#btn_action').val('Add');
	});

	$(document).on('submit','#purchase_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"purchase_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#purchase_form')[0].reset();
				$('#purchaseModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#action').attr('disabled', false);
				purchasedataTable.ajax.reload();
			}
		})
	});

	$(document).on('click', '.update', function(){
		var p_id = $(this).attr("id");
		var btn_action = 'fetch_single';
		$.ajax({
			url:"purchase_action.php",
			method:"POST",
			data:{p_id:p_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#purchaseModal').modal('show');
				$('#invoice_no').val(data.invoice_no);
				$('#p_date').val(data.p_date);
				$('#p_qty').val(data.p_qty);
				$('#vendor_id').val(data.vendor_id);
				$('#it_id').val(data.it_id);
				$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Purchase");
				$('#p_id').val(p_id);
				$('#action').val('Edit');
				$('#btn_action').val("Edit");
			}
		})
	});

	var purchasedataTable = $('#purchase_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"purchase_fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[5,7,8],
				"orderable":false,
			},
		],
		"pageLength": 25
	});
	$(document).on('click', '.delete', function(){
		var p_id = $(this).attr('id');
		var status = $(this).data('status');
		var btn_action = 'delete';
		if(confirm("Are you sure you want to change status?"))
		{
			$.ajax({
				url:"purchase_action.php",
				method:"POST",
				data:{p_id:p_id, status:status, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
					purchasedataTable.ajax.reload();
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
				