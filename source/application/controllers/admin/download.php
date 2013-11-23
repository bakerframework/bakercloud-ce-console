<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Download extends Admin_Controller {

    public function index() {
        $var = array();
        $var['file'] = __FILE_UPLOAD_REAL_PATH__ . $_GET['file'];
        $this->load->view('layouts/admin/download', $var);
    }

}