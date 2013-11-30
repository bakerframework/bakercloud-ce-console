<div id="header"><div class="container"><h1>User Manager: Tables</h1></div></div>
<div class="container">
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 10px;">
        	<?php if ((int) $crudAuth['group']['group_manage_flag'] == 1 || 
        		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
        		(int) $crudAuth['user_manage_flag'] == 1 ||
                (int) $crudAuth['user_manage_flag'] == 3) { ?>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/index">Users</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/group">Groups</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/permission">Permissions</a></li>
            <?php } ?>
            <?php if ((int) $crudAuth['group']['group_manage_flag'] == 2 || 
            		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
            		(int) $crudAuth['user_manage_flag'] == 2 ||
                    (int) $crudAuth['user_manage_flag'] == 3 ) { ?>
            <li class="active"><a href="<?php echo base_url(); ?>index.php/admin/table/index">Tables</a></li>
            <?php } ?>
        </ul>
        <div>
            <h3 style="margin-top: 7px;">Table manager</h3>
            <p style="text-align: right;"><a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/admin/table/form">Add table</a></p>
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="text-align:center;width:30px; cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;"><?php echo $this->lang->line('__LBL_NO__'); ?></th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;"><?php echo $this->lang->line('__LBL_TABLE__'); ?></th>
                        <th style="text-align:center; width: 120px;  cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;"><?php echo $this->lang->line('__LBL_ACTIONS__'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($tables) > 2) {
                        foreach ($tables as $k => $table) {
                            if ($table == 'cruds')
                                continue;
                            if (strpos($table, 'crud_') !== false)
                                continue;
                            ?>
                            <tr>
                                <td style="text-align:center;"><?php echo ($k + 1); ?></td>
                                <td><?php echo $table; ?></td>
                                <td style="text-align: center;">
                                    <input type="button" class="btn btn-mini btn-primary" id="table_btn_fields" value="Edit" onclick="edit_table('<?php echo $table; ?>')"/>
                                    <input type="button" class="btn btn-mini btn-danger" id="table_btn_delete" value="Delete" onclick="modal_delete_table('<?php echo $table; ?>')"/>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">No tables to display.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
        </footer>
    </div>
</div>
<div id="delModal" class="modal hide fade" tabindex="-1" aria-hidden="true" style="width: 290px; margin: -150px 0 0 -180px;">
    <div class="modal-body">
        <p>Are you sure to delete <strong id="del_table"></strong> table ?</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-danger" onclick="delete_table();">Delete</button>
    </div>
</div>
<script>
    function edit_table(table){
        window.location = '<?php echo base_url(); ?>index.php/admin/table/form?table='+table;
    }
    function modal_delete_table(table){
        $('#del_table').text(table);
        $('#delModal').modal('show');
    }
    function delete_table(){
        $.post('<?php echo base_url(); ?>index.php/admin/table/delete', {table:$('#del_table').text()}, function(data){
            $('#delModal').modal('hide');
            window.location = '<?php echo base_url(); ?>index.php/admin/table/index';
        },'html');
    }
    $(document).ready(function(){
        $('title').html($('h2').html());
    });
</script>