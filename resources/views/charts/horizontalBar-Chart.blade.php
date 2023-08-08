@extends('layout.layout')

@section('content')
    <div class="container">
        <x-filters :showContries='false' />
        <div class="card">
            <div class="card-body">
                <canvas id="horizontalBarChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>

    <script>
        let chart;

        function getData() {
            $.ajax({
                url: '/horizontal-bar-chart-data',
                method: 'GET',
                dataType: 'json',
                data: {
                    'from': $("#from").val(),
                    'to': $("#to").val(),
                },
                success: function(data) {
                    console.log(data);
                    const countries = data.countries;
                    const casesData = data.cases;

                    const ctx = document.getElementById('horizontalBarChart').getContext('2d');
                    if (chart) {
                        chart.destroy();
                    }
                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: countries,
                            datasets: [{
                                label: 'Cases',
                                data: casesData,
                                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    });
                },
                error: function(error) {
                    console.error('Error fetching horizontal bar chart data:', error);
                }
            });
        }
        $(document).ready(function() {
            getData();
        });
    </script>
@endsection
