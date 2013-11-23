<?php

/**
 *
 * @param $fieldName
 * @param $options
 */
function __text($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<input type="text" name="' . $name . '" id="' . $id . '" ' . $strAttr . ' />';

    return $html;
}

/**
 *
 * @param $fieldName
 * @param $options
 */
function __image($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $fname = '';
    $fid = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $fid = 'img_' . $fieldName;
        $name = $fieldName;
        $fname = 'img_' . $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
                $fname = 'img_' . $v;
                $fid = 'img_' . $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);

                $fname .= '[' . $v . ']';
                $fid .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            if ($k == 'thumbnail')
                continue;
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '
                 <input type="file" name="' . $fname . '" id="' . $fid . '" ' . $strAttr . '/>
                 <input type="hidden" name="' . $name . '" id="' . $id . '" ' . $strAttr . '/>
                     
                 <input id="f_text_' . $id . '" class="input disabled" readonly="readonly" type="text"> 
		 <input class="btn" value="Choose..."  type="button" id="f_button_' . $id . '">
			
<script>
$("#f_button_' . $id . '").click(function(){
    $("#' . $fid . '").trigger("click");
});
$("#' . $fid . '").change(function(){
    $("#f_text_' . $id . '").val($(this).val());
});
</script>
                ';
    if (!empty($attributes['value']) && is_file(FCPATH . '/media/images/' . $attributes['value'])) {
        if (file_exists(__IMAGE_UPLOAD_REAL_PATH__.'/thumbnail_'. $attributes['value'])) {
            $imgFile = __MEDIA_PATH__ . "images/thumbnail_" . $attributes['value'];
        }else{
            $imgFile = __MEDIA_PATH__ . "images/mini_thumbnail_" . $attributes['value'];
        }
        $html .= " <div style='display:inline-block;'><img src='" . $imgFile . "' />
            <input type='button' class='btn btn-mini btn-danger' value='delete' id='del_img_btn_" . $id . "' style='vertical-align: bottom;' /></div>
";
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $queryString);
        }
        $q = $queryString;
        unset($q['wp']);
        $q['xtype'] = 'delFile';
        $html .= "
<script>
    $('#del_img_btn_" . $id . "').click(function(){
        var delBtn = this;
        $.post('" . base_url() . "index.php/admin/scrud/delfile?fileType=img&table=" . $_GET['table'] . "&" . http_build_query($q, '', '&') . "',{src:{file:'" . $attributes['value'] . "',field:'" . $fieldName . "'}},function(){
            $(delBtn).parent().remove();
            $('#" . $id . "').val('');
        });
    });
</script>
";
    }

    return $html;
}

function __date($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $tmpDate = (!empty($attributes['value'])) ? $attributes['value'] : date("Y-m-d");
    $html = '<div class="input-append date" id="' . $id . '" data-date="' . $tmpDate . '" data-date-format="yyyy-mm-dd">
				<input type="text"  name="' . $name . '" ' . $strAttr . '>
				<span class="add-on"><i class="icon-calendar"></i></span>
			  </div>
<script>
$(document).ready(function(){
    $(\'#' . $id . '\').datepicker().on(\'changeDate\', function(ev){
        $(this).datepicker(\'hide\');
        $(this).blur();
    });
});
</script>';

    return $html;
}

