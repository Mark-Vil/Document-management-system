document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.submitted-card').addEventListener('click', function() {
        window.location.href = 'status.php';
    });
    document.querySelector('.accepted-card').addEventListener('click', function() {
        window.location.href = 'mysubmission.php';
    });
    document.querySelector('.pending-card').addEventListener('click', function() {
        window.location.href = 'rejected.php';
    });
});
$(document).ready(function() {
    // Fetch years and populate the dropdown
    $.ajax({
        url: 'php-functions/fetch-yearselection.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var $yearFilterchart = $('#yearFilterchart');
            $yearFilterchart.empty();
            $yearFilterchart.append('<option>Choose year...</option>');

            // Populate dropdown with years
            $.each(response, function(index, year) {
                $yearFilterchart.append('<option value="' + year + '">' + year + '</option>');
            });

            // Automatically select the current year
            var currentYear = new Date().getFullYear();
            $yearFilterchart.val(currentYear).change();
        },
        error: function() {
            displayError('Failed to fetch years.');
        }
    });

    // Function to update the chart data
    function updateUploadsChart(data) {
        var formattedData = [];
        for (var i = 1; i <= 12; i++) {
            formattedData.push(data[i] || 0);
        }
        uploadsChart.data.datasets[0].data = formattedData;
        uploadsChart.update();
    }

    // Function to update the latest uploads
    function updateLatestUploads(data) {
        var $latestUploads = $('#latest-uploads');
        $latestUploads.empty();
        $.each(data, function(index, upload) {
            $latestUploads.append(
                '<div class="activity-item d-flex">' +
                '<i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>' +
                '<div class="activity-content">' +
                '<a href="#" class="view-pdf" data-file-path="' + upload.file_path + '">' + upload.research_title + '</a>' +
                '</div>' +
                '</div>'
            );
        });

        // Add click event listener to the links
        $('.view-pdf').on('click', function(e) {
            e.preventDefault();
            var filePath = $(this).data('file-path');
            window.location.href = 'view-pdf.html?file=' + encodeURIComponent(filePath);
        });
    }

    // Function to display error messages
    function displayError(message) {
        var $errorContainer = $('#error-container');
        $errorContainer.text(message).show();
    }

    // Function to clear error messages
    function clearError() {
        var $errorContainer = $('#error-container');
        $errorContainer.hide().text('');
    }

    // Handle year selection change
    $('#yearFilterchart').on('change', function() {
        var selectedYear = $(this).val();
        clearError(); // Clear any previous error messages

        if (selectedYear !== 'Choose year...') {
            // Perform AJAX request to fetch uploads per month for the selected year
            $.ajax({
                url: 'php-functions/fetch-uploads-per-month.php',
                method: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        updateUploadsChart(response.data);
                    } else {
                        displayError(response.message);
                    }
                },
                error: function() {
                    displayError('Failed to fetch uploads per month.');
                }
            });

            // Perform AJAX request to fetch accepted submissions count for the selected year
            $.ajax({
                url: 'php-functions/fetch-accepted-submissions.php',
                method: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var acceptedCount = response.accepted_count;
                        $('#accepted-count').text(acceptedCount);
                    } else {
                        displayError(response.message);
                    }
                },
                error: function() {
                    displayError('Failed to fetch accepted submissions count.');
                }
            });

            $.ajax({
                url: 'php-functions/fetch-pending-submissions.php',
                method: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var pendingCount = response.pending_count;
                        $('#pending-count').text(pendingCount);
                    } else {
                        displayError(response.message);
                    }
                },
                error: function() {
                    displayError('Failed to fetch accepted submissions count.');
                }
            });

            // Perform AJAX request to fetch submitted count for the selected year
            $.ajax({
                url: 'php-functions/fetch-submitted-count.php',
                method: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var submittedCount = response.submitted_count;
                        $('#submitted-count').text(submittedCount);
                    } else {
                        displayError(response.message);
                    }
                },
                error: function() {
                    displayError('Failed to fetch submitted count.');
                }
            });

            // Perform AJAX request to fetch latest uploads for the selected year
            $.ajax({
                url: 'php-functions/fetch-latest-uploads.php',
                method: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        updateLatestUploads(response.data);
                    } else {
                        displayError(response.message);
                    }
                },
                error: function() {
                    displayError('Failed to fetch latest uploads.');
                }
            });
        }
    });


      function initializeMetricsChart() {
        $.ajax({
            url: 'php-functions/fetch-research-metrics.php',
            method: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                const ctx = document.getElementById('metricsChart').getContext('2d');
                let myChartMetrics;
    
                // Check if there's any data
                const hasData = data && data.length > 0;
    
                // Function to adjust data for display
                const adjustData = (value) => value === 0 ? 0.1 : value;
    
                // Prepare labels and datasets
                const labels = hasData ? data.map(item => item.title) : ['No Data', 'No Data', 'No Data'];
                const datasets = hasData ? [
                    {
                        label: 'Downloads',
                        data: data.map(item => adjustData(item.downloads)),
                        backgroundColor: '#36A2EB',
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    },
                    {
                        label: 'Views',
                        data: data.map(item => adjustData(item.views)),
                        backgroundColor: '#4BC0C0',
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    }
                ] : [
                    {
                        label: 'No Data',
                        data: [Math.random() * 10, Math.random() * 10, Math.random() * 10],
                        backgroundColor: 'rgba(200, 200, 200, 0.8)',
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    },
                    {
                        label: 'No Data',
                        data: [Math.random() * 10, Math.random() * 10, Math.random() * 10],
                        backgroundColor: 'rgba(180, 180, 180, 0.8)',
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    }
                ];
    
                if (myChartMetrics) {
                    myChartMetrics.destroy(); // Destroy previous chart instance if it exists
                }
    
                myChartMetrics = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                display: hasData && labels.length <= 2 // Hide legend if no data or more than 2 labels
                            },
                            title: {
                                display: false
                            },
                            tooltip: {
                                enabled: hasData, // Disable tooltips if no data
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + (context.raw === 0.1 ? 0 : context.raw); // Show 0 in tooltip
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0,  // Prevent rotation
                                    autoSkip: false,
                                    display: hasData && labels.length <= 2 // Hide ticks if no data or more than 2 labels
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [2, 4]
                                },
                                ticks: {
                                    display: hasData, // Hide ticks if no data
                                    stepSize: 1 // Ensure steps are 1 unit
                                }
                            }
                        },
                        plugins: [{
                            beforeDraw: function(chart) {
                                if (!hasData) {
                                    var ctx = chart.ctx;
                                    var width = chart.width;
                                    var height = chart.height;
                                    chart.clear();
                                    ctx.save();
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    ctx.font = '16px normal Helvetica';
                                    ctx.fillText('No data available', width / 2, height / 2);
                                    ctx.restore();
                                }
                            }
                        }]
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching metrics:', error);
            }
        });
    }
    
    initializeMetricsChart();
    
    
    
    
    
});
