$(document).ready(function() {
    
    function fetchYears(callback) {
        $.ajax({
            url: '../Admin/php-functions/fetch-years.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var yearFilter = $('#yearSelect');
                yearFilter.empty();
                yearFilter.append('<option value="" selected>All Years</option>');

                if (response.error) {
                    alert(response.error);
                } else if (response.length > 0) {
                    response.forEach(function(item) {
                        var option = '<option value="' + item.year + '">' + item.year + '</option>';
                        yearFilter.append(option);
                    });

                    // Execute the callback function
                    if (callback) {
                        callback();
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching years: ' + error);
            }
        });
    }

    function fetchDepartments() {
        $.ajax({
            url: 'php-functions/fetch-faculty-documents.php',
            method: 'GET',
            dataType: 'json',
            success: function(departments) {
                var departmentSelect = $('#departmentSelect');
                departmentSelect.empty();
                departmentSelect.append('<option selected disabled>Select...</option>');

                if (departments.length > 0) {
                    var highestTotal = 0;
                    var highestDepartmentCode = '';

                    departments.forEach(function(department) {
                        var option = '<option value="' + department.department_code + '">' + department.department_name + '</option>';
                        departmentSelect.append(option);

                        if (department.total_archives > highestTotal) {
                            highestTotal = department.total_archives;
                            highestDepartmentCode = department.department_code;
                        }
                    });

                    if (highestDepartmentCode) {
                        departmentSelect.val(highestDepartmentCode);
                        fetchResearchData(); // Fetch research data for the selected department
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching the department data: ' + error);
            }
        });
    }
    var ctxAccepted = document.getElementById('acceptedSubmissionsChart').getContext('2d');
    var ctxPending = document.getElementById('pendingSubmissionsChart').getContext('2d');
    var departmentName = '';

    
    function displayNoDataText(chart) {
        var ctx = chart.ctx;
        var width = chart.width;
        var height = chart.height;
        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = '16px normal Helvetica';
        ctx.fillText('No data available', width / 2, height / 2);
        ctx.restore();
    }

    
    var ctxAccepted = document.getElementById('acceptedSubmissionsChart').getContext('2d');
    var myChartAccepted = new Chart(ctxAccepted, {
        type: 'bar',
        data: {
            labels: [], // Placeholder for years
            datasets: [{
                label: '# Accepted Submissions Per Year',
                data: [], // Placeholder for data
                backgroundColor: 'rgba(204, 0, 41, 0.2)',
                borderColor: 'rgba(204, 0, 41, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Enable responsiveness
            maintainAspectRatio: true, // Maintain aspect ratio
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
                        title: function(tooltipItems) {
                            return 'Year: ' + tooltipItems[0].label;
                        },
                        label: function(tooltipItem) {
                            return 'Faculty: ' + departmentName + ', Total Papers: ' + tooltipItem.raw;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Accepted Submissions Per Year'
                }
            }
        },
        plugins: [{
            beforeDraw: function(chart) {
                if (chart.data.datasets[0].data.length === 0 || chart.data.datasets[0].data.every(item => item === 0)) {
                    // Draw the placeholder bars
                    var ctx = chart.ctx;
                    chart.clear();
                    chart.data.labels = [];
                    chart.data.datasets[0].data = [];
                    for (var i = 0; i < 10; i++) {
                        chart.data.labels.push('No data');
                        chart.data.datasets[0].data.push(Math.random() * 10); // Generate random values for different heights
                    }
                    chart.data.datasets[0].backgroundColor = chart.data.datasets[0].data.map(() => 'rgba(200, 200, 200, 0.2)');
                    chart.data.datasets[0].borderColor = chart.data.datasets[0].data.map(() => 'rgba(200, 200, 200, 1)');
                    chart.update();
    
                    // Draw the "No data available" text after a delay
                    setTimeout(function() {
                        ctx.save();
                        var width = chart.width;
                        var height = chart.height;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = '16px normal Helvetica';
                        ctx.fillText('No data available', width / 2, height / 2);
                        ctx.restore();
                    }, 1000); // Delay of 1 second
                }
            }
        }]
    });

// Set canvas size
var canvas = document.getElementById('pendingSubmissionsChart');
canvas.width = 300;
canvas.height = 300;

var myChartPending = new Chart(ctxPending, {
    type: 'pie',
    data: {
        labels: [], // Placeholder for department names
        datasets: [{
            label: '# Pending Submissions',
            data: [], // Placeholder for data
            backgroundColor: [],
            borderColor: [],
            borderWidth: 1
        }]
    },
    options: {
        responsive: false, // Disable responsiveness
        maintainAspectRatio: false,
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
                text: 'Pending Submissions by Department'
            }
        }
    },
    plugins: [{
        beforeDraw: function(chart) {
            if (chart.data.datasets[0].data.length === 1 && chart.data.datasets[0].data[0] === 0.01) {
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

function fetchData(departmentCode, collegeCode, year) {
    $.ajax({
        url: 'php-functions/fetch-accepted-submissions-per-faculty.php',
        type: 'POST',
        data: { department_code: departmentCode, college_code: collegeCode, year: year },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'error') {
                alert(response.message);
            } else {
                var acceptedLabels = response.map(function(item) {
                    return item.year;
                });
                var acceptedData = response.map(function(item) {
                    return item.total_paper;
                });

                // Update department name
                if (response.length > 0 && response[0].department_name) {
                    departmentName = response[0].department_name;
                }

                if (acceptedData.length === 0) {
                    // If no data, show the placeholder with 10 bars with different heights
                    acceptedLabels = [];
                    acceptedData = [];
                    for (var i = 0; i < 10; i++) {
                        acceptedLabels.push('No data');
                        acceptedData.push(Math.random() * 10); // Generate random values for different heights
                    }
                    myChartAccepted.data.datasets[0].backgroundColor = acceptedData.map(() => 'rgba(200, 200, 200, 0.2)');
                    myChartAccepted.data.datasets[0].borderColor = acceptedData.map(() => 'rgba(200, 200, 200, 1)');
                    myChartAccepted.data.labels = acceptedLabels;
                    myChartAccepted.data.datasets[0].data = acceptedData;
                    myChartAccepted.update();

                    // Display no data text after a delay
                    setTimeout(function() {
                        displayNoDataText(myChartAccepted);
                    }, 1000); // Delay of 1 second
                } else {
                    myChartAccepted.data.labels = acceptedLabels;
                    myChartAccepted.data.datasets[0].data = acceptedData;
                    myChartAccepted.data.datasets[0].backgroundColor = acceptedData.map(() => 'rgba(204, 0, 41, 0.2)');
                    myChartAccepted.data.datasets[0].borderColor = acceptedData.map(() => 'rgba(204, 0, 41, 1)');
                    myChartAccepted.update();
                }
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while fetching data: ' + error);
        }
    });
}


function fetchPendingData(collegeCode) {
    $.ajax({
        url: 'php-functions/fetch-pending-submissions.php',
        type: 'POST',
        data: { college_code: collegeCode },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                alert(response.error);
            } else {
                var pendingLabels = response.map(function(item) {
                    return item.department_name;
                });
                var pendingData = response.map(function(item) {
                    return item.total_paper;
                });

                if (pendingData.length === 0) {
                    // If no data, show the placeholder
                    pendingLabels = ['No data'];
                    pendingData = [0.01]; // Use small value to ensure the placeholder appears
                    myChartPending.data.datasets[0].backgroundColor = ['rgba(200, 200, 200, 0.2)'];
                    myChartPending.data.datasets[0].borderColor = ['rgba(200, 200, 200, 1)'];
                } else {
                    myChartPending.data.datasets[0].backgroundColor = pendingData.map(() => 'rgba(255, 99, 132, 0.2)');
                    myChartPending.data.datasets[0].borderColor = pendingData.map(() => 'rgba(255, 99, 132, 1)');
                }

                myChartPending.data.labels = pendingLabels;
                myChartPending.data.datasets[0].data = pendingData;
                myChartPending.update();
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while fetching data: ' + error);
        }
    });
}

function fetchResearchData() {
    var collegeCode = $('#collegeCode').val();
    var departmentCode = $('#departmentSelect').val();
    var year = $('#yearSelect').val() || new Date().getFullYear(); 

    $.ajax({
        url: 'php-functions/fetch-research-data.php',
        type: 'GET',
        data: {
            college_code: collegeCode,
            department_code: departmentCode,
            year: year
        },
        dataType: 'json',
        success: function(response) {
            var tbody = $('table.table tbody');
            tbody.empty();

            if (response.length > 0) {
                response.forEach(function(item) {
                    var row = '<tr>' +
                        '<td></td>' +
                        '<td>' + item.research_title + '</td>' +
                        '<td>' + item.dateofsubmission + '</td>' +
                        '<td>' + item.total_downloads + '</td>' +
                        '<td>' + item.total_views + '</td>' +
                         // '<td>' + item.total_citation + '</td>' +
                        '</tr>';
                    tbody.append(row);
                });
            } else {
                showNoDataInTable();
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while fetching research data: ' + error);
        }
    });
}

function showNoDataInTable() {
    var tbody = $('table.table tbody');
    tbody.empty();
    tbody.append('<tr><td colspan="6" class="text-center">No Data</td></tr>');
}

// Set the year select to the current year by default if not already set
$(document).ready(function() {
    if (!$('#yearSelect').val()) {
        $('#yearSelect').val(new Date().getFullYear());
    }
    fetchResearchData(); // Fetch data on page load
});

$('#departmentSelect, #yearSelect').change(fetchResearchData);

// Fetch departments on document ready
fetchDepartments();
    
    $('#departmentSelect').on('change', function() {
        var departmentCode = $(this).val();
        var collegeCode = $('#collegeCode').val();
        var year = $('#yearSelect').val();
        fetchData(departmentCode, collegeCode, year);
    });

    $('#yearSelect').on('change', function() {
        var departmentCode = $('#departmentSelect').val();
        var collegeCode = $('#collegeCode').val();
        var year = $(this).val();
        fetchData(departmentCode, collegeCode, year);
    });

    // Call fetchYears on page load
    fetchYears(function() {
        var departmentCode = $('#departmentSelect').val();
        var collegeCode = $('#collegeCode').val();
        var year = $('#yearSelect').val();
        fetchData(departmentCode, collegeCode, year);
        fetchPendingData(collegeCode); // Fetch pending data on page load
    });
});