function __datetime($fieldName, $attributes = array()) {
	$strAttr = '';
	$name = '';
	$id = '';
	$f = explode('.', trim($fieldName));
	if (count($f) == 1) {
		if (isset($_POST[$fieldName])) {
			$attributes['value'] = $_POST[$fieldName];
		}
		$id = $fieldName;
		$name = $fieldName;
	} else if (count($f) > 1) {
		$tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
		$attributes['value'] = $_POST;
		foreach ($f as $k => $v) {
			if ($k == 0) {
				$name = $v;
				$id = $v;
			} else {
				$name .= '[' . $v . ']';
				$id .= ucfirst($v);
			}
		}
		foreach ($f as $k => $v) {
			if (isset($attributes['value'][$v])) {
				$attributes['value'] = $attributes['value'][$v];
			} else {
				if (empty($tmpValue)) {
					unset($attributes['value']);
				} else {
					$attributes['value'] = $tmpValue;
				}
				break;
			}
		}
	}
	if (!empty($attributes)) {
		if (isset($attributes['value']) && $attributes['value'] == '0000-00-00 00:00:00'){
			$attributes['value'] = '';
		}
		foreach ($attributes as $k => $v) {
			$strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
		}
	}
	$tmpDate = (!empty($attributes['value'])) ? $attributes['value'] : date("Y-m-d");
	$html = '<div class="input-append date" id="' . $id . '" data-date="' . $tmpDate . '">
				<input type="text"  name="' . $name . '" ' . $strAttr . '  data-format="yyyy-MM-dd hh:mm:ss" />
				<span class="add-on"><i class="icon-calendar"></i></span>
			  </div>
<script>
$(document).ready(function(){
   	$(\'#' . $id . '\').datetimepicker();
});
</script>';

	return $html;
}

/**
 *
 * @param $fieldName
 * @param $options
 * @param $attributes
 */
function __radio($fieldName, $options = array(), $attributes = array()) {
    $CI = & get_instance();
    $html = '';
    $strAttr = '';
    $value = null;
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (isset($attributes['value'])) {
        $value = $attributes['value'];
        unset($attributes['value']);
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }

    foreach ($options as $k => $v) {
        if ($value == $k && $value != "") {
            $html .= '<label class="radio inline" style="margin-bottom:9px;">';
            $html .= '<input name="' . $name . '" id="' . $id . ucfirst($k) . '" value="' . htmlspecialchars($k) . '" type="radio" checked="checked" ' . $strAttr . '>';
            $html .= htmlspecialchars($v);
            $html .= '</label>';
        } else {
            $html .= '<label class="radio inline" style="margin-bottom:9px;">';
            $html .= '<input name="' . $name . '" id="' . $id . ucfirst($k) . '" value="' . htmlspecialchars($k) . '" type="radio" ' . $strAttr . '>';
            $html .= htmlspecialchars($v);
            $html .= '</label>';
        }
    }

    return $html;
}

/**
 *
 * @param $fieldName
 * @param $options
 */
function __checkbox($fieldName, $options = array(), $attributes = array()) {
    $CI = & get_instance();
    $html = '';
    $strAttr = '';
    $value = null;
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (isset($attributes['value'])) {
        $value = $attributes['value'];
        unset($attributes['value']);
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    if (!is_array($value)) {
        $value = explode(',', $value);
    }
    $tmp = array();
    foreach ($value as $v) {
        if (!empty($v)) {
            $tmp[] = $v;
        }
    }
    $value = $tmp;
    foreach ($options as $k => $v) {
        if (in_array($k, $value)) {
            $html .= '<label class="checkbox inline" style="margin-bottom:9px;">';
            $html .= '<input name="' . $name . '[' . $k . ']" id="' . $id . ucfirst($k) . '" value="' . htmlspecialchars($k) . '" type="checkbox" checked="checked" ' . $strAttr . '>';
            $html .= htmlspecialchars($v);
            $html .= '</label>';
        } else {
            $html .= '<label class="checkbox inline" style="margin-bottom:9px;">';
            $html .= '<input name="' . $name . '[' . $k . ']" id="' . $id . ucfirst($k) . '" value="' . htmlspecialchars($k) . '" type="checkbox" ' . $strAttr . '>';
            $html .= htmlspecialchars($v);
            $html .= '</label>';
        }
    }

    return $html;
}

/**
 *
 * @param $fieldName
 * @param $attributes
 */
function __password($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            if (strtolower(trim($k)) == 'value')
                continue;
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<input type="password" name="' . $name . '" id="' . $id . '" ' . $strAttr . ' />';

    return $html;
}

/**
 *
 * @param $fieldName
 * @param $attributes
 */
function __hidden($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            if (is_array($v)) {
                $v = ',' . implode(",", $v) . ',';
            }
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<input type="hidden" name="' . $name . '" id="' . $id . '" ' . $strAttr . ' />';

    return $html;
}

/**
 *
 * @param $fieldName
 * @param $attributes
 */
function __file($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $fname = '';
    $fid = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $fid = 'file_' . $fieldName;
        $name = $fieldName;
        $fname = 'file_' . $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
                $fname = 'file_' . $v;
                $fid = 'file_' . $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);

                $fname .= '[' . $v . ']';
                $fid .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '
                 <input type="file" name="' . $fname . '" id="' . $fid . '" ' . $strAttr . '/>
                 <input type="hidden" name="' . $name . '" id="' . $id . '" ' . $strAttr . '/>
                     
                 <input id="f_text_' . $id . '" class="input disabled" readonly="readonly" type="text"> 
		 <input class="btn" value="Choose..."  type="button" id="f_button_' . $id . '">
			
<script>
$("#f_button_' . $id . '").click(function(){
    $("#' . $fid . '").trigger("click");
});
$("#' . $fid . '").change(function(){
    $("#f_text_' . $id . '").val($(this).val());
});
</script>
                ';
    if (!empty($attributes['value']) && is_file(FCPATH . '/media/files/' . $attributes['value'])) {
        $html .= " <div style='display:inline-block;'>
            <span><a href='".base_url()."index.php/admin/download?file=" . $attributes['value'] . "'>" . $attributes['value'] . "</a></span>
            <input type='button' class='btn btn-mini btn-danger' value='delete' id='del_file_btn_" . $id . "' style='vertical-align: bottom;' /></div>
";
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $queryString);
        }
        $q = $queryString;
        unset($q['wp']);
        $q['xtype'] = 'delFile';
        $html .= "
<script>
    $('#del_file_btn_" . $id . "').click(function(){
        var delBtn = this;
        $.post('" . base_url() . "index.php/admin/scrud/delfile?table=" . $_GET['table'] . "&" . http_build_query($q, '', '&') . "',{src:{file:'" . $attributes['value'] . "',field:'" . $fieldName . "'}},function(){
            $(delBtn).parent().remove();
            $('#" . $id . "').val('');
        });
    });
</script>
";
    }

    return $html;
}

