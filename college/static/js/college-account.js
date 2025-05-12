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

  // CREATE ACCOUNT
$(document).ready(function() {
    console.log("Document is ready");

    $('#create-account-btn').on('click', function() {
        console.log("Create account button clicked");

        // Validate email, password, and confirm password fields
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();

        if (!email || !password || !confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields.',
            });
            return;
        }

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords do not match.',
            });
            return;
        }

        $('#roleModal').modal('show');
    });

    $('#collegecodeform').on('submit', function(event) {
        event.preventDefault();
        console.log("College code form submitted");

        // Collect data from both forms
        const email = $('#email').val();
        const password = $('#password').val();
        const collegeCode = $('#collegeCode').val();

        const data = {
            email: email,
            password: password,
            collegeCode: collegeCode
        };

        Swal.fire({
            title: 'Creating...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/create-college-account.php',
            type: 'POST',
            data: data, // Send the collected data
            success: function(response) {
                console.log("AJAX request successful", response);
                // Handle the response from the server
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        $('#roleModal').modal('hide'); // Hide the modal
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
                console.log("AJAX request failed", error);
                // Handle any errors
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: error,
                });
            }
        });
    });
});
// CREATE ACCOUNT

// LOGIN ACCOUNT
$(document).ready(function() {
    $('#college-account-login').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        var email = $('#email').val();
        var password = $('#password').val();

        Swal.fire({
            title: 'Logging in...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        // Perform AJAX request
        $.ajax({
            url: 'college/php-functions/college-login.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                // Parse the JSON response
                response = JSON.parse(response);
        
                // Handle the response from the server
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: response.message,
                        timer: 800,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'college/college-dashboard.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false
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
                    timer: 800,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        });
    });
});
// LOGIN ACCOUNT

// ADD DEPARTMENT CODE 
$(document).ready(function() {
    $('#adddepartmentCode').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission


        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/add-department-code.php', // URL to the server-side script
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
                        $('#adddepartmentCodeModal').modal('hide'); // Hide the modal
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
// ADD DEPARTMENT CODE


// FETCH DEPARTMENT LIST
$(document).ready(function() {
    $.ajax({
        url: 'php-functions/fetch-department-list.php',
        type: 'GET',
        success: function(response) {
            var departments = JSON.parse(response);
            var tableBody = $('#faculty-table-body');
            tableBody.empty();

            if (departments.length > 0) {
                departments.forEach(function(department) {
                    // Create delete button only if no connected records
                    var deleteButton = department.connected_records == 0 ? 
                        '<li><a class="dropdown-item delete-department" href="#" data-id="' + department.department_code + '">Delete</a></li>' : '';

                    var row = '<tr>' +
                        '<th scope="row"></th>' +
                        '<td>' + $('<div>').text(department.department_name).html() + '</td>' +
                        '<td>' + $('<div>').text(department.department_code).html() + '</td>' +
                        '<td>' +
                            '<div class="dropdown">' +
                                '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu-' + department.department_code + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                                    'Actions' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownMenu-' + department.department_code + '">' +
                                    '<li><a class="dropdown-item edit-department" href="#" data-id="' + department.department_code + '">Edit</a></li>' +
                                    deleteButton +
                                '</ul>' +
                            '</div>' +
                        '</td>' +
                        '</tr>';
                    tableBody.append(row);
                });
            } else {
                tableBody.append("<tr><td colspan='4' class='text-center'>No department yet</td></tr>");
            }
        },
        error: function(xhr, status, error) {
            var tableBody = $('#faculty-table-body');
            tableBody.empty();
            tableBody.append("<tr><td colspan='4' class='text-center'>An error occurred while fetching data</td></tr>");
        }
    });
});
// FETCH DEPARTMENT LIST

// EDIT DEPARTMENT
$(document).ready(function() {
    // Edit button click handler
    $(document).on('click', '.edit-department', function(e) {
        e.preventDefault();
        const departmentCode = $(this).data('id');
        const row = $(this).closest('tr');
        const departmentName = row.find('td:eq(0)').text();
        const currentDeptCode = row.find('td:eq(1)').text();

        $('#edit_department_name').val(departmentName);
        $('#edit_department_code').val(currentDeptCode);
        $('#edit_department_code_original').val(departmentCode);

        $('#editDepartmentModal').modal('show');
    });

    // Save changes button click handler
    $('#saveDepartmentChanges').click(function() {
        const originalDeptCode = $('#edit_department_code_original').val();
        const newDeptName = $('#edit_department_name').val();
        const newDeptCode = $('#edit_department_code').val();

        // Validate inputs
        if (!newDeptName || !newDeptCode) {
            alert('Please fill in all fields');
            return;
        }

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });


        // Send AJAX request to update department
        $.ajax({
            url: 'php-functions/update-faculty.php',
            type: 'POST',
            data: {
                original_department_code: originalDeptCode,
                department_name: newDeptName,
                department_code: newDeptCode
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Department updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Close modal
                            $('#editDepartmentModal').modal('hide');
                            // Refresh department list
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: result.message || 'Error updating department'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error processing response'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Error connecting to server'
                });
            }
        });
    });
});
// EDIT DEPARTMENT

