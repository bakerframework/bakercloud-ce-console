<?php $CI = & get_instance(); ?>
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
                <a class="btn" onclick="crudBack();" > &nbsp; Back &nbsp; </a>
                <a class="btn btn-info" onclick="crudUpdate();" > &nbsp;  <i class="icon-ok icon-white"></i> Save &nbsp; </a>
            </div>
        </div>
    </div>
    </div>
<div class="container">
<div class='x-table well <?php echo $this->conf['color']; ?>'>
    <form method="post" action="" id="crudForm" <?php if ($this->frmType == '2'){ ?>class="form-horizontal"<?php } ?>>
        <input type="hidden" name="auth_token" id="auth_token" value="<?php echo $this->getToken(); ?>"/>
        <?php
        $elements = $this->form;
        $key = $CI->input->post('key');
        foreach ($this->primaryKey as $f) {
            $ary = explode('.', $f);
            if (isset($key[$ary[0]][$ary[1]])) {
                echo __hidden('key.' . $f);
            }
        }
        ?>
            <?php if (!empty($elements)) {
                foreach ($elements as $field => $v) {
                    if (empty($v['element']))  continue;
                        ?>
                        	<div class="control-group ">
                                <label for="crudTitle"	class="control-label"><b><?php echo (!empty($v['alias'])) ? $v['alias'] : $field; ?></b></label>
                                    <div class="controls" style="padding-top:5px;">
                                        <?php
                                        $elements = (isset($v['element'])) ? $v['element'] : array();
                                        switch ($elements[0]) {
                                            case 'radio':
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
                                        echo __hidden('data.' . $field);
                                        switch ($elements[0]) {
                                            case 'image':
                                            case 'editor':
                                                echo __value('data.' . $field, $elements);
                                                break;
                                            case 'file':
                                                $value = __value('data.' . $field);
                                                if (file_exists(FCPATH.'/media/files/'.$value)){
                                                    echo '<a href="'.base_url().'index.php/admin/download.php?file='.$value.'">'.$value.'</a>';
                                                }else{
                                                    echo $value;
                                                }
                                                break;
                                            case 'textarea':
                                                echo nl2br(htmlspecialchars(__value('data.' . $field, $elements)));
                                                break;
                                            case 'password':
                                                echo '******';
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
            }
            ?>
    </form>
    <script>
        function crudBack(){
<?php
$q = $this->queryString;
$q['xtype'] = 'form';
if (isset($q['xid']))
    unset($q['xid']);
?>
        $('#crudForm').attr({action:'?<?php echo http_build_query($q,'','&'); ?>'});
        $('#crudForm').submit();
    }
    function crudUpdate(){
<?php
$q = $this->queryString;
$q['xtype'] = 'update';
if (isset($q['xid']))
    unset($q['xid']);
?>
        $('#crudForm').attr({action:'?<?php echo http_build_query($q,'','&'); ?>'});
        $('#crudForm').submit();
    }
    $(document).ready(function(){
        $('title').html($('h3').html());
    });
    </script>
</div>