@extends('layout.layout')

@section('content')
    <div class="container">
        <x-filters />
        <div class="card">
            <div class="card-body">
                <canvas id="doughnutChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>

    <!-- Bar Chart Script -->
    <script>
        let chart;

        function getData() {
            $.ajax({
                url: '/doughnut-chart-data',
                method: 'GET',
                dataType: 'json',
                data: {
                    'country': $("#country").val(),
                    'from': $("#from").val(),
                    'to': $("#to").val(),
                },
                success: function(data) {
                    const ctx = document.getElementById('doughnutChart').getContext('2d');
                    if (chart) {
                        chart.destroy();
                    }
                    chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Confirmed', 'Recovered', 'Active'],
                            datasets: [{
                                data: data.data,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(75, 192, 192, 0.7)',
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
                    console.error('Error fetching doughnut chart data:', error);
                }
            });
        }
        $(document).ready(function() {
            getData();
        });
    </script>
@endsection
