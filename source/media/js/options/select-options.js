var selectOption = function(field,object){
    if (ScrudCForm.elements[field].db_options == undefined){
        ScrudCForm.elements[field].db_options = {}
    }
    var so = $('<div id="select-options" style="margin-top:5px; display:none;"></div>');
    var sd = $('<div id="select-database" style="margin-top:5px; display:none;"></div>');
    object.html('');
    object.append('<label><b>Options</b></label>');
    var too1 = $('<input name="type-of-options" value="default" type="radio">');
    if (ScrudCForm.elements[field].list_choose == 'default'){
        too1.attr({
            checked:'checked'
        });
        so.show();
        sd.hide();
    }
    too1.click(function(){
        ScrudCForm.elements[field].list_choose = 'default';
        ScrudCForm.changeFieldToForm(field);
        so.show();
        sd.hide();
        $('#form-preview').css({
            height:'auto'
        });
        $('#form-preview').height($('#form-preview').height());
    });
	
    object.append($('<label class="radio inline"></label>').append(too1).append(' Default '));
	
    var too2 = $('<input name="type-of-options" value="database"  type="radio">');
    if (ScrudCForm.elements[field].list_choose == 'database'){
        too2.attr({
            checked:'checked'
        });
        so.hide();
        sd.show();
    }
    too2.click(function(){
        ScrudCForm.elements[field].list_choose = 'database';
        ScrudCForm.changeFieldToForm(field);
        so.hide();
        sd.show();
    });
	
    object.append($('<label class="radio inline"></label>').append(too2).append(' Database '));
	
    object.append(so).append(sd);
    if (ScrudCForm.elements[field].options == undefined){
        ScrudCForm.elements[field].options = [];
    }
    if (ScrudCForm.elements[field].options.length > 0){
        so.html('');
        var opts = ScrudCForm.elements[field].options;
        for(var i in opts){
            so.append(__scrudOption(field,opts[i]));
        }
    }else{
        so.append(__scrudOption(field));
    }
	
    var tbl = $('<select style="width:176px;"></select>');
    tbl.append('<option></option>');
    for(var i in ScrudCForm.tables){
        tbl.append('<option value="'+ScrudCForm.tables[i]+'">'+ScrudCForm.tables[i]+'</option>');
    }
    sd.append($('<label>Table &nbsp; </label>').append(tbl));
	
    var osd = $('<div></div>');
    sd.append(osd);
	
    tbl.change(function(){
        if ($.trim($(this).val()) != ''){
            __scrudDbOption(osd,$(this).val(),field);
            ScrudCForm.elements[field].db_options.table = $(this).val();
        }else{
            ScrudCForm.elements[field].db_options.table = '';
            ScrudCForm.elements[field].db_options.key = '';
            ScrudCForm.elements[field].db_options.value = '';
            osd.html('');
        }
    });
    
    if (ScrudCForm.elements[field].list_choose == 'database'){
        if (ScrudCForm.elements[field].db_options != undefined){
            if (ScrudCForm.elements[field].db_options.table != undefined && ScrudCForm.elements[field].db_options.table != ''){
                tbl.val(ScrudCForm.elements[field].db_options.table);
                __scrudDbOption(osd,ScrudCForm.elements[field].db_options.table,field);
            }
        }
    }
    if (ScrudCForm.elements[field].type == 'select'){
    	var multiple = $('<input type="checkbox" />');
    	object.append($('<label class="checkbox"> multiple</label>').prepend(multiple));
	    multiple.click(function(){
	    	if ($(this).attr("checked")) {
	    		ScrudCForm.elements[field].multiple = 'multiple';
	    		$('#preview_field_'+field).find('.controls').children('select').attr('multiple','multiple');
	    	}else{
	    		ScrudCForm.elements[field].multiple = null;
	    		$('#preview_field_'+field).find('.controls').children('select').attr('multiple',null);
	    	}
	    });
		
	    if (ScrudCForm.elements[field].multiple != undefined && ScrudCForm.elements[field].multiple == 'multiple'){
	    	multiple.attr('checked','checked');
	    }
    }
	
};

var __scrudOption = function(field,val){
    val = (val == undefined)?"":val;
    var o = $('<div></div>');
    var t = $('<input type="text"  style="width:120px;" value="'+val+'" />');
    t.keyup(function(){
        ScrudCForm.elements[field].options = [];
        $('#preview_field_'+field).find('.controls').children('select').html('');
        $('#preview_field_'+field).find('.controls').children('select').append($('<option></option>'));
        $('#select-options').find('input[type="text"]').each(function(){
            if ($.trim($(this).val()) != ''){
                $('#preview_field_'+field).find('.controls').children('select').append($('<option>'+$(this).val()+'</option>'));
                ScrudCForm.elements[field].options[ScrudCForm.elements[field].options.length] = $(this).val();
            }
        });
    });
    var ab = $('<a class="btn btn-small btn-info" style="margin-bottom:2px;">Add</a>');
    ab.click(function(){
        __scrudOption(field).insertAfter($(this).parent());
    });
    var db = $('<a class="btn btn-small btn-danger" style="margin-bottom:2px;">Del</a>');
    db.click(function(){
        if ($('#select-options').children('div[class!="deleted"]').length > 1){
            $(this).parent().hide();
            $(this).parent().addClass('deleted');
            $(this).parent().find('input[type="text"]').val('');
            
            ScrudCForm.elements[field].options = [];
            $('#preview_field_'+field).find('.controls').children('select').html('');
            $('#preview_field_'+field).find('.controls').children('select').append($('<option></option>'));
            $('#select-options').find('input[type="text"]').each(function(){
                if ($.trim($(this).val()) != ''){
                    $('#preview_field_'+field).find('.controls').children('select').append($('<option>'+$(this).val()+'</option>'));
                    ScrudCForm.elements[field].options[ScrudCForm.elements[field].options.length] = $(this).val();
                }
            });
        }
    });
    o.append(t).append(' ').append(ab).append(' ').append(db);
    return o;
};

var __scrudDbOption = function(osd,table,field){
    osd.html('');
    $.get(ScrudCForm.wpage+'/admin/scrud/getfields?table='+table,{},function(json){
        var value = $('<select style="width:176px;"></select>');
        value.append('<option></option>');
        osd.append($('<label>Value  &nbsp; </label>').append(value));
        for(var i in json){
            value.append('<option value="'+json[i]+'">'+json[i]+'</option>');
        }
        if (ScrudCForm.elements[field].db_options.key != undefined && ScrudCForm.elements[field].db_options.key != ''){
            value.val(ScrudCForm.elements[field].db_options.key);
        }
        value.change(function(){
            ScrudCForm.elements[field].db_options.key = $(this).val();
            ScrudCForm.changeFieldToForm(field);
        });
		
        var option = $('<select  style="width:176px;"></select>');
        option.append('<option></option>');
        osd.append($('<label>Option </label>').append(option));
        for(var i in json){
            option.append('<option value="'+json[i]+'">'+json[i]+'</option>');
        }
        if (ScrudCForm.elements[field].db_options.value != undefined && ScrudCForm.elements[field].db_options.value != ''){
            option.val(ScrudCForm.elements[field].db_options.value);
        }
        option.change(function(){
            ScrudCForm.elements[field].db_options.value = $(this).val();
            ScrudCForm.changeFieldToForm(field);
        });
        $('#form-preview').css({
            height:'auto'
        });
        $('#form-preview').height($('#form-preview').height());
    },'json');
}
