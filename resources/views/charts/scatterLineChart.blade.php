@extends('layout.layout')

@section('content')
    <div class="container">
        <x-filters :showContries='true' />
        <div class="card">
            <div class="card-body">
                <canvas id="scatter-line-chart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

    <script>
        let chart;

        function getData() {
            $.ajax({
                url: "/scatter-line-chart-data",
                method: "GET",
                data: {
                    'country': $("#country").val(),
                    'from': $("#from").val(),
                    'to': $("#to").val(),
                },
                success: function(response) {
                    var chartData = response;
                    var datasets = [{
                            label: 'Confirmed',
                            data: chartData.map(chart => ({
                                x: new Date(chart.date),
                                y: chart.Confirmed
                            })),
                            borderColor: 'red',
                            backgroundColor: 'transparent',
                            pointRadius: 5,
                            pointBackgroundColor: 'red',
                            showLine: true,
                            borderWidth: 2,
                        },
                        {
                            label: 'Deaths',
                            data: chartData.map(chart => ({
                                x: new Date(chart.date),
                                y: chart.Deaths
                            })),
                            borderColor: 'blue',
                            backgroundColor: 'transparent',
                            pointRadius: 5,
                            pointBackgroundColor: 'blue',
                            showLine: true,
                            borderWidth: 2,
                        },
                        {
                            label: 'Recovered',
                            data: chartData.map(chart => ({
                                x: new Date(chart.date),
                                y: chart.Recovered
                            })),
                            borderColor: 'green',
                            backgroundColor: 'transparent',
                            pointRadius: 5,
                            pointBackgroundColor: 'green',
                            showLine: true,
                            borderWidth: 2,
                        },
                        {
                            label: 'Active',
                            data: chartData.map(chart => ({
                                x: new Date(chart.date),
                                y: chart.Active
                            })),
                            borderColor: 'orange',
                            backgroundColor: 'transparent',
                            pointRadius: 5,
                            pointBackgroundColor: 'orange',
                            showLine: true,
                            borderWidth: 2,
                        }
                    ];

                    var ctx = document.getElementById('scatter-line-chart').getContext('2d');

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'scatter',
                        data: {
                            datasets
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: 'day'
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                        },
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        $(document).ready(function() {
            getData();
        });
    </script>
@endsection
