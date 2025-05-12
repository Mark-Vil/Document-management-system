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
            url: 'php-functions/fetch-departments.php',
            type: 'POST',
            data: { college_code: collegeCode, year: year },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('table.table tbody');
                tableBody.empty();

                if (response.error) {
                    alert(response.error);
                } else if (response.length > 0) {
                    response.forEach(function(item) {
                        var row = '<tr>' +
                            '<th scope="row"></th>' +
                            '<td>' + item.college_name + '</td>' +
                            '<td>' + item.department_name + '</td>' +
                            '<td>' + item.total_documents + '</td>' +
                            '<td><i class="bi bi-eye view-department" style="font-size: 1.5rem; cursor: pointer;" data-department-code="' + item.department_code + '" data-year="' + year + '"></i></td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    var row = '<tr>' +
                        '<td colspan="5" class="text-center">No data available for the selected filters.</td>' +
                        '</tr>';
                    tableBody.append(row);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching departments: ' + error);
            }
        });
    }

    // Function to fetch all departments for all colleges
    function fetchAllDepartments(year) {
        $.ajax({
            url: 'php-functions/fetch-all-departments.php',
            type: 'POST',
            data: { year: year },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('table.table tbody');
                tableBody.empty();

                if (response.error) {
                    alert(response.error);
                } else if (response.length > 0) {
                    response.forEach(function(item) {
                        var row = '<tr>' +
                            '<th scope="row"></th>' +
                            '<td>' + item.college_name + '</td>' +
                            '<td>' + item.department_name + '</td>' +
                            '<td>' + item.total_documents + '</td>' +
                            '<td><i class="bi bi-eye view-department" style="font-size: 1.5rem; cursor: pointer;" data-department-code="' + item.department_code + '" data-year="' + year + '"></i></td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    var row = '<tr>' +
                        '<td colspan="5" class="text-center">No data available for the selected filters.</td>' +
                        '</tr>';
                    tableBody.append(row);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching departments: ' + error);
            }
        });
    }

    

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




    $(document).on('click', '.view-department', function() {
        var departmentCode = $(this).data('department-code');
        var selectedYear = $(this).data('year');
        $.ajax({
            url: 'php-functions/fetch-department-documents.php',
            type: 'POST',
            data: { department_code: departmentCode, year: selectedYear },
            dataType: 'json',
            success: function(response) {
                var modalBody = $('#documentsModal .modal-body');
                modalBody.empty();

                if (response.error) {
                    modalBody.append('<p>' + response.error + '</p>');
                } else if (response.length > 0) {
                    response.forEach(function(item) {
                        var row = '<div class="document-item">' +
                            '<h5>' + item.research_title + '</h5>' +
                            '<p><strong>Author:</strong> ' + item.author + '</p>' +
                            '<p><strong>Date of Submission:</strong> ' + item.dateofsubmission + '</p>' +
                            '<p><strong>Date Accepted:</strong> ' + item.date_accepted + '</p>' +
                            '<button class="btn btn-primary view-pdf" data-file-path="' + item.file_path + '">View PDF</button>' +
                            '<button class="btn btn-info view-info" data-id="' + item.id + '"><i class="bi bi-info-circle"></i> Information</button>' +
                            '</div><hr>';
                        modalBody.append(row);
                    });
                } else {
                    modalBody.append('<p>No documents available for this department.</p>');
                }

                $('#documentsModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching documents: ' + error);
            }
        });
    });

    // Event listener for View PDF button click
    $(document).on('click', '.view-pdf', function() {
        var filePath = $(this).data('file-path');
        window.location.href = 'view-pdf.html?file=' + encodeURIComponent(filePath);
    });

    $(document).on('click', '.view-info', function() {
        var documentId = $(this).data('id');
        $.ajax({
            url: 'php-functions/fetch-document-info.php',
            type: 'POST',
            data: { id: documentId },
            dataType: 'json',
            success: function(response) {
                var modalBody = $('#infoModal .modal-body');
                modalBody.empty();

                if (response.error) {
                    modalBody.append('<p>' + response.error + '</p>');
                } else {
                    var info = '<h5>' + response.research_title + '</h5>' +
                        '<p><strong>Author:</strong> ' + response.author + '</p>' +
                        '<p><strong>Co-Authors:</strong> ' + response.co_authors + '</p>' +
                        '<p><strong>Abstract:</strong> ' + response.abstract + '</p>' +
                        '<p><strong>Adviser Name:</strong> ' + response.adviser_name + '</p>' +
                        '<p><strong>Date of Submission:</strong> ' + response.dateofsubmission + '</p>' +
                        '<p><strong>Date Accepted:</strong> ' + response.date_accepted + '</p>' +
                        '<p><strong>Status:</strong> ' + response.status + '</p>';
                    modalBody.append(info);
                }

                $('#documentsModal').modal('hide');
                $('#infoModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching document information: ' + error);
            }
        });
    });

    // Event listener for Info Modal close
    $('#infoModal').on('hidden.bs.modal', function () {
        $('#documentsModal').modal('show');
    });

    

});