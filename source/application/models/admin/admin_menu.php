<?php

class Admin_menu extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function fetch($type = 'dashboard') {

        $var = array();
        $var['type'] = $type;
        $var['crudAuth'] = $this->session->userdata('CRUD_AUTH');

        $tables = array();
        $query = $this->db->query('SHOW TABLES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }
        $var['tables'] = $tables;
        $var['database_name'] = $this->db->database;

        $this->load->model('crud_auth');
        $var['auth'] = $this->crud_auth;

        return $this->load->view('admin/menu/menu', $var, true);
    }

}