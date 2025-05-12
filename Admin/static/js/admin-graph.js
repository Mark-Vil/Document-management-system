$(document).ready(function() {
  $.ajax({
    url: 'php-functions/college-highest-total.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success) {
            var highestTotals = response.data;
            var labels = highestTotals.map(function(item) { return item.college_name; });
            var data = highestTotals.map(function(item) { return item.total_documents === 0 ? 0.1 : item.total_documents; });
            var backgroundColors = highestTotals.map(function(item) { return item.total_documents === 0 ? 'rgba(255, 0, 0, 0.2)' : 'rgba(204, 0, 41, 0.2)'; });

            var ctx = document.getElementById('college-total').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Documents Per College',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: 'rgba(204, 0, 41, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            ticks: {
                                display: labels.length <= 4
                            }
                        },
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
        } else {
            console.error('Error:', response.message);
        }
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
    }
});
  $.ajax({
    url: 'php-functions/faculty-highest-total.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success) {
            var highestTotals = response.data;
            var labels = highestTotals.map(function(item) { return item.faculty_name; });
            var data = highestTotals.map(function(item) { return item.total_documents === 0 ? 0.1 : item.total_documents; });
            var backgroundColors = highestTotals.map(function(item) { return item.total_documents === 0 ? 'rgba(255, 0, 0, 0.2)' : 'rgba(204, 0, 41, 0.2)'; });

            var ctx = document.getElementById('faculty-total').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Documents Per Faculty',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: 'rgba(204, 0, 41, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            ticks: {
                                display: labels.length <= 5 // Hide labels if more than 5
                            }
                        },
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
        } else {
            console.error('Error:', response.message);
        }
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
    }
});
});
