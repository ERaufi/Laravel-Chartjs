@extends('layout.layout')
@section('content')
    <div class="container">
        <x-filters :showContries='true'/>
        <div class="card">
            <div class="card-body">
                <canvas id="bubbleChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>

    <script>
        let chart;

        function getData() {
            $.ajax({
                url: 'bubble-chart-data',
                method: 'GET',
                dataType: 'json',
                data: {
                    'country': $("#country").val(),
                    'from': $("#from").val(),
                    'to': $("#to").val(),
                },
                success: function(data) {
                    const casesData = data.cases;
                    const deathsData = data.deaths;
                    const recoveriesData = data.recoveries;
                    const bubbleSizes = data.bubbleSizes;

                    const bubbleData = bubbleSizes.map(function(size, index) {
                        return {
                            x: casesData[index],
                            y: deathsData[index],
                            r: size //Set the radius size for each bubble
                        }
                    });

                    const ctx = document.getElementById('bubbleChart').getContext('2d');

                    // check if the chart is already exist if exists then we have to destroy it first

                    if (chart) {
                        chart.destroy();
                    }
                    chart = new Chart(ctx, {
                        type: 'bubble',
                        data: {
                            datasets: [{
                                lable: 'Cases vs Deaths',
                                data: bubbleData,
                                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                hoverBackgroundColor: 'rgba(234, 99, 132, 0.9)',
                                borderWidth: 1,
                                hoverBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    })
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }

        $(function() {
            getData();
        })
    </script>
@endsection
