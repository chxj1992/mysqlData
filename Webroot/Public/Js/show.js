
//数据选择页
$(document).ready(function(){
    
    var model = $("#model").val();
    var p = $("#p").val();
    var psize = $("#psize").val();
    $("#main-models").val(model);
     
    $.ajax({
            url:"/Index/data/",
            type:"POST",
            dataType:'json',
            data:{
                model:model,
                p:p,
                psize:psize
            },
            success:function(data,status){
                if ( data.status != 0 ) {
                    $("#data-table").html(data.data);
                } else {
                    $("#data-table").html("<h4>"+data.info+"</h4>");
                }
            },
    });

    //选择主表
    $("#main-models").change(function(){
        var model = $(this).val();
        $.ajax({
            url:"/Index/show/",
            type:"POST",
            data:{
                model:model,
                refresh:true,
            },
            success:function(data,status){
                window.location.reload();
            },
        });

    });

    //提交关联表
    $("#submit-rela-btn").click(function(){
        var model = $("#model").val();
        var relation = Array();
        var i =0;
        $(".attach-models").each(function(){
            i ++;
            if ( i == 1 ) {
                return true;
            }
            relation[i] = $(this).val(); 
        });
        $.ajax({
            url:"/Index/data/",
            type:"POST",
            dataType:'json',
            data:{
                model:model,
                relation:relation,
                refresh:true,
            },
            success:function(data,status){
                if ( data.status != 0 ) {
                    $("#data-table").html(data.data);
                } else {
                    $("#data-table").html("<h4>"+data.info+"</h4>");
                }
            },
        
        });

    });
    
    $("#add-attach").click(function(){
        var attach_select = $("#attach-eg").html(); 
        $("#attach-models").append(attach_select);
    }); 
   

    $("body").delegate(".del-attach","click", function(){
        $(this).parent().remove();
    });


    $("#to-cal-btn").click(function(){
        var sql = $("#sql").val();
        var field_str = $("#field_str").val();
        $.ajax({
            url:"/Index/calcu/",
            type:"POST",
            data:{
                sql:sql,
                field:field_str,
            },
            success:function(data,status){
                window.location.href = '/Index/calcu/';
            },
        });


    });

});
