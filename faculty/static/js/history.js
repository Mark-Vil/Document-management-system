$(document).ready(function() {
    // Fetch years and populate the dropdown
    $.ajax({
        url: 'php-functions/fetch-yearselection.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var $yearFilterchart = $('#yearFilterchart');
            $yearFilterchart.empty();
            $yearFilterchart.append('<option>Choose year...</option>');

            // Populate dropdown with years
            $.each(response, function(index, year) {
                $yearFilterchart.append('<option value="' + year + '">' + year + '</option>');
            });

            // Automatically select the current year
            var currentYear = new Date().getFullYear();
            $yearFilterchart.val(currentYear).change();
        },
        error: function() {
            alert('Failed to fetch years.');
        }
    });

    function fetchHistoryData() {
        var selectedYear = $('#yearFilterchart').val();
    
        $.ajax({
            url: 'php-functions/fetch-doc-history.php',
            method: 'GET',
            data: {
                year: selectedYear
            },
            dataType: 'json',
            success: function(response) {
                var tbody = $('table.doc-history tbody');
                tbody.empty();
    
                if (response.length > 0) {
                    response.forEach(function(item) {
                        var date = new Date(item.dateofsubmission);
                        var formattedDate = formatDateTo12Hour(date);
    
                        // Ensure abstract is defined
                        var abstract = item.abstract ? item.abstract.split(' ').slice(0, 30).join(' ') : '';
                        if (item.abstract && item.abstract.split(' ').length > 30) {
                              abstract += ' ...';
                        }
    
                        var row = '<tr>' +
                            '<td></td>' +
                            '<td>' + item.research_title + '</td>' +
                            '<td class="abstract-cell" data-full-abstract="' + (item.abstract || '') + '" data-truncated-abstract="' + abstract + '">' + abstract + '</td>' +
                            '<td><button class="btn view-btn" data-id="' + item.submission_id + '"><i class="bi bi-eye"></i></button></td>' +
                            '<td>' + formattedDate + '</td>' +
                            '<td>' +
                                '<button class="btn btn-secondary history-btn" data-id="' + item.id + '"><i class="bi bi-clock-history"></i></button>' +
                            '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
    
                    // Add double-click event to toggle abstract
                    $('.abstract-cell').on('dblclick', function() {
                        var isExpanded = $(this).data('expanded');
                        if (isExpanded) {
                            $(this).html($(this).data('truncated-abstract'));
                        } else {
                            $(this).html($(this).data('full-abstract'));
                        }
                        $(this).data('expanded', !isExpanded);
                    });
    
                } else {
                    tbody.append('<tr><td colspan="6" class="text-center">No Data</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching the table data: ' + error);
            }
        });
    
    }

  

    function loadVerificationHistory() {
        $.ajax({
            url: 'php-functions/fetch-verification-history.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var tbody = $('table.verification-history tbody');
                tbody.empty();
    
                if (response && response.length > 0) {
                    response.forEach(function(item) {
                        const corPath = item.cor ? '../' + item.cor : '';
                        
                        var row = '<tr>' +
                            '<td></td>' +
                            '<td>' + item.email + '</td>' +
                            '<td>' + item.id_number + '</td>' +
                            '<td>' + item.first_name + '</td>' +
                            '<td>' + item.middle_name + '</td>' +
                            '<td>' + item.last_name + '</td>' +
                            '<td>' + item.status + '</td>' +
                            '<td><button class="btn view-cor-btn" data-cor="' + corPath + '"><i class="bi bi-eye"></i></button></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
    
                    $('.view-cor-btn').on('click', function() {
                        const corPath = $(this).data('cor');
                        if (corPath) {
                            window.location.href = 'view-pdf.html?file=' + encodeURIComponent(corPath);
                        } else {
                            alert('No COR file available');
                        }
                    });
                } else {
                    tbody.append('<tr><td colspan="9" class="text-center">No Data Available</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching verification history:', error);
                $('table.verification-history tbody').html(
                    '<tr><td colspan="9" class="text-center text-danger">Error loading data</td></tr>'
                );
            }
        });
    }
    
    // Fetch table data on document ready
    fetchHistoryData();
    loadVerificationHistory();

    // Fetch table data when year filter changes
    $('#yearFilterchart').change(fetchHistoryData);




     // Use event delegation for dynamically created elements
     $(document).on('click', '.view-btn', function() {
        var submissionId = $(this).data('id');
        $.ajax({
            url: 'php-functions/fetch-history-submission-data.php',
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
                $('#viewModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching the submission data: ' + error);
            }
        });
    });


    // Use event delegation for dynamically created elements
    $(document).on('click', '.history-btn', function() {
        var submissionId = $(this).data('id');
        var $row = $(this).closest('tr');

        // Check if history rows are already appended
        if ($row.next().hasClass('history-row')) {
            // Toggle visibility of existing history rows
            $row.nextUntil(':not(.history-row)').toggle();
            return;
        }

        $.ajax({
            url: '../php-functions/fetch-history.php',
            method: 'POST',
            data: { submission_id: submissionId },
            success: function(response) {
                var historyData = JSON.parse(response);
                var historyHtml = '';

                if (historyData.length > 0) {
                    historyData.forEach(function(history) {
                        var date = new Date(history.dateofsubmission);
                        var formattedDate = formatDateTo12Hour(date);

                        historyHtml += '<tr class="history-row">';
                        historyHtml += '<th scope="row"></th>';
                        historyHtml += '<td>' + htmlspecialchars(history.research_title) + '</td>';
                        historyHtml += '<td>' + htmlspecialchars(history.abstract) + '</td>';
                        historyHtml += '<td><button type="button" class="btn viewhistory-btn" data-bs-toggle="modal" data-bs-target="#viewhistoryModal" data-id="' + htmlspecialchars(history.id) + '"><i class="bi bi-eye"></i></button></td>';
                        historyHtml += '<td>' + formattedDate + '</td>';
                        historyHtml += '<td style="color: black;">History</td>';
                        historyHtml += '</tr>';
                    });
                } else {
                    historyHtml += '<tr class="history-row">';
                    historyHtml += '<td colspan="6" class="text-center">No history data</td>';
                    historyHtml += '</tr>';
                }

                $row.after(historyHtml);
            },
            error: function() {
                alert('Failed to fetch history data.');
            }
        });
    });

   

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
        $('#archivesearchInput').on('keyup', function() {
            var searchQuery = $(this).val();
            $.ajax({
                url: 'php-functions/search-myarchive.php',
                type: 'POST',
                data: { query: searchQuery },
                dataType: 'json',
                success: function(response) {
                    var tbody = $('#archiveTableBody');
                    tbody.empty();
                    if (response.length > 0) {
                        response.forEach(function(item) {
                            var date = new Date(item.dateofsubmission);
                            var formattedDate = formatDateTo12Hour(date);
    
                            // Highlight matching search terms
                            var researchTitle = highlightMatch(item.research_title, searchQuery);
                            var abstract = highlightMatch(item.abstract, searchQuery);
    
                            var row = '<tr>' +
                                '<td></td>' +
                                '<td>' + researchTitle + '</td>' +
                                '<td>' + abstract + '</td>' +
                                '<td><button class="btn view-btn" data-id="' + item.submission_id + '"><i class="bi bi-eye"></i></button></td>' +
                                '<td>' + formattedDate + '</td>' +
                                '<td>' +
                                '<button class="btn btn-secondary history-btn" data-id="' + item.id + '"><i class="bi bi-clock-history"></i></button>' +
                            '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="6" class="text-center">No Data</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });

    function highlightMatch(text, query) {
        var regex = new RegExp('(' + query + ')', 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }

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


    function formatDateTo12Hour(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return date.getMonth() + 1 + '/' + date.getDate() + '/' + date.getFullYear() + ' ' + strTime;
    }

    // Fetch table data on document ready
    fetchHistoryData();

    // Fetch table data when year filter changes
    $('#yearFilterchart').change(fetchHistoryData);
});