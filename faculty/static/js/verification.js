document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('.verified-icon, .not-verified-icon');
    tooltips.forEach(function(tooltip) {
        tooltip.addEventListener('mouseover', function() {
            const title = this.getAttribute('title');
            const tooltipElement = document.createElement('div');
            tooltipElement.className = 'tooltip';
            tooltipElement.innerText = title;
            document.body.appendChild(tooltipElement);

            const rect = this.getBoundingClientRect();
            tooltipElement.style.left = rect.left + window.scrollX + 'px';
            tooltipElement.style.top = rect.top + window.scrollY - tooltipElement.offsetHeight + 'px';
        });

        tooltip.addEventListener('mouseout', function() {
            const tooltipElement = document.querySelector('.tooltip');
            if (tooltipElement) {
                tooltipElement.remove();
            }
        });
    });
});
$(document).ready(function() {
    // Handle Approve button click
    $('.approve-btn').click(function() {
        var email = $(this).data('email');

        Swal.fire({
            title: 'Please Wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/approve-reject-student.php',
            type: 'POST',
            data: { email: email, action: 'approve' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Student verification approved successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload the page after the alert is closed
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing the request. Please try again.',
                    timer: 2000,
                    showConfirmButton: false
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Handle Reject button click
    $('.reject-btn').click(function() {
        var email = $(this).data('email');

        Swal.fire({
            title: 'Please wait...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });
        $.ajax({
            url: 'php-functions/approve-reject-student.php',
            type: 'POST',
            data: { email: email, action: 'reject' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Student verification rejected successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing the request. Please try again.',
                    timer: 2000,
                    showConfirmButton: false
                });
                console.error(xhr.responseText);
            }
        });
    });
});