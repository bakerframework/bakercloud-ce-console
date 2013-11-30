<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function index() {
        $this->load->model('crud_auth');
        $this->load->helper('url');
        
        $var = array();
        
        $tables = array();
        $query = $this->db->query('SHOW TABLES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }
        $var['tables'] = $tables;
        
        // Validate User didn't just switch the URL to access the Dashboard page which is an Admin only page
        $userData = $this->session->userdata('CRUD_AUTH');
		if($userData['group']['group_name'] == "Administrators"){
			$this->load->model('admin/admin_menu');
			$var['main_menu'] = $this->admin_menu->fetch();
			$var['main_content'] = $this->load->view('admin/common/dashboard',$var,true);
		}else{
		    //redirect to home page instead of dashboard
		    redirect('/admin/home');
		}

        $this->load->view('layouts/admin/default', $var);
    }

}