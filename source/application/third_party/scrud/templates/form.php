<?php $CI = & get_instance(); 
$key = $CI->input->post('key');
?>

</div>
<div style="height: 52px;">
    <div data-spy="affix" data-offset-top="94" style="
         top: 54px;
         width: 100%;
         padding-top:5px;
         padding-bottom:5px;
         z-index: 100;">
        <div class="container" style="border-bottom: 1px solid #CCC; padding-bottom:5px;padding-top:5px;">
            <div style="text-align:right;width:100%;">
                <a class="btn"  onclick="crudCancel();">  &nbsp; Cancel  &nbsp; </a>
                <a class="btn btn-info" onclick="crudConfirm();" > &nbsp;  <i class="icon-edit icon-white"></i>  Confirm &nbsp; </a>
            </div>
        </div>
    </div>
    </div>
<div class="container">

<div class='x-table well  <?php echo $this->conf['color']; ?>'  style="background:#FBFBFB;">
    <?php
    $q = $this->queryString;
    $q['xtype'] = 'confirm';
    if (isset($q['key']))
        unset($q['key']);
    ?>
    <form method="post" action="?<?php echo http_build_query($q, '', '&'); ?>"  enctype="multipart/form-data"
          id="crudForm" style="padding: 0; margin: 0;" <?php if ($this->frmType == '2') { ?>class="form-horizontal"<?php } ?>>
              <?php
              $elements = $this->form;
              foreach ($this->primaryKey as $f) {
                  $ary = explode('.', $f);
                  if (isset($_GET['key'][$f]) || isset($key[$ary[0]][$ary[1]])) {
                      if (isset($_GET['key'][$f])) {
                          $_POST['key'][$ary[0]][$ary[1]] = $_GET['key'][$f];
                      }
                      echo __hidden('key.' . $f);
                  }
              }
              ?>
              <?php if (!empty($this->errors)) { ?>
            <div class="alert alert-error">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <?php foreach ($this->errors as $error) { ?>
                    <?php if (count($error) > 0) { ?>
                        <strong>Error!</strong>
                        <?php echo implode('<br />', $error); ?>
                        <br />
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
        if (!empty($elements)) {
            foreach ($elements as $field => $v) {
                if (empty($v['element']))
                    continue;
                ?>
                    <?php if ($this->frmType == '2') { ?><tr><th style="width:100px;" ><?php } ?>
                    <div class="control-group <?php if (array_key_exists($field, $this->errors)) { ?> error <?php } ?>">
                        <label for="crudRowsPerPage" class="control-label"><b><?php echo (!empty($v['alias'])) ? $v['alias'] : $field; ?>
                                <?php if (array_key_exists($field, $this->validate)) { ?><b
                                        style="color: red;">*</b> <?php } ?> 
                            </b> </label>
                            <div class="controls">
                                <?php
                                $e = $v['element'];
                                if (!empty($e) && isset($e[0])) {
                                    switch (strtolower(trim($e[0]))) {
                                        case 'image':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            $attributes['style'] = 'display:none;';
                                            echo __image('data.' . $field, $attributes);
                                            break;
                                        case 'text':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __text('data.' . $field, $attributes);
                                            break;
                                        case 'date':
                                        	$attributes = array();
                                        	$attributes['style'] = "width:180px;";
                                        	if (isset($e[1]) && !empty($e[1])) {
                                        		$attributes = $e[1];
                                        	}
                                        	echo __date('data.' . $field, $attributes);
                                        	break;
                                        case 'datetime':
                                        	$attributes = array();
                                        	$attributes['style'] = "width:180px;";
                                        	if (isset($e[1]) && !empty($e[1])) {
                                        		$attributes = $e[1];
                                        	}
                                        	echo __datetime('data.' . $field, $attributes);
                                        	break;
                                        case 'textarea':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __textarea('data.' . $field, $attributes);
                                            break;
                                        case 'editor':
                                            $attributes = array();
                                            $attributes['style'] = 'width:680px; height:400px;';
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __editor('data.' . $field, $attributes);
                                            break;
                                        case 'hidden':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __hidden('data.' . $field, $attributes);
                                            break;
                                        case 'radio':
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
                                                            foreach ($rs as $v) {
                                                                $options[$v[$e[1]['option_key']]] = $v[$e[1]['option_value']];
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $options = $e[1];
                                                }
                                            }
                                            $attributes = array();
                                            if (isset($e[2]) && !empty($e[2])) {
                                                $attributes = $e[2];
                                            }
                                            echo __radio('data.' . $field, $options, $attributes);
                                            break;
                                        case 'checkbox':
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
                                                            foreach ($rs as $v) {
                                                                $options[$v[$e[1]['option_key']]] = $v[$e[1]['option_value']];
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $options = $e[1];
                                                }
                                            } else {
                                                $e[1] = array(1 => 'Yes');
                                                $options = $e[1];
                                            }
                                            $attributes = array();
                                            if (isset($e[2]) && !empty($e[2])) {
                                                $attributes = $e[2];
                                            }
                                            echo __checkbox('data.' . $field, $options, $attributes);
                                            break;
                                        case 'password':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __password('data.' . $field, $attributes);
                                            break;
                                        case 'file':
                                            $attributes = array();
                                            $attributes['style'] = 'display:none;';
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __file('data.' . $field, $attributes);
                                            break;
                                        case 'select':
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
                                                            foreach ($rs as $v) {
                                                                $options[$v[$e[1]['option_key']]] = $v[$e[1]['option_value']];
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $options = $e[1];
                                                }
                                            }
                                            $attributes = array();
                                            if (isset($e[2]) && !empty($e[2])) {
                                                $attributes = $e[2];
                                            }
                                            echo __select('data.' . $field, $options, $attributes);
                                            break;
                                            case 'autocomplete':
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
                                            					foreach ($rs as $v) {
                                            						$options[$v[$e[1]['option_key']]] = $v[$e[1]['option_value']];
                                            					}
                                            				}
                                            			}
                                            		} else {
                                            			$options = $e[1];
                                            		}
                                            	}
                                            	$attributes = array();
                                            	if (isset($e[2]) && !empty($e[2])) {
                                            		$attributes = $e[2];
                                            	}
                                            	echo __autocomplete('data.' . $field, $options, $attributes);
                                            	break;
                                        case 'button':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __button($attributes);
                                            break;
                                        case 'submit':
                                            $attributes = array();
                                            if (isset($e[1]) && !empty($e[1])) {
                                                $attributes = $e[1];
                                            }
                                            echo __submit($attributes);
                                            break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                }
            }
            ?>
    </form>
    <script>
        function crudCancel(){
<?php
$q = $this->queryString;
$q['xtype'] = 'index';
if (isset($q['key']))
    unset($q['key']);
?>
        window.location = "?<?php echo http_build_query($q, '', '&'); ?>";
    }
                        
    function crudConfirm(){
        $('#crudForm').submit();
    }
    $(document).ready(function(){
        $('title').text($('h3').text());
    });
    </script>
</div>
