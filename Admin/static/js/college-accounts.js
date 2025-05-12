  // SHOW PASSWORD
$(".toggle-password").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
  // SHOW PASSWORD


$(document).ready(function() {
    $('#addCodeForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: 'php-functions/add-college-code.php', // URL to the server-side script
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                // Handle the response from the server
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        $('#addCodeModal').modal('hide'); // Hide the modal
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    }).then(() => {
                        location.reload(); // Reload the page
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle any errors
                let errorMessage = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else {
                    errorMessage = error;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: errorMessage,
                }).then(() => {
                    location.reload(); // Reload the page
                });
            }
        });
    });
});

$(document).ready(function() {
    // Handle Edit button click
    $(document).on('click', '.edit-btn', function() {
        var collegeCode = $(this).data('id');
        var collegeName = $(this).data('name');
        $('#editCollegeName').val(collegeName);
        $('#editCollegeCode').val(collegeCode);
        $('#originalCollegeCode').val(collegeCode);
        $('#editCollegeModal').modal('show');
    });

     // Handle Delete button click
     $(document).on('click', '.delete-btn', function() {
        var collegeCode = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'php-functions/delete-college.php', // URL to the server-side script
                    type: 'POST',
                    data: { college_code: collegeCode },
                    success: function(response) {
                        // Handle the response from the server
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                            }).then(() => {
                                location.reload(); // Reload the page
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else {
                            errorMessage = error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occurred',
                            text: errorMessage,
                        });
                    }
                });
            }
        });
    });


    // Handle Edit College form submission
    $('#editCollegeForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: 'php-functions/update-college.php', // URL to the server-side script
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                // Handle the response from the server
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        $('#editCollegeModal').modal('hide'); // Hide the modal
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle any errors
                let errorMessage = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else {
                    errorMessage = error;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: errorMessage,
                });
            }
        });
    });
});

$(document).ready(function() {
    $('.approve-btn').click(function() {
        var collegeCode = $(this).data('college-code');
        var button = $(this);
        var statusCell = button.closest('tr').find('.status');

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/activate-status-college-account.php',
            type: 'POST',
            data: { college_code: collegeCode },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    statusCell.text('Active');
                    button.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the status.'
                });
            }
        });
    });
});

$(document).ready(function() {
    $('.reject-btn').click(function() {
        var collegeCode = $(this).data('college-code');
        var button = $(this);
        var statusCell = button.closest('tr').find('.status');

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/reject-status-college-account.php',
            type: 'POST',
            data: { college_code: collegeCode },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    statusCell.text('Active');
                    button.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the status.'
                });
            }
        });
    });
});

