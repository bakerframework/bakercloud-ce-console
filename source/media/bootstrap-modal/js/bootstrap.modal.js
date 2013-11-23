(function($) {
    var __f = {
        elements:[],
        text:function(o){
            var cg = $('<div class="control-group"></div>');
            var l = $('<label class="control-label" for="'+o.name+'"></label>')
            .css({
                fontWeight:'bold'
            })
            .append(o.label);
            var c = $('<div class="controls"></div>');
            var text = $('<input type="text" id="'+o.name+'"  name="'+o.name+'"/>');
            if (o.value != undefined){
                text.val(o.value);
            }
            if (o.attr != undefined){
                for(var i in o.attr){
                    switch (i){
                        case 'css':
                            if (typeof o.attr[i] == 'object'){
                                text.css(o.attr[i]);
                            }
                            break;
                        case 'addClass':
                            if (typeof o.attr[i] == 'string'){
                                text.addClass(o.attr[i]);
                            }
                            break;
                        case 'id':
                        case 'name':
                        case 'type':
                            break;
                        default:
                            if (typeof o.attr[i] != 'object' && typeof o.attr[i] != 'function'){
                                text.attr(i,__f.escapeHtml(o.attr[i]));
                            }
                            break;
                    }
                }
            }
            cg
            .append(l)
            .append(c.append(text));
            
            return cg;
        },
        
        hidden:function(o){
            var hidden = $('<input type="hidden" id="'+o.name+'" name="'+o.name+'"/>');
            if (o.value != undefined){
                hidden.val(o.value);
            }
            
            return hidden;
        },
        
        textarea:function(o){
            var cg = $('<div class="control-group"></div>');
            var l = $('<label class="control-label" for="'+o.name+'"></label>')
            .css({
                fontWeight:'bold'
            })
            .append(o.label);
            var c = $('<div class="controls"></div>');
            var textarea = $('<textarea  id="'+o.name+'" name="'+o.name+'"></textarea>');
            if (o.value != undefined){
                textarea.val(o.value);
            }
            if (o.attr != undefined){
                for(var i in o.attr){
                    switch (i){
                        case 'css':
                            if (typeof o.attr[i] == 'object'){
                                textarea.css(o.attr[i]);
                            }
                            break;
                        case 'addClass':
                            if (typeof o.attr[i] == 'string'){
                                textarea.addClass(o.attr[i]);
                            }
                            break;
                        case 'id':
                        case 'name':
                        case 'type':
                            break;
                        default:
                            if (typeof o.attr[i] != 'object' && typeof o.attr[i] != 'function'){
                                textarea.attr(i,__f.escapeHtml(o.attr[i]));
                            }
                            break;
                    }
                }
            }
            cg
            .append(l)
            .append(c.append(textarea));
            
            return cg;
        },
        
        checkbox:function(o){
            var cg = $('<div class="control-group"></div>');
            var l = $('<label class="control-label" for="'+o.name+'"></label>')
            .css({
                fontWeight:'bold'
            })
            .append(o.label);
            var c = $('<div class="controls"></div>');
            var checkbox = $('<input type="checkbox" id="'+o.name+'" name="'+o.name+'"/>');
            if (o.value != undefined){
                checkbox.val(o.value);
            }
            if (o.checked != undefined && o.checked == true){
                checkbox.attr({
                    checked:true
                });
            }
            if (o.attr != undefined){
                for(var i in o.attr){
                    switch (i){
                        case 'css':
                            if (typeof o.attr[i] == 'object'){
                                checkbox.css(o.attr[i]);
                            }
                            break;
                        case 'addClass':
                            if (typeof o.attr[i] == 'string'){
                                checkbox.addClass(o.attr[i]);
                            }
                            break;
                        case 'id':
                        case 'name':
                        case 'type':
                            break;
                        default:
                            if (typeof o.attr[i] != 'object' && typeof o.attr[i] != 'function'){
                                checkbox.attr(i,__f.escapeHtml(o.attr[i]));
                            }
                            break;
                    }
                }
            }
            cg
            .append(l)
            .append(c.append(checkbox));
            
            return cg;
        },
        
        radio:function(o){
            var cg = $('<div class="control-group"></div>');
            var l = $('<label class="control-label" for="'+o.name+'"></label>')
            .css({
                fontWeight:'bold'
            })
            .append(o.label);
            var c = $('<div class="controls"></div>');
            if (o.options != undefined && o.options.length > 0){
                for(var i =0; i < o.options.length; i++){
                    var label = $('<label class="radio inline"></label>');
                    var radio = $('<input type="radio" id="'+o.name+'_'+i+'" name="'+o.name+'" />');
                    if (o.value != undefined){
                        radio.val(o.options[i].value);
                    }
                    if (o.value != undefined && o.value == o.options[i].value){
                        radio.attr({
                            checked:true
                        });
                    }
                    if (o.attr != undefined){
                        for(var j in o.attr){
                            switch (j){
                                case 'css':
                                    if (typeof o.attr[j] == 'object'){
                                        radio.css(o.attr[j]);
                                    }
                                    break;
                                case 'addClass':
                                    if (typeof o.attr[j] == 'string'){
                                        radio.addClass(o.attr[j]);
                                    }
                                    break;
                                case 'id':
                                case 'name':
                                case 'type':
                                    break;
                                default:
                                    if (typeof o.attr[j] != 'object' && typeof o.attr[j] != 'function'){
                                        radio.attr(j,__f.escapeHtml(o.attr[j]));
                                    }
                                    break;
                            }
                        }
                    }
                    label.append(radio);
                    label.append(o.options[i].label);
                    c.append(label);
                }
            }
            
            cg
            .append(l)
            .append(c);
            
            return cg;
        },
        
        selectbox:function(o){
            var cg = $('<div class="control-group"></div>');
            var l = $('<label class="control-label" for="'+o.name+'"></label>')
            .css({
                fontWeight:'bold'
            })
            .append(o.label);
            var c = $('<div class="controls"></div>');
            var selectbox = $('<select id="'+o.name+'_'+i+'" name="'+o.name+'" ></select>');
            selectbox.append('<option></option>');
            if (o.options != undefined && o.options.length > 0){
                for(var i =0; i < o.options.length; i++){
                    var option = $('<option value="'+o.options[i].value+'" ></option>');
                    option.append(o.options[i].label);
                    if (o.value != undefined && o.value == o.options[i].value){
                        option.attr({
                            selected:true
                        });
                    }
                    if (o.attr != undefined){
                        for(var j in o.attr){
                            switch (j){
                                case 'css':
                                    if (typeof o.attr[j] == 'object'){
                                        option.css(o.attr[j]);
                                    }
                                    break;
                                case 'addClass':
                                    if (typeof o.attr[j] == 'string'){
                                        option.addClass(o.attr[j]);
                                    }
                                    break;
                                case 'id':
                                case 'name':
                                case 'type':
                                    break;
                                default:
                                    if (typeof o.attr[j] != 'object' && typeof o.attr[j] != 'function'){
                                        option.attr(j,__f.escapeHtml(o.attr[j]));
                                    }
                                    break;
                            }
                        }
                    }
                    selectbox.append(option);
                }
            }
            c.append(selectbox);
            
            cg
            .append(l)
            .append(c);
            
            return cg;
        },
        
        escapeHtml:function (unsafe) {
            return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
        },
        
        add:function(element){
            __f.elements[__f.elements.length] = element;
        },
        
        reset: function(){
            __f.elements = [];
        },
        
        render:function(){
            var f = $('<form></form>').css({
                margin:'0'
            });
            for(var i =0; i < __f.elements.length; i++){
                var o = __f.elements[i];
                switch (o.type){
                    case 'text':
                        f.append(__f.text(o));
                        break;
                    case 'hidden':
                        f.append(__f.hidden(o));
                        break;
                    case 'textarea':
                        f.append(__f.textarea(o));
                        break;
                    case 'checkbox':
                        f.append(__f.checkbox(o));
                        break;
                    case 'radio':
                        f.append(__f.radio(o));
                        break;
                    case 'selectbox':
                        f.append(__f.selectbox(o));
                        break;
                }
            }
            return f;
        }
        
    };
    var __m = {
        opts:[],
        easings:{
            jswing:'jswing',
            easeInQuad:'easeInQuad',
            easeOutQuad:'easeOutQuad',
            easeInOutQuad:'easeInOutQuad',
            easeInCubic:'easeInCubic',
            easeOutCubic:'easeOutCubic',
            easeInOutCubic:'easeInOutCubic',
            easeInQuart:'easeInQuart',
            easeOutQuart:'easeOutQuart',
            easeInOutQuart:'easeInOutQuart',
            easeInQuint:'easeInQuint',
            easeOutQuint:'easeOutQuint',
            easeInOutQuint:'easeInOutQuint',
            easeInSine:'easeInSine',
            easeOutSine:'easeOutSine',
            easeInOutSine:'easeInOutSine',
            easeInExpo:'easeInExpo',
            easeOutExpo:'easeOutExpo',
            easeInOutExpo:'easeInOutExpo',
            easeInCirc:'easeInCirc',
            easeOutCirc:'easeOutCirc',
            easeInOutCirc:'easeInOutCirc',
            easeInElastic:'easeInElastic',
            easeOutElastic:'easeOutElastic',
            easeInOutElastic:'easeInOutElastic',
            easeInBack:'easeInBack',
            easeOutBack:'easeOutBack',
            easeInOutBack:'easeInOutBack',
            easeInBounce:'easeInBounce',
            easeOutBounce:'easeOutBounce',
            easeInOutBounce:'easeInOutBounce'
        },
        animates:{
            flipX :{
                i:'flipInX',
                o:'flipOutX'
            },
            flipY :{
                i:'flipInY',
                o:'flipOutY'
            },
            fadeUp :{
                i:'fadeInUp',
                o:'fadeOutUp'
            },
            fadeDown :{
                i:'fadeInDown',
                o:'fadeOutDown'
            },
            fadeLeft :{
                i:'fadeInLeft',
                o:'fadeOutLeft'
            },
            fadeRight :{
                i:'fadeInRight',
                o:'fadeOutRight'
            },
            fadeUpBig :{
                i:'fadeInUpBig',
                o:'fadeOutUpBig'
            },
            fadeDownBig :{
                i:'fadeInDownBig',
                o:'fadeOutDownBig'
            },
            fadeLeftBig :{
                i:'fadeInLeftBig',
                o:'fadeOutLeftBig'
            },
            fadeRightBig :{
                i:'fadeInRightBig',
                o:'fadeOutRightBig'
            },
            bounce :{
                i:'bounceIn',
                o:'bounceOut'
            },
            bounceUp :{
                i:'bounceInUp',
                o:'bounceOutUp'
            },
            bounceDown :{
                i:'bounceInDown',
                o:'bounceOutDown'
            },
            bounceLeft :{
                i:'bounceInLeft',
                o:'bounceOutLeft'
            },
            bounceRight :{
                i:'bounceInRight',
                o:'bounceOutRight'
            },
            rotate :{
                i:'rotateIn',
                o:'rotateOut'
            },
            rotateUpLeft :{
                i:'rotateInUpLeft',
                o:'rotateOutUpLeft'
            },
            rotateUpRight :{
                i:'rotateInUpRight',
                o:'rotateOutUpRight'
            },
            rotateDownLeft :{
                i:'rotateInDownLeft',
                o:'rotateOutDownLeft'
            },
            rotateDownRight :{
                i:'rotateInDownRight',
                o:'rotateOutDownRight'
            },
            lightSpeed :{
                i:'lightSpeedIn',
                o:'lightSpeedOut'
            },
            roll :{
                i:'rollIn',
                o:'rollOut'
            }
        },
        types:{
            info:'info',
            success:'success',
            error:'error'
        },
        effects:{
            slide:'slide',
            fade:'fade'
        },
        init:function (opts){
            var id = __m.guid();
            if (opts.delay != undefined){
                opts.delay = parseInt(opts.delay);
            }
            opts = (opts != undefined)?opts:{};
            opts = $.extend(true,{
                image:null,
                header:null,
                content:'',
                timeOut:1000,
                delay:0,
                effect:'',
                animate:'',
                easing:'jswing',
                form:[],
                duration:300,
                width:400,
                buttons:[],
                onStart:function(id){},
                onShow:function(id){},
                onClose:function(id){}
       
            },opts);
            
            if (__m.easings[opts.easing] == undefined){
                opts.easing = 'jswing';
            }
            
            __m.opts[id] = opts;
    
            if (opts.delay > 0){
                setTimeout(function(){
                    __m.create(id);
                },opts.delay);
            }else{
                __m.create(id);
            }
    
            return id;
        },
        create:function(id){
            var opts = __m.opts[id];
            var o = $('<div></div>').attr({
                id:id
            });
            
            var a = {
                x: $(window).width(),
                y: $(window).height()
            };
            var b = {
                x: $(window).scrollLeft(),
                y: $(window).scrollTop()
            };
            
            var y = b.x + ((a.x - opts.width) / 2);
            
            o.css({
                position:'absolute',
                zIndex:2000,
                top:80,
                left:y,
                margin:0
            });
            o.width(opts.width);
            o.addClass('modal').addClass('tb_modal');
            var backdrop = $('<div class="modal-backdrop fade in" id="'+id+'_backdrop"></div>');
            backdrop.css({
                opacity: '0.5', 
                filter: 'alpha(opacity=50)'
            });
            $('body').append(backdrop); 
            o.appendTo('#table');
            opts.onStart(id);
            __m.addHeader(id);
            __m.addBody(id);
            __m.addFooter(id);
            if (__m.animates[opts.animate] != undefined){
                o.addClass('animated '+ __m.animates[opts.animate].i);
            }
            var x = b.y + ((a.y - $('#'+id).outerHeight()) / 2) - $('#'+id).outerHeight() / 2 - 30;
            if (x > 80){
                o.css({
                    top:x
                });
            }
            
            opts.onShow(id);
        },
        addHeader: function (id){
            var opts = __m.opts[id];
            if (opts.header != null){
                var o = $('#'+id);
                var _h = $('<div></div>').addClass('modal-header');
                var btn_close = $('<button class="close" type="button">×</button>');
                btn_close.data('parent_id',id);
                btn_close.click(function(){
                    var id = $(this).data('parent_id');
                    __m.close(id);
                });
                _h.append(btn_close);
                _h.append($('<h3></h3>').html(opts.header));
                o.append(_h);
            }
        },
        addBody:function(id){
            var opts = __m.opts[id];
            
            if (opts.form.length > 0){
                for(var i = 0; i < opts.form.length; i++){
                    var obj = opts.form[i];
                    var target = {};
                    if (obj.name != undefined){
                        target.name = obj.name;
                    }
                    if (obj.type != undefined){
                        target.type = obj.type;
                    }
                    if (obj.label != undefined){
                        target.label = obj.label;
                    }
                    if (obj.attr != undefined){
                        target.attr = obj.attr;
                    }
                    if (obj.value != undefined){
                        target.value = obj.value;
                    }
                    if (obj.options != undefined){
                        target.options = obj.options;
                    }
                    __f.add(obj);
                    
                }
                if ($.trim(opts.content) != ''){
                    opts.content = $('<div></div>')
                    .append(
                        $('<div></div>')
                        .css({
                            marginBottom:'7px'
                        })
                        .append(opts.content)
                        );
                }else{
                    opts.content = $('<div></div>').append(__f.render());
                }    
                
            }
            
            if (opts.image != null){
                var tbl = $('<table></table>');
                var tr = $('<tr></tr>');
                var img = $('<img />').attr('src', opts.image);
                tr.append($('<td></td>').attr({
                    valign:'top',
                    align:'left'
                }).css('padding-right', '15px').append(img));
                tr.append($('<td></td>').attr({
                    valign:'middle',
                    align:'left'
                }).append(opts.content));
                tbl.append(tr);
                opts.content = tbl;
            }
            
            var o = $('#'+id);
            var _b = $('<div></div>').addClass('modal-body');
            _b.append(opts.content);
            o.append(_b);
        },
        addFooter:function(id){
            var opts = __m.opts[id];
            if (opts.buttons.length > 0){
                var o = $('#'+id);
                var btnc = $('<div></div>').addClass('modal-footer');
                for(var i = 0; i < opts.buttons.length; i++){
                    var btn = $('<a class="btn"></a>');
                    if (opts.buttons[i].text != undefined){
                        btn.html(opts.buttons[i].text);
                    }
                    if (opts.buttons[i].addClass != undefined){
                        btn.addClass(opts.buttons[i].addClass);
                    }
                    if (opts.buttons[i].click != undefined){
                        btn.data('i',i);
                        btn.click(function(){
                            var _i = parseInt($(this).data('i'));
                            var data = $('#'+id).find('form').serializeArray();
                            opts.buttons[_i].click(id,data);
                        });
                    }
                    btnc.append(btn).append(' ');
                }
                o.append(btnc);
            }
        },
        close:function (id){
            var opts = __m.opts[id];
            var o = $('#'+id);
            opts.onClose(id);
            if (__m.animates[opts.animate] != undefined){
                o.addClass('animated '+ __m.animates[opts.animate].o);
            }
            __f.reset();
            switch (opts.effect){
                case 'slide':
                    o.slideUp(opts.duration,opts.easing,function(){
                        $(this).remove();
                        $('#'+id+'_backdrop').remove();
                    });
                    break;
                case 'fade':
                    o.fadeOut(opts.duration,opts.easing,function(){
                        $(this).remove();
                        $('#'+id+'_backdrop').remove();
                    });
                    break;
                default:
                    if (__m.animates[opts.animate] != undefined){
                        if ( $.browser.msie && parseInt($.browser.version) < 10) {
                            o.remove();
                            $('#'+id+'_backdrop').remove();
                        }else{
                            setTimeout(function(){
                                o.remove();
                                $('#'+id+'_backdrop').remove();
                            },opts.timeOut); 
                        }
                        
                    }else{
                        o.remove();
                        $('#'+id+'_backdrop').remove();
                    }
                    break;
            }
        },
        guid:function() {
            var S4 = function() {
                return Math.floor(Math.random() * 0x10000 /* 65536 */
                    ).toString(16);
            };
            return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
        }
    };
    $.sModal = function(o,id){
        id = (id != undefined)?id:'';
        switch (typeof o){
            case 'object':
                return __m.init(o);
                break;
            case 'string':
                switch (o){
                    case 'close':
                        __m.close(id);
                        break;
                }
                break;
        }
        
    };
    
})(jQuery);

$(document).ready(function(){
    $(window).keyup(function (e) {
        if (e.keyCode == 27) {
            $('.tb_modal').each(function(){
                $.sModal('close',$(this).attr('id'));
            });
        }
    });
});
