$(document).ready(function() {
    // Fetch years for the year filter
    function fetchYears() {
        $.ajax({
            url: 'php-functions/fetch-years.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var yearFilter = $('#yearFilterchart');
                yearFilter.empty();
                yearFilter.append('<option value="" selected disabled>Choose year...</option>');
    
                if (response.length > 0) {
                    // Sort years in descending order (just in case)
                    response.sort((a, b) => b - a);
                    
                    // Add all years to dropdown
                    response.forEach(function(year) {
                        var option = '<option value="' + year + '">' + year + '</option>';
                        yearFilter.append(option);
                    });
    
                    // Select latest year and fetch data
                    var latestYear = response[0]; // First year is latest due to DESC order
                    yearFilter.val(latestYear);
                    
                    // Fetch all data with latest year
                    fetchDashboardData(latestYear);
                    fetchYearSubmissions(latestYear);
                    fetchdownloadedmost(latestYear);
                    fetchMostViewed(latestYear);
                    fetchFacultyHighestTotal(latestYear);
                } else {
                    alert('No years found.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching years: ' + error);
            }
        });
    }

    var facultyChart; // Declare the chart variable outside the function

    function fetchFacultyHighestTotal(year) {
        $.ajax({
            url: 'php-functions/faculty-highest-total.php',
            type: 'GET',
            data: { year: year },
            dataType: 'json',
            success: function(response) {
                var ctx = document.getElementById('highest-faculty').getContext('2d');
    
                if (facultyChart) {
                    facultyChart.destroy(); // Destroy the previous chart instance
                }
    
                if (response.success && response.data.length > 0) {
                    var highestTotals = response.data;
                    var labels = highestTotals.map(function(item) { return item.faculty_name; });
                    var data = highestTotals.map(function(item) { return item.total_documents === 0 ? 0.1 : item.total_documents; });
                    var backgroundColors = highestTotals.map(function(item) { return item.total_documents === 0 ? 'rgba(255, 0, 0, 0.2)' : 'rgba(204, 0, 41, 0.2)'; });
    
                    facultyChart = new Chart(ctx, {
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
                    // Display placeholder chart with 10 bars labeled "No Data" and random heights
                    var placeholderLabels = Array(10).fill('No Data');
                    var placeholderData = Array.from({ length: 10 }, () => Math.floor(Math.random() * 10) + 1); // Random heights between 1 and 10
                    var placeholderColors = Array(10).fill('rgba(128, 128, 128, 0.2)'); // Gray color
                    var placeholderBorderColors = Array(10).fill('rgba(128, 128, 128, 1)');
    
                    facultyChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: placeholderLabels,
                            datasets: [
                                {
                                    label: 'Total Documents Per Faculty',
                                    data: placeholderData,
                                    backgroundColor: placeholderColors,
                                    borderColor: placeholderBorderColors,
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            scales: {
                                x: {
                                    ticks: {
                                        display: true
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        display: true // Show y-axis ticks
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    enabled: false // Disable tooltips
                                }
                            }
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }
    // Fetch dashboard data
    function fetchDashboardData(selectedYear) {
        $.ajax({
            url: 'php-functions/college-dashboard-data.php',
            type: 'POST',
            data: { college_code: collegeCode, year: selectedYear },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Update total documents
                    $('#totalDocuments').text(response.total_documents);

                    // Update total accepted
                    $('#totalAccepted').text(response.total_accepted);

                    // Update total ongoing
                    $('#totalOngoing').text(response.total_ongoing);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching dashboard data: ' + error);
            }
        });
    }

    // Fetch year submissions data
    function fetchYearSubmissions(selectedYear) {
        $.ajax({
            url: 'php-functions/fetch-year-submissions.php',
            type: 'POST',
            data: { college_code: collegeCode, year: selectedYear },
            dataType: 'json',
            success: function(response) {
                var labels = [];
                var data = [];
                var backgroundColor = [];
                var borderColor = [];

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        labels.push(item.year);
                        data.push(item.total_documents);
                        backgroundColor.push('rgba(204, 0, 41, 0.2)');
                        borderColor.push('rgba(204, 0, 41, 1)');
                    });
                } else {
                    // If no data, show the placeholder with 10 bars with different heights
                    for (var i = 0; i < 10; i++) {
                        labels.push('No data');
                        data.push(Math.random() * 10); // Generate random values for different heights
                        backgroundColor.push('rgba(200, 200, 200, 0.2)');
                        borderColor.push('rgba(200, 200, 200, 1)');
                    }
                }

                updateYearSubmissionChart(labels, data, backgroundColor, borderColor);
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching year submissions data: ' + error);
            }
        });
    }

    // Update year submission chart
    function updateYearSubmissionChart(labels, data, backgroundColor, borderColor) {
        var ctx = document.getElementById('yearsubmissionChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# Total Document Per Year',
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: '# Total Document Per Year'
                    }
                }
            },
            plugins: [{
                beforeDraw: function(chart) {
                    if (chart.data.datasets[0].data.length === 10 && chart.data.datasets[0].data[0] < 1) {
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
        });
    }


// Fetch most viewed research document
function fetchMostViewed(selectedYear) {
    console.log('fetchMostViewed called with year:', selectedYear); // Debugging log

    $.ajax({
        url: 'php-functions/most-viewed.php',
        type: 'POST',
        data: { college_code: collegeCode, year: selectedYear },
        dataType: 'json',
        success: function(response) {
            console.log('AJAX success response:', response); // Debugging log
    
            if (response.status === 'success') {
                var viewedData = response.data;
                if (viewedData && viewedData.length > 0) {
                    var mostViewedHtml = '';
                    viewedData.forEach(function(item) {
                        mostViewedHtml += `
                            <div class="most-viewed-item d-flex">
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="most-viewed-content">
                                    <a href="view-pdf.html?file=${encodeURIComponent(item.file_path)}" target="_blank">${item.research_title}</a>
                                </div>
                            </div>
                        `;
                    });
                    $('.most-viewed').html(mostViewedHtml);
                } else {
                    $('.most-viewed').html('<p>No data found.</p>');
                }
            } else {
                console.error('Error fetching most viewed:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching most viewed:', error);
        }
    });
}

// Fetch most downloaded research document
function fetchdownloadedmost(selectedYear) {
    console.log('fetchMostDownloaded called with year:', selectedYear); // Debugging log

    $.ajax({
        url: 'php-functions/most-downloaded.php',
        type: 'POST',
        data: { college_code: collegeCode, year: selectedYear },
        dataType: 'json',
        success: function(response) {
            console.log('AJAX success response:', response); // Debugging log
    
            if (response.status === 'success') {
                var downloadedData = response.data;
                if (downloadedData && downloadedData.length > 0) {
                    var mostDownloadedHtml = '';
                    downloadedData.forEach(function(item) {
                        mostDownloadedHtml += `
                            <div class="most-downloaded-item d-flex">
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="most-downloaded-content">
                                    <a href="view-pdf.html?file=${encodeURIComponent(item.file_path)}" target="_blank">${item.research_title}</a>
                                </div>
                            </div>
                        `;
                    });
                    $('#downloaded-most').html(mostDownloadedHtml);
                } else {
                    $('#downloaded-most').html('<p>No data found.</p>');
                }
            } else {
                console.error('Error fetching most downloaded:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching most downloaded:', error); // Debugging log
        }
    });
}


// Fetch years and select the current year by default
fetchYears();

// Fetch data when the year filter changes
$('#yearFilterchart').on('change', function() {
    var selectedYear = $(this).val();
    console.log('Year filter changed:', selectedYear); // Debugging log
    fetchDashboardData(selectedYear);
    fetchYearSubmissions(selectedYear);
    fetchdownloadedmost(selectedYear);
    fetchMostViewed(selectedYear);
    fetchFacultyHighestTotal(selectedYear);
});



});