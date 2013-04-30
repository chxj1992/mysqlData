
//数据选择页
$(document).ready(function(){

    var sql = $("#sql").val();
    $.ajax({
            url:"/Index/chartData/",
            type:"POST",
            dataType:'json',
            data:{
                sql:sql,
            },
            success:function(data,status){
                if ( data.status != 0 ) {
                    $("#data-table").html(data.data);
                } else {
                    $("#data-table").html("<h4>"+data.info+"</h4>");
                }
            }
    });
    
    $("#add-field").click(function(){
        var field_eg = $("#field-eg").html(); 
        $("#field-block").append(field_eg);
    }); 
    $("body").delegate(".del-field","click", function(){
        $(this).parent().remove();
    });

    $("#submit-unset-btn").click(function(){
        var sql = $("#sql").val();
        $.ajax({
                url:"/Index/unsetChart/",
                type:"POST",
                success:function(data,status){
                    window.location.href = "/Index/charts";
                }
        });
    }); 

    $("#submit-chart-btn").click(function(){
        var sql = $("#sql").val();
        var type = $("#chart-type").val();
        var x_name = $("#x_name").val();
        var y_name = $("#y_name").val();
        var column_field = $("#column-field").val();
        var title = $("#title").val();
        var data_field = Array();
        var i = 0;
        $(".data-field").each(function(){
            i ++;
            if ( i == 1 ) {
                return true;
            }
            data_field[i] = $(this).val();
        });

        $.ajax({
                url:"/Index/chartShow/",
                type:"POST",
                dataType:'json',
                data:{
                    sql:sql,
                    type:type,
                    x_name:x_name,
                    y_name:y_name,
                    column_field:column_field,
                    data_field:data_field,
                    title:title,
                },
                success:function(data,status){
                    if ( data.status != 0 ) {
                        window.location.href = "/Index/charts";
                    } else {
                        $("#data-table").html("<h4>"+data.info+"</h4>");
                    }
                }
        });

    }); 

 
    $("#do-save-chart").click(function(){
        
        var title = $("#save-chart-title").val();
        if ( title == undefined ) {
            title = $("#chart-title").val();
        }
        var chart = $("#chart_div").html();
        $.ajax({
                url:"/Check/saveChart/",
                type:"POST",
                dataType:'json',
                data:{
                    chart:chart,
                    title:title,
                },
                success:function(data,status){
                    if ( data.status != 0 ) {
                        $("#do-save-chart").text('Success');
                        $("#do-save-chart").attr('class','btn btn-warning');
                        setTimeout(" $('#myModal').modal('hide') ",1500);
                    } else {
                        $('#do-save-chart').text('Fail');
                        $('#do-save-chart').attr('class','btn btn-danger');
                        setTimeout(" $('#do-save-chart').text('Submit'); $('#do-save-chart').attr('class','btn btn-success');",1500);
                    }
                }
        });
    }); 


    $("#save-chart-btn").click(function(){
        $('#myModal').modal('show'); 
    });

        

});
