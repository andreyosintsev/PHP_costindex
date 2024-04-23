function drawChart(graphData, elementID, yAxis) {
    console.log(graphData);

    if (!elementID) {
        console.error('Ошибка: отсутствует ID блока для вывода графика');
        
        return;
    }

    const graph = document.getElementById(elementID);
    if (!graph) {
        console.error('Ошибка HTML: отсутствует блок для вывода графика');
        
        return;
    }

    const data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Дата');
    data.addColumn('number', yAxis);
    data.addRows(graphData);
            
    var options = {'title':'',
        'height': 300,
        'legend': {
            'position': 'none'
        },
        'titleTextStyle': {
            'fontName' : 'Georgia',
            'fontSize' :  20,
            'bold': false
        },
        hAxis: {
            title: 'Дата'
        },
        vAxis: {
            title: 'Индекс, р.',
            textPosition: 'in'},
        chartArea: {width: '100%'},
    };

    const chart = new google.visualization.AreaChart(graph);
    chart.draw(data, options);
}