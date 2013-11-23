<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['scrud_version'] = '1.0';

$sysUser = array();
$sysUser['name'] = "systemAdmin";
$sysUser['password'] = "123456";
$sysUser['enable'] = false;

$config['sysUser'] = $sysUser;

define('__DATABASE_CONFIG_PATH__', FCPATH.'application/config/database');
define('__IMAGE_UPLOAD_REAL_PATH__', FCPATH.'media/images/');
define('__FILE_UPLOAD_REAL_PATH__', FCPATH.'media/files/');

$CI =& get_instance();
define('__MEDIA_PATH__', $CI->config->base_url('') .'/media/');

$config['imageExtensions'] = array(".png", ".jpg", ".gif");
$config['fileExtensions'] = array(".png", ".jpg", ".gif",".doc",".docx",".xls",".xlsx",".zip",".rar",".7z");



