<?php

class User_menu extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function fetch($type='profile') {
        $var = array();
        $var['type'] = $type;
        
        return $this->load->view('user/menu',$var,true);
    }
}
