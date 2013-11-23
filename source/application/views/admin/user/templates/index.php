<?php $CI = & get_instance(); ?>
<?php
$permissions = $auth->getPermissionType();

$clsWidth = 50;
foreach ($this->colsCustom as $k => $v) {
	if ($k == 'action') {
		$matches = array();
		preg_match_all('/<a[^>]*>([^<]*)<\/a>/iU', $this->colsCustom['action'], $matches);
		
		if (!in_array(2, $permissions)) {
			unset($matches[0][1]);
		}
		
		if (!in_array(3, $permissions)) {
			unset($matches[0][2]);
		}
		
		$clsWidth = $clsWidth* count($matches[0]);
		$this->colsCustom[$k] = implode(' ', $matches[0]);
	}
}

foreach ($this->colsWidth as $k => $v) {
	if ($k == 'action') {
		$this->colsWidth[$k] =  $clsWidth;
	}
}

?>

<?php if (!empty($this->form)) { ?>
    </div>
	<div style="height: 52px;">
    <div data-spy="affix" data-offset-top="88" style="
         top: 24px;
         width: 100%;
         padding-top:5px;
         padding-bottom:5px;
         z-index: 100;">
        <div class="container" style="border-bottom: 1px solid #CCC; padding-bottom:5px;padding-top:5px;
        	background: #FBFBFB;
       		background-image: linear-gradient(to bottom, #FFFFFF, #FBFBFB);">
            <div style="text-align:right;width:100%;">
            	<div class="btn-group">
                <a class="btn" onclick="searchModal();"><i class="icon-search"></i> Search</a>
	                <?php
			        $q = $this->queryString;
			        if (isset($q['xtype']))
			            unset($q['xtype']);
			        ?>
	                <a class="btn" href="?<?php echo http_build_query($q, '', '&'); ?>"><i class="icon-remove-sign"></i></a>
	             </div>
                <?php if (!empty($this->results)) { ?>
                    <a class="btn"  onclick="crudExport();"><i class="icon-download"></i> Export CSV</a>
                <?php } else { ?>
                    <a class="btn disabled"><i class="icon-download"></i> Export CSV</a>
                <?php } ?>
                <a class="btn"  onclick="crudExportAll();"><i class="icon-download"></i> Export CSV (All)</a>
                <?php if (in_array(1, $permissions))  { ?>
                    <a type="button" class="btn btn-info" onclick="newRecord();"> <i class="icon-plus icon-white"></i> Add</a>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>
    <div class="container">
    <?php } ?>

<div class='<?php echo $this->conf['color']; ?>'>
    <?php
    $q = $this->queryString;
    $q['src']['p'] = 1;
    $q['xtype'] = 'index';
    if (isset($q['xid']))
        unset($q['xid']);
    ?>
    <form method="post" action="?<?php echo http_build_query($q, '', '&'); ?>" id="table" class="form-horizontal">
        <?php //require dirname(__FILE__) . '/search_form.php'; ?>
        <input type="hidden" name="src[page]" id="srcPage" value="<?php echo $this->pageIndex; ?>"/>
        <input type="hidden" name="src[limit]" id="srcLimit" value="<?php echo $this->limit; ?>"/>
        <input type="hidden" name="src[order_field]" id="srcOrder_field" value="<?php echo $this->orderField; ?>"/>
        <input type="hidden" name="src[order_type]" id="srcOrder_type" value="<?php echo $this->orderType; ?>"/>
        <?php require dirname(__FILE__) . '/paginate.php'; ?>
        <div style="overflow: auto;">
            <table class="table table-bordered table-hover list table-condensed">
                <thead>
                    <tr>
                        <?php foreach ($fields as $k => $field) { ?>
                            <?php if (in_array($field, $this->fields)) { ?>
                                <th onclick="order('<?php echo ($field) ?>');" style="cursor:pointer; text-align:center; color:#333333;text-shadow: 0 1px 0 #FFFFFF;  background-color: #e6e6e6;
                                    <?php if (isset($this->colsWidth[$field])) { ?>width:<?php echo $this->colsWidth[$field]; ?>px; 
                                    <?php } else if (isset($this->colsWidth[$k])) { ?>width:<?php echo $this->colsWidth[$k]; ?>px; <?php } ?>">
                                    <?php echo htmlspecialchars((isset($this->fieldsAlias[$field])) ? $this->fieldsAlias[$field] : $field); ?>
                                    <?php if ($this->orderField == $field) { ?>
                                        <i class="arrow <?php echo $this->orderType; ?>"></i>
                                    <?php } ?>
                                </th>
                            <?php } else { ?>
                                <th style="cursor:default;  text-align:center; color:#333333;text-shadow: 0 1px 0 #FFFFFF;  background-color: #e6e6e6;
                                    <?php if (isset($this->colsWidth[$field])) { ?>width:<?php echo $this->colsWidth[$field]; ?>px; 
                                    <?php } else if (isset($this->colsWidth[$k])) { ?>width:<?php echo $this->colsWidth[$k]; ?>px; <?php } ?>">
                                    <?php echo htmlspecialchars((isset($this->fieldsAlias[$field])) ? $this->fieldsAlias[$field] : $field); ?>
                                </th>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($this->results)) {
                        $s = array();
                        foreach ($this->fields as $field) {
                            $s[] = '{' . $field . '}';
                        }
                        $s[] = '{ppri}';
                        $s[] = '{no}';
                        $offset = ($this->pageIndex - 1) * $this->limit;
                        ?>
                        <?php
                        foreach ($this->results as $result) {
                            $r = array();
                            foreach ($this->fields as $k => $field) {
                                $__value = '';
                                $__aryField = explode('.', $field);
                                if (count($__aryField) > 1) {
                                    $__tmp = $result;
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
                                if (isset($this->form[$field]) && isset($this->form[$field]['element'][0])) {
                                    switch (trim(strtolower($this->form[$field]['element'][0]))) {
                                        case 'radio':
                                        case 'autocomplete':
                                        case 'select':
                                            $e = $this->form[$field]['element'];
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
                                            $this->form[$field]['element'][1] = $options;
                                            if (isset($this->form[$field]['element'][1]) &&
                                                    !empty($this->form[$field]['element'][1]) &&
                                                    is_array($this->form[$field]['element'][1]) &&
                                                    !empty($this->form[$field]['element'][1][$__value])
                                            ) {
                                                $r[] = htmlspecialchars($this->form[$field]['element'][1][$__value]);
                                            } else {
                                                $r[] = '';
                                            }
                                            break;
                                        case 'editor':
                                            $r[] = $__value;
                                            break;
                                        case 'checkbox':
                                            $value = explode(',', $__value);
                                            if (!empty($value) && is_array($value) && count($value) > 0) {
                                                $tmp = array();
                                                foreach ($value as $k1 => $v1) {
                                                    if (isset($this->form[$field]['element'][1][$v1])) {
                                                        $tmp[] = $this->form[$field]['element'][1][$v1];
                                                    }
                                                }
                                                $value = implode(', ', $tmp);
                                            } else {
                                                $value = '';
                                            }

                                            $r[] = htmlspecialchars($value);
                                            break;

                                        case 'textarea':
                                            $r[] = nl2br(htmlspecialchars($__value));
                                            break;
                                        default:
                                            $r[] = htmlspecialchars($__value);
                                            break;
                                    }
                                } else {
                                    $r[] = htmlspecialchars($__value);
                                }
                            }
                            $offset++;
                            $ppri = "";
                            $_tmp = "";
                            foreach ($this->primaryKey as $f) {
                                $__value = '';
                                $__aryField = explode('.', $f);
                                if (count($__aryField) > 1) {
                                    $__tmp = $result;
                                    foreach ($__aryField as $key => $value) {
                                        if (is_array($__tmp[$value])) {
                                            $__tmp = $__tmp[$value];
                                        } else {
                                            $__value = $__tmp[$value];
                                        }
                                    }
                                } else if (count($__aryField) == 1) {
                                    $__value = $result[$f];
                                }
                                $ppri .= $_tmp . 'key[' . $f . ']=' . htmlspecialchars($__value);
                                $_tmp = '&';
                            }
                            $r[] = $ppri;
                            $r[] = $offset;
                            ?>
                            <tr>
                                <?php foreach ($fields as $field) { ?>
                                    <td style="<?php if (isset($this->colsAlign[$field])) { ?>text-align:<?php echo $this->colsAlign[$field]; ?>;<?php } ?>">
                                        <?php if (isset($this->colsCustom[$field])) { ?>
                                            <?php echo str_replace($s, $r, $this->colsCustom[$field]); ?>
                                        <?php } else { ?>
                                            <?php
                                            $__value = '';
                                            $__aryField = explode('.', $field);
                                            if (count($__aryField) > 1) {
                                                $__tmp = $result;
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
                                            if (isset($this->form[$field]) && isset($this->form[$field]['element'][0])) {
                                                switch (trim(strtolower($this->form[$field]['element'][0]))) {
                                                    case 'radio':
                                                    case 'autocomplete':
                                                    case 'select':
                                                        $e = $this->form[$field]['element'];
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
                                                                        $rs = $rs[$e[1]['option_table']];
                                                                        foreach ($rs as $v) {
                                                                            $options[$v[$e[1]['option_key']]] = $v[$e[1]['option_value']];
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                $options = $e[1];
                                                            }
                                                        }
                                                        $this->form[$field]['element'][1] = $options;
                                                        if (isset($e[2]) && array_key_exists('multiple', $e[2]) && !is_array($__value)){
											            		$tmp = explode(',', $__value);
											            		$__value = array();
											            		foreach ($tmp as $mv){
											            			if (!empty($mv)){
											            				$__value[] = $mv;
											            			}
											            		}
											            	}
											            	if (!is_array($__value)){
	                                                            if (isset($this->form[$field]['element'][1]) &&
	                                                                    !empty($this->form[$field]['element'][1]) &&
	                                                                    is_array($this->form[$field]['element'][1]) &&
	                                                                    !empty($this->form[$field]['element'][1][$__value])) {
	                                                                echo nl2br(htmlspecialchars($this->form[$field]['element'][1][$__value]));
	                                                            }
                                                            }else{
																$sp = '';
																foreach ($__value as $vv){
																	if (isset($this->form[$field]['element'][1]) &&
																		!empty($this->form[$field]['element'][1]) &&
																		is_array($this->form[$field]['element'][1]) &&
																		!empty($this->form[$field]['element'][1][$vv])) {
																			echo $sp.nl2br(htmlspecialchars($this->form[$field]['element'][1][$vv]));
																			$sp = ',';
																		}
																}
															}
															break;
                                                    case 'editor':
                                                    	echo $__value;
                                                        break;
                                                    case 'checkbox':
                                                        $value = explode(',', $__value);
                                                        if (!empty($value) && is_array($value) && count($value) > 0) {
                                                            $tmp = array();
                                                            foreach ($value as $k1 => $v1) {
                                                                if (isset($this->form[$field]['element'][1][$v1])) {
                                                                    $tmp[] = $this->form[$field]['element'][1][$v1];
                                                                }
                                                            }
                                                            $value = implode(', ', $tmp);
                                                        } else {
                                                            $value = '';
                                                        }
                                                        echo htmlspecialchars($value);
                                                        break;
                                                    case 'textarea':
                                                        echo nl2br(htmlspecialchars($__value));
                                                        break;
                                                    case 'file':
                                                        if (file_exists(FCPATH . '/media/files/' . $__value)) {
                                                            echo '<a href="' . base_url() . 'index.php/admin/download?file=' . $__value . '">' . $__value . '</a>';
                                                        } else {
                                                            echo $__value;
                                                        }
                                                        break;
                                                    case 'image':
                                                        if (file_exists(__IMAGE_UPLOAD_REAL_PATH__ . '/thumbnail_' . $__value)) {
                                                            echo "<img src='" . __MEDIA_PATH__ . "images/thumbnail_" . $__value . "' />";
                                                        } else if (__IMAGE_UPLOAD_REAL_PATH__ . '/mini_thumbnail_' . $__value) {
                                                            echo "<img src='" . __MEDIA_PATH__ . "images/mini_thumbnail_" . $__value . "' />";
                                                        } else {
                                                            echo '';
                                                        }
                                                        break;
                                                    default:
                                                        echo nl2br(htmlspecialchars($__value));
                                                        break;
                                                }
                                            } else {
                                                echo nl2br(htmlspecialchars($__value));
                                            }
                                            ?>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?php echo count($fields); ?>">No results found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php require dirname(__FILE__) . '/paginate.php'; ?>
    </form>
</div>
<iframe src="" id="crudIframe" height="0" width="0" style="width: 0px; height: 0px; border: 0px; padding: 0px; margin: 0px;"></iframe>
<script>
    function crudSearch(){
        document.getElementById('srcPage').value = 1;
        document.getElementById('table').submit();
    }
    function crudExport(){
        $('#crudIframe').attr({src:'<?php echo base_url(); ?>index.php/admin/scrud/exportcsv?table=<?php echo $this->table; ?>&xtype=exportcsv'});
    }
    function crudExportAll() {
        $('#crudIframe').attr({src: '<?php echo base_url(); ?>index.php/admin/scrud/exportcsv?table=<?php echo $this->table; ?>&xtype=exportcsvall'});
    }
</script>
<script>

function searchModal() {
    $.sModal({
        header:'Search box',
        animate: 'fadeDown',
        content:'<div id="scrud_model_search"><center><img src="<?php echo base_url(); ?>media/icons/loading.gif" /></center></div>',
        onShow: function(id){ 
			<?php
			$q = $this->queryString;
			$q['xtype'] = 'modalform';
			if (isset($q['key']))
			    unset($q['key']);
			?>
			$('#' + id).find('#scrud_model_search').load('?<?php echo http_build_query($q, '', '&'); ?>');
			 setTimeout(function(){
				 $('#' + id).removeClass('fadeInDown');
                },1000);
        },
        buttons: [
            {
                text: ' <i class="icon-search icon-white"></i>  Search  ',
                addClass: 'btn-primary',
                click: function(id, data) {
                	crudSearch();
                    $.sModal('close', id);
                }
            },
            {
                text: ' Cancel ',
                click: function(id, data) {
                    $.sModal('close', id);
                }
            }
        ]
    });
}

    function newRecord(){
<?php
$q = $this->queryString;
$q['xtype'] = 'form';
if (isset($q['key']))
    unset($q['key']);
?>
        window.location = "?<?php echo http_build_query($q, '', '&'); ?>";
    }

    function __view(id){
<?php
$q = $this->queryString;
$q['xtype'] = 'view';
if (isset($q['key']))
    unset($q['key']);
?>
        window.location = "?<?php echo http_build_query($q, '', '&'); ?>&"+id;
    }

    function __edit(id){
<?php
$q = $this->queryString;
$q['xtype'] = 'form';
if (isset($q['key']))
    unset($q['key']);
?>
        window.location = "?<?php echo http_build_query($q, '', '&'); ?>&"+id;
    }

    function __delete(id){
<?php
$q = $this->queryString;
$q['xtype'] = 'delconfirm';
if (isset($q['key']))
    unset($q['key']);
?>
        window.location = "?<?php echo http_build_query($q, '', '&'); ?>&"+id;
    }

    function order(field){
        var oldField = document.getElementById('srcOrder_field').value;
        var oldType = document.getElementById('srcOrder_type').value;
<?php
$q = $this->queryString;
$q['xtype'] = 'index';
if (isset($q['key']))
    unset($q['key']);
if (isset($q['src']['o']))
    unset($q['src']['o']);
if (isset($q['src']['t']))
    unset($q['src']['t']);
?>
        var url = "?<?php echo http_build_query($q, '', '&'); ?>";
        url += "&src[o]="+field;
        if (field == oldField){
            if (oldType == 'asc'){
                url += "&src[t]=desc"
            }else{
                url += "&src[t]=asc"
            }
        }else{
            url += "&src[t]=asc"
        }
        window.location = url;
    }
    $(document).ready(function(){
        $('title').html('<?php echo $this->title; ?>');
        //$('table > tbody > tr').each(function(){
        //$($(this).find('td:last > input').get(0)).hide();
        //$($(this).find('td:last > input').get(1)).hide();
        //$($(this).find('td:last > input').get(2)).hide();
        //});
    });
</script>