$(document).ready(function() {
    $('.deactivate-btn').click(function() {
        var collegeCode = $(this).data('college-code');
        var button = $(this);
        var statusCell = button.closest('tr').find('.status');

        $.ajax({
            url: 'php-functions/deactivate-status-college-account.php',
            type: 'POST',
            data: { college_code: collegeCode },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    statusCell.text('Active');
                    button.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the status.'
                });
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '.details-btn', function() {
        var collegeCode = $(this).data('college-code');

        $.ajax({
            url: 'php-functions/fetch-college-details.php',
            method: 'GET',
            data: { college_code: collegeCode },
            dataType: 'json',
            success: function(data) {
                $('#collegeName').text(data.college_name);
                $('#collegeEmail').text(data.email);
                $('#collegeStatus').text(data.status);

                var departmentList = $('#departmentList');
                departmentList.empty();
                data.departments.forEach(function(department) {
                    departmentList.append('<li>' + department.department_name + '</li>');
                });

                $('#detailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
});

$(document).ready(function() {
     // Fetch college codes and names
     function fetchColleges() {
        $.ajax({
            url: 'php-functions/fetch-available-college.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var collegeSelect = $('#confirmcollegeCode');
                collegeSelect.empty();
                collegeSelect.append('<option value="">Select College</option>');
                response.forEach(function(college) {
                    collegeSelect.append('<option value="' + college.college_code + '">' + college.college_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching colleges:', error);
            }
        });
    }

    // Call fetchColleges on page load
    fetchColleges();

    $('#create-account-btn').on('click', function(event) {
        event.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        var collegeCode = $('#confirmcollegeCode').val();

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords do not match!',
            });
            return;
        }

        Swal.fire({
            title: 'Creating...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/create-college-account.php',
            type: 'POST',
            data: {
                email: email,
                password: password,
                collegeCode: collegeCode
            },
            success: function(response) {
                console.log("Raw response:", response); // Debugging line
                // Assuming response is already a JavaScript object
                var jsonResponse = response;
                console.log("Parsed response:", jsonResponse); // Debugging line
                if (jsonResponse.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message,
                    }).then(() => {
                        $('#accountModal').modal('hide'); // Hide the modal
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error); // Debugging line
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: error,
                });
            }
        });
    });
});

$(document).ready(function() {
    function fetchUsersPerCollege() {
        $.ajax({
            url: 'php-functions/fetch-users-per-college.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var labels = [];
                    var data = [];

                    response.data.forEach(function(item) {
                        labels.push(item.college_name);
                        data.push(item.user_count);
                    });

                    renderUsersPerCollegeChart(labels, data);
                } else {
                    console.error('Error fetching users per college:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching users per college:', error);
            }
        });
    }

    function renderUsersPerCollegeChart(labels, data) {
        var ctx = document.getElementById('usersPerCollegeChart').getContext('2d');
        var usersPerCollegeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: '# Users per College',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Ensure the chart respects the specified dimensions
                plugins: {
                    legend: {
                        display: false 
                    },
                    title: {
                        display: true,
                        text: '# Users per College'
                    }
                }
            }
        });
    }

    function fetchFacultyPerCollege() {
        $.ajax({
            url: 'php-functions/fetch-faculty-per-college.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var labels = [];
                    var data = [];
                    var departments = {};

                    response.data.forEach(function(item) {
                        labels.push(item.college_name);
                        data.push(item.department_count);
                        departments[item.college_name] = item.departments;
                    });

                    renderFacultyPerCollegeChart(labels, data, departments);
                } else {
                    console.error('Error fetching faculty per college:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching faculty per college:', error);
            }
        });
    }

    function renderFacultyPerCollegeChart(labels, data, departments) {
        var ctx = document.getElementById('facultyPerCollegeChart').getContext('2d');
        var facultyPerCollegeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: '# Department',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false 
                    },
                    title: {
                        display: true,
                        text: '# Department Per College'
                    },
                    tooltip: {
                        enabled: false,
                        external: function(context) {
                            // Tooltip Element
                            var tooltipEl = document.getElementById('chartjs-tooltip');
    
                            // Create element on first render
                            if (!tooltipEl) {
                                tooltipEl = document.createElement('div');
                                tooltipEl.id = 'chartjs-tooltip';
                                tooltipEl.innerHTML = '<table></table>';
                                document.body.appendChild(tooltipEl);
                            }
    
                            // Hide if no tooltip
                            var tooltipModel = context.tooltip;
                            if (tooltipModel.opacity === 0) {
                                tooltipEl.style.opacity = 0;
                                return;
                            }
    
                            // Set caret Position
                            tooltipEl.classList.remove('above', 'below', 'no-transform');
                            if (tooltipModel.yAlign) {
                                tooltipEl.classList.add(tooltipModel.yAlign);
                            } else {
                                tooltipEl.classList.add('no-transform');
                            }
    
                            function getBody(bodyItem) {
                                return bodyItem.lines;
                            }
    
                            // Set Text
                            if (tooltipModel.body) {
                                var titleLines = tooltipModel.title || [];
                                var bodyLines = tooltipModel.body.map(getBody);
    
                                var innerHtml = '<thead>';
    
                                titleLines.forEach(function(title) {
                                    innerHtml += '<tr><th>' + title + '</th></tr>';
                                });
    
                                innerHtml += '</thead><tbody>';
    
                                bodyLines.forEach(function(body, i) {
                                    var departmentsList = departments[titleLines[i]] || [];
                                    var departmentHtml = departmentsList.map(function(dept) {
                                        return '<tr><td>' + dept + '</td></tr>';
                                    }).join('');
                                    innerHtml += '<tr><td>' + body + '</td></tr>' + departmentHtml;
                                });
    
                                innerHtml += '</tbody>';
    
                                var tableRoot = tooltipEl.querySelector('table');
                                tableRoot.innerHTML = innerHtml;
                            }
    
                            // `this` will be `chart` in Chart.js 3
                            var position = context.chart.canvas.getBoundingClientRect();
    
                            // Display, position, and set styles for font
                            tooltipEl.style.opacity = 1;
                            tooltipEl.style.position = 'absolute';
                            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
                            tooltipEl.style.font = tooltipModel.options.bodyFont.string;
                            tooltipEl.style.padding = tooltipModel.options.padding + 'px ' + tooltipModel.options.padding + 'px';
                            tooltipEl.style.pointerEvents = 'none';
                        }
                    }
                }
            }
        });
    }
    
    
    
    // Fetch and render the chart on page load
    fetchUsersPerCollege();
    fetchFacultyPerCollege();

});