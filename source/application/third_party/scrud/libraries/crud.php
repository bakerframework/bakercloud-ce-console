<?php

$class_path = dirname(dirname(__FILE__));

require_once $class_path . '/class/Validation.php';
require_once $class_path . '/class/FileUpload.php';
require_once $class_path . '/class/Image.php';

class Crud {

    private $da;
    private $conf;
    private $title = '';
    private $errors = array();
    private $dao;
    private $primaryKey = array();
    private $fields;
    private $conditions = null;
    private $join = array();
    private $fieldsDisplay = array();
    private $fieldsAlias = array();
    private $orderField = '';
    private $orderType = '';
    private $colsWidth = array();
    private $colsCustom = array();
    private $colsAlign = array();
    private $pageIndex = 1;
    private $limit = 20;
    private $search = 'one_field';
    private $form = array();
    private $elements = array();
    private $validate = array();
    private $data = array();
    private $queryString = array();
    private $table;
    private $fileUpload;
    private $image;
    private $frmType = '1';

    public function __construct($params = array('table' => null, 'conf' => array())) {
        $class_path = dirname(dirname(__FILE__));

        $CI = & get_instance();
        $this->da = & $CI->db;

        $table = $params['table'];
        $conf = $params['conf'];



        $hook = Hook::singleton();
        
        if (empty($this->da)) {
            die('DataAccess object is not null.');
        }
        if ($hook->isExisted('SCRUD_INIT')) {
            $conf = $hook->filter('SCRUD_INIT', $conf);
        }

        $conf['theme_path'] = (!empty($conf['theme_path'])) ? $conf['theme_path'] : $class_path . '/templates';
        if (file_exists($conf['theme_path'] . '/template_functions.php')) {
            require_once $conf['theme_path'] . '/template_functions.php';
        } else {
            require_once $class_path . '/templates/template_functions.php';
        }

        $this->fileUpload = new FileUpload();

        $this->image = new Image(__IMAGE_UPLOAD_REAL_PATH__);

        if (isset($conf['frm_type'])) {
            $this->frmType = $conf['frm_type'];
        }

        if (empty($conf['order_field'])) {
            $conf['order_field'] = '';
        }
        if (empty($conf['order_type'])) {
            $conf['order_type'] = '';
        }

        if (isset($conf['title'])) {
            $this->setTitle($conf['title']);
        }

        if (isset($conf['form_elements'])) {
            $this->formElements($conf['form_elements']);
        }

        if (isset($conf['elements'])) {
            $this->elements($conf['elements']);
        }

        if (isset($conf['search_form'])) {
            $this->searchForm('fields', $conf['search_form']);
        }

        if (isset($conf['data_list'])) {
            $this->dataList($conf['data_list']);
        }

        if (isset($conf['validate']) && is_array($conf['validate'])) {
            $this->validate = $conf['validate'];
        }
        if ($hook->isExisted('SCRUD_BEFORE_VALIDATE')) {
            $this->validate = $hook->filter('SCRUD_BEFORE_VALIDATE', $this->validate);
        }

        if (isset($conf['join']) && is_array($conf['join'])) {
            $this->join = $conf['join'];
        }

        $conf['limit_opts'] = (isset($conf['limit_opts']) && is_array($conf['limit_opts'])) ? $conf['limit_opts'] : array();
        //$conf['theme_path'] = (!empty($conf['theme_path'])) ? $conf['theme_path'] : dirname(__FILE__) . '/templates';
        $conf['theme'] = (!empty($conf['theme'])) ? $conf['theme'] : '';
        $conf['color'] = (!empty($conf['color'])) ? $conf['color'] : '';
        $this->table = $conf['table'] = $table;

        $this->dao = new ScrudDao($conf['table'], $this->da);
        $this->conf = $conf;

        $fields = $this->dao->listFields($this->conf['table']);
        foreach ($fields as $v) {
            $this->fields[] = $this->conf['table'] . '.' . $v['Field'];
            if ($v['Key'] == "PRI") {
                $this->primaryKey[] = $this->conf['table'] . '.' . $v['Field'];
            }
        }

        if (!empty($this->conf['join'])) {
            foreach ($this->conf['join'] as $table => $v) {
                $fields = $this->dao->listFields($table);
                foreach ($fields as $v) {
                    $this->fields[] = $table . '.' . $v['Field'];
                }
            }
        }

        $this->dao->p_fields = $this->fields;


        $this->limit = (isset($conf['limit'])) ? $conf['limit'] : 20;
        $data = $CI->input->post('data');
        $this->data = (!empty($data)) ? $data : array();
    }

