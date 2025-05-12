$(document).ready(function() {
    // Function to load adviser accounts
    function loadAdviserAccountswaiting(departmentCode) {
        var collegeCode = $('#collegeCode').val();
        $.ajax({
            url: 'php-functions/fetch-adviser-accounts-waiting.php',
            type: 'POST',
            data: { department_code: departmentCode, college_code: collegeCode },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('#adviserAccountsTableBodywaiting');
                tableBody.empty();
                if (response.length > 0) {
                    $.each(response, function(index, row) {
                        var statusColor = (row.status === 'Active') ? 'green' : 'black';
                        var actionButtons = '';
                        if (row.status === 'Waiting') {
                            actionButtons = `
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item approve-btn" href="#" data-email="${row.email}">Approve</a></li>
                                        <li><a class="dropdown-item decline-btn" href="#" data-email="${row.email}">Decline</a></li>
                                    </ul>
                                </div>`;
                        } else if (row.status === 'Active') {
                            actionButtons = "<button class='btn btn-warning deactivate-btn' data-email='" + row.email + "'>Deactivate</button>";
                        } else if (row.status === 'Deactivated') {
                            actionButtons = "<button class='btn btn-success activate-btn' data-email='" + row.email + "'>Activate</button>";
                        }
    
                        var emailVerifiedIcon = row.is_emailverified == 1 
                            ? "<span class='verified-icon' title='Email verified'>&#10003;</span>" 
                            : "<span class='not-verified-icon' title='Email not verified'>&#10007;</span>";
    
                        tableBody.append(
                            "<tr>" +
                                "<th scope='row'></th>" +
                                "<td>" + row.email + emailVerifiedIcon + "</td>" +
                                "<td><button class='btn view-btn' data-email='" + row.email + "'><i class='bi bi-eye'></i></button></td>" +
                                "<td>" + row.creation_date + "</td>" +
                                "<td><span style='color: " + statusColor + ";'>" + row.status + "</span></td>" +
                                "<td>" + actionButtons + "</td>" +
                            "</tr>"
                        );
                    });
                } else {
                    tableBody.append("<tr><td colspan='6' style='text-align: center;'>No waiting accounts as of the moment</td></tr>");
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
    var style = document.createElement('style');
style.innerHTML = `
    .verified-icon {
        color: green;
        font-size: 0.8em;
        margin-left: 5px;
        cursor: pointer;
    }
    .not-verified-icon {
        color: red;
        font-size: 0.8em;
        margin-left: 5px;
        cursor: pointer;
    }
    .tooltip {
        position: absolute;
        background-color: #333;
        color: #fff;
        padding: 5px;
        border-radius: 3px;
        font-size: 0.8em;
        z-index: 1000;
    }
`;
document.head.appendChild(style);


    // Handle view button click
    $(document).on('click', '.view-btn', function() {
        var email = $(this).data('email');
        $.ajax({
            url: 'php-functions/fetch-adviser-details.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var emailVerifiedIcon = response.data.is_emailverified == 1 
                        ? "<span class='verified-icon' title='Email verified' style='color: green; margin-left: 5px;'>&#10003;</span>" 
                        : "<span class='not-verified-icon' title='Email not verified' style='color: red; margin-left: 5px;'>&#10007;</span>";
                    
                    $('#viewModal .modal-body').html(
                        '<p><strong>Email:</strong> ' + response.data.email + emailVerifiedIcon + '</p>' +
                        '<p><strong>First Name:</strong> ' + response.data.first_name + '</p>' +
                        '<p><strong>Middle Name:</strong> ' + response.data.middle_name + '</p>' +
                        '<p><strong>Last Name:</strong> ' + response.data.last_name + '</p>' +
                        '<p><strong>ID Number:</strong> ' + response.data.id_number + '</p>' +
                        '<p><strong>Submission Code:</strong> ' + response.data.adviser_code + '</p>' +
                        '<p><strong>Status:</strong> ' + response.data.status + '</p>'
                    );
                    $('#viewModal').modal('show');
                } else {
                    // ...existing error handling...
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Event delegation for approve button
    $(document).on('click', '.approve-btn, .activate-btn', function() {
        var email = $(this).data('email');

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });


        $.ajax({
            url: 'php-functions/approve-adviser.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadAdviserAccountswaiting($('#departmentSelect').val()); // Reload the table
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Event delegation for decline button
    $(document).on('click', '.decline-btn', function() {
        var email = $(this).data('email');

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/decline-adviser.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadAdviserAccountswaiting($('#departmentSelect').val()); // Reload the table
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Event delegation for deactivate button
    $(document).on('click', '.deactivate-btn', function() {
        var email = $(this).data('email');

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });
        
        $.ajax({
            url: 'php-functions/deactivate-adviser.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadAdviserAccountswaiting($('#departmentSelect').val()); // Reload the table
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    

    // Handle department selection change
    $('#departmentSelect').change(function() {
        var departmentCode = $(this).val();
        loadAdviserAccountswaiting(departmentCode);
    });

    // Initial load
    loadAdviserAccountswaiting('');
});