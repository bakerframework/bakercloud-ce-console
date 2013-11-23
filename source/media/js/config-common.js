var ScrudCCommon = {
    elements : {},
    init:function(){
        var elements = [];
        elements[elements.length] = {
            name:'title',
            label:'<b>Title</b>',
            control:'<input type="text"/>'
        };
        elements[elements.length] = {
            name:'rows_per_page',
            label:'<b>Rows per page</b>',
            control:'<input type="text" class="input-mini" />'
        };
		
        var control = 
        $('<select></select>')
        .append($('<option value=""></option>'))
        .append($('<option value="id">id</option>'))
        .append($('<option value="category_id">category_id</option>'))
        .append($('<option value="article_title">article_title</option>'))
        .append($('<option value="article_date">article_date</option>'))
        .append($('<option value="article_summary">article_summary</option>'))
        .append($('<option value="article_content">article_content</option>'));
        elements[elements.length] = {
            name:'order_field',
            label:'<b>Order field</b>',
            control:control
        };
		
        var control = 
        $('<select></select>')
        .append($('<option value=""></option>'))
        .append($('<option value="asc">ASC</option>'))
        .append($('<option value="desc">DESC</option>'));
        elements[elements.length] = {
            name:'order_type',
            label:'<b>Order type</b>',
            control:control
        };
		
        for(var i in elements){
            ScrudElement(elements[i].name,elements[i].label,elements[i].control).appendTo('#config-common-left');
        }
		
        var elements = [];
		
        var control = 
        $('<select></select>')
        .append($('<option value="physical">Physical</option>'))
        .append($('<option value="logical">Logical</option>'));
        elements[elements.length] = {
            name:'delete_type',
            label:'<b>Delete type</b>',
            control:control
        };
        elements[elements.length] = {
            name:'join_table',
            label:'<b>Join table</b>',
            control:'<input type="button" class="btn btn-mini" value="Add join"/>'
        };
		
		
        for(var i in elements){
            ScrudElement(elements[i].name,elements[i].label,elements[i].control).appendTo('#config-common-center');
        }
    },
    resetForm : function() {

    },
    buildFields:function(){
		
    },
    buildOptions:function(){
		
    }
};
$(document).ready(function(){
    ScrudCCommon.init();
});