$(document).ready(function() {
    // Function to load data on initial load
    function loadData(year) {
        $.ajax({
            url: 'php-functions/admin-dashboard-data.php',
            type: 'POST',
            data: { year: year },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Populate year filter options
                    var yearFilter = $('#yearFilterchart');
                    yearFilter.empty();
                    yearFilter.append('<option value="" selected disabled>Choose year...</option>');
                    $.each(response.years, function(index, year) {
                        yearFilter.append('<option value="' + year + '">' + year + '</option>');
                    });

                    // Set the current year as selected
                    yearFilter.val(year);

                    // Update total counts
                    $('#totalColleges').text(response.totalColleges);
                    $('#totalDepartments').text(response.totalDepartments);
                    $('#totalDocuments').text(response.totalDocuments);

                    // Fetch most viewed college
                    fetchMostViewedCollege(year);
                    fetchMostDownloadedCollege(year);
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('An error occurred: ' + error);
            }
        });
    }

     // Fetch most viewed college
     $(document).ready(function() {
        // Fetch most viewed college
        function fetchMostViewedCollege(selectedYear) {
            $.ajax({
                url: 'php-functions/most-viewed-college.php',
                type: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var viewedData = response.data;
                        if (viewedData) {
                            var mostViewedHtml = `
                                <div class="most-viewed-college-item d-flex">
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="most-viewed-college-content">
                                        <span>${viewedData.college_name} <i class="bi bi-eye" title="${viewedData.view_count}"></i></span>
                                    </div>
                                </div>
                            `;
                            $('.most-viewed-college').html(mostViewedHtml);
                        } else {
                            $('.most-viewed-college').html('<p>No data found.</p>');
                        }
                    } else {
                        console.error('Error fetching most viewed college:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching most viewed college:', error);
                }
            });
        }
    
        // Fetch most downloaded college
        function fetchMostDownloadedCollege(selectedYear) {
            $.ajax({
                url: 'php-functions/most-downloaded-college.php',
                type: 'POST',
                data: { year: selectedYear },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var downloadData = response.data;
                        if (downloadData) {
                            var mostDownloadedHtml = `
                                <div class="most-downloaded-college-item d-flex">
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="most-downloaded-college-content">
                                        <span>${downloadData.college_name} <i class="bi bi-eye" title="${downloadData.download_count}"></i></span>
                                    </div>
                                </div>
                            `;
                            $('.most-downloaded-college').html(mostDownloadedHtml);
                        } else {
                            $('.most-downloaded-college').html('<p>No data found.</p>');
                        }
                    } else {
                        console.error('Error fetching most downloaded college:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching most downloaded college:', error);
                }
            });
        }
    
        // Event delegation for view count toggle
        $(document).on('click', '.bi-eye', function() {
            var title = $(this).attr('title');
            if ($(this).next('.view-count, .download-count').length) {
                $(this).next('.view-count, .download-count').remove();
            } else {
                $(this).after(`<span class="view-count download-count" style="margin-left: 5px;">${title}</span>`);
            }
        });
    
        // Example usage
        var selectedYear = new Date().getFullYear();
        fetchMostViewedCollege(selectedYear);
        fetchMostDownloadedCollege(selectedYear);
    });

    // Get the current year
    var currentYear = new Date().getFullYear();
    loadData(currentYear);

    // Event listener for year filter change
    $('#yearFilterchart').on('change', function() {
        var selectedYear = $(this).val();
        if (selectedYear !== '') {
            loadData(selectedYear);
        }
    });
});