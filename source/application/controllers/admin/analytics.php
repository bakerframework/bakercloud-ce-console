<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analytics extends Admin_Controller {

    public function index() {
        $this->load->model('crud_auth');
        $this->load->model('admin/home_menu');

        $var = array();

		  $table_view_head = "<table class='table'>
		    <thead>
		    <tr>
		    <th>Name</th>
		    <th>Free Subscriptions</th>
		    <th>Paid Subscription Purchases</th>
		    <th>Paid Issue Purchases</th>
		    <th>Issue Downloads</th>
		    <th>API Interactions</th>
		    </tr>
		    </thead>
		    <tbody>";

		    $table_view_body = "";
		    
		    //Get list of publications
		    $query = $this->db->query("SELECT * FROM PUBLICATION");

			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
			   {			      
				  //Loop through publications and gather statistics
				  $issue_purchases = "SELECT COUNT(*) FROM RECEIPTS WHERE APP_ID = '" . $row->APP_ID . "' AND TYPE = 'issue'";
				  $queryStats = $this->db->query($issue_purchases);
				  $resultStats = $queryStats->row_array();
				  $issue_purchases_value = $resultStats['COUNT(*)'];
				  
				  $subscription_purchases = "SELECT COUNT(*) FROM RECEIPTS WHERE APP_ID = '" . $row->APP_ID . "' AND TYPE = 'auto-renewable-subscription'";
				  $queryStats = $this->db->query($subscription_purchases);
				  $resultStats = $queryStats->row_array();
				  $subscription_purchases_value = $resultStats['COUNT(*)'];
				  
				  $free_subscriptions = "SELECT COUNT(*) FROM RECEIPTS WHERE APP_ID = '" . $row->APP_ID . "' AND TYPE = 'free-subscription'";
				  $queryStats = $this->db->query($free_subscriptions);
				  $resultStats = $queryStats->row_array();
				  $free_subscriptions_value = $resultStats['COUNT(*)'];
				  
				  $issue_downloads = "SELECT COUNT(*) FROM ANALYTICS WHERE APP_ID = '" . $row->APP_ID . "' AND TYPE = 'download'";
				  $queryStats = $this->db->query($issue_downloads);
				  $resultStats = $queryStats->row_array();
				  $issue_downloads_value = $resultStats['COUNT(*)'];
				  
				  $api_interactions = "SELECT COUNT(*) FROM ANALYTICS WHERE APP_ID = '" . $row->APP_ID . "' AND TYPE = 'api_interaction'";
				  $queryStats = $this->db->query($api_interactions);
				  $resultStats = $queryStats->row_array();
				  $api_interactions_value = $resultStats['COUNT(*)'];
				  		  
				  
				  //Build Table Row		  
				  
				  $table_view_body .= "<tr>
				    <td>" . $row->NAME . "</td>  
				    <td>" . $free_subscriptions_value . "</td>  				    
				    <td>" . $subscription_purchases_value . "</td>  
				    <td>" . $issue_purchases_value . "</td>   
				    <td>" . $issue_downloads_value . "</td>
				    <td>" . $api_interactions_value . "</td>
				    </tr>";
			   }
			} 

		  
		  $table_view_footer = "</tbody></table>";
		  
        $var['publications_table'] = $table_view_head . $table_view_body . $table_view_footer;        
        
        $var['main_menu'] = $this->home_menu->fetch('tools');
        $var['main_content'] = $this->load->view('admin/common/analytics',$var,true);

        $this->load->view('layouts/admin/default', $var);
    }

}