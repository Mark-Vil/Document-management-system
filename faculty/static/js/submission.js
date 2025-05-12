$(document).ready(function() {
    $('.view-btn').on('click', function() {
        var submissionId = $(this).data('id');
        $.ajax({
            url: 'php-functions/fetch-submission-data.php',
            type: 'GET',
            data: { submission_id: submissionId },
            dataType: 'json',
            success: function(data) {
                var modalContent = $('#modalContent');
                modalContent.html(`
                    <p><strong>Research Title:</strong> ${data.research_title}</p>
                    <p><strong>Author:</strong> ${data.author}</p>
                    <p><strong>Co-Authors:</strong> ${data.co_authors}</p>
                    <p><strong>Abstract:</strong> ${data.abstract}</p>
                    <p><strong>Keywords:</strong> ${data.keywords}</p>         
                    <p><strong>Date Submitted:</strong> ${data.dateofsubmission}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>PDF:</strong> <a href="view-pdf.html?file=${encodeURIComponent(data.file_path)}" target="_blank">View PDF</a></p>
                `);
            }
        });
    });
});

$(document).ready(function() {
    // Approve button click event
    $('.approve-btn').on('click', function() {
        var submissionId = $(this).data('id');


        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });
        $.ajax({
            url: 'php-functions/approve-submission.php',
            type: 'POST',
            data: { submission_id: submissionId },
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optionally, you can refresh the page or update the status in the table
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while approving the submission.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Reject button click event
    $('.reject-btn').on('click', function() {
        var submissionId = $(this).data('id');
        $('#rejectSubmissionId').val(submissionId);
        $('#rejectCommentsModal').modal('show');
    });

    // Handle the form submission inside the reject modal
    $('#rejectCommentsForm').on('submit', function(e) {
        e.preventDefault();

        // Check if the comments field is empty
        var comments = $('#rejectComments').val().trim();
        if (comments === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Comments cannot be empty.',
            });
            return; // Prevent form submission
        }
        var formData = $(this).serialize();

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/reject-submission.php',
            type: 'POST',
            data: formData,
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optionally, you can refresh the page or update the status in the table
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while rejecting the submission.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Revise button click event
    $('.revise-btn').on('click', function() {
        var submissionId = $(this).data('id');
        $('#reviseSubmissionId').val(submissionId);
        $('#reviseCommentsModal').modal('show');
    });

    // Handle the form submission inside the revise modal
    $('#reviseCommentsForm').on('submit', function(e) {
        e.preventDefault();

        // Check if the comments field is empty
        var comments = $('#reviseComments').val().trim();
        if (comments === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Comments cannot be empty.',
            });
            return; // Prevent form submission
        }
        var formData = $(this).serialize();

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/revise-submission.php',
            type: 'POST',
            data: formData,
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        // Add a 1500 milliseconds (1.5 seconds) delay before reloading the page
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while revising the submission.',
                });
            }
        });
    });


    $('.history-btn').on('click', function() {
        var submissionId = $(this).data('id');
        var $row = $(this).closest('tr');

        // Check if history rows are already appended
    if ($row.next().hasClass('history-row')) {
        // Toggle visibility of existing history rows
        $row.nextUntil(':not(.history-row)').toggle();
        return;
    }

        $.ajax({
            url: 'php-functions/fetch-history.php',
            method: 'POST',
            data: { submission_id: submissionId },
            success: function(response) {
                var historyData = JSON.parse(response);
                var historyHtml = '';

                historyData.forEach(function(history) {
                    historyHtml += '<tr class="history-row">';
                    historyHtml += '<th scope="row"></th>';
                    historyHtml += '<td>' + htmlspecialchars(history.research_title) + '</td>';
                    historyHtml += '<td><button type="button" class="btn viewhistory-btn" data-bs-toggle="modal" data-bs-target="#viewhistoryModal" data-id="' + htmlspecialchars(history.id) + '"><i class="bi bi-eye"></i></button></td>';
                    historyHtml += '<td>' + htmlspecialchars(history.dateofsubmission) + '</td>';
                    historyHtml += '<td style="color: black;"></td>';
                    historyHtml += '<td style="color: black;">History</td>'; // Status column
                    historyHtml += '</tr>';
                });

                $row.after(historyHtml);
            },
            error: function() {
                alert('Failed to fetch history data.');
            }
        });


        
    });
    function htmlspecialchars(str) {
        if (typeof str !== 'string') {
            return str;
        }
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;')
                  .replace(/'/g, '&#039;');
    }

    $(document).on('click', '.viewhistory-btn', function() {
        var submissionId = $(this).data('id');

        $.ajax({
            url: 'php-functions/fetch-submission-data-history.php',
            method: 'POST',
            data: { id: submissionId }, // Ensure the correct key is used
            success: function(response) {
                var detailsData = JSON.parse(response);

                // Populate modal with details data
                $('#viewhistoryModal .modal-body').html(
                    '<p><strong>Research Title:</strong> ' + htmlspecialchars(detailsData.research_title) + '</p>' +
                    '<p><strong>Author:</strong> ' + htmlspecialchars(detailsData.author) + '</p>' +
                    '<p><strong>Co-authors:</strong> ' + htmlspecialchars(detailsData.co_authors) + '</p>' +
                    '<p><strong>Abstract:</strong> ' + htmlspecialchars(detailsData.abstract) + '</p>' +
                    '<p><strong>Keywords:</strong> ' + htmlspecialchars(detailsData.keywords) + '</p>' +
                    '<p><strong>Date of Submission:</strong> ' + htmlspecialchars(detailsData.dateofsubmission) + '</p>' +
                    '<p><strong>Comments:</strong> ' + htmlspecialchars(detailsData.comments) + '</p>' +
                    '<p><strong>PDF:</strong> <a href="view-pdf.html?file=' + encodeURIComponent(detailsData.file_path) + '" target="_blank">View PDF</a></p>'
                );

                // Show the modal
                $('#viewhistoryModal').modal('show');
            },
            error: function() {
                alert('Failed to fetch details data.');
            }
        });
    });

    $(document).ready(function() {
        function fetchSubmissions() {
            $.ajax({
                url: 'php-functions/fetch-myupload.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var tbody = $('#submissionTableBody');
                    tbody.empty(); // Clear existing data
                
                    if (data.length > 0) {
                        data.forEach(function(submission) {
                            var lockButton = '';
                            if (submission.status === 'Accepted') {
                                lockButton = `<li><button class="dropdown-item btn btn-warning lock-btn" data-id="${submission.submission_id}">Lock</button></li>`;
                            } else if (submission.status === 'Locked') {
                                lockButton = `<li><button class="dropdown-item btn btn-success unlock-btn" data-id="${submission.submission_id}">Unlock</button></li>`;
                            }
                
                            var row = `
                                <tr>
                                    <th scope="row"></th>
                                    <td>${submission.research_title}</td>
                                    <td><button type="button" class="btn viewupload-btn" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="${submission.submission_id}"><i class="bi bi-eye"></i></button></td>
                                    <td>${submission.dateofsubmission}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${submission.submission_id}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${submission.submission_id}">
                                                <li><button class="dropdown-item btn btn-info myhistory-btn" data-id="${submission.id}">History</button></li>
                                                <li><button class="dropdown-item btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${submission.submission_id}">Edit</button></li>
                                                ${lockButton}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });

                        $(document).ready(function() {
                            // Lock and Unlock button click event
                            $(document).on('click', '.lock-btn, .unlock-btn', function() {
                                var submissionId = $(this).data('id');
                                var action = $(this).hasClass('lock-btn') ? 'lock' : 'unlock';
                                $.ajax({
                                    url: 'php-functions/lock-document.php',
                                    type: 'POST',
                                    data: { submission_id: submissionId, action: action },
                                    dataType: 'json', // Expect JSON response
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: response.message,
                                            }).then(() => {
                                                // Optionally, you can refresh the page or update the status in the table
                                                location.reload();
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
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'An error occurred while processing the request.',
                                        });
                                    }
                                });
                            });
                        });

                        $(document).ready(function() {
                            $('.edit-btn').on('click', function() {
                                $('#add-coauthor-btn').click(function() {
                                    var newInput = $('<input class="form-control mb-2" name="coauthorname[]" type="text" placeholder="Enter Co-Author Full Name">');
                                    $('#coauthor-container').append(newInput);
                                  });
                              
                                  // Add new keyword input field
                                  $('#add-keywords-btn').click(function() {
                                    var newkeyInput = $('<input class="form-control mb-2" name="addkeywords[]" type="text" placeholder="Enter Keywords">');
                                    $('#addkeywords-container').append(newkeyInput);
                                  });
                              
                                var submissionId = $(this).data('id');
                        
                                $.ajax({
                                    url: 'php-functions/fetch-submission-data.php',
                                    type: 'GET',
                                    data: { submission_id: submissionId },
                                    dataType: 'json',
                                    success: function(data) {
                                        $('#editSubmissionId').val(data.submission_id);
                                        $('#editResearchTitle').val(data.research_title);
                                        $('#editAuthor').val(data.author);
                                        $('#editCoAuthors').val(data.co_authors);
                                        $('#editAbstract').val(data.abstract);
                                        $('#editKeywords').val(data.keywords);
                                        $('#editModal').modal('show');
                                    }
                                });
                            });
                        
                            $('#editForm').on('submit', function(e) {
                                e.preventDefault();
                                var formData = new FormData(this);
                        
                                Swal.fire({
                                    title: 'Uploading...',
                                    html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                        
                                $.ajax({
                                    url: 'php-functions/update-submission.php',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    dataType: 'json', // Expect JSON response
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: response.message,
                                            }).then(() => {
                                                $('#editModal').modal('hide');
                                                location.reload(); // Optionally, refresh the page to reflect changes
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
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'An error occurred. Please try again.',
                                        });
                                    }
                                });
                            });
                        });

                        $(document).on('click', '.viewmyhistory-btn', function() {
                            var submissionId = $(this).data('id');
                    
                            $.ajax({
                                url: 'php-functions/fetch-submission-data-history.php',
                                method: 'POST',
                                data: { id: submissionId }, // Ensure the correct key is used
                                success: function(response) {
                                    var detailsData = JSON.parse(response);
                    
                                    // Populate modal with details data
                                    $('#viewhistoryModal .modal-body').html(
                                        '<p><strong>Research Title:</strong> ' + htmlspecialchars(detailsData.research_title) + '</p>' +
                                        '<p><strong>Author:</strong> ' + htmlspecialchars(detailsData.author) + '</p>' +
                                        '<p><strong>Co-authors:</strong> ' + htmlspecialchars(detailsData.co_authors) + '</p>' +
                                        '<p><strong>Abstract:</strong> ' + htmlspecialchars(detailsData.abstract) + '</p>' +
                                        '<p><strong>Keywords:</strong> ' + htmlspecialchars(detailsData.keywords) + '</p>' +
                                        '<p><strong>Date of Submission:</strong> ' + htmlspecialchars(detailsData.dateofsubmission) + '</p>' +
                                        '<p><strong>Comments:</strong></p>' +
                                       '<p><strong>PDF:</strong> <a href="view-pdf.html?file=' + encodeURIComponent(detailsData.file_path.replace('', '../')) + '" target="_blank">View PDF</a></p>'
                                    );
                    
                                    // Show the modal
                                    $('#viewhistoryModal').modal('show');
                                },
                                error: function() {
                                    alert('Failed to fetch details data.');
                                }
                            });
                        });

                        $('.viewupload-btn').on('click', function() {
                            var submissionId = $(this).data('id');
                            $.ajax({
                                url: 'php-functions/fetch-submission-data.php',
                                type: 'GET',
                                data: { submission_id: submissionId },
                                dataType: 'json',
                                success: function(data) {
                                    var modalContent = $('#modalContent');
                                    modalContent.html(`
                                        <p><strong>Research Title:</strong> ${data.research_title}</p>
                                        <p><strong>Author:</strong> ${data.author}</p>
                                        <p><strong>Co-Authors:</strong> ${data.co_authors}</p>
                                        <p><strong>Abstract:</strong> ${data.abstract}</p>
                                        <p><strong>Keywords:</strong> ${data.keywords}</p>         
                                        <p><strong>Date Submitted:</strong> ${data.dateofsubmission}</p>
                                        <p><strong>PDF:</strong> <a href="view-pdf.html?file=${encodeURIComponent(data.file_path.replace('', '../'))}" target="_blank">View PDF</a></p>
                                    `);
                                }
                            });
                        });
    
                        // Attach event listeners for history buttons
                        $('.myhistory-btn').on('click', function() {
                            var submissionId = $(this).data('id');
                            var $row = $(this).closest('tr');
    
                            // Check if history rows are already appended
                            if ($row.next().hasClass('history-row')) {
                                // Toggle visibility of existing history rows
                                $row.nextUntil(':not(.history-row)').toggle();
                                return;
                            }
    
                            $.ajax({
                                url: 'php-functions/fetch-history.php',
                                method: 'POST',
                                data: { submission_id: submissionId },
                                success: function(response) {
                                    var historyData = JSON.parse(response);
                                    var historyHtml = '';
    
                                    if (historyData.length > 0) {
                                        historyData.forEach(function(history) {
                                            historyHtml += '<tr class="history-row">';
                                            historyHtml += '<th scope="row"></th>';
                                            historyHtml += '<td>' + htmlspecialchars(history.research_title) + '</td>';
                                            historyHtml += '<td><button type="button" class="btn viewmyhistory-btn" data-bs-toggle="modal" data-bs-target="#viewhistoryModal" data-id="' + htmlspecialchars(history.id) + '"><i class="bi bi-eye"></i></button></td>';
                                            historyHtml += '<td>' + htmlspecialchars(history.dateofsubmission) + '</td>';
                                            historyHtml += '<td style="color: black;">History</td>'; // Status column
                                            historyHtml += '</tr>';
                                        });
                                    } else {
                                        historyHtml += '<tr class="history-row">';
                                        historyHtml += '<td colspan="6" class="text-center">No history</td>';
                                        historyHtml += '</tr>';
                                    }
    
                                    $row.after(historyHtml);
                                },
                                error: function() {
                                    alert('Failed to fetch history data.');
                                }
                            });
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-center">No submissions yet</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching submissions:', error);
                }
            });
        }
    
        // Fetch submissions on page load
        fetchSubmissions();
    });

});