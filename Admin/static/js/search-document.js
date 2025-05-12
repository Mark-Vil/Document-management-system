$(document).ready(function() {

    function fetchYears(callback) {
        $.ajax({
            url: 'php-functions/fetch-years.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var yearFilter = $('#yearFilter');
                yearFilter.empty();
                yearFilter.append('<option selected disabled>Choose year...</option>');

                if (response.error) {
                    alert(response.error);
                } else if (response.length > 0) {
                    response.forEach(function(item) {
                        var option = '<option value="' + item.year + '">' + item.year + '</option>';
                        yearFilter.append(option);
                    });

                    // Set the current year as selected
                    var currentYear = new Date().getFullYear();
                    yearFilter.val(currentYear);

                    // Execute the callback function
                    if (callback) {
                        callback(currentYear);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching years: ' + error);
            }
        });
    }

     // Function to fetch available colleges
    function fetchColleges(callback) {
        $.ajax({
            url: 'php-functions/fetch-colleges.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var collegeFilter = $('#selectedCollege');
                collegeFilter.empty();
                collegeFilter.append('<option value="" selected disabled>Select college...</option>');

                if (response.error) {
                    alert(response.error);
                } else if (response.colleges.length > 0) {
                    response.colleges.forEach(function(college) {
                        var option = '<option value="' + college.college_code + '">' + college.college_name + '</option>';
                        collegeFilter.append(option);
                    });

                    // Execute the callback function
                    if (callback) {
                        callback(response.colleges);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching colleges: ' + error);
            }
        });
    }

    // Function to fetch departments based on the selected college
    function fetchDepartments(collegeCode, year) {
        if (!collegeCode) {
            fetchAllDepartments(year);
            return;
        }
    
        $.ajax({
            url: 'php-functions/fetch-college-documents.php',
            type: 'POST',
            data: { college_code: collegeCode, year: year },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('#documentTableBody');
                tableBody.empty();
    
                if (response.status === 'error') {
                    alert(response.message);
                } else if (response.data.length > 0) {
                    response.data.forEach(function(item) {
                        var row = '<tr>' +
                            '<th scope="row">' + item.id + '</th>' +
                            '<td>' + item.research_title + '</td>' +
                            '<td>' + item.author_email + '</td>' +
                            '<td>' + item.date_of_submission + '</td>' +
                            '<td>' + item.date_accepted + '</td>' +
                            '<td>' + item.status + '</td>' +
                           '<td>' +
                            '<div class="dropdown">' +
                                '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + item.id + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                                    'Actions' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + item.id + '">' +
                                    '<li><button class="dropdown-item btn btn-info details-btn" data-id="' + item.id + '">Details</button></li>' +
                                    '<li><button class="dropdown-item btn btn-info delete-btn" data-id="' + item.id + '">Delete</button></li>' +
                                '</ul>' +
                            '</div>' +
                        '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    var row = '<tr>' +
                        '<td colspan="6" class="text-center">No data available for the selected filters.</td>' +
                        '</tr>';
                    tableBody.append(row);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching documents: ' + error);
            }
        });
    }
    
    

    // Function to fetch all departments for all colleges
    function fetchAllDepartments(year) {
        $.ajax({
            url: 'php-functions/fetch-all-documents.php',
            type: 'POST',
            data: { year: year },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('#documentTableBody');
                tableBody.empty();
    
                if (response.status === 'error') {
                    alert(response.message);
                } else if (response.data.length > 0) {
                    response.data.forEach(function(item) {
                        var row = '<tr>' +
                            '<th scope="row">' + item.id + '</th>' +
                            '<td>' + item.research_title + '</td>' +
                            '<td>' + item.author_email + '</td>' +
                            '<td>' + item.date_of_submission + '</td>' +
                            '<td>' + item.date_accepted + '</td>' +
                            '<td>' + item.status + '</td>' +
                          '<td>' +
                            '<div class="dropdown">' +
                                '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + item.id + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                                    'Actions' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + item.id + '">' +
                                    '<li><button class="dropdown-item btn btn-info details-btn" data-id="' + item.id + '">Details</button></li>' +
                                    '<li><button class="dropdown-item btn btn-info delete-btn" data-id="' + item.id + '">Delete</button></li>' +
                                '</ul>' +
                            '</div>' +
                        '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    var row = '<tr>' +
                        '<td colspan="7" class="text-center">No data available for the selected filters.</td>' +
                        '</tr>';
                    tableBody.append(row);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching documents: ' + error);
            }
        });
    }
    

    $("#searchField").on("input", function() {
        var searchTerm = $(this).val();
        var selectedCollege = $('#selectedCollege').val(); // Assuming you have an input or select element with id="collegeCode"
        var year = $("#yearFilter").val(); // Assuming you have an input or select element with id="yearFilter"
    
        if (searchTerm.length > 0) {
            $.ajax({
                url: "php-functions/search-documents.php",
                method: "GET",
                data: { search: searchTerm, college_code: selectedCollege, year: year },
                success: function(data) {
                    var documentTableBody = $("#documentTableBody");
                    documentTableBody.empty();
                    if (data.length > 0) {
                        data.forEach(function(document) {
                            var row = `
                                <tr>
                                    <td>${highlightText(document.id.toString(), searchTerm)}</td>
                                    <td>${highlightText(document.title, searchTerm)}</td>
                                    <td>${highlightText(document.author_email, searchTerm)}</td>
                                    <td>${highlightText(document.dateofsubmission, searchTerm)}</td>
                                    <td>${highlightText(document.date_accepted, searchTerm)}</td>
                                    <td>${highlightText(document.status, searchTerm)}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${document.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${document.id}">
                                                <li><button class="dropdown-item btn btn-info details-btn" data-id="${document.id}">Details</button></li>
                                                <li><button class="dropdown-item btn btn-info delete-btn" data-id="${document.id}">Delete</button></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            documentTableBody.append(row);
                        });
                    } else {
                        documentTableBody.append("<tr><td class='text-center' colspan='7' style='text-align: center;'>No results found</td></tr>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        } else {
            $("#documentTableBody").empty();
            $("#documentTableBody").append("<tr><td class='text-center' colspan='7' style='text-align: center;'>No results found</td></tr>");
        }
    });

     // Fetch available years on page load and then fetch data based on the current year
     fetchYears(function(currentYear) {
        // Fetch available colleges on page load and then fetch all departments for the current year
        fetchColleges(function(colleges) {
            fetchAllDepartments(currentYear);
        });
    });

    // Fetch data based on year filter
    $('#yearFilter').on('change', function() {
        var selectedCollege = $('#selectedCollege').val();
        var selectedYear = $(this).val();
        if (selectedYear === 'Choose year...') {
            alert('Please select a valid year.');
            return;
        }
        fetchDepartments(selectedCollege, selectedYear);
    });

    // Fetch departments based on selected college
    $('#selectedCollege').on('change', function() {
        var selectedCollege = $(this).val();
        var selectedYear = $('#yearFilter').val();
        if (selectedYear === 'Choose year...') {
            alert('Please select a valid year.');
            return;
        }
        fetchDepartments(selectedCollege, selectedYear);
    });

    function highlightText(text, searchTerm) {
        if (!text) return ''; // Handle null or undefined values
        var regex = new RegExp("(" + searchTerm.split(" ").join("|") + ")", "gi");
        return text.replace(regex, '<span class="highlight">$1</span>');
    }


    $(document).on('click', '.details-btn', function() {
        var documentId = $(this).data('id');
        console.log('Document ID:', documentId);
    
        $.ajax({
            url: "php-functions/fetch-document-details.php",
            method: "GET",
            data: { id: documentId },
            dataType: 'json',
            success: function(data) {
                console.log('AJAX Success:', data); // Log the response data
        
                if (data) {
                    function getValue(value, defaultValue = 'N/A') {
                        return value !== null && value !== undefined ? value : defaultValue;
                    }
        
                    function getRole(isFaculty, isStudent) {
                        if (isFaculty === 1) {
                            return 'Faculty';
                        } else if (isStudent === 1) {
                            return 'Student';
                        } else {
                            return 'Unknown';
                        }
                    }
        
                    var detailsHtml = `
                        <p><strong>Research Title:</strong> ${getValue(data.title)}</p>
                        <p><strong>Author:</strong> ${getValue(data.author)}</p>
                        <p><strong>Co-Author:</strong> ${getValue(data.co_authors)}</p>
                        <p><strong>Date Submitted:</strong> ${getValue(data.dateofsubmission)}</p>
                        <p><strong>Date Accepted:</strong> ${getValue(data.date_accepted)}</p>
                        <p><strong>Adviser Name:</strong> ${getValue(data.adviser_name)}</p>
                        <p><strong>Status:</strong> ${getValue(data.status)}</p>
                        <p><strong>Role:</strong> ${getRole(data.is_faculty, data.is_student)}</p>
                        <p><strong>College Name:</strong> ${getValue(data.college_name)}</p>
                         <p><strong>Faculty/Department Name:</strong> ${getValue(data.department_name)}</p>
                        <p><strong>View PDF:</strong> <a href="view-pdf.html?file=${encodeURIComponent(getValue(data.file_path))}" target="_blank">View PDF</a></p>
                    `;
                    $('#viewdetails').html(detailsHtml);
        
                    $('#detailsModal').modal('show');
                } else {
                    console.error('No data returned');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });
  
    // Event listener for delete button
    $(document).on('click', '.delete-btn', function() {
        var documentId = $(this).data('id');
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
                    url: "php-functions/delete-document.php",
                    method: "POST",
                    data: { id: documentId },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                'Document has been deleted.',
                                'success'
                            );
                            $("#searchField").trigger('input'); // Refresh the search results
                        } else {
                            Swal.fire(
                                'Failed!',
                                'Failed to delete document: ' + response.error,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'AJAX Error: ' + status + error,
                            'error'
                        );
                    }
                });
            }
        });
    });

});