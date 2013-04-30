    
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

$(document).ready(function(){
   
    // 组装数据
    var draw_data = Array();
    var i = 0;
    $(".column-field-v").each(function(){
        draw_data[i] = Array();
        if ( i == 0 ) {
            draw_data[i][0] = $("#y_name").attr('placeholder');
            i++;
            return true;
        }
        draw_data[i][0] = $(this).val();
        i++;
    });

    var i = 0;
    var row = 0;
    $(".data-field-v").each(function(){
        var now_row = $(this).attr('row');
        if ( row != now_row ) {
            row = now_row;
            i = 0;
            draw_data[i][row] = $(this).attr('row_name');
            i ++;
            return true;
        }
        draw_data[i][row] = parseFloat($(this).val());
        i++;
    });

    console.log(draw_data);
    var title = $("#chart-title").val();

    var data = new google.visualization.arrayToDataTable(draw_data);

    // Set chart options
    
    var options = {'title':title,
        'width':800,
        'height':600,
        vAxis: {title: $("#y_name").attr('placeholder')},
        hAxis: {title: $("#x_name").attr('placeholder')},
    };

    // Instantiate and draw our chart, passing in some options.
    var chart_type = $("#chart-type-v").val(); 

    if ( chart_type == 'bar' ) {
        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    } else if ( chart_type == 'pie' ) {
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    } else if ( chart_type == 'line' ) {
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    } else if ( chart_type == 'line_func' ) {
        options = {'title':title,
            'width':800,
            'height':600,
            curveType: "function",
            vAxis: {title: $("#y_name").attr('placeholder')},
            hAxis: {title: $("#x_name").attr('placeholder')},
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    } else if ( chart_type == 'area' ) {
        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    } else if ( chart_type == 'column' ) {
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
    } else {
        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    }

    chart.draw(data, options);
    
});
