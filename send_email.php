<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $service = htmlspecialchars(trim($_POST['service'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validation
    if (empty($name) || empty($email) || empty($service) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }
    
    // Email configuration
    $to = 'info@creativeidea.lk';
    $subject = 'New Inquiry from CreativeiDEA Website - ' . $service;
    
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #667eea; }
            .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>New Website Inquiry</h1>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Name:</div>
                    <div>{$name}</div>
                </div>
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div>{$email}</div>
                </div>
                <div class='field'>
                    <div class='label'>Phone:</div>
                    <div>" . ($phone ?: 'Not provided') . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Service Interested In:</div>
                    <div>{$service}</div>
                </div>
                <div class='field'>
                    <div class='label'>Message:</div>
                    <div>{$message}</div>
                </div>
            </div>
            <div class='footer'>
                <p>This inquiry was submitted from the CreativeiDEA website</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: CreativeiDEA Website <info@creativeidea.lk>" . "\r\n";
    $headers .= "Reply-To: {$email}" . "\r\n";
    
    
    $num1 = (int) $_POST['puzzle_num1'];
$num2 = (int) $_POST['puzzle_num2'];
$userAnswer = (int) $_POST['puzzle_answer'];

if ($userAnswer !== ($num1 + $num2)) {
    die('Spam check failed. Please go back and try again.');
}
    
    // Send email
    if (mail($to, $subject, $email_body, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again or contact us directly.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>