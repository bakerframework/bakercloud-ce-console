<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Editprofile extends Admin_Controller {

    public function index() {
        $this->load->model('crud_auth');
        $userData = $this->session->userdata('CRUD_AUTH');
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }
        $this->load->model('user/user_menu');
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');

        $crudAuth = $this->session->userdata('CRUD_AUTH');
        $var = array();
        
	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('Account');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('Account');
		  }
        
        $hook = Hook::singleton();

        $hook->add_function('SCRUD_EDIT_FORM', 'removeElement');
        $hook->add_function('SCRUD_EDIT_CONFIRM', 'removeElement');
        $hook->add_function('SCRUD_BEFORE_VALIDATE', 'removeValidate');
        $hook->add_function('SCRUD_COMPLETE_UPDATE', 'completeUpdate');
        $hook->add_function('SCRUD_BEFORE_SAVE', 'removeElementData');
        
        
        if (!isset($_GET['xtype'])){
            $_GET['xtype'] = 'form';
        }
        $_GET['table'] = 'crud_users';
        $_GET['key']['crud_users.id'] = $crudAuth['id'];
        
        $_SERVER['QUERY_STRING'] = $_SERVER['QUERY_STRING'].'&key[crud_users.id]='.$crudAuth['id'];

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $this->input->get('table') . '.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $this->input->get('table') . '.php'));
        $conf = unserialize($content);
        $conf['theme_path'] = FCPATH . 'application/views/user/profile/crud';
        $this->load->library('crud', array('table' => $this->input->get('table'), 'conf' => $conf));
        
        $var['main_content'] = $this->load->view('user/profile/profile', array('content' => $this->crud->process(),'user_menu' => $this->user_menu->fetch('profile')), true);

        $this->load->view('layouts/user/default', $var);
    }

}