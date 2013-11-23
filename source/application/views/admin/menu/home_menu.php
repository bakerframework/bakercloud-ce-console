<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container"><a class="btn btn-navbar"
                                  data-toggle="collapse" data-target=".nav-collapse"> <span
                    class="icon-bar"></span> <span class="icon-bar"></span> <span
                    class="icon-bar"></span> </a> <a class="brand" href="<?php echo base_url(); ?>index.php/admin/home"><?php echo $this->lang->line('__LBL_PROJECT_NAME__'); ?></a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li <?php if ($type == 'dashboard') { ?>class="active"<?php } ?>><a href="<?php echo base_url(); ?>index.php/admin/home"><?php echo $this->lang->line('__LBL_MAIN__'); ?></a></li>
                    <?php if ((int) $crudAuth['group']['group_manage_flag'] != 0 || 
                    		(int) $crudAuth['user_manage_flag'] != 0) { ?>
                        <li class="dropdown <?php if ($type == 'user') { ?>active<?php } ?>">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Users <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            	<?php if ((int) $crudAuth['group']['group_manage_flag'] == 1 || 
                            			 (int) $crudAuth['group']['group_manage_flag'] == 3 ||
                            			 (int) $crudAuth['user_manage_flag'] == 1 || 
                            			 (int) $crudAuth['user_manage_flag'] == 3) { ?>
                                <li><a href="<?php echo base_url(); ?>index.php/admin/user/index"><?php echo $this->lang->line('__LBL_USER_MANAGER__'); ?></a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/admin/user/group"><?php echo $this->lang->line('__LBL_GROUP_MANAGER__'); ?></a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/admin/user/permission"><?php echo $this->lang->line('__LBL_PERMISSION_MANAGER__'); ?></a></li>
                                <?php }?>
                                <?php if ((int) $crudAuth['group']['group_manage_flag'] == 2 || 
                    					 (int) $crudAuth['group']['group_manage_flag'] == 3 ||
                    					 (int) $crudAuth['user_manage_flag'] == 2 || 
                    					 (int) $crudAuth['user_manage_flag'] == 3 ) { ?> 
                                <li><a href="<?php echo base_url(); ?>index.php/admin/table/index"><?php echo $this->lang->line('__LBL_TABLE_MANAGER__'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <!-- MAGROCKET - HARDCODING THE LIST OF AVAILABLE OPTIONS FOR THE USER MENU -->
                    <li class="dropdown <?php if ($type == 'browse' && ($_SERVER['QUERY_STRING']=='table=PUBLICATION' || $_SERVER['QUERY_STRING']=='table=ISSUES')) { ?>active<?php } ?>"  id="mnu_browse_publications" style="display:none;">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $this->lang->line('__LBL_PUBLICATION_MANANGER__'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header"><?php echo $this->lang->line('__LBL_BROWSE_TABLE__'); ?></li>
                            <li  >
                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=PUBLICATION" >Publication</a>
                            </li>
                            <li  >
                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=ISSUES" >Issues</a>
                            </li>
                        </ul>
                    </li>   
                    <?php if ($crudAuth['group']['group_name'] != 'Users') { ?>          
                    	<!-- MAGROCKET - HARDCODING THE LIST OF AVAILABLE OPTIONS FOR THE USER MENU -->
	                    <li class="dropdown  <?php if ($type == 'browse' && ($_SERVER['QUERY_STRING']=='table=PURCHASES' || $_SERVER['QUERY_STRING']=='table=RECEIPTS' || $_SERVER['QUERY_STRING']=='table=SUBSCRIPTIONS' || $_SERVER['QUERY_STRING']=='table=APNS_TOKENS' || $_SERVER['QUERY_STRING']=='table=SYSTEM_LOG')) { ?>active<?php } ?>"  id="mnu_browse" style="display:none;">
	                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $this->lang->line('__LBL_BROWSER__'); ?> <b class="caret"></b></a>
	                        <ul class="dropdown-menu">
	                            <li class="nav-header"><?php echo $this->lang->line('__LBL_BROWSE_TABLE__'); ?></li>
	                            <li  >
	                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=PURCHASES" >Purchases</a>
	                            </li>
	                            <li  >
	                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=RECEIPTS" >Receipts</a>
	                            </li>
	                            <li  >
	                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=SUBSCRIPTIONS" >Subscriptions</a>
	                            </li>
	                            <li  >
	                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=APNS_TOKENS" >APNS Tokens</a>
	                            </li>
	                            <li  >
	                                <a  href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=SYSTEM_LOG" >System Log</a>
	                            </li>
	                        </ul>
	                    </li>
	                <?php } ?>
                    <li class="dropdown  <?php if ($type == 'config') { ?>active<?php } ?>" id="mnu_config" style="display:none;">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $this->lang->line('__LBL_CONFIG__'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header"><?php echo $this->lang->line('__LBL_CONFIG_TABLE__'); ?></li>
                            <?php
                            foreach ($tables as $k => $table) {
								$permissions = $auth->getPermissionType($table);
                                if ($table == 'cruds') continue;
                                if (strpos($table, 'crud_') !== false) continue;
                                if (!in_array(5, $permissions)) continue;
                                ?>
                                <li><a href="<?php echo base_url(); ?>index.php/admin/scrud/config?table=<?php echo $table; ?>"><?php echo $table; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
                <ul class="nav pull-right">
                    <li class="dropdown   <?php if ($type == 'account') { ?>active<?php } ?>">
                        <a class=" dropdown-toggle" data-toggle="dropdown" href="#" > &nbsp;  <i class="icon icon-user"></i>&nbsp; <?php echo $crudAuth['user_name']; ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php if ($crudAuth['group']['group_name'] != 'SystemAdmin') { ?>
                                <li><a href="<?php echo base_url(); ?>index.php/user/editprofile"> <i class="icon-user"></i> <?php echo $this->lang->line('__LBL_EDIT_PROFILE__'); ?></a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/user/changepassword"> <i class="icon-pencil"></i> <?php echo $this->lang->line('__LBL_CHANGE_PASSWORD__'); ?></a></li>
                                <li class="divider"></li>
                            <?php } ?>
                            <li><a href="<?php echo base_url(); ?>index.php/admin/logout"><i class="icon-minus-sign"></i> <?php echo $this->lang->line('__LBL_LOGOUT__'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
       if ($('#mnu_config').children('ul').find('li').length <= 1){
           $('#mnu_config').hide();
       }else{
           $('#mnu_config').show();
       } 
       
       if ($('#mnu_browse').children('ul').find('li').length <= 1){
           $('#mnu_browse').hide();
       }else{
           $('#mnu_browse').show();
       } 
       
       if ($('#mnu_browse_publications').children('ul').find('li').length <= 1){
           $('#mnu_browse_publications').hide();
       }else{
           $('#mnu_browse_publications').show();
       } 
    });
</script>