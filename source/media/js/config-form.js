var ScrudCForm = {
    elements : {},
    config:{
        frm_type:'1',
        table:{},
        filter:{
            list:undefined,
            actived:[],
            atrr:{}
        },
        column:{
            list:undefined,
            actived:[],
            attr:{}
        },
        join:[]
    },
    wpage:'',
    fields:[],
    table:'',
    tables:[],
    current_field:'',
    
    init:function(table){
        if (table != undefined){
            ScrudCForm.table = table;	    	
        }
        $('#fieldTab a[href="#options"]').hide();
    },
    
    buildFields:function(){
        //        $('#form-fields').append('<li class="nav-header">'+ScrudCForm.table+' table</li>');
        for(var k in ScrudCForm.fields){
            $('#form-fields').append(ScrudCForm.createField(ScrudCForm.fields[k]));
        }
    },
    
    addJoin:function (object){
        if (object == undefined){
            object = {};
        }
        
        var joinType = $('<select id="joinType" style="width:auto;"></select>');
        joinType.append('<option value="INNER">INNER</option>');
        joinType.append('<option value="LEFT">LEFT</option>');
        joinType.append('<option value="RIGHT">RIGHT</option>');
    	
        if (object.type != undefined){
            joinType.val(object.type);
        }
        
        var joinTable = $('<select id="joinTable"></select>');
        //joinTable.append('<option value=""></option>');
        for(var i in ScrudCForm.tables){
            if (ScrudCForm.tables[i] == ScrudCForm.table) continue; 
            joinTable.append('<option value="'+ScrudCForm.tables[i]+'">'+ScrudCForm.tables[i]+'</option>');
        }
    	
        if (object.table != undefined){
            joinTable.val(object.table);
        }
    	
        var currentField = $('<select id="currentField"></select>');
        //currentField.append('<option value=""></option>');
        for(var i in ScrudCForm.fields){
            currentField.append('<option value="'+ScrudCForm.table +'.'+ScrudCForm.fields[i]+'">'+ScrudCForm.table +'.'+ScrudCForm.fields[i]+'</option>');
        }
        if (object.currentField != undefined){
            currentField.val(object.currentField);
        }
    	
        var targetField = $('<select id="targetField"></select>');
    	
        var deleteButton = $('<input type="button" value="Delete" class="btn btn-danger" />');
        deleteButton.click(function(){
            $(this).parent().remove();
                
            var joinFields = [];
        
            $('#dataListJoin > div > #targetField > option').each(function(){
                joinFields[joinFields.length] = $(this).val();
            });

            var removeFields = [];
            var tmpFields = [];
                
            for(var i in ScrudCForm.fields){
                tmpFields[tmpFields.length] = ScrudCForm.fields[i];
            }
                
            for(var i in joinFields){
                tmpFields[tmpFields.length] = joinFields[i];
            }

            for(var i in ScrudCForm.config.column.list){
                if ($.inArray(ScrudCForm.config.column.list[i], tmpFields) == -1){
                    removeFields[removeFields.length] = ScrudCForm.config.column.list[i];
                }
            }
            for(var i in removeFields){
                ScrudCForm.config.column.list.splice($.inArray(removeFields[i], ScrudCForm.config.column.list),1);
            }
            ScrudCForm.buildColumn();
                
        });
    	
        joinTable.change(function(){
            var targetTable = $(this).val();
            $.get(ScrudCForm.wpage+'/admin/scrud/getfields?table='+targetTable,{},function(json){
                if (json != null){
                    targetField.children().remove();
                    for(var i in json){
                        targetField.append('<option value="'+targetTable+'.'+json[i]+'">'+targetTable+'.'+json[i]+'</option>');
                    }
                    targetField.val(object.targetField);
                }
                var joinFields = [];
        
                $('#dataListJoin > div > #targetField > option').each(function(){
                    joinFields[joinFields.length] = $(this).val();
                });

                var removeFields = [];
                var tmpFields = [];

                for(var i in ScrudCForm.fields){
                    tmpFields[tmpFields.length] = ScrudCForm.fields[i];
                }

                for(var i in joinFields){
                    tmpFields[tmpFields.length] = joinFields[i];
                }

                for(var i in ScrudCForm.config.column.list){
                    if ($.inArray(ScrudCForm.config.column.list[i], tmpFields) == -1){
                        removeFields[removeFields.length] = ScrudCForm.config.column.list[i];
                    }
                }
                for(var i in removeFields){
                    ScrudCForm.config.column.list.splice($.inArray(removeFields[i], ScrudCForm.config.column.list),1);
                }
                ScrudCForm.buildColumn();
            },'json');
        });
    	
        $('#dataListJoin').append(
            $('<div></div>').css({
                'margin-bottom':'5px'
            })
            .append(joinType)
            .append(' JOIN ')
            .append(joinTable)
            .append(' ON ')
            .append(currentField)
            .append(' = ')
            .append(targetField)
            .append(' ')
            .append(deleteButton)
            );
        joinTable.trigger('change');
    },
    
    buildFilter:function(){
        if (ScrudCForm.config.filter == undefined){
            ScrudCForm.config.filter = {};
            ScrudCForm.config.filter.list = ScrudCForm.fields;
            ScrudCForm.config.filter.actived = [];
        }
        if (ScrudCForm.config.filter.list == undefined){
            ScrudCForm.config.filter.list = ScrudCForm.fields;
        }
        
        for(var i in ScrudCForm.fields){
            if ($.inArray(ScrudCForm.fields[i],ScrudCForm.config.filter.list) == -1){
                ScrudCForm.config.filter.list[ScrudCForm.config.filter.list.length] = ScrudCForm.fields[i];
            }
        }
        
        for(var i in ScrudCForm.config.filter.list){
            var f = $('<input type="checkbox">');
            f.val(ScrudCForm.config.filter.list[i]);
            if (jQuery.inArray(ScrudCForm.config.filter.list[i], ScrudCForm.config.filter.actived) >= 0){
                f.attr({
                    checked:'checked'
                });
            }
            var ebtn = $('<a class="btn btn-mini" style="float: right; cursor:pointer;"></a>')
            .attr({
                name:ScrudCForm.config.filter.list[i]
            })
            .append($('<i class="icon-pencil"></i>'));
    		
            ebtn.click(function(){
                var field = $(this).attr('name');
                if (ScrudCForm.config.filter.atrr == undefined){
                    ScrudCForm.config.filter.atrr = {}
                }
                if (ScrudCForm.config.filter.atrr[field] == undefined){
                    ScrudCForm.config.filter.atrr[field] = {}
                }
                $('#filter_container').removeClass('span5');
                $('#filter_container_right').remove();
                $('#filter_container').addClass('span5');
                var ccr = $('<div></div>').attr({
                    id:'filter_container_right'
                }).addClass('span7');
    			
                ccr.append(
                    $('<div style="margin-bottom:5px;"></div>')
                    .append(' Name &nbsp;&nbsp;&nbsp;&nbsp; ')
                    .append(field)
                    );
    			
                var alias = $('<input type="text" />');
    			
                if (ScrudCForm.config.filter.atrr[field].alias != undefined){
                    alias.val(ScrudCForm.config.filter.atrr[field].alias);
                }else{
                    if (ScrudCForm.elements[field] != undefined){
                        alias.val(ScrudCForm.elements[field].label);
                        ScrudCForm.config.filter.atrr[field].alias = ScrudCForm.elements[field].label;
                    }else{
                        alias.val(field);
                        ScrudCForm.config.filter.atrr[field].alias = field;
                    }
                }
                alias.keyup(function(){
                    ScrudCForm.config.filter.atrr[field].alias = $(this).val();
                });
    			
                ccr.append(
                    $('<div style="margin-bottom:5px;"></div>')
                    .append(' Alias &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ')
                    .append(alias)
                    );
                $('#filter_container').after(ccr);
            });
    		
            $('#filter_elements').append(
                $('<li></li>')
                .append(
                    $('<a></a>')
                    .append(
                        $('<label class="checkbox"></label>')
                        .append(f)
                        .append(ScrudCForm.config.filter.list[i])
                        )
                    .append(ebtn)
                    )
                );
        }
    },
    
    buildColumn:function(){
        $('#column_elements').children().remove();
        if (ScrudCForm.config.column == undefined){
            ScrudCForm.config.column = {};
            ScrudCForm.config.column.list = ScrudCForm.fields;
            ScrudCForm.config.column.actived = [];
        }
        if (ScrudCForm.config.column.list == undefined){
            ScrudCForm.config.column.list = ScrudCForm.fields;
        }
        
        for(var i in ScrudCForm.fields){
            if ($.inArray(ScrudCForm.fields[i],ScrudCForm.config.column.list) == -1){
                ScrudCForm.config.column.list[ScrudCForm.config.column.list.length] = ScrudCForm.fields[i];
            }
        }
        
        
        $('#dataListJoin > div > #targetField > option').each(function(){
            if ($.inArray($(this).val(), ScrudCForm.config.column.list) == -1){
                ScrudCForm.config.column.list[ScrudCForm.config.column.list.length] = $(this).val();
            }
        });
        
        for(var i in ScrudCForm.config.column.list){
            var f = $('<input type="checkbox">');
            f.val(ScrudCForm.config.column.list[i]);
            if (jQuery.inArray(ScrudCForm.config.column.list[i], ScrudCForm.config.column.actived) >= 0){
                f.attr({
                    checked:'checked'
                });
            }
            var li = $('<li></li>');
            var ebtn = $('<a class="btn btn-mini" style="float: right; cursor:pointer;"></a>')
            .attr({
                name:ScrudCForm.config.column.list[i]
            })
            .append($('<i class="icon-pencil"></i>'));
    		 
            ebtn.click(function(){
                var field = $(this).attr('name');
                if (ScrudCForm.config.column.atrr == undefined){
                    ScrudCForm.config.column.atrr = {}
                }
                if (ScrudCForm.config.column.atrr[field] == undefined){
                    ScrudCForm.config.column.atrr[field] = {}
                }
                $('#column_container').removeClass('span5');
                $('#column_container_right').remove();
                $('#column_container').addClass('span5');
                var ccr = $('<div></div>').attr({
                    id:'column_container_right'
                }).addClass('span7');
    			
                ccr.append(
                    $('<div style="margin-bottom:5px;"></div>')
                    .append(' Name &nbsp;&nbsp;&nbsp;&nbsp; ')
                    .append(field)
                    );
    			
                var alias = $('<input type="text" />');
    			
                if (ScrudCForm.config.column.atrr[field].alias != undefined){
                    alias.val(ScrudCForm.config.column.atrr[field].alias);
                }else{
                    if (ScrudCForm.elements[field] != undefined){
                        alias.val(ScrudCForm.elements[field].label);
                        ScrudCForm.config.column.atrr[field].alias = ScrudCForm.elements[field].label;
                    }else{
                        alias.val(field);
                        ScrudCForm.config.column.atrr[field].alias = field;
                    }
                }
                alias.keyup(function(){
                    ScrudCForm.config.column.atrr[field].alias = $(this).val();
                });
    			
                ccr.append(
                    $('<div style="margin-bottom:5px;"></div>')
                    .append(' Alias &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ')
                    .append(alias)
                    );
    			
                var format = $('<textarea ></textarea>');
    			
                if (ScrudCForm.config.column.atrr[field].format != undefined){
                    format.val(ScrudCForm.config.column.atrr[field].format);
                }
                format.keyup(function(){
                    ScrudCForm.config.column.atrr[field].format = $(this).val();
                });
    			
                ccr.append(
                    $('<div style="margin-bottom:5px;"></div>')
                    .append(' Format &nbsp;&nbsp; ')
                    .append(format)
                    );
    			
                var width = $('<input style="width: 40px;" type="text" />');
    			
                if (ScrudCForm.config.column.atrr[field].width != undefined){
                    width.val(ScrudCForm.config.column.atrr[field].width);
                }
                width.keyup(function(){
                    ScrudCForm.config.column.atrr[field].width = $(this).val();
                });
    			
                ccr.append(
                    $('<div style="margin-bottom:5px;"></div>')
                    .append(' Width &nbsp;&nbsp;&nbsp;&nbsp; ')
                    .append(width)
                    .append(' px')
                    );
    			
                var la = $('<select name="crud[list_align]" id="crudListAlign" style="width: auto;"></select>');
    			
                la.change(function(){
                    ScrudCForm.config.column.atrr[field].align = $(this).val();
                });
    			
                la.append('<option value=""></option>');
                la.append('<option value="left">Left</option>');
                la.append('<option value="center">Center</option>');
                la.append('<option value="right">Right</option>');
                ccr.append(' Align &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ');
                ccr.append(la);
    			
                if (ScrudCForm.config.column.atrr[field].align != undefined){
                    la.val(ScrudCForm.config.column.atrr[field].align);
                }
    			

                $('#column_container').after(ccr);
            });
            $('#column_elements').append(
                li
                .append(
                    $('<a></a>')
                    .append(
                        $('<label class="checkbox"></label>')
                        .append(f)
                        .append(ScrudCForm.config.column.list[i])
                        )
                    .append(ebtn)
                    )
                );
        }
    },
    
    buildConfigTable:function(){
        if (ScrudCForm.config.table == undefined){
            ScrudCForm.config.table = {};
        }
        if (ScrudCForm.config.table.crudTitle != undefined){
            $('#crudTitle').val(ScrudCForm.config.table.crudTitle);
        }else{
            $('#crudTitle').val(ScrudCForm.table+' manager');
            ScrudCForm.config.table.crudTitle = $('#crudTitle').val();
        }
        if (ScrudCForm.config.table.crudRowsPerPage != undefined){
            $('#crudRowsPerPage').val(ScrudCForm.config.table.crudRowsPerPage);
        }else{
            $('#crudRowsPerPage').val(20);
            ScrudCForm.config.table.crudRowsPerPage = $('#crudRowsPerPage').val();
        }
        if (ScrudCForm.config.table.crudOrderField != undefined){
            $('#crudOrderField').val(ScrudCForm.config.table.crudOrderField);
        }
        if (ScrudCForm.config.table.crudOrderType != undefined){
            $('#crudOrderType').val(ScrudCForm.config.table.crudOrderType);
        }
        if (ScrudCForm.config.table.noColumn != undefined && 
            ScrudCForm.config.table.noColumn == 1){
            //$('#crudNoColumn').val(ScrudCForm.config.table.noColumn);
            ScrudCForm.config.table.noColumn = 1;
            $('#crudNoColumn').attr({
                checked:'checked'
            });
        }
    	
        $('#crudTitle').keyup(function(){
            ScrudCForm.config.table.crudTitle = $(this).val();
        });
        
        $('#crudRowsPerPage').keyup(function(){
            ScrudCForm.config.table.crudRowsPerPage = $(this).val();
        });
        $('#crudOrderField').change(function(){
            ScrudCForm.config.table.crudOrderField = $(this).val();
        });
        $('#crudOrderType').change(function(){
            ScrudCForm.config.table.crudOrderType = $(this).val();
        });
        $('#crudNoColumn').click(function(){
            if ($('#crudNoColumn').attr('checked') == 'checked'){
                ScrudCForm.config.table.noColumn = 1;
            }else{
                ScrudCForm.config.table.noColumn = 0;
            }
        })
    },
        
    createField:function(field){
        var f = $('<li id="field_'+field+'" style="cursor:default;" ><a><i class="icon-plus"></i> &nbsp; '+field+'</a></li>');
        if ($('#preview_field_'+field).length >0){
            f.addClass('disabled');
        }
        f.click(function(){
            if (!$(this).hasClass('disabled')){
                ScrudCForm.addFieldToForm(field,'prepend');
            }
        });
        return f;
    },
    buildOptions:function(field){
        $('#preview_field_'+field).find('#form-options').append('<label><b>Label</b></label>');
        var lbl = $('<input type="text" placeholder="Type something…" value="'+ScrudCForm.elements[field].label+'" />');
        lbl.keyup(function(){
            ScrudCForm.elements[field].label = $(this).val();
            $('#preview_field_'+field).find('.control-label').children('b').html($(this).val());
            if (ScrudCForm.config.column.atrr == undefined){
                ScrudCForm.config.column.atrr = {};
            }
            if (ScrudCForm.config.column.atrr[field] == undefined){
                ScrudCForm.config.column.atrr[field] = {}
            }
            ScrudCForm.config.column.atrr[field].alias = $(this).val();
            
            if (ScrudCForm.config.filter.atrr == undefined){
                ScrudCForm.config.filter.atrr = {};
            }
            if (ScrudCForm.config.filter.atrr[field] == undefined){
                ScrudCForm.config.filter.atrr[field] = {}
            }
            ScrudCForm.config.filter.atrr[field].alias = $(this).val();
        });
        $('#preview_field_'+field).find('#form-options').append(lbl);
		
        var type = 
        $('<select></select>')
        .append($('<option value="text">Textbox</option>'))
        .append($('<option value="password">Password</option>'))
        .append($('<option value="date">Date</option>'))
        .append($('<option value="datetime">DateTime</option>'))
        .append($('<option value="textarea">Textarea</option>'))
        .append($('<option value="editor">Editor</option>'))
        .append($('<option value="file">File</option>'))
        .append($('<option value="image">Image</option>'))
        .append($('<option value="checkbox">Checkbox</option>'))
        .append($('<option value="radio">Radio</option>'))
        .append($('<option value="select">Selectbox</option>'))
        .append($('<option value="autocomplete">Autocomplete</option>'));
        type.val(ScrudCForm.elements[field].type);
		
        type.change(function(){
            ScrudCForm.elements[field].type = $(this).val();
            ScrudCForm.changeFieldToForm(field);
            $('#type-options').html('');
            switch (ScrudCForm.elements[field].type){
                case 'text':
                    textOptions(field,typeOptions);
                    break;
                case 'password':
                    passwordOptions(field,typeOptions);
                    break;
                case 'textarea':
                    textareaOption(field,typeOptions);
                    break;
                case 'select':
                    selectOption(field,typeOptions);
                    break;
                case 'autocomplete':
                    selectOption(field,typeOptions);
                    break;
                case 'radio':
                    radioOption(field,typeOptions);
                    break;
                case 'checkbox':
                    checkboxOption(field,typeOptions);
                    break;
                case 'image':
                    imageOptions(field,typeOptions);
                    break;
            }
            $('#form-preview').css({
                height:'auto'
            });
            $('#form-preview').height($('#form-preview').height());
        });
		
        $('#preview_field_'+field).find('#form-options').append('<label><b>Type</b></label>').append(type);
        var typeOptions = $('<div id="type-options"></div>').css({
            marginBottom:'10px'
        });
		
        $('#preview_field_'+field).find('#form-options').append(typeOptions);
		
        switch (ScrudCForm.elements[field].type){
            case 'text':
                textOptions(field,typeOptions);
                break;
            case 'password':
                passwordOptions(field,typeOptions);
                break;
            case 'textarea':
                textareaOption(field,typeOptions);
                break;
            case 'select':
                selectOption(field,typeOptions);
                break;
            case 'autocomplete':
                selectOption(field,typeOptions);
                break;
            case 'radio':
                radioOption(field,typeOptions);
                break;
            case 'checkbox':
                checkboxOption(field,typeOptions);
                break;
            case 'image':
                imageOptions(field,typeOptions);
                break;
        }
		
        var validation = 
        $('<select></select>')
        .append($('<option value=""></option>'))
        .append($('<option value="notEmpty" selected="selected">Required</option>'))
        .append($('<option value="alpha">Alpha Characters</option>'))
        .append($('<option value="alphaSpace">Alpha Characters with Space</option>'))
        .append($('<option value="numeric">Numeric Characters</option>'))
        .append($('<option value="alphaNumeric">Alpha-Numeric Characters</option>'))
        .append($('<option value="alphaNumericSpace">Alpha-Numeric Characters with Space</option>'))
        .append($('<option value="date">Date (Format: yyyy-mm-dd)</option>'))
        .append($('<option value="datetime">Date time(Format: yyyy-mm-dd H:i:s)</option>'))
        .append($('<option value="email">Email</option>'))
        .append($('<option value="ip">IP</option>'))
        .append($('<option value="url">URL</option>'));
		
        validation.val(ScrudCForm.elements[field].validation);
        if (ScrudCForm.elements[field].validation != ''){
            $('#preview_error_field_'+field).show();
        }else{
            $('#preview_error_field_'+field).hide();
        }
        validation.change(function(){
            ScrudCForm.elements[field].validation = $(this).val();
            if (ScrudCForm.elements[field].validation != ''){
                $('#preview_error_field_'+field).show();
            }else{
                $('#preview_error_field_'+field).hide();
            }
        });
		
        $('#preview_field_'+field).find('#form-options').append('<label><b>Validations</b></label>').append(validation);
    },
    addFieldToForm:function(field,pType){
        if (pType == undefined){
            pType = 'append';
        }
        if (ScrudCForm.elements[field] == undefined){
            ScrudCForm.elements[field] = {
                field:field,
                label:field,
                type:'text',
                options:[],
                type_options:{
                    size:210,
                    width:300,
                    height:100,
                    thumbnail:'mini'
                },
                validation:''
            }
        }
        var ir = $('<a class="btn" id="btn_close" />').append($('<i class="icon-remove"></i>'));
        var iedit = $('<a class="btn" id="btn_edit" />').append($('<i class="icon-edit"></i>'));
        iedit.clickover({
            placement: 'left',
            title:'Design form',
            html:true,
            width:250,
            content:'<div id="form-options"></div>&nbsp;',
            onShown:function(){
                ScrudCForm.buildOptions(field);
            }
        }); 
        
        ir.click(function(){
            $('#preview_field_'+field).remove();
            $('#field_'+field).removeClass('disabled');
            $('#form-preview').css({
                height:'auto'
            });
            $('#form-preview').height($('#form-preview').height());
            if (ScrudCForm.current_field == field){
                $('#fieldTab a[href="#fields"]').tab('show');
                //$('#form-options').html('');
                $('#fieldTab a[href="#options"]').hide();
            }
        });
        var lbl = $('<label class="control-label">'+
            '<b>'+ScrudCForm.elements[field].label+' </b> <span id="preview_error_field_'+field+'" style="color:red;display:none;">*</span></label>');
		
        // fixme
        var ctl;
        var select2 = [];
        var field_editor = [];
        switch (ScrudCForm.elements[field].type){
            case 'text':
                ctl = $('<input type="text" />').width(ScrudCForm.elements[field].type_options.size); // for
                // text
                break;
            case 'password':
                ctl = $('<input type="password" />').width(ScrudCForm.elements[field].type_options.size);
                break;
            case 'date':
                var strDate = '<div data-date-format="yyyy-mm-dd" data-date="2012-11-27" class="input-append date">' +
                '<input type="text" value="" style="width:180px;">'+
                '<span class="add-on"><i class="icon-calendar"></i></span>'+
                '</div>';
                ctl = $(strDate);
                break;
            case 'datetime':
                var strDate = '<div data-date-format="yyyy-mm-dd" data-date="2012-11-27" class="input-append date">' +
                '<input type="text" value="" style="width:180px;">'+
                '<span class="add-on"><i class="icon-calendar"></i></span>'+
                '</div>';
                ctl = $(strDate);
                break;  
            case 'textarea':
                ctl = $('<textarea></textarea>');
                if (ScrudCForm.elements[field].type_options.width != undefined){
                    ctl.width(ScrudCForm.elements[field].type_options.width);
                }
                if (ScrudCForm.elements[field].type_options.height != undefined){
                    ctl.height(ScrudCForm.elements[field].type_options.height);
                }
                break;
            case 'editor':
                ctl = $('<textarea style="width:660px; height:200px;" id="preview_field_'+field+'_editor"></textarea>');
                field_editor[field_editor.length] = 'preview_field_'+field+'_editor';
                break;
            case 'image':
                ctl = $('<input id="filename" type="text" class="input disabled" name="filename" readonly="readonly"> '+
                    '<input type="button" class="btn" value="Choose..."/>');
                break;
            case 'file':
                ctl = $('<input id="filename" type="text" class="input disabled" name="filename" readonly="readonly"> '+
                    '<input type="button" class="btn" value="Choose..."/>');
                break;
            case 'checkbox':
                var tmp = '';
                if (ScrudCForm.elements[field].options != undefined && 
                    ScrudCForm.elements[field].options.length > 0){
                    var opts = ScrudCForm.elements[field].options;
                    for(var i in opts){
                        if ($.trim(opts[i]) != ''){
                            tmp += '<label class="checkbox" style="display:inline-block; margin-right: 15px;"><input type="checkbox"/>'+opts[i]+'</label>';
                        }
                    }
                }
                ctl = $(tmp);
                break;
            case 'radio':
                var tmp = '';
                if (ScrudCForm.elements[field].options != undefined &&
                    ScrudCForm.elements[field].options.length > 0){
                    var opts = ScrudCForm.elements[field].options;
                    for(var i in opts){
                        if ($.trim(opts[i]) != ''){
                            tmp += '<label class="radio" style="display:inline-block; margin-right: 15px;"><input type="radio" name="optionsRadios"/>'+opts[i]+'</label>';
                        }
                    }
                }
                ctl = $(tmp);
                break;
            case 'select':
            	var tmpMultiple = '';
            	if (ScrudCForm.elements[field].multiple != undefined && ScrudCForm.elements[field].multiple == 'multiple'){
            		tmpMultiple = 'multiple="multiple"';
        	    }
                if (ScrudCForm.elements[field].list_choose == 'default'){
                    ctl = $('<select style="width:auto;" '+tmpMultiple+' ></select>').append($('<option></option>'));
                    if (ScrudCForm.elements[field].options.length > 0){
                        var opts = ScrudCForm.elements[field].options;
                        for(var i in opts){
                            if ($.trim(opts[i]) != ''){
                                ctl.append($('<option>'+opts[i]+'</option>'));
                            }
                        }
                    }
                }else if (ScrudCForm.elements[field].list_choose == 'database'){
                    $.post(ScrudCForm.wpage+'/admin/scrud/getoptions',{
                        config:ScrudCForm.elements[field].db_options
                    },function(json){
                        ctl = $('<select '+tmpMultiple+'></select>').append($('<option></option>'));
                        for(var i in json){
                            ctl.append($('<option>'+json[i]+'</option>'));
                        }
                        $('#preview_field_'+field).find('.controls').append(ctl);
                    },'json');
                }
                break;
            case 'autocomplete':
                if (ScrudCForm.elements[field].list_choose == 'default'){
                    ctl = $('<select id="preview_field_'+field+'_select2"  style="width:220px;" ></select>').append($('<option>&nbsp;</option>'));
                    if (ScrudCForm.elements[field].options.length > 0){
                        var opts = ScrudCForm.elements[field].options;
                        for(var i in opts){
                            if ($.trim(opts[i]) != ''){
                                ctl.append($('<option>'+opts[i]+'</option>'));
                            }
                        }
                    }
                }else if (ScrudCForm.elements[field].list_choose == 'database'){
                    $.post(ScrudCForm.wpage+'/admin/scrud/getoptions',{
                        config:ScrudCForm.elements[field].db_options
                    },function(json){
                        ctl = $('<select id="preview_field_'+field+'_select2" style="width:220px;" ></select>').append($('<option>&nbsp;</option>'));
                        for(var i in json){
                            ctl.append($('<option>'+json[i]+'</option>'));
                        }
                        $('#preview_field_'+field).find('.controls').append(ctl);
                        $("#preview_field_"+field+"_select2").select2();
                    },'json');
                }
                
                select2[select2.length] = "preview_field_"+field+"_select2";
                break;
        }
		
		
        var el = $('<div class="controls"></div>').append(ctl);
        var li = $('<li id="preview_field_'+field+'"></li>');
		
        li.hover(function(){
            $(this).children('#btn_close').show();
            $(this).children('#btn_edit').show();
        },function(){
            if (!$(this).hasClass('selected')){
                $(this).children('#btn_close').hide();
                $(this).children('#btn_edit').hide();
            }
        });
        
        iedit.click(function(){
            $('a[id=btn_close]').each(function(){
                $(this).hide();
            });
            $('a[id=btn_edit]').each(function(){
                $(this).hide();
            });
            ScrudCForm.current_field = field;
            $('#elements_preview > li').removeClass('selected');
            $('#preview_field_'+field).addClass('selected');
            $('#preview_field_'+field).children('#btn_close').show();
            $('#preview_field_'+field).children('#btn_edit').show();
        });
        
        li.click(function(){
            if (dragFlag == false){
                $('a[id=btn_close]').each(function(){
                    $(this).hide();
                });
                $('a[id=btn_edit]').each(function(){
                    $(this).hide();
                });
                ScrudCForm.current_field = field;
                $('#elements_preview > li').removeClass('selected');
                $('#preview_field_'+field).addClass('selected');
                
                //$('#form-options').html('');
                //ScrudCForm.buildOptions(field);
                
                $('#fieldTab a[href="#options"]').show();
                $('#fieldTab a[href="#options"]').tab('show');
                $(this).children('#btn_close').show();
                $(this).children('#btn_edit').show();
                
            }else{
                dragFlag = false;
            }
        });
		
        li.append(ir).append(iedit).append($('<div class="control-group"></div>').append(lbl).append(el));
        $('#form-preview').css({
            height:'auto'
        });
        switch (pType){
            case 'prepend':
                $('#elements_preview').prepend(li);
                break;
            default:
                $('#elements_preview').append(li);
                break;
        }
		
        for(var k in field_editor){
        	CKEDITOR.replace(field_editor[k],{width:660,height:200});
        }
		
        if (ScrudCForm.elements[field].validation != ''){
            $('#preview_error_field_'+field).show();
        }else{
            $('#preview_error_field_'+field).hide();
        }
        $('#form-preview').height($('#form-preview').height());
        $('#field_'+field).addClass('disabled');
    },
    changeFieldToForm:function(field){
        $('#preview_field_'+field).find('.controls').html('');
        switch (ScrudCForm.elements[field].type){
            case 'text':
                $('#preview_field_'+field).find('.controls').append($('<input type="text" />').width(ScrudCForm.elements[field].type_options.size));
                break;
            case 'password':
                $('#preview_field_'+field).find('.controls').append($('<input type="password" />').width(ScrudCForm.elements[field].type_options.size));
                break;
            case 'date':
                var strDate = '<div data-date-format="yyyy-mm-dd" data-date="2012-11-27" class="input-append date">' +
                '<input type="text" value="" style="width:180px;">'+
                '<span class="add-on"><i class="icon-calendar"></i></span>'+
                '</div>';
                $('#preview_field_'+field).find('.controls').append($(strDate));
                break;
            case 'datetime':
                var strDate = '<div data-date-format="yyyy-mm-dd" data-date="2012-11-27" class="input-append date">' +
                '<input type="text" value="" style="width:180px;">'+
                '<span class="add-on"><i class="icon-calendar"></i></span>'+
                '</div>';
                $('#preview_field_'+field).find('.controls').append($(strDate));
                break;
            case 'textarea':
                var ctl = $('<textarea></textarea>');
                if (ScrudCForm.elements[field].type_options.width != undefined){
                    ctl.width(ScrudCForm.elements[field].type_options.width);
                }
                if (ScrudCForm.elements[field].type_options.height != undefined){
                    ctl.height(ScrudCForm.elements[field].type_options.height);
                }
                $('#preview_field_'+field).find('.controls').append(ctl);
                break;
            case 'editor':
                $('#preview_field_'+field).find('.controls').append($('<textarea style="width:660px; height:200px;" id="preview_field_'+field+'_editor"></textarea>'));
                CKEDITOR.replace('preview_field_'+field+'_editor',{width:660,height:200});
                break;
            case 'image':
                var file = $('<input id="filename" type="text" class="input disabled" name="filename" readonly="readonly"> '+
                    '<input type="button" class="btn" value="Choose..."/>');
                $('#preview_field_'+field).find('.controls').append(file);
                break;
            case 'file':
                var file = $('<input id="filename" type="text" class="input disabled" name="filename" readonly="readonly"> '+
                    '<input type="button" class="btn" value="Choose..."/>');
                $('#preview_field_'+field).find('.controls').append(file);
                break;
            case 'checkbox':
                var tmp = '';
                if (ScrudCForm.elements[field].options != undefined && ScrudCForm.elements[field].options.length > 0){
                    var opts = ScrudCForm.elements[field].options;
                    for(var i in opts){
                        if ($.trim(opts[i]) != ''){
                            tmp += '<label class="checkbox" style="display:inline-block; margin-right: 15px;"><input type="checkbox"/>'+opts[i]+'</label>';
                        }
                    }
                }
                $('#preview_field_'+field).find('.controls').append($(tmp));
                break;
            case 'radio':
                var tmp = '';
                if (ScrudCForm.elements[field].options != undefined && ScrudCForm.elements[field].options.length > 0){
                    var opts = ScrudCForm.elements[field].options;
                    for(var i in opts){
                        if ($.trim(opts[i]) != ''){
                            tmp += '<label class="radio" style="display:inline-block; margin-right: 15px;"><input type="radio" name="optionsRadios"/>'+opts[i]+'</label>';
                        }
                    }
                }
                $('#preview_field_'+field).find('.controls').append($(tmp));
                break;
            case 'select':
                if (ScrudCForm.elements[field].list_choose == 'default'){
                    var ctl = $('<select style="width:auto;" ></select>').append($('<option></option>'));
                    if (ScrudCForm.elements[field].options.length > 0){
                        var opts = ScrudCForm.elements[field].options;
                        for(var i in opts){
                            if ($.trim(opts[i]) != ''){
                                ctl.append($('<option>'+opts[i]+'</option>'));
                            }
                        }
                    }
                    $('#preview_field_'+field).find('.controls').append(ctl);
                }else if (ScrudCForm.elements[field].list_choose == 'database'){
                    $.post(ScrudCForm.wpage+'/admin/scrud/getoptions',{
                        config:ScrudCForm.elements[field].db_options
                    },function(json){
                        var ctl = $('<select></select>').append($('<option></option>'));
                        for(var i in json){
                            ctl.append($('<option>'+json[i]+'</option>'));
                        }
                        $('#preview_field_'+field).find('.controls').append(ctl);
                        $('#form-preview').css({
                            height:'auto'
                        });
                        $('#form-preview').height($('#form-preview').height());
                    },'json');
                }
                break;
            case 'autocomplete':
                if (ScrudCForm.elements[field].list_choose == 'default'){
                    var ctl = $('<select id="preview_field_'+field+'_select2"  style="width:220px;"  ></select>').append($('<option>&nbsp;</option>'));
                    if (ScrudCForm.elements[field].options.length > 0){
                        var opts = ScrudCForm.elements[field].options;
                        for(var i in opts){
                            if ($.trim(opts[i]) != ''){
                                ctl.append($('<option>'+opts[i]+'</option>'));
                            }
                        }
                    }
                    $('#preview_field_'+field).find('.controls').append(ctl);
                    $("#preview_field_"+field+"_select2").select2();
                }else if (ScrudCForm.elements[field].list_choose == 'database'){
                    $.post(ScrudCForm.wpage+'/admin/scrud/getoptions',{
                        config:ScrudCForm.elements[field].db_options
                    },function(json){
                        var ctl = $('<select id="preview_field_'+field+'_select2"  style="width:220px;" ></select>').append($('<option>&nbsp;</option>'));
                        for(var i in json){
                            ctl.append($('<option>'+json[i]+'</option>'));
                        }
                        $('#preview_field_'+field).find('.controls').append(ctl);
                        $('#form-preview').css({
                            height:'auto'
                        });
                        $('#form-preview').height($('#form-preview').height());
                        $("#preview_field_"+field+"_select2").select2();
                    },'json');
                }
                break;
        }
		
    },
    buildPreviews:function(){
        var fields = ScrudCForm.elements;
        if (ScrudCForm.config.ids == undefined){
            ScrudCForm.config.ids = [];
            for(var i in fields){
                ScrudCForm.config.ids[ScrudCForm.config.ids.length] = i;
            }
        }
        for(var i in ScrudCForm.config.ids){
            ScrudCForm.addFieldToForm(fields[ScrudCForm.config.ids[i]].field,fields[ScrudCForm.config.ids[i]].label);
        }
        $('#form-preview').css({
            height:'auto'
        });
        $('#form-preview').height($('#form-preview').height());
    },
    saveElements:function(table){
        if (table == undefined) return;
        
        ScrudCForm.config.filter.list = [];
        $('#filter_elements').find('input').each(function(){
            ScrudCForm.config.filter.list[ScrudCForm.config.filter.list.length] = $(this).val();
        });
        
        ScrudCForm.config.filter.actived = [];
        $('#filter_elements').find('input:checked').each(function(){
            ScrudCForm.config.filter.actived[ScrudCForm.config.filter.actived.length] = $(this).val();
        });
        
        ScrudCForm.config.column.list = [];
        $('#column_elements').find('input').each(function(){
            ScrudCForm.config.column.list[ScrudCForm.config.column.list.length] = $(this).val();
        });
        
        ScrudCForm.config.column.actived = [];
        $('#column_elements').find('input:checked').each(function(){
            ScrudCForm.config.column.actived[ScrudCForm.config.column.actived.length] = $(this).val();
        });
        
        ScrudCForm.config.join = [];
        
        $('#dataListJoin > div').each(function(){
            var object = {};
            object.type = $(this).find('#joinType').val();
            object.table = $(this).find('#joinTable').val();
            object.currentField = $(this).find('#currentField').val();
            object.targetField = $(this).find('#targetField').val();
            ScrudCForm.config.join[ScrudCForm.config.join.length] = object;
        });
        
        var elements = {};
        var ids = [];
        $('#elements_preview > li').each(function(){
            var id = $(this).attr('id');
            id = id.replace("preview_field_",""); 
            ids[ids.length] = id;
            if (ScrudCForm.elements[id] != undefined){
                elements[id] = ScrudCForm.elements[id];
            }
        });
        var config = ScrudCForm.config;
        config.ids = ids;
        //console.log(ScrudCForm);
        var strAlertSuccess = '<div class="alert alert-success" style="position: fixed; right:0px; top:45px; display: none;">' +
        '<button data-dismiss="alert" class="close" type="button">×</button>' +
        '<strong>Success!</strong> You successfully saved' +
        '</div>';
        $.post(ScrudCForm.wpage+'/admin/scrud/saveconfig', {
            scrud:elements,
            table:table,
            config:config
        }, function(json){
            var alertSuccess = $(strAlertSuccess).appendTo('body');
            alertSuccess.show();
            setTimeout(function(){ 
                alertSuccess.remove();
            },2000);
        }, 'html');
    }
	
};
var dragFlag = false;
$(document).ready(function(){
    $("#elements_preview").sortable({
        forcePlaceholderSize: true,
        placeholder: "ui-state-highlight",
        start: function(event, ui) {
            dragFlag = true;
            $('#form-preview').height($('#form-preview').height());
        },
        stop:function(event, ui){
            if (!$.browser.mozilla){
                dragFlag = false;
            }
        }
    });
    // $("#elements_preview").disableSelection();
    
    $('#btn_field_to_form').clickover({
        placement: 'bottom',
        title:'Add field to form',
        html:true,
        width:250,
        content:'<ul class="nav nav-tabs nav-stacked" style="margin-bottom:0px;" id="form-fields"></ul>&nbsp;',
        onShown:function(){
            ScrudCForm.buildFields();
        }
    }); 
	        
    ScrudCForm.init();
    ScrudCForm.buildPreviews();
    ScrudCForm.buildConfigTable();
    ScrudCForm.buildFilter();
    ScrudCForm.buildColumn();
    
    $('#btnSaveDataList').click(function(){
        ScrudCForm.saveElements(ScrudCForm.table);
    });
    
    $('#addJoinButton').click(function(){
        ScrudCForm.addJoin();
    });
    if (ScrudCForm.config.join != undefined){
        //        console.log(ScrudCForm.config.join);
        for(var i in ScrudCForm.config.join){
            ScrudCForm.addJoin(ScrudCForm.config.join[i]);
        }
    }
});