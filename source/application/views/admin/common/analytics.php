<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header"><div class="container"><h1><?php echo $this->lang->line('__LBL_ANALYTICS__'); ?></h1></div></div>
<div class="container">
		  <br>    	  
    	  <p>
    	  		MagRocket currently supports basic statistics and analytics for your MagRocket based publications.
    	  		<br>Please note, depending on your Publication type - some data may or may not be relevant.
    	  		<br><br>*Statistics are approximate, factors such as "Restore Purchases" initiated by end users may skew results.
		  </p>
		  <br><br>
		  <?php echo $publications_table; ?>
		  <br><br><br><br><br><br><br>
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
            <img class="pull-right" src="<?php echo base_url(); ?>media/images/MagRocketLogo.png">
        </footer>
    </div>
</div>