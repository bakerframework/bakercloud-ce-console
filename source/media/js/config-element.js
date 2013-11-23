var ScrudElement = function(name,label,control) {
    var e = $('<div class="control-group"></div>');
    var lbl = $('<label class="control-label" for="crud'+name+'"></lable>').html(label);
    var ct = $('<div class="controls"></div>').html($(control).attr({
        id:'crud'+name,
        name:'crud['+name+']'
    }));
    return  e.append(lbl).append(ct);
};
