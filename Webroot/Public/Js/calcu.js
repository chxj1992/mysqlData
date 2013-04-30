
//数据选择页
$(document).ready(function(){

    var sql = $("#old_sql").val();
    var p = $("#p").val();
    var psize = $("#psize").val();
    $.ajax({
            url:"/Index/doCalcu/",
            type:"POST",
            dataType:'json',
            data:{
                sql:sql,
                p:p,
                psize:psize
            },
            success:function(data,status){
                if ( data.status != 0 ) {
                    $("#data-table").html(data.data);
                    var sql = $('#sql').val();
                    $("#old_sql").val(sql);
                } else {
                    $("#data-table").html("<h4>"+data.info+"</h4>");
                    $("#data-table").append("<div> <h5>SQL语句:</h5>"+sql+"</div>");
                }
            }
    });


    $("#add-show").click(function(){
        var show_eg = $("#show-eg").html(); 
        $("#show-block").append(show_eg);
    }); 
    $("body").delegate(".del-show","click", function(){
        $(this).parent().remove();
    });

    $("#add-where").click(function(){
        var where_eg = $("#where-eg").html(); 
        $("#where-block").append(where_eg);
    }); 
    $("body").delegate(".del-where","click", function(){
        $(this).parent().remove();
    });

    $("#add-group").click(function(){
        var group_eg = $("#group-eg").html(); 
        $("#group-block").append(group_eg);
    }); 
    $("body").delegate(".del-group","click", function(){
        $(this).parent().remove();
    });

    $("body").delegate("#add-field","click",function(){
        var field = $("#field-select").val();
        field = '`'+field+'` ';
        var val = $("#field-area").val()+field;
        $("#field-area").val(val);
    }); 

    $("#add-order").click(function(){
        var order_eg = $("#order-eg").html(); 
        $("#order-block").append(order_eg);
    }); 
    $("body").delegate(".del-order","click", function(){
        $(this).parent().remove();
    });

    function getShow() {
        var show = {};
        var i = 0;
        $(".show-div").each(function(){
            i ++;
            if ( i == 1 ) {
                return true;
            }
            show[i] = Object();
            show[i].field = $(this).find("[name='field']").val(); 
            show[i].func = $(this).find("[name='func']").val(); 
            show[i].param = $(this).find("[name='param']").val(); 
        });
        return show;
    }

    function getWhere() {
        var where = {};
        var i = 0;
        $(".where-div").each(function(){
            i ++;
            if ( i == 1 ) {
                return true;
            }
            where[i] = Object();
            where[i].field = $(this).find("[name='field']").val(); 
            where[i].sign = $(this).find("[name='sign']").val(); 
            where[i].value = $(this).find("[name='value']").val(); 
        });
        return where;
    }
    
    function getGroup() {
        var group = {};
        var i = 0;
        $(".group-div").each(function(){
            i ++;
            if ( i == 1 ) {
                return true;
            }
            group[i] = Object();
            group[i].field = $(this).find("[name='field']").val(); 
        });
        return group;
    }

    function getOrder() {
        var order = {};
        var i = 0;
        $(".order-div").each(function(){
            i ++;
            if ( i == 1 ) {
                return true;
            }
            order[i] = Object();
            order[i].field = $(this).find("[name='field']").val(); 
            order[i].order = $(this).find("[name='order']").val(); 
        });
        return order;
    }

    //提交查询参数
    $("#submit-calcu-btn").click(function(){
        var data = Array(); 
        var form_sql = $("#form-sql").val();
        var sql = $("#sql").val();
        var psize = $("#limit").val();
        var show = getShow();
        var where = getWhere();
        var group = getGroup();
        var order = getOrder();
        var field = $("#field-area").val();
        if ( form_sql ) {
            sql = form_sql;
            data = {
                sql:sql,
            }; 
        } else {
            data = {
                    sql:sql,
                    show:show,
                    where:where,
                    group:group,
                    order:order,
                    psize:psize,
                    my_field:field,
                };
        }
            
        $.ajax({
            url:"/Index/doCalcu/",
            type:"POST",
            dataType:'json',
            data: data ,
            success:function(data,status){
                if (data.status != 0) {
                    window.location.href="/Index/calcu";
                } else {
                    $("#data-table").html("<h4>"+data.info+"</h4>");
                    $("#data-table").append("<div> <h5>SQL语句:</h5>"+sql+"</div>");
                }
            },
        });

    });
    

    $("#submit-unset-btn").click(function(){
        var sql = $("#show_sql").val();
        $.ajax({
            url:"/Index/doCalcu/",
            type:"POST",
            dataType:'json',
            data:{
                sql:sql,
                refresh:true,
            },
            success:function(data,status){
                window.location.href="/Index/calcu";
            },
        });
    });


    $("#to-draw-btn").click(function(){
        var sql = $("#old_sql").val();
        var field_str = $("#field_str").val();
        $.ajax({
            url:"/Index/charts/",
            type:"POST",
            data:{
                sql:sql,
                field:field_str,
            },
            success:function(data,status){
                window.location.href="/Index/charts";
            },
        });
    });

    $("body").delegate("#save-data-btn","click", function(){
        $('#myModal').modal('show'); 
    });

    $("body").delegate("#do-save-data","click", function(){
        var sql = $("#old_sql").val();
        var sql_name = $("#sql-name").val();
        $.ajax({
            url:"/Check/saveData/",
            type:"POST",
            dataType:'json',
            data:{
                sql:sql,
                sql_name:sql_name,
            },
            success:function(data,status){
                if ( data.status != 0 ) {
                    $("#do-save-data").text('Success');
                    $("#do-save-data").attr('class','btn btn-warning');
                    setTimeout(" $('#myModal').modal('hide') ",1500);
                } else {
                    $("#do-save-data").text('Fail');
                    $("#do-save-data").attr('class','btn btn-danger');
                    setTimeout(" $('#do-save-data').text('Submit'); $('#do-save-data').attr('class','btn btn-success');",1500);
                }
            },
        });
    });


    $(".isadvance-btn").click(function(){
        $(".isadvance-btn").toggle('slow');
        $(".mode").toggle('slow');
    });

});