    //public function join($type, $table, $conditions) {
    //    $this->join[] = array($type, $table, $conditions);
    //}

    public function conditions($conditions) {
        $this->conditions = $conditions;
    }

    private function setTitle($title) {
        $this->title = $title;
    }

    private function fields($fields = array()) {
        $this->fields = $fields;
    }

    private function colsWidth($colsWidth = array()) {
        $this->colsWidth = $colsWidth;
    }

    /**
     * @param $dataList
     */
    private function dataList($dataList = array()) {
        foreach ($dataList as $field => $v) {
            if (isset($field)) {
                $this->fieldsDisplay[] = $field;
            } else {
                continue;
            }
            if (isset($v['alias'])) {
                $this->fieldsAlias[$field] = $v['alias'];
            }
            if (isset($v['width'])) {
                $this->colsWidth[$field] = $v['width'];
            }
            if (isset($v['format'])) {
                $this->colsCustom[$field] = $v['format'];
            }
            if (isset($v['align'])) {
                $this->colsAlign[$field] = $v['align'];
            }
        }
    }

    /**
     *
     * @param $type
     * @param $elements
     */
    private function searchForm($type = 'one_field', $elements = array()) {
        switch ($type) {
            case 'one_field':
                $this->search = 'one_field';
                break;
            case 'fields':
                $this->search = $elements;
                break;
        }
    }

    /**
     *
     * @param $form
     */
    private function formElements($form = array()) {
        $this->form = $form;
    }

    private function elements($element = array()) {
        $this->elements = $element;
    }

    /**
     *
     */
    public function getDa() {
        return $this->da;
    }

