<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Table extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('crud_auth');
        $this->crud_auth->checkDatabaseManagement();
        $this->db->db_debug = false;
    }

    public function index() {
    	  $userData = $this->session->userdata('CRUD_AUTH');
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }

        $var = array();

        $tables = array();
        $query = $this->db->query('SHOW TABLES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }
        $var['tables'] = $tables;
	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('user');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('user');
		  }
        $var['main_content'] = $this->load->view('admin/table/browse', $var, true);

        $this->load->view('layouts/admin/user/default', $var);
    }

    public function delete() {
        $table = $this->input->post('table');
        if (empty($table) || trim($table) == '')
            exit;
        $sql = 'DROP TABLE IF EXISTS `' . $table . '`';
        $this->db->query($sql);

        if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table)) {
            removeDir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table);
        }
        if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php')) {
            @unlink(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php');
        }
    }

    public function form() {
    	  $userData = $this->session->userdata('CRUD_AUTH');
		  if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
		  }else{
		  	   $this->load->model('admin/home_menu');
		  }

        $var = array();


        $tables = array();
        $query = $this->db->query('SHOW TABLES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }
        $var['tables'] = $tables;

        $cs = array();
        $cs[''] = '';
        $query = $this->db->query('SHOW CHARACTER SET');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $cs[$row['Charset']] = $row['Charset'];
            }
        }

        asort($cs);

        $en = array();
        $en[''] = '';
        $query = $this->db->query('SHOW ENGINES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $en[$row['Engine']] = $row['Engine'];
            }
        }

        $this->savant->engines = $en;
        $var['engines'] = $en;

        $collations = array();

        foreach ($cs as $key => $value) {
            if (!empty($value)) {
                $collations[$key] = array();
                $query = $this->db->query("SHOW COLLATION LIKE '" . $value . "%'");
                if (!empty($query)) {
                    foreach ($query->result_array() as $row) {
                        $collations[$key][$row['Collation']] = $row['Collation'];
                    }
                }
            }
        }
        $var['collations'] = $collations;

        $table = $this->input->get('table');

        if (!empty($table) && in_array($table, $tables)) {
            $tblInfo = array();
            $rs = $this->db->query("SHOW TABLE STATUS FROM `" . $this->db->database . "` WHERE `name` = '" . $table . "'");
            if (!empty($rs)) {
                $var['table_info'] = $rs->row_array();
            }

            $colInfo = array();
            $rs = $this->db->query("SHOW FULL COLUMNS FROM `" . $table . "`");
            if (!empty($rs)) {
                foreach ($rs->result_array() as $row) {
                    $colInfo[] = $row;
                }
                //$this->savant->columns_info = $colInfo;
                $var['columns_info'] = $colInfo;
            }
        }

	     if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('user');
		  }else{
				$var['main_menu'] = $this->home_menu->fetch('user');
		  }
        $var['main_content'] = $this->load->view('admin/table/add', $var, true);

        $this->load->view('layouts/admin/user/default', $var);
    }

    public function insert() {
        $var = array();
        $errors = array();

        $table_name = $this->input->post('table_name');

        if (empty($table_name) || trim($table_name) == '') {
            $errors[] = "Please enter the value for Table Name";
        }

        $comma = ",";
        $sql = "CREATE TABLE `" . $table_name . "` (\n";

        $keys = array();
        $fields = $this->input->post('fields');
        foreach ($fields as $i => $v) {
            if (isset($v['key'])) {
                $keys[] = $v['name'];
            }
            if ($i == (count($fields) - 1)) {
                if (count($keys) <= 0) {
                    $comma = "";
                } else {
                    $comma = ",";
                }
            }
            if (trim($v['length_value']) == '') {
                switch (strtolower(trim($v['type']))) {
                    case 'bit':
                        $v['length_value'] = '1';
                        break;
                    case 'tinyint':
                        $v['length_value'] = '4';
                        break;
                    case 'smallint':
                        $v['length_value'] = '6';
                        break;
                    case 'mediumint':
                        $v['length_value'] = '9';
                        break;
                    case 'int':
                        $v['length_value'] = '11';
                        break;
                    case 'bigint':
                        $v['length_value'] = '20';
                        break;
                    case 'decimal':
                        $v['length_value'] = '10,0';
                        break;
                    case 'char':
                        $v['length_value'] = '50';
                        break;
                    case 'varchar':
                        $v['length_value'] = '255';
                        break;
                    case 'binary':
                        $v['length_value'] = '50';
                        break;
                    case 'varbinary':
                        $v['length_value'] = '255';
                        break;
                    case 'year':
                        $v['length_value'] = '4';
                        break;
                }
            }
            if (trim($v['length_value']) != '') {
                $v['length_value'] = " (" . $v['length_value'] . ") ";
            }

            $null = '';
            if (!isset($v['is_null'])) {
                $null = " NOT NULL ";
            } else {
                $null = " NULL ";
            }

            $def = "";
            if (trim($v['def']) != "") {
                switch (trim($v['def'])) {
                    case 'NULL':
                        if ($null != " NOT NULL ") {
                            $def = " DEFAULT NULL ";
                        }
                        break;
                    case 'USER_DEFINED':
                        $def = " DEFAULT '" . str_replace("'", "\'", $v['user_def']) . "' ";
                        break;
                    case 'CURRENT_TIMESTAMP':
                        $def = " DEFAULT CURRENT_TIMESTAMP ";
                        break;
                }
            }

            if (in_array($v['name'], $keys)) {
                $null = " NOT NULL ";

                if ($def == " DEFAULT NULL ") {
                    $def = "";
                }
            }

            $ai = '';
            if (isset($v['ai'])) {
                $ai = " AUTO_INCREMENT ";
            }

            $collation = "";
            if (trim($v['collation']) != '') {
                $ary = explode('_', trim($v['collation']));
                $collation = " CHARACTER SET " . $ary[0] . " COLLATE " . trim($v['collation']) . " ";
            }

            $sql .= "`" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai . $comma . " \n";
            $_fields = $this->input->post('fields');
            if ($i == (count($_fields) - 1)) {
                if (count($keys) > 0) {
                    $sql .= "PRIMARY KEY (`" . implode('`,`', $keys) . "`) \n";
                }
            }
        }

        $storage_engine = $this->input->post('storage_engine');
        if (!empty($storage_engine) && trim($storage_engine) != '') {
            $storage_engine = " ENGINE = " . $storage_engine . " ";
        }

        $collation = $this->input->post('collation');
        if (!empty($collation) && trim($collation) != '') {
            $ary = explode('_', trim($collation));
            $collation = " DEFAULT CHARACTER SET = " . $ary[0] . " DEFAULT COLLATE = " . $collation;
        }

        $table_comment = $this->input->post('table_comment');
        if (!empty($table_comment) && trim($table_comment) != '') {
            $table_comment = " COMMENT = '" . str_replace("'", "\'", $table_comment) . "' ";
        }

        $sql .= ")" . $storage_engine . $collation . $this->input->post('table_comment');

        if (count($errors) <= 0) {
            $this->db->query($sql);
            $err = $this->db->_error_message();
            if (!empty($err)) {
                $errors[] = $this->db->_error_message();
            }
        }

        if (count($errors) > 0) {
            $var['error'] = 1;
            $var['messages'] = $errors;
        } else {
            $var['error'] = 0;
        }

        echo json_encode($var);
    }

    public function update() {
        $var = array();
        $errors = array();
        $tables = array();

        $query = $this->db->query('SHOW TABLES');
        if (empty($query)) {
            throw new Exception($this->db->_error_message());
        }
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }

        $table_name = $this->input->post('table_name_id');

        if (in_array($table_name, $tables)) {
            try {
                $tblInfo = array();
                $rs = $this->db->query("SHOW TABLE STATUS FROM `" . $this->db->database . "` WHERE `name` = '" . $table_name . "'");
                if (empty($rs)) {
                    throw new Exception($this->db->_error_message());
                }
                $tblInfo = $rs->row_array();

                $colInfo = array();
                $fields = array();
                $oldKey = array();

                $rs = $this->db->query("SHOW FULL COLUMNS FROM `" . $table_name . "`");
                if (empty($rs)) {
                    throw new Exception($this->db->_error_message());
                }
                if (!empty($rs)) {
                    foreach ($rs->result_array() as $row) {
                        $colInfo[] = $row;
                        $fields[] = $row['Field'];
                        if (!empty($row['Key'])) {
                            $oldKey[] = $row['Field'];
                        }
                    }
                }


                $keys = array();
                $prevColumn = '';
                $newColumns = array();
                $q = array();
                $_fields = $this->input->post('fields');
                foreach ($_fields as $v) {
                    $sql = "";
                    if (isset($v['key'])) {
                        $keys[] = $v['name'];
                    }
                    if (trim($v['length_value']) == '') {
                        switch (strtolower(trim($v['type']))) {
                            case 'bit':
                                $v['length_value'] = '1';
                                break;
                            case 'tinyint':
                                $v['length_value'] = '4';
                                break;
                            case 'smallint':
                                $v['length_value'] = '6';
                                break;
                            case 'mediumint':
                                $v['length_value'] = '9';
                                break;
                            case 'int':
                                $v['length_value'] = '11';
                                break;
                            case 'bigint':
                                $v['length_value'] = '20';
                                break;
                            case 'decimal':
                                $v['length_value'] = '10,0';
                                break;
                            case 'char':
                                $v['length_value'] = '50';
                                break;
                            case 'varchar':
                                $v['length_value'] = '255';
                                break;
                            case 'binary':
                                $v['length_value'] = '50';
                                break;
                            case 'varbinary':
                                $v['length_value'] = '255';
                                break;
                            case 'year':
                                $v['length_value'] = '4';
                                break;
                        }
                    }
                    if (trim($v['length_value']) != '') {
                        $v['length_value'] = " (" . $v['length_value'] . ") ";
                    }

                    $null = '';
                    if (!isset($v['is_null'])) {
                        $null = " NOT NULL ";
                    } else {
                        $null = " NULL ";
                    }

                    $def = "";
                    if (trim($v['def']) != "") {
                        switch (trim($v['def'])) {
                            case 'NULL':
                                if ($null != " NOT NULL ") {
                                    $def = " DEFAULT NULL ";
                                }
                                break;
                            case 'USER_DEFINED':
                                $def = " DEFAULT '" . str_replace("'", "\'", $v['user_def']) . "' ";
                                break;
                            case 'CURRENT_TIMESTAMP':
                                $def = " DEFAULT CURRENT_TIMESTAMP ";
                                break;
                        }
                    }

                    if (in_array($v['name'], $keys)) {
                        $null = " NOT NULL ";

                        if ($def == " DEFAULT NULL ") {
                            $def = "";
                        }
                    }

                    $ai = '';
                    if (isset($v['ai'])) {
                        $ai = " AUTO_INCREMENT ";
                    }

                    $collation = "";
                    if (trim($v['collation']) != '') {
                        $ary = explode('_', trim($v['collation']));
                        $collation = " CHARACTER SET " . $ary[0] . " COLLATE " . trim($v['collation']) . " ";
                    }

                    if (isset($v['key']) && $ai != '') {
                        $q[$v['name']] = $v['type'] . $v['length_value'] . $collation . $null . $def . $ai;
                        $ai = '';
                    }

                    if (!empty($v['id'])) {
                        if ($v['name'] == $v['id']) {
                            $sql .= "ALTER TABLE `" . $table_name . "` MODIFY COLUMN `" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai;
                        } else {
                            $sql .= "ALTER TABLE `" . $table_name . "` CHANGE COLUMN `" . $v['id'] . "` `" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai . $prevColumn;
                        }
                    } else {
                        $sql .= "ALTER TABLE `" . $table_name . "` ADD COLUMN `" . $v['name'] . "` " . $v['type'] . $v['length_value'] . $collation . $null . $def . $ai . $prevColumn;
                    }

                    $rs = $this->db->query($sql);
                    if (empty($rs)) {
                        throw new Exception($this->db->_error_message());
                    }
                    $prevColumn = " AFTER `" . $v['name'] . "`";
                    $newColumns[] = $v['name'];
                }

                foreach ($fields as $field) {
                    if (!in_array($field, $newColumns)) {
                        $sql = "ALTER TABLE `" . $table_name . "` DROP COLUMN `" . $field . "`";
                        $rs = $this->db->query($sql);
                        if (empty($rs)) {
                            throw new Exception($this->db->_error_message());
                        }
                    }
                }

                $crs = array_diff($oldKey, $keys);
                if (!empty($crs) || count($oldKey) != count($keys)) {
                    if (count($keys) > 0) {
                        if (empty($oldKey)) {
                            $rs = $this->db->query("ALTER TABLE `" . $table_name . "` ADD PRIMARY KEY(`" . implode('`,`', $keys) . "`)");
                            if (empty($rs)) {
                                throw new Exception($this->db->_error_message());
                            }
                        } else {
                            $rs = $this->db->query("ALTER TABLE `" . $table_name . "` DROP PRIMARY KEY, ADD PRIMARY KEY(`" . implode('`,`', $keys) . "`)");
                            if (empty($rs)) {
                                throw new Exception($this->db->_error_message());
                            }
                        }
                    } else {
                        if (count($oldKey) > 0) {
                            $newKeys = array();
                            $rs = $this->db->query("SHOW FULL COLUMNS FROM `" . $table_name . "`");
                            if (!empty($rs)) {
                                foreach ($rs->result_array() as $row) {
                                    if (!empty($row['Key'])) {
                                        $newKeys[] = $row['Field'];
                                    }
                                }
                            }
                            if (empty($rs)) {
                                throw new Exception($this->db->_error_message());
                            }
                            if (!empty($newKeys)) {
                                $rs = $this->db->query("ALTER TABLE `" . $table_name . "` DROP PRIMARY KEY");
                                if (empty($rs)) {
                                    throw new Exception($this->db->_error_message());
                                }
                            }
                        }
                    }
                }

                if (!empty($q)) {
                    foreach ($q as $name => $v1) {
                        $rs = $this->db->query("ALTER TABLE `" . $table_name . "` MODIFY COLUMN `" . $name . "` " . $v1);
                        if (empty($rs)) {
                            throw new Exception($this->db->_error_message());
                        }
                    }
                }

                $_storage_engine = $this->input->post('storage_engine');
                if (strtolower($_storage_engine) != strtolower($tblInfo['Engine'])) {
                    $sql = "ALTER TABLE `" . $table_name . "` ENGINE = " . $_storage_engine;
                    $rs = $this->db->query($sql);
                    if (empty($rs)) {
                        throw new Exception($this->db->_error_message());
                    }
                }

                $_collation = $this->input->post('collation');
                if (strtolower($_collation) != strtolower($tblInfo['Collation'])) {
                    $ary = explode('_', trim($_collation));
                    $sql = "ALTER TABLE `" . $table_name . "` CONVERT TO CHARACTER SET " . $ary[0] . " COLLATE " . $_collation;
                    $rs = $this->db->query($sql);
                    if (empty($rs)) {
                        throw new Exception($this->db->_error_message());
                    }
                }

                $sql = "ALTER TABLE `" . $table_name . "` COMMENT = '" . $this->input->post('table_comment') . "'";
                $rs = $this->db->query($sql);
                if (empty($rs)) {
                    throw new Exception($this->db->_error_message());
                }

                $old_table_name = $this->input->post('table_name');
                if (strtolower($table_name) != strtolower($old_table_name)) {
                    $sql = "RENAME TABLE `" . $table_name . "` TO  `" . $old_table_name . "`";
                    $rs = $this->db->query($sql);
                    if (empty($rs)) {
                        throw new Exception($this->db->_error_message());
                    }
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            $var['error'] = 1;
            $var['messages'] = $errors;
        } else {
            $var['error'] = 0;
            if (isset($table_name) &&
                    trim($table_name) != '') {
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table_name)) {
                    removeDir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table_name);
                }
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table_name . '.php')) {
                    @unlink(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table_name . '.php');
                }
            }
        }

        echo json_encode($var);
    }

}
