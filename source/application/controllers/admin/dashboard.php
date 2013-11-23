<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function index() {
        $this->load->model('crud_auth');
        $this->load->model('admin/admin_menu');

        $var = array();
        
        $tables = array();
        $query = $this->db->query('SHOW TABLES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }
        $var['tables'] = $tables;
        
        $var['main_menu'] = $this->admin_menu->fetch();
        $var['main_content'] = $this->load->view('admin/common/dashboard',$var,true);

        $this->load->view('layouts/admin/default', $var);
    }

}