// DELETE DEPARTMENT
$(document).ready(function() {
    $.ajax({
        url: 'php-functions/fetch-department-list.php',
        type: 'GET',
        success: function(response) {
            var departments = JSON.parse(response);
            var tableBody = $('#faculty-table-body');
            tableBody.empty();

            if (departments.length > 0) {
                departments.forEach(function(department) {
                    // Create delete button only if no connected records
                    var deleteButton = department.connected_records == 0 ? 
                        '<li><a class="dropdown-item delete-department" href="#" data-id="' + department.department_code + '">Delete</a></li>' : '';

                    var row = '<tr>' +
                        '<th scope="row"></th>' +
                        '<td>' + $('<div>').text(department.department_name).html() + '</td>' +
                        '<td>' + $('<div>').text(department.department_code).html() + '</td>' +
                        '<td>' +
                            '<div class="dropdown">' +
                                '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu-' + department.department_code + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                                    'Actions' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownMenu-' + department.department_code + '">' +
                                    '<li><a class="dropdown-item edit-department" href="#" data-id="' + department.department_code + '">Edit</a></li>' +
                                    deleteButton +
                                '</ul>' +
                            '</div>' +
                        '</td>' +
                        '</tr>';
                    tableBody.append(row);
                });
            } else {
                tableBody.append("<tr><td colspan='4' class='text-center'>No department yet</td></tr>");
            }
        },
        error: function(xhr, status, error) {
            var tableBody = $('#faculty-table-body');
            tableBody.empty();
            tableBody.append("<tr><td colspan='4' class='text-center'>An error occurred while fetching data</td></tr>");
        }
    });

    $(document).on('click', '.delete-department', function(e) {
        e.preventDefault();
        var departmentCode = $(this).data('id');

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

                Swal.fire({
                    title: 'Please wait...',
                    html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                $.ajax({
                    url: '../college/php-functions/delete-department.php',
                    type: 'POST',
                    data: { department_code: departmentCode },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire(
                                'Deleted!',
                                'Department has been deleted.',
                                'success'
                            ).then(() => {
                                // Refresh department list
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                result.message || 'Error deleting department',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Error connecting to server',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
// DELETE DEPARTMENT

// PIE CHART USERS PER FACULTY
$(document).ready(function() {
    function fetchUsersPerFaculty() {
        $.ajax({
            url: 'php-functions/fetch-users-per-faculty.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var labels = [];
                var data = [];
                var backgroundColor = [];
                var borderColor = [];

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        labels.push(item.department_name);
                        data.push(item.user_count);
                        backgroundColor.push('rgba(255, 99, 132, 0.2)');
                        borderColor.push('rgba(255, 99, 132, 1)');
                    });
                } else {
                    // If no data, show the placeholder
                    labels = ['No data'];
                    data = [0.01]; // Use small value to ensure the placeholder appears
                    backgroundColor = ['rgba(200, 200, 200, 0.2)'];
                    borderColor = ['rgba(200, 200, 200, 1)'];
                }

                renderUsersPerFacultyChart(labels, data, backgroundColor, borderColor);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching users per faculty:', error);
            }
        });
    }

    function renderUsersPerFacultyChart(labels, data, backgroundColor, borderColor) {
        var ctx = document.getElementById('usersPerFacultyChart').getContext('2d');
        var usersPerFacultyChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: '# Users',
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hide the legend labels
                    },
                    title: {
                        display: true,
                        text: '# Users per Department Faculty'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
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
    }
    fetchUsersPerFaculty();
});
// PIE CHART USERS PER FACULTY