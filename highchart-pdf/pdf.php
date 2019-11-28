<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<?php
    
    $year=array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $data=array(29.9, 71.5, 106.4, 129.2, 144.0, 176.0,
                135.6, 148.5, 216.4, 194.1, 95.6, 54.4);
?>
    <div id="container1"></div>

    <script type="text/javascript">
    /**
    * Create a global getSVG method that takes an array of charts as an
    * argument
    */
    Highcharts.getSVG = function (charts) {
        var svgArr = [],
            top = 0,
            width = 0;

        Highcharts.each(charts, function (chart) {
            var svg = chart.getSVG(),
                // Get width/height of SVG for export
                svgWidth = +svg.match(
                    /^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/
                )[1],
                svgHeight = +svg.match(
                    /^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/
                )[1];

            svg = svg.replace(
                '<svg',
                '<g transform="translate(0,' + top + ')" '
            );
            svg = svg.replace('</svg>', '</g>');

            top += svgHeight;
            width = Math.max(width, svgWidth);

            svgArr.push(svg);
        });

        return '<svg height="' + top + '" width="' + width +
            '" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
            svgArr.join('') + '</svg>';
    };

    /**
    * Create a global exportCharts method that takes an array of charts as an
    * argument, and exporting options as the second argument
    */
    Highcharts.exportCharts = function (charts, options) {

        // Merge the options
        options = Highcharts.merge(Highcharts.getOptions().exporting, options);

        // Post to export server
        Highcharts.post(options.url, {
            filename: options.filename || 'chart',
            type: options.type,
            width: options.width,
            svg: Highcharts.getSVG(charts)
        });
    };

    var chart1 = Highcharts.chart('container1', {

        chart: {
            height: 600
        },

        title: {
            text: 'auto download highchart'
        },
        credits: {
            enabled: false
        },

        xAxis: {

            categories: <?php echo json_encode($year)?>
        },
         yAxis: {
                title: {
                    text: 'Values'
                },
                resize: {
                    enabled: true
                },
                startOnTick: false,
                minPadding: 0.01,
                endOnTick: false,
                maxPadding: 0.01,
                max:250,
                min:0
                },

        series:[{
            name: 'Series',
            data: <?php echo json_encode($data); ?>,
            showInLegend: true
        }],

        exporting: {
            enabled: false // hide button
        }

    });

    Highcharts.exportCharts([chart1], {
            type: 'application/pdf'
    });
    </script>

