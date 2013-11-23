<?php
$editFlag = false;
$table = $this->input->get('table');
if (!empty($table) && trim($table) != '') {
    if (!in_array($table, $tables)) {
        redirect('/admin/table/index');
    }
    $editFlag = true;
}
?>
<div class="container">
		<h2>User Manager: Tables</h2>
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
        <div id="errors" style="display:none;"></div>
        <form class="form-horizontal" id="frm_new_table">
            <?php if ($editFlag == true) { ?>
                <input type="hidden" id="table_name_id" value="<?php echo $_GET['table']; ?>" />
            <?php } ?>
            <div class="control-group" style="margin-bottom: 8px !important;">
                <label class="control-label" for="table_name">Table Name:</label>
                <div class="controls">
                    <input type="text" id="table_name" name="table_name" placeholder="Table Name">
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 8px !important;">
                <label class="control-label" for="storage_engine">Storage Engine:</label>
                <div class="controls">
                    <select id="storage_engine" name="storage_engine">
                        <?php foreach ($engines as $k => $v) { ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 8px !important;">
                <label class="control-label" for="collation">Collation:</label>
                <div class="controls">
                    <select name="collation" id="collation">
                        <option value=""></option>
                        <?php foreach ($collations as $k => $v) { ?>
                            <optgroup label="<?php echo $k; ?>">
                                <?php foreach ($v as $k1 => $v1) { ?>
                                    <option value="<?php echo $k1; ?>"><?php echo $v1; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 8px !important;">
                <label class="control-label" for="table_comment">Table Comments:</label>
                <div class="controls">
                    <input type="text" id="table_comment" name="table_comment" placeholder="Comments" class="input-xxlarge">
                </div>
            </div>
            <table class="table table-bordered list table-condensed" id="crud_table">
                <thead>
                    <tr>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Name</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Type</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Length/Values</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Default</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Collation</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Null</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Key</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">A_I</th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;">Action</th>
                    </tr>
                </thead>
                <tr style="display:none;" id="template">
                    <td style="width: 300px;">
                        <input type="text" style="width:95%;" name="field_name[]" placeholder="Field Name"/>
                    </td>
                    <td style="width: 130px;">
                        <select name="field_type[]" style="width: auto;" onchange="resetCollation(this);">
                            <option selected="selected" value="INT">INT</option>
                            <option value="VARCHAR">VARCHAR</option>
                            <option value="TEXT">TEXT</option>
                            <option value="DATE">DATE</option>
                            <optgroup label="NUMERIC">
                                <option value="TINYINT">TINYINT</option>
                                <option value="SMALLINT">SMALLINT</option>
                                <option value="MEDIUMINT">MEDIUMINT</option>
                                <option value="INT">INT</option>
                                <option value="BIGINT">BIGINT</option>
                                <option value="-">-</option>
                                <option value="DECIMAL">DECIMAL</option>
                                <option value="FLOAT">FLOAT</option>
                                <option value="DOUBLE">DOUBLE</option>
                                <option value="REAL">REAL</option>
                                <option value="-">-</option>
                                <option value="BIT">BIT</option>
                                <option value="BOOL">BOOL</option>
                                <option value="SERIAL">SERIAL</option>
                            </optgroup>
                            <optgroup label="DATE and TIME">
                                <option value="DATE">DATE</option>
                                <option value="DATETIME">DATETIME</option>
                                <option value="TIMESTAMP">TIMESTAMP</option>
                                <option value="TIME">TIME</option>
                                <option value="YEAR">YEAR</option>
                            </optgroup>
                            <optgroup label="STRING">
                                <option value="CHAR">CHAR</option>
                                <option value="VARCHAR">VARCHAR</option>
                                <option value="-">-</option>
                                <option value="TINYTEXT">TINYTEXT</option>
                                <option value="TEXT">TEXT</option>
                                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                                <option value="LONGTEXT">LONGTEXT</option>
                                <option value="-">-</option>
                                <option value="BINARY">BINARY</option>
                                <option value="VARBINARY">VARBINARY</option>
                                <option value="-">-</option>
                                <option value="TINYBLOB">TINYBLOB</option>
                                <option value="MEDIUMBLOB">MEDIUMBLOB</option>
                                <option value="BLOB">BLOB</option>
                                <option value="LONGBLOB">LONGBLOB</option>
                                <option value="-">-</option>
                                <option value="ENUM">ENUM</option>
                                <option value="SET">SET</option>
                            </optgroup>
                        </select>
                    </td>
                    <td style="width: 90px;">
                        <input type="text" name="field_length_value[]" style="width: 89%;" placeholder="Length"/>
                    </td>
                    <td  style="width: 175px;">
                        <select name="field_default[]" style="width: auto;" onchange="default_field(this);">
                            <option value=""></option>
                            <option value="NULL">NULL</option>
                            <option value="USER_DEFINED">As defined:</option>
                            <option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
                        </select>
                        <br>
                        <input type="text" name="field_user_defined[]" style=" margin-top: 3px; width: 93%; display: none;" />

                    </td>
                    <td style="width: 185px;">
                        <select  name="field_collation[]" style="width: auto;">
                            <option value=""></option>
                            <?php foreach ($collations as $k => $v) { ?>
                                <optgroup label="<?php echo $k; ?>">
                                    <?php foreach ($v as $k1 => $v1) { ?>
                                        <option value="<?php echo $k1; ?>"><?php echo $v1; ?></option>
                                    <?php } ?>
                                </optgroup>
                            <?php } ?>
                        </select>
                    </td>
                    <td style="width: 30px; text-align: center;">
                        <input type="checkbox" value="1" checked="checked" name="field_null[]">
                    </td>
                    <td style="width: 30px; text-align: center;">
                        <input type="checkbox" value="1" name="field_key[]"/>
                    </td>
                    <td style="width: 30px; text-align: center;">
                        <input type="checkbox" value="1" name="field_ai[]"/>
                    </td>
                    <td style="text-align: center;">
                        <a class="btn btn-primary btn-mini" onclick="add_field(this);">Add</a>
                        <a class="btn btn-danger btn-mini" onclick="delete_field(this);">Delete</a>
                    </td>
                </tr>

            </table>
            <?php if ($editFlag == true){ ?>
                <p>A_I: AUTO_INCREMENT<br />
                Config of this table will be removed when save button is clicked.
                </p>
            <?php } ?>
            <div style="text-align: center;">
                <input type="button" class="btn" value="Cancel" onclick="cancel();"/>
                <?php if ($editFlag == true) { ?>
                    <input type="button" class="btn btn-primary" value=" Save " onclick="updateTable();" />
                <?php } else { ?>
                    <input type="button" class="btn btn-primary" value=" Save " onclick="createNewTable();" />
                <?php } ?>
            </div>
        </form>
        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
        </footer>
    </div>
</div>

<script>
    function add_field(obj){
        var _field = $('#template').clone();
        _field.attr('id', '');
        _field.addClass('crud_field');
        _field.show();
        $(obj).parent().parent().after(_field);
    }
    function delete_field(obj){
        if ($('.crud_field').length > 1){
            $(obj).parent().parent().remove();
        }
    }
    function default_field(obj){
        var target = $(obj).parent().children('input[name="field_user_defined\[\]"]');
        if ($(obj).val() == 'USER_DEFINED'){
            target.show();
        }else{
            target.hide();
        }
    }
    function cancel(){
        window.location = '<?php echo base_url(); ?>index.php/admin/table/index';
    }
    function resetCollation(obj){
        var _field = $(obj).parent().parent();
        _field.find('select[name="field_collation\[\]"]').val('');
    }
    function createNewTable(){
        var obj = {};
        obj.table_name = $('#table_name').val();
        obj.storage_engine = $('#storage_engine').val();
        obj.collation = $('#collation').val();
        obj.table_comment = $('#table_comment').val();
        obj.fields = [];
        $('.crud_field').each(function(){
            var o = {};
            o.name = $(this).find('input[name="field_name\[\]"]').val();
            o.type = $(this).find('select[name="field_type\[\]"]').val();
            o.length_value = $(this).find('input[name="field_length_value\[\]"]').val();
            o.def = $(this).find('select[name="field_default\[\]"]').val();
            o.user_def = $(this).find('input[name="field_user_defined\[\]"]').val();
            o.collation = $(this).find('select[name="field_collation\[\]"]').val();
            o.is_null = $(this).find('input[name="field_null\[\]"]:checked').val();
            o.key = $(this).find('input[name="field_key\[\]"]:checked').val();
            o.ai = $(this).find('input[name="field_ai\[\]"]:checked').val();
            
            obj.fields[obj.fields.length] = o;
        });
        
        $.post('<?php echo base_url(); ?>index.php/admin/table/insert', obj, function(o){
            if (o.error == 1){
                var objError = $('<div class="alert alert-error"></div>');
                objError.append('<button data-dismiss="alert" class="close" type="button">×</button>');
                if (o.messages.length >0){
                    for(var i in o.messages){
                        objError.append('<strong>Error!</strong> '+o.messages[i]+'. <br/>');
                    }
                }
                $('#errors').html(objError);
                $('#errors').show();
            }else{
                window.location = '<?php echo base_url(); ?>index.php/admin/table/index';
            }
        }, 'json');
    }
    
    function updateTable(){
        var obj = {};
        obj.table_name = $('#table_name').val();
        obj.table_name_id = $('#table_name_id').val();
        obj.storage_engine = $('#storage_engine').val();
        obj.collation = $('#collation').val();
        obj.table_comment = $('#table_comment').val();
        obj.fields = [];
        $('.crud_field').each(function(){
            var o = {};
            o.id = $(this).attr('id');
            o.name = $(this).find('input[name="field_name\[\]"]').val();
            o.type = $(this).find('select[name="field_type\[\]"]').val();
            o.length_value = $(this).find('input[name="field_length_value\[\]"]').val();
            o.def = $(this).find('select[name="field_default\[\]"]').val();
            o.user_def = $(this).find('input[name="field_user_defined\[\]"]').val();
            o.collation = $(this).find('select[name="field_collation\[\]"]').val();
            o.is_null = $(this).find('input[name="field_null\[\]"]:checked').val();
            o.key = $(this).find('input[name="field_key\[\]"]:checked').val();
            o.ai = $(this).find('input[name="field_ai\[\]"]:checked').val();
            
            obj.fields[obj.fields.length] = o;
        });
        
        $.post('<?php echo base_url(); ?>index.php/admin/table/update', obj, function(o){
            if (o.error == 1){
                var objError = $('<div class="alert alert-error"></div>');
                objError.append('<button data-dismiss="alert" class="close" type="button">×</button>');
                if (o.messages.length >0){
                    for(var i in o.messages){
                        objError.append('<strong>Error!</strong> '+o.messages[i]+'. <br/>');
                    }
                }
                $('#errors').html(objError);
                $('#errors').show();
            }else{
                window.location = '<?php echo base_url(); ?>index.php/admin/table/index';
            }
        }, 'json');
    }
    
    $(document).ready(function(){
        $('title').html($('h3').text());
        
<?php if ($editFlag == true) { ?>
            $('#table_name').val('<?php echo $table_info['Name']; ?>');
            $('#storage_engine').val('<?php echo $table_info['Engine']; ?>');
            $('#collation').val('<?php echo $table_info['Collation']; ?>');
            $('#table_comment').val('<?php echo $table_info['Comment']; ?>');
    <?php if (is_array($columns_info) && count($columns_info) > 0) { ?>
        <?php foreach ($columns_info as $k => $v) { ?>
            <?php
            $aryType = explode('(', $v['Type']);
			preg_match("/\((.*)\)/i", $v['Type'],$aryTmp);
			$valType = (isset($aryTmp[1]))?$aryTmp[1]:'';
            ?>
                                var _field = $('#template').clone();
                                _field.attr('id', '<?php echo $v['Field'] ?>');
                                _field.addClass('crud_field');
                                _field.find('input[name="field_name\[\]"]').val('<?php echo $v['Field'] ?>');
                                _field.find('select[name="field_type\[\]"]').val('<?php echo strtoupper($aryType[0]); ?>');
            <?php if (isset($aryType[1])) { ?>
                                    _field.find('input[name="field_length_value\[\]"]').val('<?php echo $valType; ?>');
            <?php } ?>
            <?php if ($v['Default'] === NULL) { ?>
                <?php if ($v['Null'] == 'YES') { ?>
                                            _field.find('select[name="field_default\[\]"]').val('NULL');
                <?php } ?>
            <?php } else if ($v['Default'] == 'CURRENT_TIMESTAMP') { ?>
                                    _field.find('select[name="field_default\[\]"]').val('<?php echo $v['Default'] ?>');
            <?php } else { ?>
                                    _field.find('select[name="field_default\[\]"]').val('USER_DEFINED');
                                    _field.find('input[name="field_user_defined\[\]"]').val('<?php echo $v['Default'] ?>');
                                    _field.find('input[name="field_user_defined\[\]"]').show();
            <?php } ?>
                                _field.find('select[name="field_collation\[\]"]').val('<?php echo $v['Collation'] ?>');
            <?php if ($v['Null'] == 'NO') { ?>
                                    _field.find('input[name="field_null\[\]"]').attr({checked:false});
            <?php } ?>
            <?php if ($v['Key'] == 'PRI') { ?>
                                    _field.find('input[name="field_key\[\]"]').attr({checked:true});
            <?php } ?>
            <?php if ($v['Extra'] == 'auto_increment') { ?>
                                    _field.find('input[name="field_ai\[\]"]').attr({checked:true});
            <?php } ?>
                                            
                                _field.show();
                                $('#crud_table').append(_field);
        <?php } ?>
    <?php } ?>
                                                    
<?php } ?>
    
        if ($('.crud_field').length <= 0){
            var _field = $('#template').clone();
            _field.attr('id', '');
            _field.addClass('crud_field');
            _field.find('input[name="field_name\[\]"]').val('id');
            _field.find('input[name="field_null\[\]"]').attr({checked:false});
            _field.find('input[name="field_key\[\]"]').attr({checked:true});
            _field.find('input[name="field_ai\[\]"]').attr({checked:true});
            _field.show();
            $('#crud_table').append(_field);
        }
    
    });
    $(document).ready(function(){
        $('title').html($('h2').html());
    });
</script>