<?php
$csv = array();
$aryHeader = array();
$aryAlias = array();
foreach ($fields as $field) {
    if (!in_array($field, $this->fields))
        continue;
    $aryHeader[] = $field;
    if (!array_key_exists($field, $this->fieldsAlias)) {
        $aryAlias[] = $field;
    } else {
        $aryAlias[] = $this->fieldsAlias[$field];
    }
}
$csv[] = $aryAlias;
foreach ($this->results as $v) {
    $tmp = array();
    foreach ($aryHeader as $v1) {
        $__value = '';
        $__aryField = explode('.', $v1);
        if (count($__aryField) > 1) {
            $__tmp = $v;
            foreach ($__aryField as $key => $value) {
                if (is_array($__tmp[$value])) {
                    $__tmp = $__tmp[$value];
                } else {
                    $__value = $__tmp[$value];
                }
            }
        } else if (count($__aryField) == 1) {
            $__value = $result[$field];
        }
        if (!isset($this->form[$v1])) {
            $this->form[$v1]['element'][0] = '';
        }
        switch ($this->form[$v1]['element'][0]) {
            case 'select':
            case 'autocomplete':
            case 'radio':
                $e = $this->form[$v1]['element'];
                $options = array();
                $params = array();
                if (isset($e[1]) && !empty($e[1])) {
                    if (array_key_exists('option_table', $e[1])) {
                        if (array_key_exists('option_key', $e[1]) &&
                                array_key_exists('option_value', $e[1])) {
                            $_dao = new ScrudDao($e[1]['option_table'], $CI->db);
                            $params['fields'] = array($e[1]['option_key'], $e[1]['option_value']);
                            $rs = $_dao->find($params);
                            if (!empty($rs)) {
                                foreach ($rs as $v2) {
                                    $options[$v2[$e[1]['option_key']]] = $v2[$e[1]['option_value']];
                                }
                            }
                        }
                    } else {
                        $options = $e[1];
                    }
                }
                $tmp[] = (isset($options[$__value])) ? $options[$__value] : '';
                break;
            case 'checkbox':
                $value = explode(',', $__value);
                if (!empty($value) && is_array($value) && count($value) > 0) {
                    $tmp1 = array();
                    foreach ($value as $k2 => $v2) {
                        if (isset($this->form[$v1]['element'][1][$v2])) {
                            $tmp1[] = $this->form[$v1]['element'][1][$v2];
                        }
                    }
                    $value = implode(', ', $tmp1);
                } else {
                    $value = '';
                }
                $tmp[] = $value;
                break;
            default :
                $tmp[] = $__value;
                break;
        }
    }
    $csv[] = $tmp;
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=" . $_GET['table'] . "-" . date("YmdHis") . ".csv");
header("Pragma: no-cache");
header("Expires: 0");

foreach ($csv as $v) {
    echo arrayToCsv($v) . "\n";
}