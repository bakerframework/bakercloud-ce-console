<?php $CI = & get_instance(); 
$permissions = $auth->getPermissionType();
?>
</div>
<div style="height: 52px;">
    <div data-spy="affix" data-offset-top="54" style="
         top: 24px;
         width: 100%;
         padding-top:5px;
         padding-bottom:5px;
         z-index: 100;">
        <div class="container" style="border-bottom: 1px solid #CCC; padding-bottom:5px;padding-top:5px;
        	background: #FBFBFB;
       		background-image: linear-gradient(to bottom, #FFFFFF, #FBFBFB);">
            <div style="text-align:right; width:100%;">
                <a class="btn"  onclick="crudBack();">  &nbsp; Back  &nbsp; </a>
                <?php if (in_array(2, $permissions)) {?>
                <a class="btn btn-info" onclick="__edit();" > &nbsp;  <i class="icon-edit icon-white"></i>  Edit &nbsp; </a>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>
<div class="container">
<div class='x-table well <?php echo $this->conf['color']; ?>'>
    <?php $elements = $this->form; ?>
    <form method="post" action="" id="crudForm" <?php if ($this->frmType == '2') { ?>class="form-horizontal"<?php } ?>>
        <?php
        foreach ($elements as $field => $v) {
            if (empty($v['element']))
                continue;
            ?>
            <div class="control-group">
                        <label for="crudTitle" class="control-label">
                            <b><?php echo (!empty($v['alias'])) ? $v['alias'] : $field; ?></b>
                        </label>
                        <div class="controls" style="padding-top:5px;">
                            <?php
                            $elements = (isset($v['element'])) ? $v['element'] : array();
                            switch ($elements[0]) {
                                case 'radio':
                                case 'autocomplete':
                                case 'select':
                                    $e = $elements;
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
                                    $elements[1] = $options;
                                    break;
                            }
                            switch ($elements[0]) {
                                case 'checkbox':
                                    $aryField = explode('.', $field);
                                    $data = $CI->input->post('data');
                                    $value = (isset($data[$aryField[0]][$aryField[1]]))?explode(',', $data[$aryField[0]][$aryField[1]]):array();
                                    if (!empty($value) && is_array($value) && count($value) > 0) {
                                        $tmp = array();
                                        foreach ($value as $k1 => $v1) {
                                            if (isset($elements[1][$v1])) {
                                                $tmp[] = $elements[1][$v1];
                                            }
                                        }
                                        $value = implode(', ', $tmp);
                                    } else {
                                        $value = '';
                                    }
                                    echo htmlspecialchars($value);
                                    break;
                                case 'image':
                                case 'editor':
                                    echo __value('data.' . $field, $elements);
                                    break;
                                case 'file':
                                    $value = __value('data.' . $field);
                                    if (file_exists(FCPATH . '/media/files/' . $value)) {
                                        echo '<a href="'.base_url().'index.php/admin/download?file=' . $value . '">' . $value . '</a>';
                                    } else {
                                        echo $value;
                                    }
                                    break;
                                case 'password':
                                    echo '******';
                                    break;
                                case 'textarea':
                                    echo nl2br(htmlspecialchars(__value('data.' . $field, $elements)));
                                    break;
                                default:
                                    echo nl2br(htmlspecialchars(__value('data.' . $field, $elements)));
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                <?php
            }
            ?>
    </form>
    <script>
    function __edit() {
    	<?php
    	$q = $this->queryString;
    	$q['xtype'] = 'form';
    	?>
    	                        window.location = "?<?php echo http_build_query($q, '', '&'); ?>";
    	                    }
        function crudBack(){
<?php
$q = $this->queryString;
$q['xtype'] = 'index';
if (isset($q['key']))
    unset($q['key']);
?>
        window.location = "?<?php echo http_build_query($q, '', '&'); ?>";
    }
    $(document).ready(function(){
        $('title').html('<?php echo $this->title; ?>');
    });
    </script>
</div>