<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=login_staff.php');
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

// Get the logged-in admin's salon ID from the session
$salonid = isset($_SESSION['salonid']) ? $_SESSION['salonid'] : '';

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);
$serviceName = $data['serviceName'];
$amount = $data['amount'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO services (servicename, price, salonid) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $serviceName, $amount, $salonid);

// Execute the statement
if ($stmt->execute()) {
    // Get the last inserted ID
    $serviceid = $stmt->insert_id;
    echo json_encode(['success' => true, 'serviceid' => $serviceid, 'servicename' => $serviceName, 'price' => $amount]);
} else {
    echo json_encode(['success' => false]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>