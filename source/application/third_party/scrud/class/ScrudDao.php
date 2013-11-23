<?php

class ScrudDao {

    protected $tableName = null;
    protected $fileds = "*";
    protected $db;
    public $p_fields;

    /**
     *
     * Enter description here ...
     * @param unknown_type $tableName
     */
    public function __construct($tableName, $db) {
        $this->tableName = $tableName;
        $this->db = $db;
    }

    /**
     * 
     * Enter description here ...
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;
        $this->fields = "*";
    }

    /**
     * 
     * @param type $sql
     * @param type $params
     * @param type $rs
     * @return boolean
     */
    public function & query($sql, $params = array(), $f = false) {
        $rows = array();
        $query = $this->db->query($sql, $params);

        if (empty($query)) {
            return false;
        } else {
            if ($f == true) {
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
            } else {
                if (!empty($this->p_fields)) {
                    $fech_row = '';
                    switch ($this->db->dbdriver) {
                        case 'mysql':
                        case 'mysqli':
                            while ($row = $this->db->call_function('fetch_row', $query->result_id)) {
                                $assoc = Array();
                                foreach ($this->p_fields as $k => $field) {
                                    $aryField = explode('.', $field);
                                    $assoc[$aryField[0]][$aryField[1]] = $row[$k];
                                }

                                $rows[] = $assoc;
                            }
                            break;
                        case 'pdo':
                            while ($row = $query->result_id->fetch(PDO::FETCH_NUM)) {
                                $assoc = Array();
                                foreach ($this->p_fields as $k => $field) {
                                    $aryField = explode('.', $field);
                                    $assoc[$aryField[0]][$aryField[1]] = $row[$k];
                                }

                                $rows[] = $assoc;
                            }
                            break;
                        default:
                            foreach ($query->result_array() as $row) {
                                $rows[] = $row;
                            }
                            break;
                    }
                } else {
                    foreach ($query->result_array() as $row) {
                        $rows[] = $row;
                    }
                }
            }
        }


        return $rows;
    }

    public function listFields($tableName) {
        $sql = "SHOW COLUMNS FROM `" . $tableName . "`";
        $rs = $this->query($sql, array(), true);

        return $rs;
    }

