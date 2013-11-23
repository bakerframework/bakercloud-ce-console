<?php

class Crud_auth extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function checkGroupManageFlag() {
    	$auth = $this->session->userdata('CRUD_AUTH');
        if ((int) $auth['group']['group_manage_flag'] == 0 &&
        (int) $auth['user_manage_flag'] == 0) {
        	show_error('No Access');
        }
    }
    
    public function checkUserManagement(){
    	$auth = $this->session->userdata('CRUD_AUTH');
    	if ((int) $auth['group']['group_manage_flag'] != 1 &&
    	(int) $auth['group']['group_manage_flag'] != 3 &&
    	(int) $auth['user_manage_flag'] != 1 &&
    	(int) $auth['user_manage_flag'] != 3) {
    		show_error('No Access');
    	}
    }
    
    public function checkDatabaseManagement(){
    	$auth = $this->session->userdata('CRUD_AUTH');
    	if ((int) $auth['group']['group_manage_flag'] != 2 &&
    	(int) $auth['group']['group_manage_flag'] != 3 &&
    	(int) $auth['user_manage_flag'] != 2 &&
    	(int) $auth['user_manage_flag'] != 3) {
    		show_error('No Access');
    	}
    }

    public function checkConfigPermission() {
        $permissions = $this->getPermissionType();
        if (!in_array(5, $permissions)) {
        	show_error('No Access');
        }
    }

    public function checkBrowsePermission() {
        $permissions = $this->getPermissionType();
        $xtype = $this->input->get('xtype');
        if (empty($xtype)) {
        	$this->session->unset_userdata('auth_token_xtable');
            $this->session->unset_userdata('xtable_search_conditions');
            $_GET['xtype'] = 'index';
        }
        switch (strtolower($xtype)) {
        	case 'index':
        		if (!in_array(4, $permissions)) {
        			show_error('No Access');
        		}
        		break;
        	case 'form':
        	case 'confirm':
        	case 'update':
        		if (isset($_REQUEST['key'])){
        			if (!in_array(2, $permissions)) {
        				show_error('No Access');
        			}
        		}else{
        			if (!in_array(1, $permissions)) {
        				show_error('No Access');
        			}
        		}
        		break;
        	case 'del':
        	case 'delFile':
        	case 'delconfirm':
        		if (!in_array(3, $permissions)) {
        			redirect('error/no_access.php');
        		}
        		break;
        }
    }

    public function getPermissionType($table = null) {
        $auth = $this->session->userdata('CRUD_AUTH');
        
        if ($table == null) {
        	if (isset($_POST['table'])) {
        		$table = $this->input->post('table', true);
        	} else if (isset($_GET['table'])) {
        		$table = $this->input->get('table', true);
        	}
        }
        $rs = array();
        if (isset($auth['group']['id'])) {
        	$this->db->select('*');
            $this->db->from('crud_permissions');
            $this->db->where('group_id', (int) $auth['group']['id']);
            $this->db->where('table_name', $table);
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        $permissions = array();
        if (!empty($rs)){
        	foreach ($rs as $v){
        		$permissions[] = $v['permission_type'];
        	}
        }
        
         if (isset($auth['id'])) {
        	$this->db->select('*');
        	$this->db->from('crud_user_permissions');
        	$this->db->where('user_id', (int) $auth['id']);
        	$this->db->where('table_name', $table);
        	$query = $this->db->get();
        	$rs = $query->result_array();
        }
        if (!empty($rs)){
        	foreach ($rs as $v){
        		if (!in_array($v['permission_type'], $permissions)){
        			$permissions[] = $v['permission_type'];
        		}
        	}
        }
        
        
        return $permissions;
    }

}
