<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div class="container">
        <h2><?php echo $this->lang->line('__LBL_DASHBOARD__'); ?></h2>
	     <div class="alert alert-info">
    	  <strong>Welcome to Baker Cloud Console (Community Edition)!</strong>
    	  </div>
    	  <p>
    	  		Baker Cloud Console (CE) is a complete backend solution and REST API for supporting Newsstand applications built with the <a href="http://www.bakerframework.com" target="_blank">Baker Framework</a>.
		  </p>
		  <p>
				For information and helpful tutorials on using Baker Cloud Console (CE), please visit <a href="http://www.bakerframework.com" target="_blank">BakerFramework.com</a> or view the bundled help documentation that accompanied this source distribution.    	  
    	  </p>
    	  <p>
				The administration console allows you to manage / view the following backend data related to your Baker Cloud Console (CE) installation.
			    <table class="table table-bordered">
			    <thead>
			    <tr>
			    <th>Data</th>
			    <th>Purpose</th>
			    </tr>
			    </thead>
			    <tbody>
			    <tr>
			    <td>Publication</td>
			    <td>Defines the list of publications that you are supporting with your Baker Cloud Console (CE) installation.  Each publication would correspond to a deployed iOS Baker Newsstand application.</td>
			    </tr>
  			    <tr>
			    <td>Issues</td>
			    <td>Defines the available issues for each of your Publications being managed by Baker Cloud Console (CE).  These issues are what show up in your iOS Baker Newsstand application.</td>
			    </tr>
			    <tr>
			    <td>Purchases</td>
			    <td>System table that tracks the issues purchased by end users of your Publications.  Both single "one off" In-App purchased issues are tracked here as well as issues falling under the coverage of an active paid Newsstand subscription.</td>
			    </tr>
			    <tr>
			    <td>Receipts</td>
			    <td>System table that tracks receipt information from In-App Purchases and Subscription purchases.  Stores Apple data from receipt and acts as the central repository of information used to validate downloads and subscription terms.</td>
			    </tr>
			    <tr>
			    <td>Subscriptions</td>
			    <td>System table that summarizes active Newsstand Subscriptions for users.  Used by the API in conjunction with the Receipts data to manage shelf and available downloads.</td>
			    </tr>
			    <tr>
			    <td>APNS Tokens</td>
			    <td>System table that tracks APNS Push Notification tokens for users and Publications.</td>
			    </tr>
			    <tr>
			    <td>System Log</td>
			    <td>System table that tracks and logs debugging level information including Error messages.</td>
			    </tr>
			    </tbody>
			    </table> 	  
    	  </p>
		  <h3>Get Started!</h3>    	  
    	  <p>
    	  		To get started using Baker Cloud Console (CE), you should create a Publication that corresponds to your iOS Baker Newsstand application.  After you create the Publication, you can add Issues.
				 <ul>
			    <li><a href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=PUBLICATION">Create/Edit Publications</a></li>
			    <li><a href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=ISSUES">Create/Edit Issues</a></li>		    			    			    			    			    			    			    
			    </ul>   
    	  </p>
    	  <p>
    	  		To manage your Publications and Issues, use the <strong>Publication Management</strong> dropdown menu.  To view/manage the backend Baker Cloud Console (CE) data, use the <strong>Data Browser</strong> dropdown menu, both located at the top of this page.
    	  </p>
        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
            <img class="pull-right" src="<?php echo base_url(); ?>media/images/BakerCloudCELogo.png">
        </footer>
    </div>
</div>