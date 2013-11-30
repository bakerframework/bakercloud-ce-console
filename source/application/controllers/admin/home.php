<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends Admin_Controller {

    public function index() {
        $this->load->model('crud_auth');
        $this->load->model('admin/home_menu');

        $var = array();
        
        $var['main_menu'] = $this->home_menu->fetch();
        $var['main_content'] = $this->load->view('admin/common/home',$var,true);

        $this->load->view('layouts/admin/default', $var);
    }

}