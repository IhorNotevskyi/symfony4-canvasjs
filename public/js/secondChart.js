$(document).ready(function () {
    var idCanvas = $('.canvas-container').attr('id');
    var splitId = idCanvas.split("_");
    var id = splitId[1];

    var dataPoints = [];

    var chart = new CanvasJS.Chart("secondChartContainer_"  + id, {
        animationEnabled: true,
        theme: "light2",
        zoomEnabled: true,
        axisY: {
            titleFontSize: 24,
            suffix: " грн",
            margin: 15
        },
        axisX: {
            valueFormatString: "DD.MM.YYYY",
            margin: 10
        },
        title: {
            text: "График изменения цен",
            fontSize: 0,
            padding: {
                top: 20
            }
        },
        data: [{
            type: "stepLine",
            includeZero: false,
            yValueFormatString: "#,##0.00 грн",
            xValueFormatString: "DD.MM.YYYY",
            dataPoints: dataPoints
        }]
    });

    function addData(data) {
        var priceAndDate = [];

        for (var i in data) {
            if (data[i].error) {
                var error = data[i].error;
                break;
            }

            priceAndDate[i] = [data[i].date, data[i].price];
        }

        if (error) {
            $('#secondChartContainer_' + id).hide();
            $('#secondChartError_' + id).append('<span>' + error + '</span>').css('margin', '30px 0 50px');
        } else {
            var abc = {intervals: priceAndDate};
            var dps = abc.intervals;

            for (i = 0; i < dps.length; i++) {
                dataPoints.push({
                    x: new Date(dps[i][0]),
                    y: dps[i][1]
                });
            }

            chart.render();
        }
    }

    $.getJSON('/second-chart/' + id, 'html', addData);
});

if ($('.canvas-container .canvasjs-chart-credit')) {
    setInterval(function () {
        $('.canvas-container .canvasjs-chart-credit').remove();
    }, 1);
}