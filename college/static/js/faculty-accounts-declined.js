$(document).ready(function() {
    // Function to load adviser accounts
    function loadAdviserAccountsdeclined(departmentCode) {
        var collegeCode = $('#collegeCode').val();
        $.ajax({
            url: 'php-functions/fetch-adviser-accounts-declined.php',
            type: 'POST',
            data: { department_code: departmentCode, college_code: collegeCode  },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('#adviserAccountsTableBodydeclined');
                tableBody.empty();
                if (response.length > 0) {
                    $.each(response, function(index, row) {
                        var statusColor = (row.status === 'Active') ? 'green' : 'black';
                        var actionButtons = '';
                        if (row.status === 'Waiting') {
                            actionButtons = "<button class='btn btn-success approve-btn' data-email='" + row.email + "'>Approve</button>" +
                                            "<button class='btn btn-danger decline-btn' data-email='" + row.email + "'>Decline</button>";
                        } else if (row.status === 'Active') {
                            actionButtons = "<button class='btn btn-warning deactivate-btn' data-email='" + row.email + "'>Deactivate</button>";
                        } else if (row.status === 'Deactivated') {
                            actionButtons = "<button class='btn btn-success activate-btn' data-email='" + row.email + "'>Activate</button>";
                        }
                        tableBody.append(
                            "<tr>" +
                                "<th scope='row'></th>" +
                                "<td>" + row.email + "</td>" +
                                "<td>" + row.creation_date + "</td>" +
                                "<td><span style='color: " + statusColor + ";'>" + row.status + "</span></td>" +
                                "<td>" + actionButtons + "</td>" +
                            "</tr>"
                        );
                    });
                } else {
                    tableBody.append("<tr><td colspan='6' style='text-align: center;'>No declined accounts as of the moment</td></tr>");
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

    // Event delegation for approve button
$(document).on('click', '.approve-btn, .activate-btn', function() {
    var email = $(this).data('email');
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
                    loadAdviserAccountsdeclined($('#departmentSelect').val()); 
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
                        loadAdviserAccountsdeclined($('#departmentSelect').val());
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
                        loadAdviserAccountsdeclined($('#departmentSelect').val());
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
        loadAdviserAccountsdeclined(departmentCode);
    });

    // Initial load
    loadAdviserAccountsdeclined('');
});