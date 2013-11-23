<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once FCPATH . 'application/third_party/scrud/class/Hook.php';
require_once FCPATH . 'application/third_party/scrud/class/ScrudDao.php';
require_once FCPATH . 'application/third_party/scrud/class/functions.php';

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->config->load('scrud');
        $this->load->helper('url');
        $this->lang->load('message', 'english');
        $this->lang->load('error', 'english');
        $this->load->database();
    }

}

class Admin_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->authenticate();

        $hook = Hook::singleton();
        $hook->set('SCRUD_INIT');
        $hook->set('SCRUD_BEFORE_VALIDATE');
        $hook->set('SCRUD_VALIDATE');
        $hook->set('SCRUD_ADD_FORM');
        $hook->set('SCRUD_EDIT_FORM');
        $hook->set('SCRUD_ADD_CONFIRM');
        $hook->set('SCRUD_EDIT_CONFIRM');
        $hook->set('SCRUD_BEFORE_SAVE');
        $hook->set('SCRUD_BEFORE_INSERT');
        $hook->set('SCRUD_BEFORE_UPDATE');
        $hook->set('SCRUD_COMPLETE_INSERT');
        $hook->set('SCRUD_COMPLETE_UPDATE');
        $hook->set('SCRUD_COMPLETE_SAVE');
    }

    private function authenticate() {
        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/v_1.0.txt')) {
            redirect('install/index');
        } else {
            $auth = $this->session->userdata('CRUD_AUTH');
            if (empty($auth) && $this->uri->uri_string() != 'admin/login') {
                redirect('/admin/login');
            }
        }
    }

}