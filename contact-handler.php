<?php
// Contact Form Handler for YMR ShipChandlers
// This file processes the contact form and sends formatted emails

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data and sanitize
    $fullName = htmlspecialchars(trim($_POST['Full_Name']));
    $email = filter_var(trim($_POST['Email_Address']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['Phone_Number']));
    $vesselName = htmlspecialchars(trim($_POST['Vessel_Name']));
    $serviceRequired = htmlspecialchars(trim($_POST['Service_Required']));
    $message = htmlspecialchars(trim($_POST['Message']));
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.html?error=invalid_email");
        exit;
    }
    
    // Validate required fields
    if (empty($fullName) || empty($email) || empty($message)) {
        header("Location: contact.html?error=missing_fields");
        exit;
    }
    
    // Email configuration
    $to = "ymrshipchandlers@gmail.com";
    $subject = "New Contact Form Submission - YMR ShipChandlers";
    
    // Get current date and time
    date_default_timezone_set('Asia/Kolkata');
    $submissionDate = date('d-m-Y');
    $submissionTime = date('h:i A');
    
    // Create HTML email body
    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #1a4d7a; color: white; padding: 20px; text-align: center; }
            .content { background: #f8f9fa; padding: 20px; }
            .field { margin-bottom: 15px; padding: 10px; background: white; border-left: 4px solid #e67e22; }
            .label { font-weight: bold; color: #1a4d7a; }
            .value { margin-top: 5px; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
                <p>YMR ShipChandlers Website</p>
            </div>
            
            <div class='content'>
                <div class='field'>
                    <div class='label'>Submission Date & Time:</div>
                    <div class='value'>{$submissionDate} at {$submissionTime} IST</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Full Name:</div>
                    <div class='value'>{$fullName}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Email Address:</div>
                    <div class='value'>{$email}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Phone Number:</div>
                    <div class='value'>" . (!empty($phone) ? $phone : 'Not provided') . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Vessel Name:</div>
                    <div class='value'>" . (!empty($vesselName) ? $vesselName : 'Not provided') . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Service Required:</div>
                    <div class='value'>" . (!empty($serviceRequired) ? $serviceRequired : 'Not specified') . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Message:</div>
                    <div class='value'>" . nl2br($message) . "</div>
                </div>
            </div>
            
            <div class='footer'>
                <p>This email was sent from the YMR ShipChandlers contact form</p>
                <p>Please respond to: {$email}</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: YMR ShipChandlers Website <noreply@ymrshipchandlers.com>" . "\r\n";
    $headers .= "Reply-To: {$email}" . "\r\n";
    
    // Send email
    if (mail($to, $subject, $emailBody, $headers)) {
        // Success - redirect to thank you page
        header("Location: contact.html?success=1");
    } else {
        // Error - redirect with error message
        header("Location: contact.html?error=send_failed");
    }
    exit;
    
} else {
    // If accessed directly, redirect to contact page
    header("Location: contact.html");
    exit;
}
?>
