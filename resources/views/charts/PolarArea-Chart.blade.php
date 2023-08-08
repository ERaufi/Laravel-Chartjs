@extends('layout.layout')

@section('content')
    <div class="container">
        <x-filters :showContries='true' />
        <div class="card">
            <div class="card-body">
                <canvas id="polarAreaChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>


    <!-- Pie Chart Script -->
    <script>
        let chart;

        function getData() {
            $.ajax({
                url: '/polar-area-chart-data',
                method: 'GET',
                dataType: 'json',
                data: {
                    'country': $("#country").val(),
                    'from': $("#from").val(),
                    'to': $("#to").val(),
                },
                success: function(data) {
                    const labels = data.labels;
                    const casesData = data.casesData;
                    console.log(data);
                    const ctx = document.getElementById('polarAreaChart').getContext('2d');

                    if (chart) {
                        chart.destroy();
                    }
                    chart = new Chart(ctx, {
                        type: 'polarArea',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: casesData,
                                backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(75, 192, 192, 0.7)',
                                    'rgba(54, 162, 235, 0.7)'
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    });
                },
                error: function(error) {
                    console.error('Error fetching polar area chart data:', error);
                }
            });

        }
        $(document).ready(function() {
            getData();
        });
    </script>
@endsection
