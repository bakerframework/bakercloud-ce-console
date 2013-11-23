var checkboxOption = function(field,object){
    object.html('');
    object.append('<label><b>Options</b></label>');
	
    var so = $('<div id="checkbox-options" style="margin-top:5px;"></div>');
    object.append(so);
    if (ScrudCForm.elements[field].values == undefined){
        ScrudCForm.elements[field].values = [];
    }
    if (ScrudCForm.elements[field].options != undefined && ScrudCForm.elements[field].options.length > 0){
        so.html('');
        var opts = ScrudCForm.elements[field].options;
        var vals = ScrudCForm.elements[field].values;
        for(var i in opts){
            var _vals = (vals[i] != undefined)?vals[i]:'';
            so.append(__scrudcheckboxOption(field,opts[i],_vals));
        }
    }else{
        so.append(__scrudcheckboxOption(field));
    }
	
};

var __scrudcheckboxOption = function(field,val,value){
    val = (val == undefined)?"":val;
    value = (value == undefined)?$('input[name="c_value"]').length+1:value;
    var o = $('<div></div>');
    var k = $('<input type="text" name="c_value"  style="width:60px;" value="'+value+'" placeholder="Value"/>');
    var t = $('<input type="text" name="c_lable"  style="width:98px;" value="'+val+'"  placeholder="Label" />');
    k.keyup(function(){
        ScrudCForm.elements[field].options = [];
        $('#preview_field_'+field).find('.controls').html('');
        $('#checkbox-options').find('input[name="c_lable"]').each(function(index){
            if ($.trim($(this).val()) != '' && $.trim($($('input[name="c_value"]').get(index)).val()) != ''){
                $('#preview_field_'+field).find('.controls').append($('<label class="checkbox" style="display:inline-block; margin-right: 15px;"><input type="checkbox" value="'+$($('input[name="c_value"]').get(index)).val()+'"/>'+$(this).val()+'</label>'));
                var _length = ScrudCForm.elements[field].options.length;
                ScrudCForm.elements[field].options[_length] = $(this).val();
                ScrudCForm.elements[field].values[_length] = $($('input[name="c_value"]').get(index)).val();
            }
        });
    });
    t.keyup(function(){
        ScrudCForm.elements[field].options = [];
        $('#preview_field_'+field).find('.controls').html('');
        $('#checkbox-options').find('input[name="c_lable"]').each(function(index){
            if ($.trim($(this).val()) != '' && $.trim($($('input[name="c_value"]').get(index)).val()) != ''){
                $('#preview_field_'+field).find('.controls').append($('<label class="checkbox" style="display:inline-block; margin-right: 15px;"><input type="checkbox" value="'+$($('input[name="c_value"]').get(index)).val()+'"/>'+$(this).val()+'</label>'));
                var _length = ScrudCForm.elements[field].options.length;
                ScrudCForm.elements[field].options[_length] = $(this).val();
                ScrudCForm.elements[field].values[_length] = $($('input[name="c_value"]').get(index)).val();
            }
        });
    });
    var ab = $('<a style="margin-bottom:10px; cursor:pointer;"><i class="icon-plus"></i></a>');
    ab.click(function(){
        __scrudcheckboxOption(field).insertAfter($(this).parent());
    });
    var db = $('<a style="margin-bottom:10px; cursor:pointer;"><i class="icon-minus"></i></a>');
    db.click(function(){
        if ($('#checkbox-options').children('div[class!="deleted"]').length > 1){
            $(this).parent().hide();
            $(this).parent().addClass('deleted');
            $(this).parent().find('input[type="text"]').val('');
            
            ScrudCForm.elements[field].options = [];
            $('#preview_field_'+field).find('.controls').html('');
            $('#checkbox-options').find('input[name="c_lable"]').each(function(index){
                if ($.trim($(this).val()) != '' && $.trim($($('input[name="c_value"]').get(index)).val()) != ''){
                    $('#preview_field_'+field).find('.controls').append($('<label class="checkbox" style="display:inline-block; margin-right: 15px;"><input type="checkbox" value="'+$($('input[name="c_value"]').get(index)).val()+'"/>'+$(this).val()+'</label>'));
                    var _length = ScrudCForm.elements[field].options.length;
                    ScrudCForm.elements[field].options[_length] = $(this).val();
                    ScrudCForm.elements[field].values[_length] = $($('input[name="c_value"]').get(index)).val();
                }
            });
        }
    });
    o.append(k).append(' ').append(t).append(' ').append(ab).append(' ').append(db);
    return o;
};