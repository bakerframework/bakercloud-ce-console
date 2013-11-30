<?php $CI = & get_instance(); 
$crudUser = $CI->input->post('crudUser');
?>
<div class="container">
	<div class="row">
		<div class="span12 pagination-centered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<image src="<?php echo base_url(); ?>media/images/BakerCloudCELogoAvatar.png" alt="Baker Cloud (CE) Logo"></image></div>
		<div class="span4 offset4 well">
			<legend><?php echo $this->lang->line('__LBL_LOGIN_HEADER__'); ?></legend>
			<form  method="post" class="">
          	<?php if (!empty($crudUser)) { ?>
            <div class="alert alert-error">
                <?php echo $this->lang->line('E_VAL_LOGIN'); ?>
            </div>   
            <?php } ?>
			
			<div class="control-group">
              <label for="inputEmail" class="control-label"><?php echo $this->lang->line('__LBL_LOGIN_NAME__'); ?> </label>
              <div class="controls">
                <input type="text" placeholder="<?php echo $this->lang->line('__LBL_LOGIN_NAME__'); ?> " name="crudUser[name]" class="span4"  value="<?php
            if (isset($crudUser['name'])) {
                echo htmlspecialchars($crudUser['name']);
            }
            ?>" />
              </div>
            </div>
            <div class="control-group">
              <label for="inputEmail" class="control-label"><?php echo $this->lang->line('__LBL_LOGIN_PASSWORD__'); ?> </label>
              <div class="controls">
                <input type="password" placeholder="<?php echo $this->lang->line('__LBL_LOGIN_PASSWORD__'); ?> "  name="crudUser[password]" class="span4"  value="<?php
                           if (isset($crudUser['password'])) {
                               echo htmlspecialchars($crudUser['password']);
                           }
            ?>" />
              </div>
            </div>
			<br />
			<button class="btn btn-info btn-block " name="submit" type="submit"><?php echo $this->lang->line('__LBL_SIGN_IN_BUTTON__'); ?></button>
			</form> 
		</div>
	</div>
</div>