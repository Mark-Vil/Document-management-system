$(document).ready(function() {
    // Fetch years for the year filter
    function fetchYears() {
        $.ajax({
            url: 'php-functions/fetch-years.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var yearFilter = $('#yearSelect');
                yearFilter.empty();
                yearFilter.append('<option value="" selected disabled>Choose year...</option>');

                var currentYear = new Date().getFullYear();
                var yearFound = false;

                if (response.length > 0) {
                    response.forEach(function(year) {
                        var option = '<option value="' + year + '">' + year + '</option>';
                        yearFilter.append(option);
                        if (year == currentYear) {
                            yearFound = true;
                        }
                    });

                    if (yearFound) {
                        yearFilter.val(currentYear);
                    }
                } else {
                    alert('No years found.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching years: ' + error);
            }
        });
    }

    $(document).ready(function() {
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
                            fetchAllDocuments(); // Fetch all documents for the selected department and year
                        } else {
                            // No department with archives found
                            showNoDataInTable();
                        }
                    } else {
                        departmentSelect.append('<option disabled>No Data</option>');
                        showNoDataInTable();
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while fetching the department data: ' + error);
                }
            });
        }
    
        $("#searchField").on("input", function() {
            var searchTerm = $(this).val();
            var departmentCode = $('#departmentSelect').val();
            var year = $('#yearSelect').val() || new Date().getFullYear(); // Default to current year if no year is selected
    
            if (searchTerm.length > 0) {
                $.ajax({
                    url: "php-functions/search-documents.php",
                    method: "GET",
                    data: { 
                        search: searchTerm,
                        department_code: departmentCode,
                        year: year
                    },
                    success: function(data) {
                        var documentTableBody = $("#college-document");
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
    
        function highlightText(text, searchTerm) {
            if (!text) return ''; // Handle null or undefined values
            var regex = new RegExp("(" + searchTerm.split(" ").join("|") + ")", "gi");
            return text.replace(regex, '<span class="highlight">$1</span>');
        }

        function fetchAllDocuments() {
            var departmentCode = $('#departmentSelect').val();
            var year = $('#yearSelect').val() || new Date().getFullYear(); // Default to current year if no year is selected
        
            $.ajax({
                url: 'php-functions/fetch-all-documents.php',
                method: 'GET',
                data: {
                    department_code: departmentCode,
                    year: year
                },
                dataType: 'json',
                success: function(data) {
                    var documentTableBody = $("#college-document");
                    documentTableBody.empty();
                    if (data.length > 0) {
                        data.forEach(function(document) {
                            var row = `
                                <tr>
                                    <td>${document.title}</td>
                                    <td>${document.author_email}</td>
                                    <td>${document.dateofsubmission}</td>
                                    <td>${document.status}</td>
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
                        documentTableBody.append("<tr><td class='text-center' colspan='5' style='text-align: center;'>No results found</td></tr>");
                    }
                },
                error: function(xhr, status, error) {
                    var documentTableBody = $("#college-document");
                    documentTableBody.empty();
                    documentTableBody.append("<tr><td class='text-center' colspan='5' style='text-align: center;'>No data available</td></tr>");
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }

        $(document).ready(function() {
    fetchAllDocuments();
});
    
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

        $(document).ready(function() {
            // Event delegation for delete button
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
                            url: 'php-functions/delete-document.php',
                            method: 'POST',
                            data: { id: documentId },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Document deleted successfully.',
                                        'success'
                                    );
                                    // Optionally, remove the document from the DOM
                                    $(`button[data-id="${documentId}"]`).closest('li').remove();
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        'Failed to delete document: ' + response.error,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting document:', error);
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting the document.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });


    
        // Fetch years on document ready
        fetchYears();
    
        // Fetch departments on document ready
        fetchDepartments();

        $('#departmentSelect, #yearSelect').change(function() {
            fetchAllDocuments();
        });
    
    });

    
    
});