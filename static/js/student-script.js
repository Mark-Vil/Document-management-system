$(document).ready(function() {
    // Add new co-author input field
    $('#add-coauthor-btn').click(function() {
      var newInput = $('<input class="form-control mb-2" name="coauthorname[]" type="text" placeholder="Enter Co-Author Full Name">');
      $('#coauthor-container').append(newInput);
    });

    // Add new keyword input field
    $('#add-keywords-btn').click(function() {
      var newkeyInput = $('<input class="form-control mb-2" name="addkeywords[]" type="text" placeholder="Enter Keywords">');
      $('#addkeywords-container').append(newkeyInput);
    });

    // Validate file input
    $('#inputfile').change(function() {
      var file = this.files[0];
      if (file && file.type !== 'application/pdf') {
        Swal.fire({
          title: 'Error!',
          text: 'Only PDF files are allowed',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        this.value = ''; // Clear the input
      }
    });

    $(document).ready(function() {
      $('[data-bs-toggle="tooltip"]').tooltip();

      $('#advisorSelect').change(function() {
        if ($(this).val() !== "") {
          $('#advisorInput').prop('disabled', true);
        } else {
          $('#advisorInput').prop('disabled', false);
        }
      });
    
      $('#advisorInput').on('input', function() {
        if ($(this).val() !== "") {
          $('#advisorSelect').prop('disabled', true);
        } else {
          $('#advisorSelect').prop('disabled', false);
        }
    }); 
   }); 

    // Handle form submission
  $('#submission').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission

    var advisorCode = $('#advisorInput').val().trim();
    var advisorSelect = $('#advisorSelect').val().trim();

    if (advisorCode || advisorSelect) {
      var selectedAdvisorCode = advisorCode || advisorSelect;

      // Validate advisor code
      $.ajax({
        url: "php-functions/validate-advisor.php",
        type: 'POST',
        data: { advisor_code: selectedAdvisorCode },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            var advisorInfo = response.data;
            var advisorHtml = `
              <div class="d-flex flex-column align-items-center">
                <img src="${advisorInfo.profile_path}" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                <p><strong>Submit To:</strong> ${advisorInfo.first_name} ${advisorInfo.last_name}</p>
              </div>
            `;
            if (advisorInfo.is_verified === 0) {
              advisorHtml += `<p class="text-danger text-center">This adviser is not verified by the system. You cannot submit on this account.</p>`;
              $('#confirmSubmitButton').hide();
            } else {
              $('#confirmSubmitButton').show();
            }
            $('#advisor-info').html(advisorHtml);
            $('#advisorModal').modal('show');
          } else {
            Swal.fire({
              title: 'Error!',
              text: response.message,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'Error!',
            text: 'An error occurred: ' + error,
            icon: 'error',
            confirmButtonText: 'OK'
          });
          console.log(xhr.responseText);
        }
      });
    } else {
      Swal.fire({
        title: 'Error!',
        text: 'Advisor code is required',
        icon: 'error',
        confirmButtonText: 'OK'
      });
    }
  });

    $(document).ready(function() {
      $.ajax({
        url: 'php-functions/fetch-adviser.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
          if (data.success) {
            $('#advisorSelect').append(
              $('<option>', {
                value: data.advisor_code,
                text: data.advisor_name
              })
            );
          } else {
            alert('Failed to fetch advisor information.');
          }
        },
        error: function() {
          alert('Error in AJAX request.');
        }
      });
    });

    // Handle confirmation button click
    $('#confirmSubmitButton').click(function() {
      var formData = new FormData($('#submission')[0]);

      Swal.fire({
        title: 'Uploading...',
        html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
        showConfirmButton: false,
        allowOutsideClick: false
      });

      $.ajax({
        url: "php-functions/submit-research.php", // URL to the PHP script that handles the form submission
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // Parse the JSON response
          var jsonResponse = JSON.parse(response);

          if (jsonResponse.status === 'success') {
            Swal.fire({
              title: 'Success!',
              text: jsonResponse.message,
              icon: 'success',
              confirmButtonText: 'OK'
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload(); // Reload the page
              }
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: jsonResponse.message,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'Error!',
            text: 'An error occurred: ' + error,
            icon: 'error',
            confirmButtonText: 'OK'
          });
          console.log(xhr.responseText);
        }
      });
    });
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
                    <p><strong>Date Accepted:</strong> ${data.date_accepted}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Comments:</strong> ${data.comments}</p>
                    <p><strong>PDF:</strong> <a href="view-pdf.html?file=${encodeURIComponent(data.file_path)}" target="_blank">View PDF</a></p>
                `);
            }
        });
    });
});

$(document).ready(function() {
    $('.edit-btn').on('click', function() {
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

$(document).ready(function() {
    // Event listener for history button
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
    
    // Event listener for view button
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