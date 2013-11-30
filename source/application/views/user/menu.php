</div>
<div id="header"><div class="container"><h1>Account Settings</h1></div></div>
<div class="container">
<ul class="nav nav-tabs" id="my_settings" style="margin-bottom:0px; " >
    <li <?php if ($type == 'profile'){ ?> class="active" <?php } ?>>
        <a href="<?php echo base_url(); ?>index.php/user/editprofile">Edit Profile</a>
    </li>
    <li  <?php if ($type == 'password'){ ?> class="active" <?php } ?>>
        <a href="<?php echo base_url(); ?>index.php/user/changepassword">Change Password</a>
    </li>
</ul>
