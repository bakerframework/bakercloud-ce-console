<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logout extends Admin_Controller {

    public function index() {
        $this->session->unset_userdata('CRUD_AUTH');
        redirect('/admin/login');
    }

}