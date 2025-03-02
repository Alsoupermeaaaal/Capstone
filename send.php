<?php
session_start();

// Assuming the user's email is stored in the session when they log in
$userEmail = $_SESSION['owneremail'] ?? 'default@example.com'; // Replace with actual session variable

// ... (rest of your existing code)

// After inserting the booking into the database
$stmt->execute(); // Execute the insert statement
$stmt->close();
$conn->close();

// Prepare email details
$email = $userEmail; // Use the logged-in user's email
$subject = "Booking Confirmation";
$message = "
    Service: $serviceNamesString,
    Date: " . htmlspecialchars($selectedDate) . ",
    Time: " . htmlspecialchars($selectedTime) . ",
    Pet: " . htmlspecialchars($petname) . ",
    Pet Salon: " . htmlspecialchars($salonName) . ",
    Payment Method: " . htmlspecialchars($selectedPayment) . ",
    Total Fee: " . number_format($totalAmount, 2) . "
";

// Send email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'markgfrancis.05@gmail.com'; // Gmail address 
    $mail->Password = 'uxutpnamcijdprru'; // Use environment variables for security
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS
    $mail->Port = 587; // Use port 587 for STARTTLS

    // Recipients
    $mail->setFrom('markgfrancis.05@gmail.com'); // Gmail address
    $mail->addAddress($email); // Send to the logged-in user's email

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = "
    <p>Message</p>
    <p>$message</p>
    <p>Best regards,</p>
    ";

    // Send email
    if ($mail->send()) {
        echo "Message has been sent successfully";
    } else {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>