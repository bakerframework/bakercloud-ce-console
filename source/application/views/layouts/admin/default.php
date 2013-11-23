<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->lang->line('__LBL_PROJECT_NAME__'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>media/images/favicon.ico">
        <link href="<?php echo base_url(); ?>media/css/template.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>media/css/style.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>media/bootstrap/js/jquery-1.7.1.min.js"></script>
        <script src="<?php echo base_url(); ?>media/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php echo $main_menu; ?>
        <?php echo $main_content; ?>
    </body>
</html>