/**
 *
 * @param $attributes
 */
function __button($attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<input type="button" ' . $strAttr . ' />';

    return $html;
}

/**
 *
 * @param $attributes
 */
function __submit($attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    if (!empty($attributes)) {
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<input type="submit" ' . $strAttr . ' />';

    return $html;
}

/**
 *
 * @param $attributes
 */
function __textarea($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        $value = '';
        if (!empty($attributes['value'])) {
            $value = $attributes['value'];
        }
        unset($attributes['value']);
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<textarea name="' . $name . '" id="' . $id . '" ' . $strAttr . ' >' . htmlspecialchars($value) . '</textarea>';

    return $html;
}

function __editor($fieldName, $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    if (!empty($attributes)) {
        $value = '';
        if (!empty($attributes['value'])) {
            $value = $attributes['value'];
        }
        unset($attributes['value']);
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $html = '<textarea name="' . $name . '" id="' . $id . '" ' . $strAttr . ' >' . htmlspecialchars($value) . '</textarea>
<script>
CKEDITOR.replace("' . $id . '",{width:660,height:250});	
</script>';

    return $html;
}

/**
 *
 * @param $fieldName
 * @param $options
 * @param $attributes
 */
function __select($fieldName, $options = array(), $attributes = array()) {
    $CI = & get_instance();
    $strAttr = '';
    $name = '';
    $id = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
    } else if (count($f) > 1) {
        $tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
        $attributes['value'] = $_POST;
        foreach ($f as $k => $v) {
            if ($k == 0) {
                $name = $v;
                $id = $v;
            } else {
                $name .= '[' . $v . ']';
                $id .= ucfirst($v);
            }
        }
        foreach ($f as $k => $v) {
            if (isset($attributes['value'][$v])) {
                $attributes['value'] = $attributes['value'][$v];
            } else {
                if (empty($tmpValue)) {
                    unset($attributes['value']);
                } else {
                    $attributes['value'] = $tmpValue;
                }
                break;
            }
        }
    }
    $value = '';
    if (!empty($attributes)) {
        if (!empty($attributes['value'])) {
            $value = $attributes['value'];
        }
        unset($attributes['value']);
        foreach ($attributes as $k => $v) {
            $strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
        }
    }
    $strOpt = '<option value=""></option>';
    if (array_key_exists('multiple', $attributes) && !is_array($value)){
    	$tmp = explode(',', $value);
    	$value = array();
    	foreach ($tmp as $mv){
    		if (!empty($mv)){
    			$value[] = $mv;
    		}
    	}
    }
    if (!is_array($value)){
	    foreach ($options as $k => $v) {
	        if ($value == $k && $value != "") {
	            $strOpt .= '<option value="' . htmlspecialchars($k) . '" selected="selected">' . htmlspecialchars($v) . '</option>';
	        } else {
	            $strOpt .= '<option value="' . htmlspecialchars($k) . '">' . htmlspecialchars($v) . '</option>';
	        }
	    }
    }else{
    	foreach ($options as $k => $v) {
    		if (in_array($k, $value)) {
    			$strOpt .= '<option value="' . htmlspecialchars($k) . '" selected="selected">' . htmlspecialchars($v) . '</option>';
    		} else {
    			$strOpt .= '<option value="' . htmlspecialchars($k) . '">' . htmlspecialchars($v) . '</option>';
    		}
    	}
    }
    if (array_key_exists('multiple', $attributes)){
    	$name = $name.'[]';
    }
    
    $html = '<select  name="' . $name . '" id="' . $id . '" ' . $strAttr . ' >' . $strOpt . '</select>';

    return $html;
}

function __autocomplete($fieldName, $options = array(), $attributes = array()) {
	$CI = & get_instance();
	$strAttr = '';
	$name = '';
	$id = '';
	$f = explode('.', trim($fieldName));
	if (count($f) == 1) {
		$_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $attributes['value'] = $_tmpValue;
        }
        $id = $fieldName;
        $name = $fieldName;
	} else if (count($f) > 1) {
		$tmpValue = (isset($attributes['value'])) ? $attributes['value'] : null;
		$attributes['value'] = $_POST;
		foreach ($f as $k => $v) {
			if ($k == 0) {
				$name = $v;
				$id = $v;
			} else {
				$name .= '[' . $v . ']';
				$id .= ucfirst($v);
			}
		}
		foreach ($f as $k => $v) {
			if (isset($attributes['value'][$v])) {
				$attributes['value'] = $attributes['value'][$v];
			} else {
				if (empty($tmpValue)) {
					unset($attributes['value']);
				} else {
					$attributes['value'] = $tmpValue;
				}
				break;
			}
		}
	}
	$value = '';
	if (!empty($attributes)) {
		if (!empty($attributes['value'])) {
			$value = $attributes['value'];
		}
		unset($attributes['value']);
		foreach ($attributes as $k => $v) {
			$strAttr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '" ';
		}
	}
	$strOpt = '<option value="">&nbsp;</option>';
	foreach ($options as $k => $v) {
		if ($value == $k && $value != "") {
			$strOpt .= '<option value="' . htmlspecialchars($k) . '" selected="selected">' . htmlspecialchars($v) . '</option>';
		} else {
			$strOpt .= '<option value="' . htmlspecialchars($k) . '">' . htmlspecialchars($v) . '</option>';
		}
	}
	$html = '<select  name="' . $name . '" id="' . $id . '" ' . $strAttr . ' style="width:220px;" >' . $strOpt . '</select>
<script>
			$(document).ready(function() { $("#' . $id . '").select2(); });
</script>
			';

	return $html;
}

