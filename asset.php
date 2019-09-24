<?php
//asset.php

include('database_connection.php');
include('function.php');

if(!isset($_SESSION["type"]))
{
    header('location:login.php');
}

include('header.php');


?>
        <span id='alert_action'></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">Asset List</h3>
                            </div>
                        
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
                                <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">ADD</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="asset_data" class="table">
                                <thead><tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Item Name</th>
                                    <th>Area</th>
                                    <th>S/N</th>
                                    <th>Ast Code</th>
                                    <th>Enter By</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr></thead>
                            </table>
                        </div></div>
                    </div>
                </div>
			</div>
		</div>

        <div id="assetModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="asset_form">                  
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Asset</h4>
                    </div>
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Select Category</label>
                                <select name="cat_id" id="cat_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php echo fill_category_list($connect);?> 
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Item</label>
                                <select name="it_id" id="it_id" class="form-control" required>
                                    <option value="">Select Item</option> <!-- items will load using selected category -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Enter Area</label>
                                <select name="ar_id" id="ar_id" class="form-control" required>
                                    <option value="">Select Area</option>
                                    <?php echo fill_area_list($connect);?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Enter Serial No</label>
                                <input type="text" name="ast_sn" id="ast_sn" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Enter Asset Code</label>
                                <input type="text" name="ast_code" id="ast_code" class="form-control"required>
                            </div>
                            <div class="form-group">
                                <label>Enter Asset Description</label>
                                <textarea name="ast_desc" id="ast_desc" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="ast_id" id="ast_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
						    <input type="submit" name="action" id="action" class="btn btn-submit"/>     
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="assetdetailsModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="asset_form">                   
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Asset Details</h4>
                    </div>
                    <div class="modal-content">
                        <div class="modal-body">
                            <Div id="asset_details"></Div>
                        </div>
                        <div class="modal-footer">                          
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<script>
$(document).ready(function(){
    var assetdataTable = $('#asset_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"asset_fetch.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[7, 8, 9],
                "orderable":false,
            },
        ],
        "pageLength": 10
    });

    $('#add_button').click(function(){
        $('#assetModal').modal('show');
        $('#asset_form')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Asset");
        $('#action').val("ADD");
        $('#btn_action').val("ADD");
    });

    $('#cat_id').change(function(){
        var cat_id = $('#cat_id').val();
        var btn_action = 'load_item';
        $.ajax({
            url:"asset_action.php",
            method:"POST",
            data:{cat_id:cat_id, btn_action:btn_action},
            success:function(data)
            {
                $('#it_id').html(data);
            }
        });
    });

    $(document).on('submit', '#asset_form', function(event){
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url:"asset_action.php",
            method:"POST",
            data:form_data,
            success:function(data)
            {
                $('#asset_form')[0].reset();
                $('#assetModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                $('#action').attr('disabled', false);
                assetdataTable.ajax.reload();
            }
        })
    });

    $(document).on('click', '.view', function(){
        var ast_id = $(this).attr("id");
        var btn_action = 'asset_details';
        $.ajax({
            url:"asset_action.php",
            method:"POST",
            data:{ast_id:ast_id, btn_action:btn_action},
            success:function(data){
                $('#assetdetailsModal').modal('show');
                $('#asset_details').html(data);
            }
        })
    });

    $(document).on('click', '.update', function(){
        var ast_id = $(this).attr("id");
        var btn_action = 'fetch_single';
        $.ajax({
            url:"asset_action.php",
            method:"POST",
            data:{ast_id:ast_id, btn_action:btn_action},
            dataType:"json",
            success:function(data){
                $('#assetModal').modal('show');
                $('#cat_id').val(data.cat_id);
                $('#it_id').html(data.item_select_box);
                $('#it_id').val(data.it_id);
                $('#ar_id').val(data.ar_id);
                $('#ast_sn').val(data.ast_sn);
                $('#ast_code').val(data.ast_code);
                $('#ast_desc').val(data.ast_desc);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit asset");
                $('#ast_id').val(ast_id);
                $('#action').val("EDIT");
                $('#btn_action').val("EDIT");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var ast_id = $(this).attr("id");
        var status = $(this).data("status");
        var btn_action = 'delete';
        if(confirm("Are you sure you want to change status?"))
        {
            $.ajax({
                url:"asset_action.php",
                method:"POST",
                data:{ast_id:ast_id, status:status, btn_action:btn_action},
                success:function(data){
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                    assetdataTable.ajax.reload();
                }
            });
        }
        else
        {
            return false;
        }
    });

});
</script>
