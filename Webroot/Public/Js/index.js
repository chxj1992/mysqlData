
//欢迎页
$(document).ready(function(){
    
    //选择主表
    $("#start-check").click(function(){
        var model = $("#main-model").val();
        $.ajax({
            url:"/Index/show/",
            type:"POST",
            data:{
                model:model,
                refresh:true,
            },
            success:function(data,status){
                window.location.href = "/Index/show/";
            },
        });
    });

    //查询历史数据
    $("#start-check-old").click(function(){
        var type = $("#data-type").val();
        window.location.href = "/Check/"+type;
    });

    //选择主表
    $("#logout").click(function(){
        $.ajax({
            url:"/User/logout/",
            type:"POST",
            success:function(data,status){
                window.location.href = "/User/logout/";
            },
        });
    });

});
