var textareaOption = function(field,object){
    object.html('');
    object.append('<label><b>Size</b></label>');
    object.append($('<label class="inline">Width: <input type="text" style="width:30px; margin-bottom:0; line-height:16px; height:16px; font-size:13px;" class="disabled" readonly="readonly" id="slider_width_val"/> px </label>'));
    object.append($('<div id="slider_width" style="width:95%;"></div>'));
    var value = $('#preview_field_'+field).find('.controls').children('textarea').width();
    $( "#slider_width" ).slider({
        range: "min",
        value: value,
        min: 50,
        max: 680,
        slide: function( event, ui ) {
            ScrudCForm.elements[field].type_options.width = ui.value;
            $('#preview_field_'+field).find('.controls').children('textarea').width(ui.value);
            $( "#slider_width_val" ).val(ui.value );
        }
    });
    $( "#slider_width_val" ).val($("#slider_width").slider( "value" ));
	
    object.append($('<label class="inline" style="margin-top:5px;">Height: <input type="text" style="width:30px; margin-bottom:0; line-height:16px; height:16px; font-size:13px;"  class="disabled" readonly="readonly"  id="slider_height_val"/> px </label>'));
    object.append($('<div id="slider_height" style="width:95%;"></div>'));
    var value = $('#preview_field_'+field).find('.controls').children('textarea').height();
    $( "#slider_height" ).slider({
        range: "min",
        value: value,
        min: 50,
        max: 630,
        slide: function( event, ui ) {
            ScrudCForm.elements[field].type_options.height = ui.value;
            $('#preview_field_'+field).find('.controls').children('textarea').height(ui.value);
            $( "#slider_height_val" ).val(ui.value );
            $('#form-preview').css({height:'auto'});
            $('#form-preview').height($('#form-preview').height());
        }
    });
    $( "#slider_height_val" ).val($("#slider_height").slider( "value" ));
};