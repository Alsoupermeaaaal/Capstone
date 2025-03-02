<?php
session_start();

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the ownerID of the currently logged-in user
$ownerID = $_SESSION['ownerID'];

// Get the form data
$petid = $_POST['petid'];
$petsalon = $_POST['petsalon'];
$date = $_POST['date'];
$time = $_POST['time'];
$paymentmethod = $_POST['paymentmethod'];
$service = isset($_POST['services']) ? $_POST['services'] : [];


// Validate user input
if (empty($petid) || empty($petsalon) || empty($date) || empty($time) || empty($paymentmethod) || empty($service)) {
    echo "Please fill in all required fields.";
    exit;
}

// Convert services array to comma-separated string
$serviceIds = $service;

// Prepare and execute the SQL statement
$stmt = $conn->prepare("INSERT INTO book (ownerID, petid, salonid, date, time, paymentmethod, serviceid) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiissss", $ownerID, $petid, $petsalon, $date, $time, $paymentmethod, $serviceIds);
$stmt->execute();

// Check for successful insertion
if ($stmt->affected_rows > 0) {
    echo "New record created successfully";
    header("Location: book.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();



?>