<div id="header"><div class="container"><h1>User Manager: Permissions</h1></div></div>
<div class="container">
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 10px;">
        	<?php if ((int) $crudAuth['group']['group_manage_flag'] == 1 || 
        		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
        		(int) $crudAuth['user_manage_flag'] == 1 ||
                (int) $crudAuth['user_manage_flag'] == 3) { ?>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/index">Users</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/group">Groups</a></li>
            <li  class="active"><a href="<?php echo base_url(); ?>index.php/admin/user/permission">Permissions</a></li>
            <?php } ?>
            <?php if ((int) $crudAuth['group']['group_manage_flag'] == 2 || 
            		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
            		(int) $crudAuth['user_manage_flag'] == 2 ||
                    (int) $crudAuth['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo base_url(); ?>index.php/admin/table/index">Tables</a></li>
            <?php } ?>
        </ul>
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 10px;">
         	<li><a href="<?php echo base_url(); ?>index.php/admin/user/permission">Group permissions</a></li>
         	<li class="active"><a href="<?php echo base_url(); ?>index.php/admin/user/user_permission">User permissions</a></li>
         </ul>
         <div>
         	<label><strong>Choose User</strong></label> 
         		<div id="user_permission" style="width:400px;"></div>
         </div>
         <br/>
         <div id="user_permission_container"></div>
</div>
<script>
$("#user_permission").select2({
    placeholder: "Search for a User",
    minimumInputLength: 1,
    ajax: {
        url: "<?php echo base_url(); ?>index.php/admin/user/user_json",
        dataType: 'jsonp',
        data: function(term, page) {
            return {
                q: term, // search term
            };
        },
        results: function(data, page) { // parse the results into the format expected by Select2.
            return {results: data};
        }
    },
    initSelection: function(element, callback) {},
    formatResult: movieFormatResult, // omitted for brevity, see the source of this page
    formatSelection: movieFormatSelection, // omitted for brevity, see the source of this page
    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
    escapeMarkup: function(m) {
        return m;
    } 
});

$("#user_permission").on('change',function(e){
	$.get("<?php echo base_url(); ?>index.php/admin/user/user_json?id="+e.val,{},function(data){
		$('#user_permission_container').html('');
		$('#user_permission_container').append(data);
	},'html');
});

function movieFormatResult(user) {
    return user.user_name;;
}

function movieFormatSelection(user) {
    return user.user_name;
}

</script>