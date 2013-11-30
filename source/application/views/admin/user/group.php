<div id="header"><div class="container"><h1>User Manager: Groups</h1></div></div>
<div class="container">
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 0px;">
        <?php if ((int) $crudAuth['group']['group_manage_flag'] == 1 || 
        		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
        		(int) $crudAuth['user_manage_flag'] == 1 ||
                (int) $crudAuth['user_manage_flag'] == 3) { ?>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/index">Users</a></li>
            <li class="active"><a href="<?php echo base_url(); ?>index.php/admin/user/group">Groups</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/admin/user/permission">Permissions</a></li>
            <?php } ?>
            <?php if ((int) $crudAuth['group']['group_manage_flag'] == 2 || 
            		(int) $crudAuth['group']['group_manage_flag'] == 3 ||
            		(int) $crudAuth['user_manage_flag'] == 2 ||
                    (int) $crudAuth['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo base_url(); ?>index.php/admin/table/index">Tables</a></li>
            <?php } ?>
        </ul>
        <?php echo $content; ?>

        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
        </footer>
    </div>
</div>