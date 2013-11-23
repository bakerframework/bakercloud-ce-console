<?php

function removeDir($dirPath) {
    if (!is_dir($dirPath)) {
        throw new Exception("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            removeDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function f_scrud_init($conf) {
    $CI = & get_instance();
    $CI->load->model('crud_auth');
    $CI->crud_auth->checkBrowsePermission();

    return $conf;
}

function addPasswordConfirmElement($element) {
    $tmp = array();
    foreach ($element as $k => $v) {
        if (isset($_REQUEST['key']) && $k == 'crud_users.user_name') {
            $v['element'][1]['readonly'] = "readonly";
        }

        $tmp[$k] = $v;
        if ($k == 'crud_users.user_password') {
            $tmp['crud_users.user_password_confirm'] = Array(
                'alias' => 'User confirm password ',
                'element' => Array(
                    0 => 'password',
                    1 => Array(
                        'style' => 'width:210px;'
                    )
                )
            );
        }
    }
    $element = $tmp;

    return $element;
}

function passwordConfirmValidate($validate) {
    if (isset($_GET['xtype']) && $_GET['xtype'] != 'update') {
        $validate['crud_users.user_password_confirm'] = array('rule' => 'notEmpty',
            'message' => 'Please enter the value for User confirm password .');
    }
    return $validate;
}

function comparePassAndConfirmPass($error) {
    $CI = & get_instance();
    $data = $CI->input->post('data');
    if (!empty($data['crud_users']['user_password']) &&
            !empty($data['crud_users']['user_password_confirm'])) {
        if ($data['crud_users']['user_password'] != $data['crud_users']['user_password_confirm']) {
            $error['crud_users.user_password'][] = 'User password doesn\'t match User confirm password ';
            $error['crud_users.user_password_confirm'] = array();
        }
    }

    return $error;
}

function encryptPassword($data) {
    $data['crud_users']['user_password'] = sha1($data['crud_users']['user_password']);

    return $data;
}

function checkUser($error) {
    $CI = & get_instance();
    $key = $CI->input->post('key');
    $data = $CI->input->post('data');
    if (empty($key)) {
        $CI->db->select('*');
        $CI->db->from('crud_users');
        $CI->db->where('user_name', $data['crud_users']['user_name']);

        $query = $CI->db->get();
        $rs = $query->row_array();

        if (!empty($rs)) {
            $error['crud_users.user_name'][] = 'Someone already has that username. Try another? ';
        }
    }

    return $error;
}

function removeElement($element) {
    unset($element['crud_users.user_name']);
    unset($element['crud_users.user_password']);
    unset($element['crud_users.group_id']);

    return $element;
}

function removeElementData($data) {

    if (isset($data['crud_users']['user_name'])) {
        unset($data['crud_users']['user_name']);
    }
    if (isset($data['crud_users']['user_password'])) {
        unset($data['crud_users']['user_password']);
    }
    if (isset($data['crud_users']['group_id'])) {
        unset($data['crud_users']['group_id']);
    }

    return $data;
}

function removeValidate($validate) {
    unset($validate['crud_users.user_name']);
    unset($validate['crud_users.user_password']);
    unset($validate['crud_users.group_id']);

    return $validate;
}

function completeUpdate($data) {
    redirect('/user/editprofile');
}