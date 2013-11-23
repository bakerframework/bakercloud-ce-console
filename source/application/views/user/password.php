<?php $CI = & get_instance(); ?>
<div class="container">
         <?php echo $user_menu; ?>
         <br />
        <form class="bs-docs-example form-horizontal" method="post" action="<?php echo base_url(); ?>index.php/user/changepassword">
            <?php if (count($errors) > 0) { ?>
                <div class="alert alert-error">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <?php foreach ($errors as $v) { ?>
                        <strong>Error!</strong> <?php echo $v; ?> <br />
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if ($update_flag == 1 && count($errors) <= 0) { ?>
                <div class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <strong>Success!</strong> You have successfully changed your password..
                </div>
            <?php } ?>
            <div class="control-group  <?php if (array_key_exists('current_password', $errors)) { ?> error <?php } ?>">
                <label for="inputPassword" class="control-label" style=" text-align: right !important;"> Current Password</label>
                <div class="controls">
                    <input type="password" placeholder="Current Password" id="current_password"  name="current_password"  value="<?php
            $current_password = $CI->input->post('current_password');
            if (!empty($current_password)) {
                echo htmlspecialchars($current_password);
            }
            ?>" >
                </div>
            </div>
            <div class="control-group  <?php if (array_key_exists('new_password', $errors)) { ?> error <?php } ?>">
                <label for="inputPassword" class="control-label" style=" text-align: right !important;">New Password</label>
                <div class="controls">
                    <input type="password" placeholder="New Password" id="new_password"   name="new_password"  value="<?php
                           $new_password = $CI->input->post('new_password');
                           if (!empty($new_password)) {
                               echo htmlspecialchars($new_password);
                           }
            ?>" >
                </div>
            </div>
            <div class="control-group <?php if (array_key_exists('confirm_new_password', $errors)) { ?> error <?php } ?>">
                <label for="inputPassword" class="control-label" style=" text-align: right !important;">Re-type New Password</label>
                <div class="controls">
                    <input type="password" placeholder="Re-type New Password" id="confirm_new_password"   name="confirm_new_password"  value="<?php
                    $confirm_password = $CI->input->post('confirm_new_password');
                    if (!empty($confirm_password)) {
                        echo htmlspecialchars($confirm_password);
                    }
            ?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-primary" type="submit">Change Password</button>
                </div>
            </div>
        </form>

        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
        </footer>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('title').html($('h3').html());
    });
</script>