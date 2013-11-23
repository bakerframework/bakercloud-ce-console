<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->lang->line('__LBL_PROJECT_NAME__'); ?> INSTALLATION</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>media/images/favicon.ico">
        <link href="<?php echo base_url(); ?>media/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>media/css/style.css" rel="stylesheet">
        <style>
            body {
                padding:0px;
                background-color: #f5f5f5;
                margin: 2em auto;
                max-width: 900px;
                padding: 1em 2em;
            }

            .form-signin {
                max-width: 350px;
                padding: 19px 29px 29px;
                margin: 0 auto 20px;
                background-color: #fff;
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                -webkit-box-shadow: 0 8px 20px rgba(0,0,0,0.4);
                -moz-box-shadow: 0 8px 20px rgba(0,0,0,0.4);
                box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            }

            .control-group{
                margin-bottom: 10px !important;
            }
        </style>
        <link href="<?php echo base_url(); ?>media/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>media/bootstrap/js/jquery-1.7.1.min.js"></script>
        <script src="<?php echo base_url(); ?>media/bootstrap/js/bootstrap.min.js"></script>

    </head>
    <body>
        <?php echo $main_content; ?> 
    </body>
</html>