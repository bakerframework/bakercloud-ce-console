<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Changepassword extends Admin_Controller {

    public function index() {
        if (count($_POST) > 0) {
            $this->update();
        } else {
            $this->display();
        }
    }

    private function display() {
    	  $userData = $this->session->userdata('CRUD_AUTH');
    	  
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
				$this->load->model('admin/home_menu');
		  }
		
        $this->load->model('user/user_menu');
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');

        $var = array();
        $var['errors'] = array();
        $var['update_flag'] = 0;
        
			if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('account');
			}else{
				$var['main_menu'] = $this->home_menu->fetch('account');
			}
        $var['user_menu'] = $this->user_menu->fetch('password');
        $var['main_content'] = $this->load->view('user/password',$var,true);
        
        $this->load->view('layouts/user/default', $var);
    }

    private function update() {
    	  $userData = $this->session->userdata('CRUD_AUTH');
    	  
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
				$this->load->model('admin/home_menu');
		  }
        $this->load->model('user/user_menu');
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');

        $var = array();
        $var['update_flag'] = 1;
        
			if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('account');
			}else{
				$var['main_menu'] = $this->home_menu->fetch('account');
			}
        $var['user_menu'] = $this->user_menu->fetch('password');
        
        $errors = array();
        $crudAuth = $this->session->userdata('CRUD_AUTH');

        if ($this->input->post('current_password') == '') {
            $errors['current_password'] = 'Please enter your current password.';
        }
        if ($this->input->post('new_password') == '') {
            $errors['new_password'] = 'Please enter a new password.';
        }
        if ($this->input->post('confirm_new_password') == '') {
            $errors['confirm_new_password'] = 'Please confirm your new password.';
        }

        if (count($errors) <= 0) {
            if ($this->input->post('new_password') != $this->input->post('confirm_new_password')) {
                $errors['confirm_new_password'] = 'New password and New confirm password does not miss match.';
            }
        }

        if (count($errors) <= 0) {
            $userDao = new ScrudDao('crud_users', $this->db);
            $params = array();
            $params['conditions'] = array('id = ? and user_password = ?', array($crudAuth['id'], sha1($this->input->post('current_password'))));
            $rs = $userDao->findFirst($params);
            if (empty($rs)) {
                $errors['current_password'] = "Current password you entered was incorrect";
            }
        }

        if (count($errors) <= 0) {
            $data['id'] = $crudAuth['id'];
            $data['user_password'] = sha1($this->input->post('new_password'));
            $userDao->save($data);
        }

        $var['errors'] = $errors;
        $var['main_content'] = $this->load->view('user/password',$var,true);


        $this->load->view('layouts/user/default', $var);
    }

}