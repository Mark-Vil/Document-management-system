<?php
// Ensure that PHP doesn't silently fail if headers are already sent
if (!headers_sent()) {
    // Set permissive CORS headers to allow any origin
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400'); // cache preflight response for 24 hours

    // Handle preflight OPTIONS request immediately
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204); // No content needed for OPTIONS
        exit();
    }

    header('Content-Type: application/json');
}

// Include the existing email credentials and functions
require_once 'email.php';

// Email template function
function getEmailTemplate($senderEmail, $senderName, $subject, $message)
{
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .email-container {
                max-width: 600px;
                margin: 0 auto;
                font-family: "Segoe UI", Arial, sans-serif;
                line-height: 1.6;
                color: #2c3e50;
                background: #ffffff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .email-header {
                padding: 30px;
                border-bottom: 2px solid #f5f6fa;
            }
            .email-header h1 {
                margin: 0;
                font-size: 24px;
                color: #2c3e50;
                font-weight: 600;
            }
            .email-content {
                padding: 30px;
                background-color: #ffffff;
            }
            .sender-info {
                margin-bottom: 25px;
                padding: 15px;
                background-color: #f8fafc;
                border-radius: 6px;
            }
            .sender-info p {
                margin: 5px 0;
                color: #4a5568;
            }
            .message-content {
                line-height: 1.8;
                color: #2d3748;
            }
            .email-footer {
                padding: 20px 30px;
                background-color: #f8fafc;
                border-top: 1px solid #edf2f7;
                font-size: 13px;
                color: #718096;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
            }
            .highlight {
                color: #2c3e50;
                font-weight: 600;
            }
            .timestamp {
                color: #a0aec0;
                font-size: 12px;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body style="background-color: #f5f6fa; padding: 20px;">
        <div class="email-container">
            <div class="email-header">
                <div class="timestamp">' . date('F j, Y \a\t g:i A') . '</div>
                <h1>' . htmlspecialchars($subject) . '</h1>
            </div>
            <div class="email-content">
                <div class="sender-info">
                    <p><span class="highlight">From:</span> ' . htmlspecialchars($senderName) . '</p>
                    <p><span class="highlight">Email:</span> ' . htmlspecialchars($senderEmail) . '</p>
                </div>
                <div class="message-content">
                    ' . $message . '
                </div>
            </div>
            <div class="email-footer">
                <p>This email was sent via portfolio contact form</p>
            </div>
        </div>
    </body>
    </html>';
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['email']) || !isset($data['name']) || !isset($data['subject']) || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

// Format email body using template
$formattedBody = getEmailTemplate($data['email'], $data['name'], $data['subject'], $data['message']);

// Send email to markvil64@gmail.com
$result = sendEmail('markvil64@gmail.com', "Contact from: " . $data['name'] . " - " . $data['subject'], $formattedBody);

// Return the result
http_response_code($result['status'] === 'success' ? 200 : 500);
echo json_encode($result);