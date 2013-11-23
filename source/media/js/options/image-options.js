var imageOptions = function (field,object){
    object.html('');
    object.append('<label><b>Thumbnail size</b></label>');
    object.append('<label class="radio">'+
        '<input type="radio" value="mini" name="thumbnail" checked="checked">'+
        'Mini'+
        '</label>');
    object.append('<label class="radio">'+
        '<input type="radio"  value="small" name="thumbnail">'+
        'Small'+
        '</label>');
        
        
    object.append('<label class="radio">'+
        '<input type="radio" value="medium" name="thumbnail">'+
        'Medium'+
        '</label>');
        
        
    object.append('<label class="radio">'+
        '<input type="radio"  value="large" name="thumbnail">'+
        'Large'+
        '</label>');
    
    object.append('<label><b>Size</b></label>');
    object.append('<label> &nbsp;Width: <input type="text" class="input-small" name="img_width" /> </lable>');
    object.append('<label> Height: <input type="text" class="input-small" name="img_height" /> </lable>');
    
    $('input[name="thumbnail"]').click(function(){
        if (ScrudCForm.elements[field].type_options == undefined){
            ScrudCForm.elements[field].type_options = {};
        }
        ScrudCForm.elements[field].type_options.thumbnail = $(this).val();
    });
    
    $('input[name="thumbnail"]').each(function(){
        if (ScrudCForm.elements[field].type_options.thumbnail == $(this).val()){
            $('input[name="thumbnail"]').attr({checked:false});
            $(this).attr({checked:true});
        }
    });
    
    $('input[name="img_width"]').keyup(function(){
    	ScrudCForm.elements[field].type_options.img_width = $(this).val();
    });
    
    $('input[name="img_height"]').keyup(function(){
    	ScrudCForm.elements[field].type_options.img_height = $(this).val();
    });
    
    if (ScrudCForm.elements[field].type_options.img_width != undefined){
    	$('input[name="img_width"]').val(ScrudCForm.elements[field].type_options.img_width);
    }
    
    if (ScrudCForm.elements[field].type_options.img_height != undefined){
    	$('input[name="img_height"]').val(ScrudCForm.elements[field].type_options.img_height);
    }
	
};