function __value($fieldName, $e = array()) {
    $CI = & get_instance();
    $value = '';
    $f = explode('.', trim($fieldName));
    if (count($f) == 1) {
        $_tmpValue = $CI->input->post($fieldName);
        if (!empty($_tmpValue)) {
            $value = $_tmpValue;
        }
    } else if (count($f) > 1) {
        $value = $_POST;
        foreach ($f as $k => $v) {
            if (isset($value[$v])) {
                $value = $value[$v];
            } else {
                $value = '';
                break;
            }
        }
    }
    if (!empty($e) && isset($e[0])) {
        switch (trim(strtolower($e[0]))) {
            case 'image':
                $value = "<img src='" . __MEDIA_PATH__ . 'images/' . $value . "'/>";
                break;
            case 'radio':
            case 'autocomplete':
            	if (!is_array($value)){
            		if (isset($e[1]) && !empty($value)) {
            			$value = (isset($e[1][$value])) ? $e[1][$value] : $value;
            		}
            	}else{
            		$tmp = '';
            		$sp = '';
            		foreach ($value as $sv){
            			$tmp .= $sp.((isset($e[1][$sv])) ? $e[1][$sv] : $sv);
            			$sp = ',';
            		}
            		$value = $tmp;
            	}
            	break;
            case 'select':
        		if (isset($e[2]) && array_key_exists('multiple', $e[2]) && !is_array($value)){
            		$tmp = explode(',', $value);
            		$value = array();
            		foreach ($tmp as $mv){
            			if (!empty($mv)){
            				$value[] = $mv;
            			}
            		}
            	}
            	if (!is_array($value)){
	                if (isset($e[1]) && !empty($value)) {
	                    $value = (isset($e[1][$value])) ? $e[1][$value] : $value;
	                }
            	}else{
            		$tmp = '';
            		$sp = '';
            		foreach ($value as $sv){
            			$tmp .= $sp.((isset($e[1][$sv])) ? $e[1][$sv] : $sv);
            			$sp = ',';
            		}
            		$value = $tmp;
            	}
                break;
            case 'checkbox':
                if (!empty($value) && is_array($value) && count($value) > 0) {
                    $tmp = array();
                    foreach ($value as $k1 => $v1) {
                        if (isset($e[1][$v1])) {
                            $tmp[] = $e[1][$v1];
                        }
                    }
                    $value = implode(', ', $tmp);
                } else {
                    $value = '';
                }

                break;
        }
    }

    return $value;
}

function __html($html = '') {
    return $html;
}

function arrayToCsv(array &$fields, $delimiter = ',', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ($fields as $field) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        } else {
            $output[] = $field;
        }
    }

    return implode($delimiter, $output);
}
