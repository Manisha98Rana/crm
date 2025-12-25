<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the timezone to IST
date_default_timezone_set('Asia/Kolkata');

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'vendor/autoload.php';

// Database connection details
$servername = "localhost";
$username = "u444558326_course_details";
$password = "e*S8KlqQImh5";
$dbname = "u444558326_course_details";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Allow CORS from specific origins
$allowed_origins = [
    'https://formfees.com',
    'https://formsadda.com',
    'https://cuetsamarth.com',
    'https://amity-ranchi.online-applications.co.in',
    'https://abbs.online-applications.co.in',
    'https://allianceuniversity.online-applications.co.in',
    'https://bennett.online-applications.co.in',
    'https://amity-kolkata.online-applications.co.in',
    'https://amity-patna.online-applications.co.in',
    'https://amity-panjab.online-applications.co.in',
    'https://amity-noida.online-applications.co.in',
    'https://upes.online-applications.co.in',
    'https://bml.online-applications.co.in',
    'https://dbs.online-applications.co.in',
    'https://glbajaj.online-applications.co.in',
    'https://lloydbusiness.online-applications.co.in',
    'https://paruluniversity.online-applications.co.in',
    'https://marwadiuniversity.online-applications.co.in',
    'https://chanakyauniversity.online-applications.co.in',
    'https://shivalik.online-applications.co.in',
    'https://jagsom.online-applications.co.in/',
    'https://iibs.online-applications.co.in/',
    'https://rvu.online-applications.co.in/'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: *");
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (isset($data['student_name'], $data['email'], $data['address'], $data['phone'], $data['site_url'], $data['course_name'])) {
    $student_name = $conn->real_escape_string(trim($data['student_name']));
    $email = $conn->real_escape_string(trim($data['email']));
    $address = $conn->real_escape_string(trim($data['address']));
    $phone = $conn->real_escape_string(trim($data['phone']));
    $site_url = $conn->real_escape_string(trim($data['site_url']));
    $course_name = $conn->real_escape_string(trim($data['course_name']));

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit();
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        echo json_encode(["status" => "error", "message" => "Invalid phone number"]);
        exit();
    }

    // Get current date and time in IST
    $created_at = date('Y-m-d H:i:s');

    // Prepare and execute the SQL query
    $sql = "INSERT INTO lead_enquiry (student_name, email, address, mobile, site_url, course_name, created_at) 
            VALUES ('$student_name', '$email', '$address', '$phone', '$site_url', '$course_name', '$created_at')";

    if ($conn->query($sql) === TRUE) {
        // Database insertion successful, now send emails
        
        // ========== PHPMAILER CONFIGURATION ==========
        function initializeMailer() {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@formsadda.com';
            $mail->Password = 'Admin@321';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPDebug = 0; // Set to 2 for debugging
            
            return $mail;
        }

        $emails_sent = [];
        $email_errors = [];

        try {
            // ========== SEND EMAIL TO STUDENT ==========
            $mail = initializeMailer();
            $mail->setFrom('admin@formsadda.com', 'Forms Adda - Course Registration');
            $mail->addAddress($email, $student_name);
            $mail->addReplyTo('admin@formsadda.com', 'Forms Adda Support');
            $mail->isHTML(true);
            $mail->Subject = "Registration Successful - " . $course_name;
            
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { border: 1px solid #ddd; padding: 20px; border-radius: 0 0 5px 5px; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        table td { padding: 10px; border: 1px solid #ddd; }
                        table td:first-child { font-weight: bold; background-color: #f5f5f5; width: 40%; }
                        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Registration Confirmation</h2>
                        </div>
                        <div class='content'>
                            <p>Hi <strong>{$student_name}</strong>,</p>
                            <p>Thank you for registering with <strong>Forms Adda</strong>! Your registration has been successfully received.</p>
                            
                            <h3>Your Registration Details:</h3>
                            <table>
                                <tr>
                                    <td>Name</td>
                                    <td>{$student_name}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{$email}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>{$phone}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{$address}</td>
                                </tr>
                                <tr>
                                    <td>Course</td>
                                    <td><strong>{$course_name}</strong></td>
                                </tr>
                                <tr>
                                    <td>Registration Date</td>
                                    <td>{$created_at}</td>
                                </tr>
                            </table>
                            
                            <p>Our team will review your registration and contact you shortly with further details about your course and enrollment process.</p>
                            
                            <p><strong>If you have any questions, feel free to reply to this email or contact our support team.</strong></p>
                            
                            <div class='footer'>
                                <p>Best regards,<br><strong>Forms Adda Team</strong></p>
                                <p>This is an automated email. Please do not reply directly to this message.</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $mail->AltBody = "Registration Confirmation\n\nHi {$student_name},\n\nThank you for registering with Forms Adda!\n\nCourse: {$course_name}\nRegistration Date: {$created_at}\n\nOur team will contact you shortly.\n\nBest regards,\nForms Adda Team";
            
            $mail->send();
            $emails_sent[] = "Student email sent to {$email}";

        } catch (Exception $e) {
            $email_errors[] = "Student email failed: " . $mail->ErrorInfo;
            error_log("Student Email Error: " . $mail->ErrorInfo, 3, "email_error_log.txt");
        }

        try {
            // ========== SEND EMAIL TO ADMIN (Multiple recipients) ==========
            $admin_mail = initializeMailer();
            $admin_mail->setFrom('admin@formsadda.com', 'Forms Adda - Auto Alert');
            
            // Add multiple admin recipients correctly
            $admin_mail->addAddress('admin@formsadda.com', 'Admin');
            $admin_mail->addAddress('formsadda@gmail.com', 'Support Team');
            
            $admin_mail->isHTML(true);
            $admin_mail->Subject = "New Registration - {$course_name} - {$student_name}";
            
            $admin_mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #FF6B6B; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { border: 1px solid #ddd; padding: 20px; border-radius: 0 0 5px 5px; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        table td { padding: 10px; border: 1px solid #ddd; }
                        table td:first-child { font-weight: bold; background-color: #f5f5f5; width: 40%; }
                        .alert { background-color: #FFF3CD; border-left: 4px solid #FF6B6B; padding: 10px; margin: 10px 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>New Registration Alert</h2>
                        </div>
                        <div class='content'>
                            <div class='alert'>
                                <strong>New student registration received!</strong>
                            </div>
                            
                            <h3>Student Information:</h3>
                            <table>
                                <tr>
                                    <td>Student Name</td>
                                    <td>{$student_name}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><a href='mailto:{$email}'>{$email}</a></td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td><a href='tel:{$phone}'>{$phone}</a></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{$address}</td>
                                </tr>
                                <tr>
                                    <td>Course</td>
                                    <td><strong>{$course_name}</strong></td>
                                </tr>
                                <tr>
                                    <td>Source URL</td>
                                    <td>{$site_url}</td>
                                </tr>
                                <tr>
                                    <td>Registration Time</td>
                                    <td>{$created_at}</td>
                                </tr>
                            </table>
                            
                            <p><strong>Action Required:</strong> Please review this registration and contact the student as soon as possible.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $admin_mail->send();
            $emails_sent[] = "Admin notifications sent to admin@formsadda.com & formsadda@gmail.com";

        } catch (Exception $e) {
            $email_errors[] = "Admin email failed: " . $admin_mail->ErrorInfo;
            error_log("Admin Email Error: " . $admin_mail->ErrorInfo, 3, "email_error_log.txt");
        }

        // Prepare response
        $response = [
            "status" => "success",
            "message" => "Registration saved successfully",
            "emails_sent" => $emails_sent,
        ];
        
        if (!empty($email_errors)) {
            $response["email_warnings"] = $email_errors;
        }
        
        echo json_encode($response);

    } else {
        // Database insertion failed
        error_log("SQL Error: " . $conn->error, 3, "sql_error_log.txt");
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Required fields are missing"]);
}

$conn->close();
?>