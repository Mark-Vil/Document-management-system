document.addEventListener('DOMContentLoaded', function() {
    
    function fetchYears(callback) {
        $.ajax({
            url: 'php-functions/fetch-years.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var yearFilter = $('#yearFilterchart');
                yearFilter.empty();
                yearFilter.append('<option selected disabled>Choose year...</option>');

                if (response.error) {
                    alert(response.error);
                } else if (response.length > 0) {
                    response.forEach(function(item) {
                        var option = '<option value="' + item.year + '">' + item.year + '</option>';
                        yearFilter.append(option);
                    });

                    // Set the current year as selected
                    var currentYear = new Date().getFullYear();
                    yearFilter.val(currentYear);

                    // Execute the callback function
                    if (callback) {
                        callback(currentYear);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching years: ' + error);
            }
        });
    }

    var ctx = document.getElementById('totalpaperChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Total Archived Per Year',
                    data: [],
                    backgroundColor: [],
                    borderColor: 'rgba(204, 0, 41, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var value = context.raw;
                            return value === 0.1 ? '0.0' : value;
                        },
                        title: function(context) {
                            return context[0].label;
                        }
                    }
                }
            }
        }
    });
    
    function fetchChartData(year) {
        fetch(`php-functions/fetch-chart-data.php?year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                var labels = data.map(item => item.college_name);
                var submissions = data.map(item => item.total_submissions);
                var adjustedSubmissions = data.map(item => item.total_submissions === 0 ? 0.1 : item.total_submissions); // Setting a small value for zero submissions
                var backgroundColors = submissions.map(submission => submission === 0 ? 'rgba(255, 0, 0, 0.2)' : 'rgba(204, 0, 41, 0.2)');
                
                chart.data.labels = labels;
                chart.data.datasets[0].data = adjustedSubmissions;
                chart.data.datasets[0].backgroundColor = backgroundColors;
                chart.update();
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }
    
    

    document.getElementById('yearFilterchart').addEventListener('change', function() {
        var selectedYear = this.value;
        if (selectedYear !== 'Choose year...') {
            fetchChartData(selectedYear);
        }
    });


    // Fetch years and initial data for the default year
    fetchYears(function(initialYear) {
        if (initialYear !== 'Choose year...') {
            fetchChartData(initialYear);
        }
    });
});