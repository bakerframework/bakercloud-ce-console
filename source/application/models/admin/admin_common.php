<?php

class Admin_common extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function login() {
        $return = false;

        $sysUser = $this->config->item('sysUser');
        $crudUser = $this->input->post('crudUser', true);
        
        if (!empty($crudUser) && isset($crudUser['name']) && isset($crudUser['password'])) {
            if ($sysUser['enable'] == true) {
                if ($crudUser['name'] == $sysUser['name'] &&
                        $crudUser['password'] == $sysUser['password']) {
                    $auth = array();
                    $auth['user_name'] = $sysUser['name'];
                    $group = array('group_name' => 'SystemAdmin',
                        'group_manage_flag' => 1);
                    $auth['group'] = $group;

                    $this->session->set_userdata('CRUD_AUTH', $auth);
                    $return = true;
                }
            }
            $this->db->select('*');
            $this->db->from('crud_users');
            $this->db->where('user_name', $crudUser['name']);
            $this->db->where('user_password', sha1($crudUser['password']));

            $query = $this->db->get();
            $rs = $query->row_array();

            if (!empty($rs)) {

                $this->db->select('*');
                $this->db->from('crud_groups');
                $this->db->where('id', $rs['group_id']);

                $query = $this->db->get();
                $rs1 = $query->row_array();

                if (!empty($rs1)) {
                    $rs['group'] = $rs1;
                } else {
                    $rs['group'] = array('group_name' => 'None',
                        'group_manage_flag' => 1);
                }
                unset($rs['group_id']);
                unset($rs['user_password']);
                unset($rs['user_info']);

                $this->session->set_userdata('CRUD_AUTH', $rs);
                $return = true;
            }
        }

        return $return;
    }

}