    /**
     *
     */
    public function process() {
        $CI = & get_instance();
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $this->queryString);
        }
        if (isset($this->queryString['wp'])) {
            unset($this->queryString['wp']);
        }

        $action = (isset($_GET['xtype'])) ? trim($_GET['xtype']) : '';
        ob_start();
        switch ($action) {
        	case 'index':
        		$this->index();
        		break;
        	case 'modalform':
        		$this->modalform();
        		break;
        	case 'form':
        		$this->form();
        		break;
        	case 'confirm':
                $this->confirm();
                break;
            case 'update':
                $this->update();
                break;
            case 'del':
                $this->del();
                break;
            case 'delFile':
                $this->delFile();
                break;
            case 'delconfirm':
                $this->delConfirm();
                break;
            case 'exportcsv':
            	$this->exportCsv();
            	break;
            case 'exportcsvall':
            	$this->exportcsvall();
            	break;
            case 'view':
                $this->view();
                break;
            default:
                $CI->session->unset_userdata('auth_token_xtable');
                $CI->session->unset_userdata('xtable_search_conditions');
                $this->index();
                break;
        }
        $content = ob_get_contents();
        ob_get_clean();

        return $content;
    }

    /**
     *
     */
    private function index() {
        $CI = & get_instance();
        $CI->load->model('crud_auth');
        $auth = $CI->crud_auth;


        if (!empty($this->conf['join'])) {
            foreach ($this->conf['join'] as $tbl => $tmp) {
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $CI->db->database . '/' . $tbl . '.php')) {
                    $content = unserialize(str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $CI->db->database . '/' . $tbl . '.php')));
                    if (!empty($content['form_elements'])) {
                        foreach ($content['form_elements'] as $k => $v) {
                            if (strpos($k, '.') !== false) {
                                $this->form[$k] = $v;
                            }
                        }
                    }
                }
            }
        }
        
        $xConditions = $CI->session->userdata('xtable_search_conditions');
        $src = $CI->input->post('src');
        if (empty($src) && !empty($xConditions)) {
            $_POST['src'] = $xConditions;
            $src = $CI->input->post('src');
            unset($src['page']);
        }
        if (!isset($src['page'])) {
            if (isset($_GET['src']['p'])) {
                $_POST['src']['page'] = $_GET['src']['p'];
                $src = $CI->input->post('src');
            }
        }
        if (isset($_GET['src']['l'])) {
            $_POST['src']['limit'] = $_GET['src']['l'];
            $src = $CI->input->post('src');
        }
        $pageIndex = (!empty($src['page'])) ? $src['page'] : 1;
        $this->pageIndex = $pageIndex = ((int) $pageIndex > 0) ? (int) $pageIndex : 1;
        $this->limit = (isset($src['limit'])) ? $src['limit'] : $this->limit;
        $conditions = '';
        $order = '';
        $ps = array();
        $strAnd = '';
        if (is_array($this->search)) {
            foreach ($this->fields as $field) {
                $ary = explode('.', $field);
                if (!empty($src) &&
                isset($src[$ary[0]][$ary[1]]) &&
                $src[$ary[0]][$ary[1]] != '$$__src_r_all_value__$$'
                		) {
                	if (!is_array($src[$ary[0]][$ary[1]]) && trim($src[$ary[0]][$ary[1]]) != '') {
                		if (isset($this->form[$field]['element'][0]) &&
                		($this->form[$field]['element'][0] == 'autocomplete' ||
                				$this->form[$field]['element'][0] == 'select')){
                			$conditions .= $strAnd . $field . ' = ? ';
                			$ps[] =  $src[$ary[0]][$ary[1]];
                			$strAnd = 'AND ';
                		}else{
                			$conditions .= $strAnd . $field . ' like ? ';
                			$ps[] = '%' . $src[$ary[0]][$ary[1]] . '%';
                			$strAnd = 'AND ';
                		}
                	} else if (is_array($src[$ary[0]][$ary[1]])) {
                		if (count($src[$ary[0]][$ary[1]]) > 0) {
                			$strOr  = '';
                			$tempConditons = "";
                			foreach ($src[$ary[0]][$ary[1]] as $v) {
                				if (!empty($v)){
                					$tempConditons .= $strOr . $field . ' like ? ';
                					$ps[] = '%,' . $v . ',%';
                					$strOr = ' OR ';
                				}
                			}
                			if ($tempConditons != ""){
                				$conditions .= $strAnd .' ( '.$tempConditons.' ) ';
                				$strAnd = ' AND ';
                			}
                		}
                	}
                }
            }
        } else if ($this->search == 'one_field') {
            if (!empty($src) &&
                    isset($src['one_field']) &&
                    trim($src['one_field']) !== '') {
                $conditions .= "(";
                foreach ($this->fields as $field) {
                    if (!in_array($field, $this->fieldsDisplay))
                        continue;
                    if (trim($src['one_field']) !== '') {
                        $conditions .= $strAnd . $field . ' like ? ';
                        $ps[] = '%' . $src['one_field'] . '%';
                        $strAnd = 'OR ';
                    }
                }
                $conditions .= ")";
                $strAnd = 'AND ';
            }
        }

        if (isset($_GET['src']['o'])) {
            $_POST['src']['order_field'] = $_GET['src']['o'];
            $src = $CI->input->post('src');
        }
        if (isset($_GET['src']['t'])) {
            $_POST['src']['order_type'] = $_GET['src']['t'];
            $src = $CI->input->post('src');
        }
        if (!empty($src['order_field']) && !empty($src['order_type'])) {
            $order .= $src['order_field'] . ' ' . $src['order_type'];
            $this->orderField = trim($src['order_field']);
            $this->orderType = trim(strtolower($src['order_type']));
        } else if (!empty($this->conf['order_field']) && !empty($this->conf['order_type'])) {
            $order .= $this->conf['order_field'] . ' ' . $this->conf['order_type'];
            $this->orderField = trim($this->conf['order_field']);
            $this->orderType = trim(strtolower($this->conf['order_type']));
        }
        if (!empty($this->conditions)) {
            if (is_array($this->conditions)) {
                $conditions .= ' ' . $strAnd . $this->conditions[0] . ' ';
                foreach ($this->conditions[1] as $v) {
                    $ps[] = $v;
                }
                $strAnd = 'AND ';
            } else {
                $conditions .= ' ' . $strAnd . $this->conditions . ' ';
                $strAnd = 'AND ';
            }
        }

        if (!empty($src)) {
            $CI->session->set_userdata('xtable_search_conditions',$CI->input->post('src'));
        }

        $params = array();
        $params['fields'] = $this->fields;
        $params['join'] = $this->join;
        $params['found_rows'] = true;
        $params['limit'] = $this->limit;
        $params['page'] = $pageIndex;
        $params['conditions'] = array($conditions, $ps);
        $params['order'] = $order;

        $this->results = $this->dao->find($params);
        $this->dao->p_fields = array();
        $this->totalRecord = $this->dao->foundRows();
        $this->totalPage = ceil($this->totalRecord / $this->limit);
        $fields = array();

        if (!empty($this->fieldsDisplay)) {
            $fields = $this->fieldsDisplay;
        } else {
            $fields = $this->fields;
        }

        if (is_file($this->conf['theme_path'] . '/index.php')) {
            require_once $this->conf['theme_path'] . '/index.php';
        } else {
            die($this->conf['theme_path'] . '/index.php is not found.');
        }
    }
    
    public function modalform(){
    	$CI = & get_instance();
    	$xConditions = $CI->session->userdata('xtable_search_conditions');
    	$src = $CI->input->post('src');
    	if (empty($src) && !empty($xConditions)) {
            $_POST['src'] = $xConditions;
    	}
    	if (is_file($this->conf['theme_path'] . '/search_form.php')) {
    		require_once $this->conf['theme_path'] . '/search_form.php';
    		exit;
    	} else {
    		die($this->conf['theme_path'] . '/search_form.php is not found.');
    	}
    }

    /**
     * 
     */
    private function exportCsv() {
        $CI = & get_instance();
        $xConditions = $CI->session->userdata('xtable_search_conditions');
        $src = $CI->input->post('src');
        if (empty($src) && !empty($xConditions)) {
            $_POST['src'] = $xConditions;
            $src = $CI->input->post('src');
            unset($_POST['src']['page']);
        }
        $conditions = '';
        $order = '';
        $ps = array();
        $strAnd = '';
        if (is_array($this->search)) {
            foreach ($this->fields as $field) {
                $ary = explode('.', $field);
                if (!empty($src) &&
                        isset($src[$ary[0]][$ary[1]]) &&
                        !is_array($src[$ary[0]][$ary[1]]) && trim($src[$ary[0]][$ary[1]]) != '') {
                    $conditions .= $strAnd . $field . ' like ? ';
                    $ps[] = '%' . $src[$ary[0]][$ary[1]] . '%';
                    $strAnd = 'AND ';
                }
            }
        } else if ($this->search == 'one_field') {
            if (trim($src['one_field']) !== '') {
                $conditions .= "(";
                foreach ($this->fields as $field) {
                    if (!in_array($field, $this->fieldsDisplay))
                        continue;
                    if (trim($src['one_field']) !== '') {
                        $conditions .= $strAnd . $field . ' like ? ';
                        $ps[] = '%' . $src['one_field'] . '%';
                        $strAnd = 'OR ';
                    }
                }
                $conditions .= ")";
                $strAnd = 'AND ';
            }
        }

        if (isset($_GET['src']['o'])) {
            $_POST['src']['order_field'] = $_GET['src']['o'];
            $src = $CI->input->post('src');
        }
        if (isset($_GET['src']['t'])) {
            $_POST['src']['order_type'] = $_GET['src']['t'];
            $src = $CI->input->post('src');
        }
        if (!empty($src['order_field']) && !empty($src['order_type'])) {
            $order .= $src['order_field'] . ' ' . $src['order_type'];
            $this->orderField = trim($src['order_field']);
            $this->orderType = trim(strtolower($src['order_type']));
        } else if (!empty($this->conf['order_field']) && !empty($this->conf['order_type'])) {
            $order .= $this->conf['order_field'] . ' ' . $this->conf['order_type'];
            $this->orderField = trim($this->conf['order_field']);
            $this->orderType = trim(strtolower($this->conf['order_type']));
        }
        if (!empty($this->conditions)) {
            if (is_array($this->conditions)) {
                $conditions .= ' ' . $strAnd . $this->conditions[0] . ' ';
                foreach ($this->conditions[1] as $v) {
                    $ps[] = $v;
                }
                $strAnd = 'AND ';
            } else {
                $conditions .= ' ' . $strAnd . $this->conditions . ' ';
                $strAnd = 'AND ';
            }
        }

        if (!empty($src)) {
            $CI->session->set_userdata('xtable_search_conditions', $CI->input->post('src'));
        }

        $params = array();
        $params['fields'] = $this->fields;
        $params['join'] = $this->join;
        $params['conditions'] = array($conditions, $ps);
        $params['order'] = $order;

        $this->results = $this->dao->find($params);
        $fields = array();
        if (!empty($this->fieldsDisplay)) {
            $fields = $this->fieldsDisplay;
        } else {
            $fields = $this->fields;
        }

        if (is_file($this->conf['theme_path'] . '/csv.php')) {
            require_once $this->conf['theme_path'] . '/csv.php';
        } else {
            die($this->conf['theme_path'] . '/csv.php is not found.');
        }
    }

    
    public function exportcsvall(){
    	$CI = & get_instance();
    	
    	$params = array();
    	$params['fields'] = $this->fields;
    	$params['join'] = $this->join;
    	 
    	$this->results = $this->dao->find($params);
    	$fields = array();
    	$fields = $this->fields;
    	 
    	if (is_file($this->conf['theme_path'] . '/csv.php')) {
    		require_once $this->conf['theme_path'] . '/csv.php';
    	} else {
    		die($this->conf['theme_path'] . '/csv.php is not found.');
    	}
    }
    
    /**
     *
     */
    private function form() {
        $hook = Hook::singleton();
        if (isset($_GET['key'])) {
            if ($hook->isExisted('SCRUD_EDIT_FORM')) {
                $this->form = $hook->filter('SCRUD_EDIT_FORM', $this->form);
            }
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $_POST = array_merge($_POST, array('data' => $rs));
        } else {
            if ($hook->isExisted('SCRUD_ADD_FORM')) {
                $this->form = $hook->filter('SCRUD_ADD_FORM', $this->form);
            }
        }
        if (is_file($this->conf['theme_path'] . '/form.php')) {
            require_once $this->conf['theme_path'] . '/form.php';
        } else {
            die($this->conf['theme_path'] . '/form.php is not found.');
        }
    }

    /**
     *
     */
    private function confirm() {
        $CI = & get_instance();
        $hook = Hook::singleton();
        $key = $CI->input->post('key');
        if (!empty($key)) {
            if ($hook->isExisted('SCRUD_EDIT_CONFIRM')) {
                $this->form = $hook->filter('SCRUD_EDIT_CONFIRM', $this->form);
            }
        } else {
            if ($hook->isExisted('SCRUD_ADD_CONFIRM')) {
                $this->form = $hook->filter('SCRUD_ADD_CONFIRM', $this->form);
            }
        }
        foreach ($this->form as $field => $v) {
            $elements = (isset($v['element'])) ? $v['element'] : array();
            switch ($elements[0]) {
                case 'image':
                    $tmpfields = explode('.', $field);
                    $this->fileUpload->uploadDir = __IMAGE_UPLOAD_REAL_PATH__;
                    $this->fileUpload->extensions = $CI->config->item('imageExtensions');
                    $this->fileUpload->tmpFileName = $_FILES['img_data']['tmp_name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->fileName = $_FILES['img_data']['name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->httpError = $_FILES['img_data']['error'][$tmpfields[0]][$tmpfields[1]];

                    if ($this->fileUpload->upload()) {
                        $this->data[$field] = $_POST['data'][$tmpfields[0]][$tmpfields[1]] = $this->fileUpload->newFileName;
                        if (isset($elements[1]) && isset($elements[1]['thumbnail'])) {
                            switch ($elements[1]['thumbnail']) {
                                case 'mini':
                                    $this->image->miniThumbnail($this->fileUpload->newFileName);
                                    break;
                                case 'small':
                                    $this->image->smallThumbnail($this->fileUpload->newFileName);
                                    break;
                                case 'medium':
                                    $this->image->mediumThumbnail($this->fileUpload->newFileName);
                                    break;
                                case 'large':
                                    $this->image->largeThumbnail($this->fileUpload->newFileName);
                                    break;
                                default :
                                    $this->image->miniThumbnail($this->fileUpload->newFileName);
                                    break;
                            }
                        } else {
                            $this->image->miniThumbnail($this->fileUpload->newFileName);
                        }
                        
                        $width = (isset($elements[1]['width']))?$elements[1]['width']:'';
                        $height = (isset($elements[1]['height']))?$elements[1]['height']:'';
                        $fix = 'width';
                        if ($width != '' || $height != ''){
                        	$this->image->newWidth = '';
                        	$this->image->newHeight = '';
                        	$this->image->pre = '';
                        	if ($width == ''){
                        		$fix = 'height';
                        	}
                        	$this->image->resize($this->fileUpload->newFileName,$width,$height,$fix);
                        }
                    }
                    $error = $this->fileUpload->getMessage();
                    if (!empty($error)) {
                        $this->errors[$field] = $error;
                        $this->data[$field] = "no error";
                    }
                    break;
                case 'file':
                    $tmpfields = explode('.', $field);
                    $this->fileUpload->uploadDir = __FILE_UPLOAD_REAL_PATH__;
                    $this->fileUpload->extensions = $CI->config->item('fileExtensions');
                    $this->fileUpload->tmpFileName = $_FILES['file_data']['tmp_name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->fileName = $_FILES['file_data']['name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->httpError = $_FILES['file_data']['error'][$tmpfields[0]][$tmpfields[1]];

                    if ($this->fileUpload->upload()) {
                        $this->data[$field] = $_POST['data'][$tmpfields[0]][$tmpfields[1]] = $this->fileUpload->newFileName;
                    }
                    $error = $this->fileUpload->getMessage();
                    if (!empty($error)) {
                        $this->errors[$field] = $error;
                        $this->data[$field] = "no error";
                    }
                    break;
            }
        }
        if (count($_POST) > 0 && $this->validate()) {
            if (is_file($this->conf['theme_path'] . '/confirm.php')) {
                require_once $this->conf['theme_path'] . '/confirm.php';
            } else {
                die($this->conf['theme_path'] . '/confirm.php is not found.');
            }
        } else {
            $key = $CI->input->post('key');
            if (!empty($key)) {
                if ($hook->isExisted('SCRUD_EDIT_FORM')) {
                    $this->form = $hook->filter('SCRUD_EDIT_FORM', $this->form);
                }
            } else {
                if ($hook->isExisted('SCRUD_ADD_FORM')) {
                    $this->form = $hook->filter('SCRUD_ADD_FORM', $this->form);
                }
            }
            if (is_file($this->conf['theme_path'] . '/form.php')) {
                require_once $this->conf['theme_path'] . '/form.php';
            } else {
                die($this->conf['theme_path'] . '/form.php is not found.');
            }
        }
    }

    /**
     *
     */
    private function update() {
        $CI = & get_instance();
        $key = $CI->input->post('key');
        $auth_token = $CI->input->post('auth_token');
        $hook = Hook::singleton();
        
        foreach ($this->data[$this->conf['table']] as $k => $v){
        	if (is_array($v)){
        		$this->data[$this->conf['table']][$k] = ','.implode(',', $v).',';
        	}else{
        		$this->data[$this->conf['table']][$k] = $v;
        	}
        }
        
        $crudAuth = $CI->session->userdata('CRUD_AUTH');
        
        $historyDao = new ScrudDao('crud_histories', $CI->db);
        $history = array();
        $history['user_id'] = (isset($crudAuth['id']))?$crudAuth['id']:0;
        $history['user_name'] = (isset($crudAuth['user_name']))?$crudAuth['user_name']:'';
        $history['history_table_name'] = $this->conf['table'];
        $history['history_date_time'] = date("Y-m-d H:i:s");
        
        if (count($_POST) > 0 && $this->validate() && $auth_token == $CI->session->userdata('auth_token_xtable')) {
            if ($hook->isExisted('SCRUD_BEFORE_SAVE')) {
                $this->data = $hook->filter('SCRUD_BEFORE_SAVE', $this->data);
            }
            $editFlag = false;
            foreach ($this->primaryKey as $f) {
                $ary = explode('.', $f);
                if (isset($key[$ary[0]][$ary[1]])) {
                    $editFlag = true;
                } else {
                    $editFlag = false;
                    break;
                }
            }
            $q = $this->queryString;
            $q['xtype'] = 'index';
            if (isset($q['key']))
                unset($q['key']);

            if ($editFlag) {
                $params = array();
                $strCon = "";
                $aryVal = array();
                $_tmp = "";
                foreach ($this->primaryKey as $f) {
                    $ary = explode('.', $f);
                    $strCon .= $_tmp . $f . ' = ?';
                    $_tmp = " AND ";
                    $aryVal[] = $key[$ary[0]][$ary[1]];
                }
                $params = array($strCon, $aryVal);
                try {
                    if ($hook->isExisted('SCRUD_BEFORE_UPDATE')) {
                        $this->data = $hook->filter('SCRUD_BEFORE_UPDATE', $this->data);
                    }
                    $this->dao->update($this->data[$this->conf['table']], $params);

                    $tmpData = $this->data[$this->conf['table']];
                    foreach ($this->primaryKey as $f) {
                    	$ary = explode('.', $f);
                    	$tmpData[$ary[1]] = $_POST['key'][$ary[0]][$ary[1]];
                    }
                    
                    $history['history_data'] = json_encode($tmpData);
                    $history['history_action'] = 'update';
                    $historyDao->insert($history);
                    
                    if ($hook->isExisted('SCRUD_COMPLETE_SAVE')) {
                        $hook->execute('SCRUD_COMPLETE_SAVE', $this->data);
                    }
                    if ($hook->isExisted('SCRUD_COMPLETE_UPDATE')) {
                        $hook->execute('SCRUD_COMPLETE_UPDATE', $this->data);
                    }

                    header("Location: ?" . http_build_query($q, '', '&'));
                } catch (Exception $e) {
                    $this->errors['__NO_FIELD__'][] = $e->getMessage();
                    if (is_file($this->conf['theme_path'] . '/form.php')) {
                        require_once $this->conf['theme_path'] . '/form.php';
                    } else {
                        die($this->conf['theme_path'] . '/form.php is not found.');
                    }
                }
            } else {
                try {
                    if ($hook->isExisted('SCRUD_BEFORE_INSERT')) {
                        $this->data = $hook->filter('SCRUD_BEFORE_INSERT', $this->data);
                    }
                    $this->dao->insert($this->data[$this->conf['table']]);
                    
                    $history['history_data'] = json_encode($this->data[$this->conf['table']]);
                    $history['history_action'] = 'add';
                    $historyDao->insert($history);

                    if ($hook->isExisted('SCRUD_COMPLETE_SAVE')) {
                        $hook->execute('SCRUD_COMPLETE_SAVE', $this->data);
                    }
                    if ($hook->isExisted('SCRUD_COMPLETE_INSERT')) {
                        $hook->execute('SCRUD_COMPLETE_INSERT', $this->data);
                    }

                    header("Location: ?" . http_build_query($q, '', '&'));
                } catch (Exception $e) {
                    $this->errors['__NO_FIELD__'][] = $e->getMessage();
                    if (is_file($this->conf['theme_path'] . '/form.php')) {
                        require_once $this->conf['theme_path'] . '/form.php';
                    } else {
                        die($this->conf['theme_path'] . '/form.php is not found.');
                    }
                }

                $CI->session->unset_userdata('xtable_search_conditions');
            }
        } else {
            if ($auth_token != $CI->session->userdata('auth_token_xtable')) {
                $this->errors['auth_token'][] = 'Auth token does not exist.';
            }
            if (is_file($this->conf['theme_path'] . '/form.php')) {
                require_once $this->conf['theme_path'] . '/form.php';
            } else {
                die($this->conf['theme_path'] . '/form.php is not found.');
            }
        }
    }

    private function delConfirm() {
        if (isset($_GET['key'])) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $_POST = array_merge($_POST, array('data' => $rs));

            if (is_file($this->conf['theme_path'] . '/delete_confirm.php')) {
                require_once $this->conf['theme_path'] . '/delete_confirm.php';
            }
        } else {
            $q = $this->queryString;
            $q['xtype'] = 'index';
            if (isset($q['key']))
                unset($q['key']);
            if (isset($q['auth_token']))
                unset($q['auth_token']);
            header("Location: ?" . http_build_query($q, '', '&'));
        }
    }

    /**
     *
     */
    private function del() {
        $CI = & get_instance();
        $crudAuth = $CI->session->userdata('CRUD_AUTH');
        $historyDao = new ScrudDao('crud_histories', $CI->db);
        $history = array();
        $history['user_id'] = (isset($crudAuth['id']))?$crudAuth['id']:0;
        $history['user_name'] = (isset($crudAuth['user_name']))?$crudAuth['user_name']:'';
        $history['history_table_name'] = $this->conf['table'];
        $history['history_date_time'] = date("Y-m-d H:i:s");
        
        if (isset($_GET['key']) && $_GET['auth_token'] == $CI->session->userdata('auth_token_xtable')) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params = array($strCon, $aryVal);
            
            $tmpData = $this->dao->findFirst(array('conditions'=>$params));
            $this->dao->remove($params);
            
            $history['history_data'] = json_encode($tmpData[$this->conf['table']]);
            $history['history_action'] = 'delete';
            $historyDao->insert($history);
        }
        $q = $this->queryString;
        $q['xtype'] = 'index';
        if (isset($q['key']))
            unset($q['key']);
        if (isset($q['auth_token']))
            unset($q['auth_token']);
        header("Location: ?" . http_build_query($q, '', '&'));
    }

    private function delFile() {
        $CI = & get_instance();
        $src = $CI->input->post('src');
        if (isset($_GET['fileType']) && $_GET['fileType'] == 'img') {
            $this->fileUpload->uploadDir = __IMAGE_UPLOAD_REAL_PATH__;
        } else {
            $this->fileUpload->uploadDir = __FILE_UPLOAD_REAL_PATH__;
        }

        $_POST['src']['field'] = str_replace('data.', '', $src['field']);
        $src = $CI->input->post('src');
        if (isset($src['field']) &&
                is_file($this->fileUpload->uploadDir . $src['file'])) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $ary = explode('.', $src['field']);
            if (!empty($rs)) {
                if (trim($rs[$ary[0]][$ary[1]]) == trim($src['file'])) {
                    $data = array();
                    $data[$ary[1]] = '';
                    $this->dao->update($data, $params['conditions']);
                    $this->fileUpload->delFile(trim($src['file']));
                    $this->fileUpload->delFile('thumbnail_' . trim($src['file']));
                }
            }
        }
    }

    /**
     *
     * Enter description here ...
     */
    private function view() {
    	$CI = & get_instance();
        $CI->load->model('crud_auth');
        $auth = $CI->crud_auth;
    	
        if (isset($_GET['key'])) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $_POST = array_merge($_POST, array('data' => $rs));

            if (is_file($this->conf['theme_path'] . '/view.php')) {
                require_once $this->conf['theme_path'] . '/view.php';
            }
        } else {
            $q = $this->queryString;
            $q['xtype'] = 'index';
            if (isset($q['key']))
                unset($q['key']);
            if (isset($q['auth_token']))
                unset($q['auth_token']);
            header("Location: ?" . http_build_query($q, '', '&'));
        }
    }

    private function validate() {
        $hook = Hook::singleton();
        foreach ($this->validate as $k => $v) {
            if (isset($v['rule'])) {
                $this->_validate($k, $v);
            } else {
                foreach ($v as $k1 => $v1) {
                    $this->_validate($k, $v1);
                }
            }
        }
        if ($hook->isExisted('SCRUD_VALIDATE')) {
            $this->errors = $hook->filter('SCRUD_VALIDATE', $this->errors);
        }

        return (count($this->errors) > 0) ? false : true;
    }

    private function _validate($k, $v) {
        $ary = explode('.', $k);
        $validation = Validation::singleton();
        if ($v['rule'] == 'notEmpty') {
            $v['required'] = true;
        }
        if (isset($v['required']) && $v['required'] === true) {
            if (@!$validation->notEmpty($this->data[$ary[0]][$ary[1]])) {
                $this->errors[$k][] = $v['message'];
            } else {
                if (!is_array($v['rule'])) {
                    if (trim($v['rule']) != '') {
                        if (!$validation->{$v['rule']}($this->data[$ary[0]][$ary[1]])) {
                            $this->errors[$k][] = $v['message'];
                        }
                    }
                } else {
                    if (trim($v['rule'][0]) != '') {
                        $params = array($this->data[$ary[0]][$ary[1]]);
                        foreach ($v['rule'] as $value) {
                            if ($value == $v['rule'][0])
                                continue;
                            $params[] = $value;
                        }
                        if (!call_user_func_array(array($validation, $v['rule'][0]), $params)) {
                            $this->errors[$k][] = $v['message'];
                        }
                    }
                }
            }
        } else if (!empty($this->data[$ary[0]][$ary[1]])) {
            if (!is_array($v['rule'])) {
                if (trim($v['rule']) != '') {
                    if (!$validation->{$v['rule']}($this->data[$ary[0]][$ary[1]])) {
                        $this->errors[$k][] = $v['message'];
                    }
                }
            } else {
                if (trim($v['rule'][0]) != '') {
                    $params = array($this->data[$ary[0]][$ary[1]]);
                    foreach ($v['rule'] as $value) {
                        if ($value == $v['rule'][0])
                            continue;
                        $params[] = $value;
                    }
                    if (!call_user_func_array(array($validation, $v['rule'][0]), $params)) {
                        $this->errors[$k][] = $v['message'];
                    }
                }
            }
        }
    }

    /**
     *
     * Enter description here ...
     */
    private function getToken() {
        $CI = & get_instance();
        $auth = $CI->session->userdata('auth_token_xtable');
        if (empty($auth)) {
            $string = 'HTTP_USER_AGENT=' . $_SERVER['HTTP_USER_AGENT'];
            $string .= 'time=' . time();
            $auth = md5($string);
            $CI->session->set_userdata('auth_token_xtable', $auth);
        } else {
            $auth = $CI->session->userdata('auth_token_xtable');
        }

        return $auth;
    }

}