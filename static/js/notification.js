
$(document).ready(function() {
    // Fetch notifications
    function fetchNotifications() {

        $.ajax({
            url: 'php-functions/fetch-notifications.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var notifications = response.notifications;
                    var unseenCount = response.unseen_count;

                    // Update badge number
                    $('#notificationBadge').text(unseenCount);
                    $('#notificationCount').text(unseenCount);

                    // Clear existing notifications and loader
                    $('#notificationList').find('.notification-item').remove();

                    // Append new notifications
                    notifications.forEach(function(notification) {
                        var notificationClass = notification.is_viewed == 0 ? 'new-notification' : '';
                        var notificationHtml = `
                            <li class="notification-item ${notificationClass}">
                                <div>
                                    <h4>${notification.title}</h4>
                                    <p>${notification.message}</p>
                                    <small>${notification.created_at}</small>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        `;
                        $('#notificationList').append(notificationHtml);
                    });
                } else {
                    console.error('Error fetching notifications:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching notifications:', error);
            }
        });
    }

    // Update notifications as viewed
    function updateNotificationsAsViewed() {
        $.ajax({
            url: 'php-functions/update-notifications.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Notifications updated as viewed
                    $('#notificationBadge').text('0'); // Update badge number to 0
                    $('#notificationCount').text('0'); // Update notification count to 0
                    // Remove highlight from new notifications
                    $('.notification-item.new-notification').removeClass('new-notification');
                } else {
                    console.error('Error updating notifications:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating notifications:', error);
            }
        });
    }
    // Fetch notifications on page load
    fetchNotifications();

    // Update notifications as viewed when the dropdown is closed
    $('#notificationDropdown').on('hidden.bs.dropdown', function() {
        updateNotificationsAsViewed();
    });
});