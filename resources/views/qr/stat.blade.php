@extends('layout')
@section('title', $title)
@section('style')
@stop
@section('body')
    <blockquote class="layui-elem-quote">{{$title}}
    </blockquote>
    <div id="container" style="width: 90%; height: 400px; margin: 0 auto"></div>
@stop
@section('script')
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script>
        $(function () {
            var title = {
                text: '渠道码的统计'
            };
            var subtitle = {
                text: ''
            };
            var xAxis = {
                categories: ['一月', '二月', '三月', '四月', '五月', '六月'
                    ,'七月', '八月', '九月', '十月', '十一月', '十二月']
            };
            var yAxis = {
                title: {
                    text: '关注者 (数量)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            };

            var tooltip = {
                valueSuffix: '个用户'
            };

            var legend = {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            };

            var series =  [
                {
                    name: '关注数量'
                }
            ];

            var json = {};
            $.post("{{route('qr.stat')}}", {id:'{{$id}}'}, function (rev) {
                xAxis.categories = rev.data.date;
                series[0].data = rev.data.data;
                console.log(xAxis);
                console.log(series);
                json.title = title;
                json.subtitle = subtitle;
                json.xAxis = xAxis;
                json.yAxis = yAxis;
                json.tooltip = tooltip;
                json.legend = legend;
                json.series = series;
                $('#container').highcharts(json);
            });
        });
    </script>
@stop