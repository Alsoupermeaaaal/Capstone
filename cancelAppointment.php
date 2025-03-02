<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=index.php');
    exit;
}

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingID = $_POST['bookID']; // Get the booking ID from the AJAX request

    // Update the booking status to cancelled and set the cancellation date
    $sql = "UPDATE book SET is_cancelled = 1, status = 'Cancelled', cancel_date = NOW() WHERE bookid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingID);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
?>