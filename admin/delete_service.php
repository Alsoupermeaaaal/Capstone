<?php
// delete_service.php

session_start();
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

// Get the input from the request
$data = json_decode(file_get_contents("php://input"), true);
$serviceId = $data['serviceId'];

// Prepare and bind
$stmt = $conn->prepare("DELETE FROM services WHERE serviceid = ?");
$stmt->bind_param("i", $serviceId);

// Execute the statement and check for success
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]); // Include error for debugging
}

$stmt->close();
$conn->close();
?>