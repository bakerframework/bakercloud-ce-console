<h2>Account Settings</h2>
<ul class="nav nav-tabs" id="my_settings" style="margin-bottom:0px; " >
    <li <?php if ($type == 'profile'){ ?> class="active" <?php } ?>>
        <a href="<?php echo base_url(); ?>index.php/user/editprofile">Edit Profile</a>
    </li>
    <li  <?php if ($type == 'password'){ ?> class="active" <?php } ?>>
        <a href="<?php echo base_url(); ?>index.php/user/changepassword">Change Password</a>
    </li>
</ul>
