@extends('layout.layout')
@section('content')
    <div class="container">
        <x-filters :showContries='true' />

        <div class="card">
            <div class="card-body">
                {{-- This Canvas is for the Chart --}}
                <canvas id="realTimeChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>
    <!-- Pie Chart Script -->
    @vite('resources/js/app.js')
    <script>
        let chart;
        let labels;
        let datas;

        function getData() {
            $.ajax({
                url: '/real-time-chart-data',
                method: 'GET',
                dataType: 'json',
                data: {
                    'country': $("#country").val(),
                },
                success: function(data) {
                    labels = data.labels;
                    datas = data.Confirmed;
                    const ctx = document.getElementById('realTimeChart').getContext('2d');
                    console.log(data);
                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `COVID-19 Statistics for ${data.country}`,
                                data: datas,
                                backgroundColor: ['rgb(255,99,132)'],
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                },
                error: function(error) {
                    console.log(error);
                }
            })
        }

        $(function() {
            getData();
        });

        setTimeout(() => {
            window.Echo.channel('addedData')
                .listen('.App\\Events\\addedDataEvent', (e) => {
                    console.log(e);

                    chart.data.labels.push(e.label);
                    chart.data.datasets[0].data.push(e.data);


                    chart.data.labels.shift();
                    chart.data.datasets[0].data.shift();


                    chart.update();
                })
        }, 1000);
    </script>
@endsection
