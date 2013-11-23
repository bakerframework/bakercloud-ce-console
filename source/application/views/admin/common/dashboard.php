<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div class="container">
        <h2><?php echo $this->lang->line('__LBL_DASHBOARD__'); ?></h2>
        <?php if ((int) $crudAuth['group']['group_manage_flag'] == 2 || 
        		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
        		(int) $crudAuth['user_manage_flag'] == 2 || 
        		(int) $crudAuth['user_manage_flag'] == 3) { ?>
            <p style="text-align: right;"><a class="btn btn-primary" id="btn_table_manager" ><?php echo $this->lang->line('__LBL_TABLE_MANAGER__'); ?></a></p>
        <?php } ?>
        <table class="table table-bordered table-hover table-condensed" id="dashboard_list_table">
            <thead>
                <tr>
                    <th style="text-align:center;width:30px; cursor:default;"><?php echo $this->lang->line('__LBL_NO__'); ?></th>
                    <th style="cursor:default;" ><?php echo $this->lang->line('__LBL_TABLE__'); ?></th>
                    <th style="text-align:center;width:70px; cursor:default;"><?php echo $this->lang->line('__LBL_RECORDS__'); ?></th>
                    <th style="text-align:center; cursor:default;"><?php echo $this->lang->line('__LBL_ACTIONS__'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tables) > 2) { ?>
                    <?php
                    foreach ($tables as $k => $table) {
						$permissions = $this->crud_auth->getPermissionType($table);
                        if ($table == 'cruds')
                            continue;
                        if (strpos($table, 'crud_') !== false)
                            continue;
                         if (!(in_array(4, $permissions) || in_array(5, $permissions))) continue;
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo ($k + 1); ?></td>
                            <td><?php echo $table; ?></td>
                            <td style="text-align:right;">
                                <?php
                                $result = $this->db->query('SELECT COUNT(1) as count FROM `' . $table . '`');
                                $num_rows = $result->row_array();
                                echo number_format($num_rows['count']);
                                ?>
                            </td>
                            <?php
                            $rs = null;
                            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php')) {
                                $rs = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php'));
                            }
                            ?>
                            <td style="text-align:center;" id="btn_actions">
                            	<?php if (in_array(4, $permissions)){ ?>
                                	<input type="button" class="btn btn-mini" value="<?php echo $this->lang->line('__BTN_BROWSE__'); ?>" onclick="window.location='<?php echo base_url(); ?>index.php/admin/scrud/browse?table=<?php echo $table; ?>'" <?php if (empty($rs)) { ?>disabled="disabled" <?php } ?> />
                                <?php } ?>
                                <?php if (in_array(5, $permissions)){ ?>
                                    <input type="button" class="btn btn-primary  btn-mini" value="<?php echo $this->lang->line('__BTN_CONFIG__'); ?>" onclick="window.location='<?php echo base_url(); ?>index.php/admin/scrud/config?table=<?php echo $table; ?>'"/>
                                    <input type="button" class="btn btn-danger btn-mini" value="<?php echo $this->lang->line('__BTN_REMOVE_CONFIG__'); ?>"  <?php if (empty($rs)) { ?>disabled="disabled" <?php } ?> onclick="removeConfig(this,'<?php echo $table; ?>')"/>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>	
                <?php } else { ?>
                    <tr>
                        <td colspan="4"><?php echo $this->lang->line('__LBL_NO_TABLE__'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
        </footer>
    </div>
</div>

<script>
    $('#btn_table_manager').click(function(){
        window.location = '<?php echo base_url(); ?>index.php/admin/table/index';
    });
    function removeConfig(obj,table){
        $.get('<?php echo base_url(); ?>index.php/admin/scrud/removeconfig', {table:table}, function(data){
            $($(obj).parent().children('input').get(0)).attr({disabled:true});
            $($(obj).parent().children('input').get(2)).attr({disabled:true});
        }, 'json');
    }
    $(document).ready(function(){
        if ($('#dashboard_list_table > tbody > tr').length <= 0){
            $('#dashboard_list_table > tbody').append('<tr><td colspan="4">No tables to display.</td></tr>');
        }
    });

    $('table > tbody > tr:first #btn_actions').width($('table > tbody > tr:first #btn_actions input').length * 70);
</script>