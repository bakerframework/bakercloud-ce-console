<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends Admin_Controller {

    public function index() {
        $this->load->model('admin/admin_common');

        $var = array();
        if ($this->admin_common->login()) {
        		$userData = $this->session->userdata('CRUD_AUTH');
				if($userData['group']['group_name'] == "Administrators"){
					redirect('/admin/dashboard');
				}else{
					redirect('/admin/home');
				}
        } else {
            $var['main_content'] = $this->load->view('admin/common/login', $var, true);

            $this->load->view('layouts/admin/login', $var);
        }
    }

}