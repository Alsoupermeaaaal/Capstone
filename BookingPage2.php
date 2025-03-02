<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You need to log in to view this page.'); window.location.href='index.php';</script>";
    exit;
}

$userId = $_SESSION['ownerID'];
$userEmail = $_SESSION['owneremail'] ?? 'default@example.com';
// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedPet = $_POST['pet_id'] ?? '';
$selectedSalon = $_POST['salon_id'] ?? '';
$selectedDate = $_POST['selected_date'] ?? '';
$selectedTime = $_POST['timeSlot'] ?? '';
$selectedPayment = $_POST['payment_method'] ?? '';
$userservices = isset($_POST['serviceid']) ? (is_array($_POST['serviceid']) ? $_POST['serviceid'] : explode(',', $_POST['serviceid'])) : [];

if (empty($selectedPet) || empty($selectedSalon) || empty($selectedDate) || empty($selectedTime) || empty($selectedPayment) || empty($userservices)) {
    echo "<script>alert('Please complete the booking by selecting all required information.'); window.location.href='BookingPage1.php';</script>";
    exit;
}

$stmt = $conn->prepare("SELECT petname FROM petinfo WHERE petid = ?");
$stmt->bind_param("i", $selectedPet);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$petname = $row['petname'] ?? 'Unknown Pet';
$stmt->close();

$stmt = $conn->prepare("SELECT shopname FROM salon WHERE salonid = ?");
$stmt->bind_param("i", $selectedSalon);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$salonName = $row['shopname'] ?? 'Unknown Salon';
$stmt->close();

