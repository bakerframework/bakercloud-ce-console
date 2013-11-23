<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Scrud extends Admin_Controller {

    public function browse() {
    	 
 	      $userData = $this->session->userdata('CRUD_AUTH');
			if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
			}else{
				$this->load->model('admin/home_menu');
			}
    	          
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');
        $var = array();
        if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('browse');
			}else{
				$var['main_menu'] = $this->home_menu->fetch('browse');
			}
        
        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $this->input->get('table') . '.php')) {
            exit;
        }
        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $this->input->get('table') . '.php'));
        $conf = unserialize($content);

        $hook = Hook::singleton();
        $hook->add_function('SCRUD_INIT', 'f_scrud_init');

        $this->load->library('crud', array('table' => $this->input->get('table'), 'conf' => $conf));

        $var['main_content'] = $this->load->view('admin/scrud/browse', array('content' => $this->crud->process(),'conf' => $conf), true);

        $this->load->view('layouts/admin/scrud/browse', $var);
    }

    public function config() {
        $var = array();
        
 	      $userData = $this->session->userdata('CRUD_AUTH');
			if($userData['group']['group_name'] == "Administrators"){
				$this->load->model('admin/admin_menu');
			}else{
				$this->load->model('admin/home_menu');
			}        

        $table = $this->input->get('table', true);
        $this->load->model('crud_auth');
        $this->crud_auth->checkConfigPermission();

        $fields = array();
        $sql = "SHOW COLUMNS FROM `" . $table . "`";
        $query = $this->db->query($sql);
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $fields[] = $row;
            }
        }
        $var['fields'] = $fields;

        if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $table . '.php')) {
            $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $table . '.php'));
            if (empty($content)) {
                $content = "{}";
            }
        } else {
            $content = "{}";
        }

        $var['crudConfigTable'] = $content;

        $tables = array();
        $query = $this->db->query('SHOW TABLES');
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $tables[] = $row['Tables_in_' . $this->db->database];
            }
        }
        $var['tables'] = $tables;

        $fieldConfig = array();
        foreach ($fields as $f) {
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $f['Field'] . '.php')) {
                $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $f['Field'] . '.php'));
                if (!empty($content)) {
                    $fieldConfig[$f['Field']] = $content;
                }
            }
        }
        $var['fieldConfig'] = $fieldConfig;

        if($userData['group']['group_name'] == "Administrators"){
				$var['main_menu'] = $this->admin_menu->fetch('config');
			}else{
				$var['main_menu'] = $this->home_menu->fetch('config');
			}
		
        $var['main_content'] = $this->load->view('admin/scrud/config', $var, true);

        $this->load->view('layouts/admin/scrud/config', $var);
    }

    public function getoptions() {
        $var = array();
        $config = $this->input->post('config');
        if (!empty($config)) {
            $crudDao = new ScrudDao($config['table'], $this->db);


            if (isset($config['key']) &&
                    trim($config['key']) != '' &&
                    isset($config['value']) &&
                    trim($config['value']) != '') {
                $params = array();
                $params['fields'] = array($config['key'], $config['value']);
                $rs = $crudDao->find($params);
                if (!empty($rs)) {
                    foreach ($rs as $v) {
                        $var[$v[$config['key']]] = $v[$config['value']];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($var);
    }

    public function getfields() {
        $table = $this->input->get('table');
        $fields = array();
        $sql = "SHOW COLUMNS FROM `" . $table . "`";
        $query = $this->db->query($sql);
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $fields[] = $row['Field'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($fields);
    }

    public function delfile() {
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');
        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $_GET['table'] . '.php')) {
            exit;
        }
        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);
        $this->load->library('crud', array('table' => $this->input->get('table'), 'conf' => $conf));
        $data = $this->crud->process();
        die($data);
    }

    public function removeconfig() {
        $this->load->model('crud_auth');
        $this->crud_auth->checkConfigPermission();

        global $config_database;
        $table = $this->input->get('table');
        if (!empty($table) &&
                trim($table) != '') {
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table)) {
                removeDir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table);
            }
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php')) {
                @unlink(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php');
            }
        }
    }

    public function exportcsv() {
        $this->load->add_package_path(APPPATH . 'third_party/scrud/');
        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $_GET['table'] . '.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);
        $this->load->library('crud', array('table' => $this->input->get('table'), 'conf' => $conf));
        echo $this->crud->process();
    }

    public function saveconfig() {
        $this->load->model('crud_auth');
        $this->crud_auth->checkConfigPermission();
        $table = $this->input->post('table');
        if (!empty($table)) {

            if (!is_dir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database)) {
                $oldumask = umask(0);
                mkdir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database, 0777);
                umask($oldumask);
            }

            if (!is_dir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table)) {
                $oldumask = umask(0);
                mkdir(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table, 0777);
                umask($oldumask);
            }

            $fields = array();
            $sql = "SHOW COLUMNS FROM `" . $table . "`";
            $query = $this->db->query($sql);
            if (!empty($query)) {
                foreach ($query->result_array() as $row) {
                    $fields[] = $row;
                }
            }
            $config = $this->input->post('config');
            if (!empty($config)) {
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $table . '.php')) {
                    @unlink(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $table . '.php');
                }
                $oldumask = umask(0);
                file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $table . '.php', "<?php exit; ?>\n" . json_encode($config));
                umask($oldumask);
            }
            $pcrud = $this->input->post('scrud');
            if (!empty($pcrud)) {
                foreach ($fields as $field) {
                    if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $field['Field'] . '.php')) {
                        @unlink(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $field['Field'] . '.php');
                    }
                }
                foreach ($pcrud as $f => $v) {
                    $oldumask = umask(0);
                    file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '/' . $f . '.php', "<?php exit; ?>\n" . json_encode($v));
                    umask($oldumask);
                }
            }

            $conf = array();
            $conf['title'] = $config['table']['crudTitle'];
            $conf['limit'] = $config['table']['crudRowsPerPage'];
            $conf['frm_type'] = $config['frm_type'];
            $join = array();

            if (isset($config['join']) && count($config['join']) > 0) {
                foreach ($config['join'] as $v) {
                    $join[$v['table']] = array($v['type'], $v['table'], $v['currentField'] . ' = ' . $v['targetField']);
                }
            }

            $conf['join'] = $join;

            if (isset($config['table']['crudOrderField'])) {
                $conf['order_field'] = $table . '.' . $config['table']['crudOrderField'];
            }

            if (isset($config['table']['crudOrderType'])) {
                $conf['order_type'] = $config['table']['crudOrderType'];
            }
            $validate = array();
            $dataList = array();
            if (isset($config['table']['noColumn']) &&
                    (int) $config['table']['noColumn'] == 1) {
                $dataList['no'] = array('alias' => $this->lang->line('__LBL_NO__'), 'width' => 40, 'align' => 'center', 'format' => '{no}');
            }


            foreach ($pcrud as $field => $v) {
                $elements[$table . '.' . $field] = array();
                $element = array();
                $element[] = $v['type'];
                $attributes = array();
                switch ($v['type']) {
                    case 'checkbox':
                    case 'radio':
                        if (!empty($v['options'])) {
                            $options = array();
                            foreach ($v['options'] as $key => $value) {
                                $options[$v['values'][$key]] = $value;
                            }
                            $element[] = $options;
                        }
                        break;
                    case 'select':
                    	if (!empty($v['multiple'])) {
                    		$attributes['multiple'] = $v['multiple'];
                    	}
                    case 'autocomplete':
                        if ($v['list_choose'] == 'default') {
                            if (!empty($v['options'])) {
                                $options = array();
                                foreach ($v['options'] as $key => $value) {
                                    $options[$key + 1] = $value;
                                }
                                $element[] = $options;
                            }
                        } else if ($v['list_choose'] == 'database') {
                            $opt = array();
                            $opt['option_table'] = $v['db_options']['table'];
                            $opt['option_key'] = $v['db_options']['key'];
                            $opt['option_value'] = $v['db_options']['value'];
                            $element[] = $opt;
                        }
                        break;
                    case 'text':
                    case 'password':
                        $style = "";
                        if (!empty($v['type_options']['size'])) {
                            $style .= "width:" . $v['type_options']['size'] . "px;";
                        }
                        if ($style != "") {
                            $attributes['style'] = $style;
                        }
                        break;
                    case 'textarea':
                        $style = "";
                        if (!empty($v['type_options']['width'])) {
                            $style .= "width:" . $v['type_options']['width'] . "px;";
                        }
                        if (!empty($v['type_options']['height'])) {
                            $style .= "height:" . $v['type_options']['height'] . "px;";
                        }
                        if ($style != "") {
                            $attributes['style'] = $style;
                        }
                        break;
                    case 'image':
                        $attributes['thumbnail'] = "mini";
                        $attributes['width'] = "";
                        $attributes['height'] = "";
                        if (!empty($v['type_options']['thumbnail'])) {
                        	$attributes['thumbnail'] = $v['type_options']['thumbnail'];
                        }
                        if (!empty($v['type_options']['img_width'])) {
                        	$attributes['width'] = $v['type_options']['img_width'];
                        }
                        if (!empty($v['type_options']['img_height'])) {
                        	$attributes['height'] = $v['type_options']['img_height'];
                        }
                        break;
                }
                if (!empty($attributes)) {
                    $element[] = $attributes;
                }
                $tmpField = $field;
                if (!empty($v['label'])) {
                    $tmpField = $formElements[$table . '.' . $field]['alias'] = $v['label'];
                    $elements[$table . '.' . $field]['alias'] = $v['label'];
                } else {
                    $formElements[$table . '.' . $field]['alias'] = $v['field'];
                    $elements[$table . '.' . $field]['alias'] = $v['field'];
                }

                $elements[$table . '.' . $field]['element'] = $element;
                $formElements[$table . '.' . $field]['element'] = $element;

                if (!empty($pcrud[$field]['validation'])) {
                    switch ($pcrud[$field]['validation']) {
                        case 'notEmpty':
                            $validate[$table . '.' . $field] = array('rule' => $pcrud[$field]['validation'], 'message' => sprintf($this->lang->line('E_VAL_REQUIRED_VALUE'), $tmpField));
                            break;
                        default :
                            switch ($pcrud[$field]['validation']) {
                                case 'alpha':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_ALPHA_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'alphaSpace':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_ALPHA_S_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'numeric':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_NUM_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'alphaNumeric':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_ALNUM_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'alphaNumericSpace':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_ALNUM_S_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'date':
                                	$tmpMessage = sprintf($this->lang->line('E_VAL_DATE_CHECK_FAILED'), $tmpField);
                                	break;
                                case 'datetime':
                                	$tmpMessage = sprintf($this->lang->line('E_VAL_DATE_TIME_CHECK_FAILED'), $tmpField);
                                	break;
                                case 'email':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_EMAIL_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'ip':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_IP_CHECK_FAILED'), $tmpField);
                                    break;
                                case 'url':
                                    $tmpMessage = sprintf($this->lang->line('E_VAL_URL_CHECK_FAILED'), $tmpField);
                                    break;
                            }
                            $validate[$table . '.' . $field][] = array('rule' => 'notEmpty', 'message' => $tmpMessage);
                            $validate[$table . '.' . $field][] = array('rule' => $pcrud[$field]['validation'], 'message' => $tmpMessage);
                            break;
                    }
                }
            }
            if (isset($config['column']['actived']) && count($config['column']['actived']) > 0) {
                foreach ($config['column']['actived'] as $field) {
                    if (isset($config['column']['atrr'][$field])) {
                        $attr = $config['column']['atrr'][$field];
                    } else {
                        $attr = array();
                    }

                    $tmpField = (strpos($field, '.') === false) ? $table . '.' . $field : $field;

                    if (!empty($attr['alias'])) {
                        $dataList[$tmpField]['alias'] = $attr['alias'];
                    } else {
                        $dataList[$tmpField]['alias'] = $field;
                    }

                    if (!empty($attr['width'])) {
                        $dataList[$tmpField]['width'] = $attr['width'];
                    }

                    if (!empty($attr['align'])) {
                        $dataList[$tmpField]['align'] = $attr['align'];
                    }

                    if (!empty($attr['format'])) {
                        $dataList[$tmpField]['format'] = $attr['format'];
                    }
                }
            }
            if (isset($config['filter']['actived']) && count($config['filter']['actived']) > 0) {
                foreach ($config['filter']['actived'] as $field) {
                    $ary = array();
                    if (isset($config['filter']['atrr'][$field]) &&
                            isset($config['filter']['atrr'][$field]['alias'])) {
                        $ary['alias'] = $config['filter']['atrr'][$field]['alias'];
                    }

                    $ary['field'] = $table . '.' . $field;
                    $searchForm[] = $ary;
                }
            }

            if (!empty($searchForm)) {
                $conf['search_form'] = $searchForm;
            }

            if (!empty($validate)) {
                $conf['validate'] = $validate;
            }

            $width = 50;
            if (!empty($formElements)) {
                $format = '<a onclick="__view(\'{ppri}\'); return false;" class="btn btn-mini" >' . $this->lang->line('__BTN_VIEW__') . '</a> ';
                $format .= '<a onclick="__edit(\'{ppri}\'); return false;" class="btn btn-mini btn-info">' . $this->lang->line('__BTN_EDIT__') . '</a> ';
                $width += 80;
            }
            $format .= '<a onclick="__delete(\'{ppri}\'); return false;" class="btn btn-mini btn-danger">' . $this->lang->line('__BTN_DELETE__') . '</a> ';

            $dataList['action'] = array('alias' => $this->lang->line('__LBL_ACTIONS__'), 'format' => $format, 'width' => $width, 'align' => 'center');

            if (!empty($dataList)) {
                $conf['data_list'] = $dataList;
            }


            if (!empty($formElements)) {
                $conf['form_elements'] = $formElements;
            }

            if (!empty($elements)) {
                $conf['elements'] = $elements;
            }

            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php')) {
                @unlink(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php');
            }
            $oldumask = umask(0);
            file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database . '/' . $table . '.php', "<?php exit; ?>\n" . serialize($conf));
            umask($oldumask);
        }
    }

}
