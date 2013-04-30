

$(document).ready(function(){

    //选择目录
    $("#chart-month").change(function(){
        var month = $(this).val();
        $.ajax({
            url:"/Check/chart/",
            type:"POST",
            data:{
                month:month,
            },
            success:function(data,status){
                window.location.href = "/Check/chart/";
            },
        });
    });

    $(".check-chart").click(function(){
        var title = $(this).attr('title');
        $.ajax({
            url:"/Check/chart/",
            type:"POST",
            data:{
                title:title,
            },
            success:function(data,status){
                window.location.href = "/Check/chart/";
            },
        });
    });

    $("#unset-chart").click(function(){
        $.ajax({
            url:"/Check/unsetChart/",
            type:"POST",
            success:function(data,status){
                window.location.href = "/Check/chart/";
            },
        });
    });
    
    $(".del-chart").click(function(){
        $('#myModal').modal('show');
        var title = $(this).attr('title');
        $('#title-del').val(title);
    });
    

    $("#sub-del-chart").click(function(){
        var title = $('#title-del').val();
        var month = $("#month").val();
        $.ajax({
            url:"/Check/delChart/",
            type:"POST",
            data:{
                title:title,
                month:month,
            },
            success:function(data,status){
                window.location.href = "/Check/chart/";
            },
        });
    });

 
    $(".del-data").click(function(){
        $('#myModal').modal('show');
        var sql_id = $(this).attr('data_id');
        $('#data-del').val(sql_id);
    });
    

    $("#sub-del-data").click(function(){
        var sql_id = $('#data-del').val();
        $.ajax({
            url:"/Check/delData/",
            type:"POST",
            data:{
                sql_id:sql_id,
            },
            success:function(data,status){
                window.location.href = "/Check/data/";
            },
        });
    });


    $(".import-data").click(function(){
        var sql = $(this).attr('data');
        $.ajax({
            url:"/Index/calcu/",
            type:"POST",
            data:{
                sql:sql,
            },
            success:function(data,status){
                window.location.href="/Index/calcu";
            },
        });
    });






});
