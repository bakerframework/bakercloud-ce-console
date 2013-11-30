<form method="post">
    <div style="padding:2px 8px 7px 8px; 
         margin-bottom:20px; 
         background: #FFFFFF;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25); 
         border: 1px solid #e8e8e8; 
         -moz-border-radius: 7px; 
         -khtml-border-radius: 7px; 
         -webkit-border-radius: 7px; 
         border-radius: 7px;">
        <center><h3>Baker Cloud Console (CE) Installation</h3></center>
        <p>Welcome to Baker Cloud Console (CE)!</p>
        <p>The Baker Cloud Console (CE) Administration Console is one of two server side components required to support In-App Purchases and Paid Subscriptions for the Baker iOS Newsstand framework.  In addition to the HTML/PHP administration backend, the REST API service layer must also be installed so that the Baker iOS application can "talk" to the server side backend.  Please follow the directions for installing both components.  If you are having issues, please head over to the <a href="http://www.github.com/bakerframework/bakercloud-ce-console" target="_blank">GitHub Repository</a> for help.</p>
        <h4>Config/Database Folder</h4>
        <p>The config/database folder must be writable, please check before continuing.</p>
        <table>
            <tr>
                <td>Database folder: &nbsp; </td>
                <td><strong><?php echo __DATABASE_CONFIG_PATH__; ?></strong></td>
            </tr>
        </table>
        <h4>Database</h4>
        <p>Open config/database.php and change your mysql database server config.</p>
        <table>
            <tr>
                <td>Database Host: </td>
                <td><b><?php echo $this->db->hostname; ?></b></td>
            </tr>
            <tr>
                <td>User Name: </td>
                <td><b><?php echo $this->db->username; ?></b></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><b>******</b></td>
            </tr>
            <tr>
                <td>Database Name: &nbsp; </td>
                <td><b><?php echo $this->db->database; ?></b></td>
            </tr>
        </table>
        <h4>Data</h4>
		<p>Sample data will be installed as part of this installation.  Examine it as reference for creating your own Publication / Issues.</p>
        <label class="checkbox">
            <input type="hidden" value="0" id="sample_data" name="sample_data">
            <input type="checkbox" value="1" id="sample_data" name="sample_data" checked="checked"> Proceed with Installation :)
        </label>
       
        <?php if (count($errors) >0){ ?>
        <br/>
        <div class="alert alert-error">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <?php foreach ($errors as $v){ ?>
            <strong>Error!</strong> <?php echo $v; ?><br />
            <?php } ?>
        </div>
        <?php } ?>
        <input type="submit" class="btn btn-large btn-success" value="Install Baker Cloud Console (CE) v1.1" />


        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
        </footer>
    </div>
</form>