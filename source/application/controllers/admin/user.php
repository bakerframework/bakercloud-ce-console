<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('crud_auth');
        $this->crud_auth->checkUserManagement();
    }

    public function index() {
    	  $userData = $this->session->userdata('CRUD_AUTH');
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }
        
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');
        $_GET['table'] = 'crud_users';
        $var = array();
        
	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('user');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('user');
		  }

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/crud_users.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/crud_users.php'));
        $conf = unserialize($content);

        $hook = Hook::singleton();

        $hook->add_function('SCRUD_ADD_FORM', 'addPasswordConfirmElement');
        $hook->add_function('SCRUD_EDIT_FORM', 'addPasswordConfirmElement');

        $hook->add_function('SCRUD_BEFORE_VALIDATE', 'passwordConfirmValidate');
        $hook->add_function('SCRUD_VALIDATE', 'comparePassAndConfirmPass');
        $hook->add_function('SCRUD_VALIDATE', 'checkUser');

        $hook->add_function('SCRUD_BEFORE_SAVE', 'encryptPassword');
        
        $conf['theme_path'] = FCPATH . 'application/views/admin/user/templates';
        $this->load->library('crud', array('table' => 'crud_users', 'conf' => $conf));
        $var['main_content'] = $this->load->view('admin/user/user', array('content' => $this->crud->process()), true);

        $this->load->view('layouts/admin/user/default', $var);
    }

    public function group() {
    	  $userData = $this->session->userdata('CRUD_AUTH');
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }
	
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');
        $_GET['table'] = 'crud_groups';
        $var = array();

	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('user');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('user');
		  }

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/crud_groups.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/crud_groups.php'));
        $conf = unserialize($content);
        $conf['theme_path'] = FCPATH . 'application/views/admin/user/templates';
        $this->load->library('crud', array('table' => 'crud_groups', 'conf' => $conf));
        $var['main_content'] = $this->load->view('admin/user/group', array('content' => $this->crud->process()), true);

        $this->load->view('layouts/admin/user/default', $var);
    }

    public function permission() {
    	  $userData = $this->session->userdata('CRUD_AUTH');    	
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }
	
        $_GET['table'] = 'crud_groups';
        $var = array();

	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('user');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('user');
		  }

        $fields = array();
        $sql = "SHOW COLUMNS FROM `" . $this->input->get('table') . "`";
        $query = $this->db->query($sql);
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $fields[] = $row;
            }
        }
        $var['fields'] = $fields;

        $groups = array();
        $query = $this->db->query('SELECT * FROM crud_groups');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $groups[] = $row;
            }
        }
        $var['groups'] = $groups;

        $query = $this->db->query('SELECT * FROM crud_permissions');
        $pt = array();
        if (!empty($query)) {
            foreach ($query->result_array() as $k => $v) {
                $pt[$v['group_id'] . '_' . $v['table_name'].'_'.$v['permission_type']] = $v['permission_type'];
            }
        }
        $var['pt'] = $pt;

        $var['main_content'] = $this->load->view('admin/user/permission', $var, true);

        $this->load->view('layouts/admin/user/default', $var);
    }
    
    public function user_permission(){
    	  $userData = $this->session->userdata('CRUD_AUTH');    
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }
    	$var = array();
	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('user');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('user');
		  }
    	$var['main_content'] = $this->load->view('admin/user/user_permission_browse', $var, true);
    	
    	$this->load->view('layouts/admin/user/default', $var);
    }
    
    public function user_json(){
    	$userDao = new ScrudDao('crud_users', $this->db);
    	 
    	if (!isset($_GET['id'])){
    		$params = array();
    		$params['fields'] = array('id','user_name');
    		$params['conditions'] = array('user_name like ?',array("%".$_GET['q']."%"));
    		$rs = $userDao->find($params);
    		echo $_GET['callback'].'('.json_encode($rs).')';
    	}else{
    		$var = array();
    		
	    	$tables = array();
	        $query = $this->db->query('SHOW TABLES');
	        if (!empty($query)) {
	            foreach ($query->result_array() as $row) {
	                $tables[] = $row['Tables_in_' . $this->db->database];
	            }
	        }
    
    		$var['tables'] = $tables;
    
    		$params = array();
    		$params['fields'] = array('id','user_name','user_manage_flag');
    		$params['conditions'] = array('id = ?',array($_GET['id']));
    		
    		$rs = $userDao->findFirst($params);
    		$var['user'] = $rs;
    
    		$pDao = new ScrudDao('crud_user_permissions', $this->db);
    		$params = array();
    		$params['conditions'] = array('user_id = ?',array($_GET['id']));
    
    		$rs = $pDao->find($params);
    		$pt = array();
    		if (!empty($rs)){
    			foreach($rs as $k => $v){
    				$pt[$v['user_id'].'_'.$v['table_name'].'_'.$v['permission_type']] = $v['permission_type'];
    			}
    		}
    
    		$var['pt'] = $pt;
    		
    		$this->load->view('admin/user/user_permission', $var);
    	}
    }

    public function savePermission() {
    	$this->load->library('session');
    	$groupDao = new ScrudDao('crud_groups', $this->db);
    	$pDao = new ScrudDao('crud_permissions', $this->db);
    	$data = $this->input->post('data');
    	$this->db->query('TRUNCATE TABLE `crud_permissions`');
    	if (count($data) > 0) {
    		foreach ($data as $k => $v) {
    			$group = array();
    			$group['group_manage_flag'] = $v['group_manage_flag'];
    			$groupDao->update($group, array('id = ?', array($v['group_id'])));
    			$crudAuth = $this->session->userdata('CRUD_AUTH');
    			if ($v['group_id'] == $crudAuth['group']['id']){
    				$crudAuth['group']['group_manage_flag'] = $v['group_manage_flag'];
    				$this->session->set_userdata('CRUD_AUTH',$crudAuth);
    			}
    			if (count($v['tables']) > 0){
    				$tables = $v['tables'];
    				foreach ($tables as $k1 => $v1){
    					if (count($v1['permission_type']) > 0){
    						foreach ($v1['permission_type'] as $permission){
    							if ((int)$permission > 0){
    								$p = array();
    								$p['group_id'] = $v['group_id'];
    								$p['table_name'] = $v1['table_name'];
    								$p['permission_type'] = $permission;
    								$pDao->save($p);
    							}
    						}
    					}
    				}
    			}
    		}
    	}
    	
    	$ary = array(1,2,3,4);
    	foreach ($data as $k => $v) {
    		foreach ($ary as $v1){
    			if ($v['group_manage_flag'] == 1 || $v['group_manage_flag'] == 3){
    				$p = array();
    				$p['group_id'] = $v['group_id'];
    				$p['table_name'] = 'crud_users';
    				$p['permission_type'] = $v1;
    				$pDao->save($p);
    			}
    		}
    	}
    	foreach ($data as $k => $v) {
    		foreach ($ary as $v1){
    			if ($v['group_manage_flag'] == 1 || $v['group_manage_flag'] == 3){
    				$p = array();
    				$p['group_id'] = $v['group_id'];
    				$p['table_name'] = 'crud_groups';
    				$p['permission_type'] = $v1;
    				$pDao->save($p);
    			}
    		}
    	}
    }
	public  function saveUserPermission(){
		$this->load->library('session');
		$userDao = new ScrudDao('crud_users', $this->db);
		$pDao = new ScrudDao('crud_user_permissions', $this->db);
		$data = $this->input->post('data');
		$this->db->query('TRUNCATE TABLE `crud_user_permissions`');
		
		if (count($data) > 0) {
			foreach ($data as $k => $v) {
				$user = array();
				$user['user_manage_flag'] = $v['user_manage_flag'];
				$userDao->update($user,array('id = ?',array($v['user_id'])));
				$crudAuth = $this->session->userdata('CRUD_AUTH');
				if ($v['user_id'] == $crudAuth['id']){
					$crudAuth['user_manage_flag'] = $v['user_manage_flag'];
					$this->session->set_userdata('CRUD_AUTH',$crudAuth);
				}
				
				if (count($v['tables']) > 0){
					$tables = $v['tables'];
					foreach ($tables as $k1 => $v1){
						if (count($v1['permission_type']) > 0){
							foreach ($v1['permission_type'] as $permission){
								if ((int)$permission > 0){
									$p = array();
									$p['user_id'] = $v['user_id'];
									$p['table_name'] = $v1['table_name'];
									$p['permission_type'] = $permission;
									$pDao->save($p);
								}
							}
						}
					}
				}
			}
		}
		 
		$ary = array(1,2,3,4);
		foreach ($data as $k => $v) {
			foreach ($ary as $v1){
				if ($v['user_manage_flag'] == 1 || $v['user_manage_flag'] == 3){
					$p = array();
					$p['user_id'] = $v['user_id'];
					$p['table_name'] = 'crud_users';
					$p['permission_type'] = $v1;
					$pDao->save($p);
				}
			}
		}
		foreach ($data as $k => $v) {
			foreach ($ary as $v1){
				if ($v['user_manage_flag'] == 1 || $v['user_manage_flag'] == 3){
					$p = array();
					$p['user_id'] = $v['user_id'];
					$p['table_name'] = 'crud_groups';
					$p['permission_type'] = $v1;
					$pDao->save($p);
				}
			}
		}
	}
}