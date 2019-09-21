<?php
//repair.php

include('database_connection.php');
include('function.php');

if(!isset($_SESSION['type']))
{
    header('location:login.php');
}

include('header.php');

?>

    <link rel="stylesheet" href="css/datepicker.css">
    <script src="js/bootstrap-datepicker1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    <script>
    $(document).ready(function(){
        $('#dis_date').datepicker({
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
                            <h3 class="panel-title">Disposal List</h3>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                        <div class="row" align="right">
                             <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#repairModal" class="btn btn-success btn-xs">ADD</button>        
                        </div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table id="repair_data" class="table">
                                <thead><tr>
                                    <th>ID</th>
                                    <th>Item Name</th>
                                    <th>Asset Code</th>
                                    <th>From</th> 
                                    <th>Enter By</th>
                                    <th>Enter Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="repairModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="repair_form">                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Disposal</h4>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                            <div class="form-group">
                                <label>Select Item</label>
                                <select name="it_id" id="it_id" class="form-control" required>
                                    <option value="">Select Item</option>
                                    <?php echo fill_item_list_two($connect);?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Asset Code</label>
                                <select name="ast_id" id="ast_id" class="form-control" required>
                                    <option value="">Select Asset Code</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Area</label>
                                <select name="frm_area" id="frm_area" class="form-control" required>
                                    <option value="">Select From Aera ID</option>
                                    <?php echo fill_area_list($connect) ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Reason</label>
                                <input type="text" name="disposal_desc" id="disposal_desc" class="form-control" >
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="dis_id" id="dis_id"/>
                        <!-- <input type="hidden" name="ast_id" id="ast_id"/> -->
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
        $('#repair_form')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add repair");
        $('#action').val('ADD');
        $('#btn_action').val('ADD');
    });


     $('#it_id').change(function(){
        var it_id = $('#it_id').val();
        var btn_action = 'load_asset_code';
        $.ajax({
            url:"disposal_action.php",
            method:"POST",
            data:{it_id:it_id, btn_action:btn_action},
            success:function(data)
            {
                $('#ast_id').html(data);
            }
        });
    });


    $(document).on('submit','#repair_form', function(event){
        event.preventDefault();
        $('#action').attr('disabled','disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url:"disposal_action.php",
            method:"POST",
            data:form_data,
            success:function(data)
            {
                $('#repair_form')[0].reset();
                $('#repairModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                $('#action').attr('disabled', false);
                repairdataTable.ajax.reload();
            }
        })
    });

    $(document).on('click', '.update', function(){
        var dis_id = $(this).attr("id");
        var btn_action = 'fetch_single';
        $.ajax({
            url:"disposal_action.php",
            method:"POST",
            data:{dis_id:dis_id, btn_action:btn_action},
            dataType:"json",
            success:function(data)
            {
                $('#repairModal').modal('show');
                $('#it_id').val(data.it_id);
                $('#ast_id').val(data.ast_id);
                $('#frm_area').val(data.frm_area);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit disposal");
                $('#dis_id').val(dis_id);
                $('#action').val('EDIT');
                $('#btn_action').val("EDIT");
            }
        })
    });

    var repairdataTable = $('#repair_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"disposal_fetch.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[3, 4],
                "orderable":false,
            },
        ],
        "pageLength": 25
    });

    $(document).on('click', '.delete', function(){
        var dis_id = $(this).attr('id');
        var status = $(this).data("status");
        var btn_action = 'delete';
        if(confirm("Are you sure you want to change status?"))
        {
            $.ajax({
                url:"disposal_action.php",
                method:"POST",
                data:{dis_id:dis_id, status:status, btn_action:btn_action},
                success:function(data)
                {
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                    repairdataTable.ajax.reload();
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
                