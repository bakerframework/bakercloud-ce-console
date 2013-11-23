<div class="row-fluid">
    <div class="span12">
        <div style="position: relative; margin-bottom:20px;" id="form-preview">
            <fieldset>
            	<div style="height:46px;">
                <div style="position: absolute; right: 0px; top:0px; padding: 9px 5px 5px;">
                    <a class="btn" id="btn_field_to_form"><i class="icon-plus"></i></a> &nbsp;
                    <label style="display:inline-block; margin-right: 15px;" class="radio">
                        <input type="radio" name="preview_type_form" value="1"><?php echo $this->lang->line('__LBL_NORMAL__'); ?>
                    </label>
                    <label style="display:inline-block; margin-right: 15px;" class="radio">
                        <input type="radio" name="preview_type_form" value="2"><?php echo $this->lang->line('__LBL_HORIZONTAL__'); ?>
                    </label>
                 </div>
                </div>
                <form id="frm_preview">
                    <ul id="elements_preview" class="nav"></ul>
                </form>
                <div style="text-align: center;">
                    <input type="button" value="Save" class="btn btn-primary" onclick="ScrudCForm.saveElements('<?php echo $_GET['table']; ?>');" >
                </div>
            </fieldset>
            <hr />
            <footer>
                <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
            </footer>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('input[name="preview_type_form"]').click(function(){
            switch ($(this).val()){
                case '1':
                    $('#frm_preview').removeClass('form-horizontal');
                    break;
                case '2':
                    $('#frm_preview').addClass('form-horizontal');
                    break;
            }
            ScrudCForm.config.frm_type=$(this).val();
            $('#form-preview').css({height:'auto'});
            $('#form-preview').height($('#form-preview').height());
        });
        
        if (ScrudCForm.config.frm_type == undefined){
            ScrudCForm.config.frm_type = '2';
        }
        
        $('input[name="preview_type_form"]').each(function(){
            if ($(this).val() == ScrudCForm.config.frm_type){
                $(this).attr({checked:"checked"});
            }
        });
        
        
        var preview_type_form = $('input[name="preview_type_form"]:checked').val();
        switch(preview_type_form){
            case '1':
                $('#frm_preview').removeClass('form-horizontal');
                break;
            case '2':
                $('#frm_preview').addClass('form-horizontal');
                break;
        }
    });
</script>
