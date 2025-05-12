$(document).ready(function() {
    $('#usersearchField').on('input', function() {
        var searchTerm = $(this).val();
        if (searchTerm.length > 0) {
            $.ajax({
                url: 'php-functions/search-account.php',
                method: 'GET',
                data: { search: searchTerm },
                success: function(data) {
                    var tbody = $('table tbody');
                    tbody.empty();
                    if (data.length > 0) {
                        data.forEach(function(user) {
                            var highlightedEmail = highlightText(user.email, searchTerm);
                            var highlightedFullName = highlightText(user.first_name + ' ' + user.middle_name + ' ' + user.last_name, searchTerm);
                            var row = `
                                <tr>
                                    <td>${user.UserID}</td>
                                    <td>${highlightedEmail}</td>
                                    <td>${highlightedFullName}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${user.UserID}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${user.UserID}">
                                                <li><a class="dropdown-item" href="#" onclick="viewInfo(${user.UserID})">View Info</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deactivateUser(${user.UserID})">Deactivate</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleteUser(${user.UserID})">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-center">No results found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        } else {
            $('table tbody').empty();
            $('table tbody').append('<tr><td colspan="5" class="text-center">No results found</td></tr>');
        }
    });


    $('#searchButton').on('click', function() {
        $('#usersearchField').trigger('input');
    });
});


function highlightText(text, searchTerm) {
    var regex = new RegExp('(' + searchTerm.split(' ').join('|') + ')', 'gi');
    return text.replace(regex, '<span class="highlight">$1</span>');
}

function viewInfo(userId) {
    $.ajax({
        url: 'php-functions/view-user-info.php',
        method: 'GET',
        data: { user_id: userId },
        success: function(data) {
            if (data.status === 'success') {
                var userInfo = data.userInfo;
                var researchData = data.researchData;

                var accountType = '';
                if (userInfo.is_student == 1) {
                    accountType = 'Student';
                } else if (userInfo.is_faculty == 1) {
                    accountType = 'Faculty';
                } else {
                    accountType = 'Unknown';
                }

                var userInfoHtml = `
                    <p><strong>Full Name:</strong> ${userInfo.full_name}</p>
                    <p><strong>ID Number:</strong> ${userInfo.id_number}</p>
                    <p><strong>College:</strong> ${userInfo.college}</p>
                    <p><strong>Department:</strong> ${userInfo.department}</p>
                    <p><strong>Account Type:</strong> ${accountType}</p>
                    <p><strong>Account Status:</strong> ${userInfo.status}</p>
                    <p><strong>Number of Documents:</strong> ${researchData.length}</p>
                `;

                var researchListHtml = '<hr><h5>Research Data</h5><hr>';
                researchData.forEach(function(research, index) {
                    researchListHtml += `
                        <div>
                            <p><strong>Title:</strong> ${research.research_title}</p>
                            <p><strong>Author:</strong> ${research.author}</p>
                            <p><strong>Abstract:</strong> ${research.abstract}</p>
                            <p><strong>View File:</strong> <a href="view-pdf.html?file=${encodeURIComponent(research.file_path)}" target="_blank">View File</a></p>
                            <p><strong>Adviser Name:</strong> ${research.adviser_name}</p>
                        </div>
                    `;
                    if (index < researchData.length - 1) {
                        researchListHtml += '<hr>';
                    }
                });

                $('#userInfoModal .modal-body').html(userInfoHtml + researchListHtml);
                var userInfoModal = new bootstrap.Modal(document.getElementById('userInfoModal'));
                userInfoModal.show();
            } else {
                alert('Error: ' + data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
        }
    });
}

function deactivateUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deactivate it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'php-functions/deactivate-user.php',
                method: 'POST',
                data: { user_id: userId },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            'Deactivated!',
                            'User has been deactivated.',
                            'success'
                        ).then(() => {
                            $('#usersearchField').trigger('input');
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
    });
}

function deleteUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deactivate it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'php-functions/delete-user.php',
                method: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Success!',
                            'User has been deleted.',
                            'success'
                        ).then(() => {
                            $('#usersearchField').trigger('input');
                        });
                    } else {
                        Swal.fire('Error', response.error, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                    Swal.fire('Error', 'An error occurred while deleting the user.', 'error');
                }
            });
        }
    });
}