    /**
     *
     * Enter description here ...
     * @param $params
     */
    public function find($params = array()) {
        $this->params = $params;
        if (!empty($params['fields'])) {
            $this->fileds = (is_array($params['fields'])) ? implode(',', $params['fields']) : $params['fields'];
        }

        $values = array();
        $sqlFoundRows = '';
        $join = '';
        $conditions = '';
        $group = '';
        $having = '';
        $order = '';
        $limit = '';

        if (!empty($params['found_rows']) && $params['found_rows'] === true) {
            $sqlFoundRows .= 'SQL_CALC_FOUND_ROWS';
        }

        if (!empty($params['join'])) {
            foreach ($params['join'] as $v) {
                $join .= ' ' . strtoupper($v[0]) . ' JOIN ' . $v[1] . ' ON ' . $v[2] . ' ';
            }
        }

        if (!empty($params['conditions'])) {
            if (is_array($params['conditions'])) {
                if (!empty($params['conditions'][0])) {
                    $conditions .= $params['conditions'][0];
                }
                if (!empty($params['conditions'][1]) && is_array($params['conditions'][1])) {
                    foreach ($params['conditions'][1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $conditions .= $params['conditions'];
            }
        }

        if (!empty($params['having'])) {
            if (is_array($params['having'])) {
                if (!empty($params['having'][0])) {
                    $having .= $params['having'][0];
                }
                if (!empty($params['having'][1]) && is_array($params['having'][1])) {
                    foreach ($params['having'][1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $having .= $params['having'];
            }
        }

        if (!empty($params['order'])) {
            $order .= (is_array($params['order'])) ? implode(',', $params['order']) : $params['order'];
        }

        if (!empty($params['group'])) {
            $group .= (is_array($params['group'])) ? implode(',', $params['group']) : $params['group'];
        }
        if (!empty($params['limit']) && (int) $params > 0) {
            $page = (isset($params['page'])) ? (int) $params['page'] : 0;
            if ($page <= 0) {
                $limit .= $params['limit'];
            } else {
                $offset = ($page - 1) * (int) $params['limit'];
                $limit .= $offset . ',' . $params['limit'];
            }
        }

        $conditions = ($conditions != '') ? ' WHERE ' . $conditions : $conditions;
        $group = ($group != '') ? ' GROUP BY ' . $group : $group;
        $having = ($having != '') ? ' HAVING  ' . $having : $having;
        $order = ($order != '') ? ' ORDER BY ' . $order : $order;
        $limit = ($limit != '') ? ' LIMIT ' . $limit : $limit;
        $sql = "SELECT " . $sqlFoundRows . " " . $this->fileds . " FROM `" . $this->tableName . "` " . $join . $conditions . " " . $group . " " . $having . " " . $order . " " . $limit;

        return $this->query($sql, $values);
    }

    /**
     *
     * Enter description here ...
     */
    public function findFirst($params) {
        $params['limit'] = 1;
        if (isset($params['page'])) {
            unset($params['page']);
        }
        $data = $this->find($params);
        if ($data === false) {
            return false;
        } else {
            return (isset($data[0])) ? $data[0] : array();
        }
    }

    public function insert($params) {

        $fields = array();
        $data = array();
        $aryFields = $this->listFields($this->tableName);
        foreach ($aryFields as $field) {
            $fields[] = $field['Field'];
        }
        $f = array();
        $o = array();
        $v = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = $field;
                $o[] = "?";
                $v[] = $params[$field];
            }
        }

        $sql = "INSERT INTO `" . $this->tableName . "` (`" . implode("`,`", $f) . "`) VALUES (" . implode(",", $o) . ")";

        $rs = $this->db->query($sql, $v);
        if (!empty($rs)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function update($params, $conditions) {
        $fields = array();
        $aryFields = $this->listFields($this->tableName);
        foreach ($aryFields as $field) {
            $fields[] = $field['Field'];
        }
        $f = array();
        $values = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = '`' . $field . '` = ?';
                $values[] = $params[$field];
            }
        }
        $c = '';
        if (!empty($conditions)) {
            if (is_array($conditions)) {
                if (!empty($conditions[0])) {
                    $c .= $conditions[0];
                }
                if (!empty($conditions[1]) && is_array($conditions[1])) {
                    foreach ($conditions[1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $c .= $conditions;
            }
        }
        $c = ($c != '') ? ' WHERE ' . $c : $c;
        
        $sql = "UPDATE `" . $this->tableName . "` SET " . implode(",", $f) . $c;
        $rs = $this->db->query($sql, $values);
        if (!empty($rs)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $data
     */
    public function save($params) {
        $fields = array();
        $aryFields = $this->listFields($this->tableName);
        foreach ($aryFields as $field) {
            $fields[] = $field['Field'];
        }
        $f = array();
        $o = array();
        $v = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = $field;
                $o[] = "?";
                $v[] = $params[$field];
            }
        }

        $sql = "INSERT INTO `" . $this->tableName . "` (`" . implode("`,`", $f) . "`) VALUES (" . implode(",", $o) . ") ON DUPLICATE KEY UPDATE ";

        $f = array();
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $f[] = $field . ' = ?';
                $v[] = $params[$field];
            }
        }

        $sql .= implode(',', $f);
        $rs = $this->db->query($sql, $v);
        if (!empty($rs)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $params
     */
    public function remove($conditions) {
        $c = '';
        $values = array();
        if (!empty($conditions)) {
            if (is_array($conditions)) {
                if (!empty($conditions[0])) {
                    $c .= $conditions[0];
                }
                if (!empty($conditions[1]) && is_array($conditions[1])) {
                    foreach ($conditions[1] as $v) {
                        $values[] = $v;
                    }
                }
            } else {
                $c .= $conditions;
            }
        }
        $c = ($c != '') ? ' WHERE ' . $c : $c;
        $sql = "DELETE FROM `" . $this->tableName . "` " . $c;
        $rs = $this->db->query($sql, $values);
        if (!empty($rs)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Enter description here ...
     */
    public function truncate() {
        return $this->db->query("TRUNCATE TABLE `" . $this->tableName . "`");
    }

    /**
     *
     * Enter description here ...
     */
    public function foundRows() {
        $sql = "SELECT FOUND_ROWS() as totalRow";
        $rs = $this->query($sql);
        if ($rs === false) {
            return false;
        } else {
            return (int) $rs[0]['totalRow'];
        }
    }

}