$totalAmount = 0;
$serviceNames = [];
if (!empty($userservices) && is_array($userservices)) {
    $serviceIdsString = implode(',', array_map('intval', $userservices));
    $sql = "SELECT servicename, price FROM services WHERE serviceid IN ($serviceIdsString)";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $serviceNames[] = htmlspecialchars($row['servicename']);
            $totalAmount += (float)$row['price'];
        }
    } else {
        echo "<script>alert('No services found for the selected IDs.');</script>";
    }
}
$serviceNamesString = implode(', ', $serviceNames);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $stmt = $conn->prepare("INSERT INTO book (ownerID, petid, salonid, serviceid, date, time, paymentmethod, paymentprice, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $serviceIDs = implode(',', $userservices);
    $status = 0;

    if ($stmt) {
        $stmt->bind_param("iiissssdi", $userId, $selectedPet, $selectedSalon, $serviceIDs, $selectedDate, $selectedTime, $selectedPayment, $totalAmount, $status);
        
        if ($stmt->execute()) {
            $email = $userEmail;
            $subject = "Booking Confirmation";
            $message = "<html><body>";
            $message .= "<div style='font-family: \"Poppins\", sans-serif; color: #000; max-width: 600px; margin: 0 auto;'>";
            $message .= "<h2 style='text-align: center; color: #602147;'>Booking Confirmation</h2>";
            $message .= "<p style='color: #000;'>Thank you for choosing our pet salon! Here are the details of your booking:</p>";
            $message .= "<hr style='border: 1px solid #FFD700;'>";
            $message .= "<p style='color: #000;'><strong>Date of Appointment:</strong> " . htmlspecialchars($selectedDate) . "</p>";
            $message .= "<p style='color: #000;'><strong>Time of Appointment:</strong> " . htmlspecialchars($selectedTime) . "</p>";
            $message .= "<p style='color: #000;'><strong>Pet Name:</strong> " . htmlspecialchars($petname) . "</p>";
            $message .= "<p style='color: #000;'><strong>Pet Salon:</strong> " . htmlspecialchars($salonName) . "</p>";
            $message .= "<p style='color: #000;'><strong>Chosen Services:</strong></p>";
            $message .= "<div style='padding-left: 20px;'>";
            foreach ($serviceNames as $service) {
                $message .= "<p style='margin-bottom: 5px;'>" . htmlspecialchars($service) . "</p>";
            }
            $message .= "</div>";
            $message .= "<p style='color: #000;'><strong>Payment Method:</strong> " . htmlspecialchars($selectedPayment) . "</p>";
            $message .= "<p style='color: #000;'><strong>Total Fee:</strong> Php" . number_format($totalAmount, 2) . "</p>";
            $message .= "<hr style='border: 1px solid #FFD700;'>";
            $message .= "<p style='color: #000;'>We look forward to seeing you and your pet soon!</p>";
            $message .= "<p style='text-align: center; color: #000; font-size: 0.9em;'>© Mission Im-Paws-Sible 2024. All rights reserved.</p>";
            $message .= "</div>";
            $message .= "</body></html>";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'missionimpawssible.mips@gmail.com';
                $mail->Password = 'cifawllhvybgeuqv';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('missionimpawssible.mips@gmail.com', 'Mission Im-Paws-Sible Booking System');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                if ($mail->send()) {
                    echo json_encode(['success' => true, 'message' => 'Booking confirmed! An email has been sent to you.']);
                    exit();
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error occurred while saving the booking: ' . $stmt->error]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement.']);
        exit();
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Mission Im-Paws-Sible</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="BookingPage(2).css">
    
</head>
<body>
    <div class="nav">
        <div>
            <a href="BookingPage1.php"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
            Booking Summary
        </div>
    </div>
    <div class="box">
        <div class="contents">Service</div>
        <div class="services1">
            <div class="contents1_service"><?php echo $serviceNamesString; ?></div>
        </div>
        
        <hr class="line1">
        <div class="container_date_time">
            <div class="date_div">
                <div class="contents">Date</div>
                <div class="contents1_date"><?php echo htmlspecialchars($selectedDate); ?></div>
            </div>
            <div class="time_div">
                <div class="contents">Time</div>
                <div class="contents1_time"><?php echo htmlspecialchars($selectedTime); ?></div>
            </div>
        </div>
        
        <hr class="line1">
        
        <div class="contents">Pet</div>
        <div class="contents1_pet"><?php echo htmlspecialchars($petname); ?></div>
        <hr class="line1">
        <div class="contents">Pet Salon</div>
        <div class="contents1_salon"><?php echo htmlspecialchars($salonName); ?></div>
        <hr class="line1">
        <div class="contents">Payment Method</div>
        <div class="contents1_payment"><?php echo htmlspecialchars($selectedPayment); ?></div>
        
        <hr class="line1">
        <div class="contents">Total Fee</div>
        <div class="contents1_fee"><?php echo number_format($totalAmount, 2); ?></div>
    </div>

    <button id="confirmBooking" class="cd-popup-trigger book_button" style="
    font-family: 'Open Sans', sans-serif; width:80%">Confirm Booking</button>

    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Booking Confirmed!</h2>
            <p>Your booking has been confirmed and sent to your email.</p>
            <button id="ratingButton">Rate Your Experience</button>
        </div>
    </div>

    <div id="ratingPopup">
        <span class="close">&times;</span>
        <h2>Rate Your Experience</h2>
        <div class="stars">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <div id="feedbackMessage">Thanks for your feedback!</div>
    </div>
    <script>
    document.getElementById('confirmBooking').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default form submission
        console.log("Confirm booking clicked");

        // Create the form data
        const formData = new FormData();
        formData.append('pet_id', '<?php echo $selectedPet; ?>');
        formData.append('salon_id', '<?php echo $selectedSalon; ?>');
        formData.append('selected_date', '<?php echo $selectedDate; ?>');
        formData.append('timeSlot', '<?php echo $selectedTime; ?>');
        formData.append('payment_method', '<?php echo $selectedPayment; ?>');
        formData.append('confirm_booking', '1');

        <?php foreach ($userservices as $service) { ?>
        formData.append('serviceid[]', '<?php echo $service; ?>');
        <?php } ?>

        // Send AJAX request
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Booking confirmed, showing confirmation modal...");
            document.getElementById('confirmationModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your booking.');
        });
    });

    // Close modal when clicking the close button
    document.querySelector('.close-modal').addEventListener('click', function() {
        document.getElementById('confirmationModal').style.display = 'none';
        window.location.href = 'Homenew.php';
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('confirmationModal')) {
            document.getElementById('confirmationModal').style.display = 'none';
        }
    });

    // Rating functionality
    document.getElementById('ratingButton').addEventListener('click', function() {
        document.getElementById('ratingPopup').style.display = 'block';
    });

    document.querySelector('#ratingPopup .close').addEventListener('click', function() {
        document.getElementById('ratingPopup').style.display = 'none';
        window.location.href = 'Homenew.php';
    });

    const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', function () {
                const value = this.getAttribute('data-value'); // Get the star rating value
                stars.forEach(s => s.classList.remove('active')); // Remove active class from all stars
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('active'); // Highlight selected stars
                }
                document.getElementById('feedbackMessage').style.display = 'block';
                document.getElementById('feedbackMessage').textContent = 'Submitting your rating...';

                // Send the star rating to the server
                fetch('ratingHandler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `rating=${value}&salonID=<?php echo $selectedSalon; ?>` // Send rating value in the request body
                })
                    .then(response => response.json()) // Parse the JSON response
                    .then(data => {
                        if (data.success) {
                            document.getElementById('feedbackMessage').textContent = 'Thanks for your feedback!';
                        } else {
                            document.getElementById('feedbackMessage').textContent = 'Failed to submit feedback. Try again.';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('feedbackMessage').textContent = 'An error occurred. Please try again.';
                    });

                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = 'Homenew.php';
                }, 2000); // 2-second delay
            });
        });

    </script>
</body>
</html>