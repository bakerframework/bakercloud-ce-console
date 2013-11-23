<?php
$h = round($this->pageIndex / 2);
if ($h > 2) {
    $f = $this->pageIndex - 2;
    $l = $this->pageIndex + 2;
    $l = ($l > $this->totalPage) ? $this->totalPage : $l;
} else {
    $f = 1;
    $l = 5;
    $l = ($this->totalPage < 5) ? $this->totalPage : $l;
}
?>

<div class="pagination pagination-right">
    <div style="float:left; padding-top:6px;"><?php echo $CI->lang->line('__LBL_TOTAL__'); ?>: <?php echo $this->totalRecord; ?> / <?php echo $this->totalPage; ?> <?php echo $CI->lang->line('__LBL_PAGE__'); ?></div>
    <?php if (!empty($this->conf['limit_opts'])) { ?>
        <div style="float:left; padding-left:3px;">
            <select onchange="changeLimit(this);">
                <?php foreach ($this->conf['limit_opts'] as $v) { ?>
                    <?php if ($v == $this->limit) { ?>
                        <option value="<?php echo $v; ?>" selected="selected"><?php echo $v; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $v; ?>"><?php echo $v; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    <?php } ?>

    <ul>
        <?php if (!empty($this->results) && (int) $this->totalPage > 1) { ?>
            <?php if ($this->pageIndex > 1 && $this->totalRecord > 0) { ?>
                <li><a onclick="paginate(1);return false;" href="#">First</a></li>
                <li><a onclick="paginate(<?php echo $this->pageIndex - 1; ?>);return false;" href="#">&laquo;</a></li>
            <?php } else { ?>
                <li class="disabled"><a onclick="return false;" href="#">First</a></li>
                <li class="disabled"><a onclick="return false;" href="#">&laquo;</a></li>
            <?php } ?>
            <?php for ($i = $f; $i <= $l; $i++) { ?>
                <?php if ($this->pageIndex == $i) { ?>
                    <li class="active"><a href="#" onclick="return false;"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a onclick="paginate(<?php echo $i; ?>);return false;" href="#"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <?php if ($this->pageIndex != $this->totalPage && $this->totalRecord > 0) { ?>
                <li><a onclick="paginate(<?php echo $this->pageIndex + 1; ?>);return false;" href="#">&raquo;</a></li>
                <li><a onclick="paginate(<?php echo $this->totalPage; ?>);return false;" href="#">Last</a></li>
            <?php } else { ?>
                <li class="disabled"><a onclick="return false;" href="#">&raquo;</a></li>
                <li class="disabled"><a onclick="return false;" href="#">Last</a></li>
            <?php } ?>
        <?php } else { ?>
            <li class="disabled"><a onclick="return false;" href="#">1</a></li>
        <?php } ?>
    </ul>
</div>
<script>
    function paginate(page_index){
<?php
$q = $this->queryString;
$q['xtype'] = 'index';
if (isset($q['xid']))
    unset($q['xid']);
if (isset($q['src']) && isset($q['src']['p']))
    unset($q['src']['p']);
?>
        window.location = "?<?php echo http_build_query($q,'','&'); ?>&src[p]="+page_index;
    }

    function changeLimit(o){
<?php
$q = $this->queryString;
$q['xtype'] = 'index';
if (isset($q['xid']))
    unset($q['xid']);
if (isset($q['src']) && isset($q['src']['p']))
    unset($q['src']['p']);
if (isset($q['src']) && isset($q['src']['l']))
    unset($q['src']['l']);
?>
        window.location = "?<?php echo http_build_query($q,'','&'); ?>&src[p]="+1+"&src[l]="+o.value;
    }
</script>