    
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

$(document).ready(function(){

    var column = Array();
    var i = 0;
    $(".column-field").each(function(){
        column[i] = $(this).val();
        i++;
    });

    var draw_data = Array();
    var i = 0;
    $(".data-field-v.row1").each(function(){
         
        draw_data[i] = Array();
        draw_data[i] = [column[i],parseFloat($(this).val())];
        i++;
    });

    var title = $("#chart-title").val();

    var data = new google.visualization.DataTable();

    // Create the data table.
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');

    data.addRows(draw_data);

    // Set chart options
    var options = {'title':title,
        'width':800,
        'height':600};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);

});
