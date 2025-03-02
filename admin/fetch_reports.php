<?php
session_start();
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate input parameters
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

// Check if all required parameters are provided
if (!$startDate || !$endDate) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Missing parameters."]);
    exit;
}

// Log the received dates for debugging
error_log("Start Date: " . $startDate);
error_log("End Date: " . $endDate);

// Prepare the SQL query based on the date range
$sql = "SELECT BookID, OwnerID, PetID, SalonID, ServiceID, Date, Time, PaymentMethod, Is_Cancelled, Cancel_Date, Status, Payment_Price 
        FROM book 
        WHERE Date >= ? AND Date <= ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code( 500); // Internal server error
    echo json_encode(["error" => "Failed to prepare SQL statement."]);
    error_log("SQL Error: " . mysqli_error($conn)); // Log the error
    exit;
}

$stmt->bind_param("ss", $startDate, $endDate);
if (!$stmt->execute()) {
    http_response_code(500); // Internal server error
    echo json_encode(["error" => "Failed to execute SQL statement."]);
    error_log("Execution Error: " . mysqli_error($conn)); // Log the error
    exit;
}

$result = $stmt->get_result();
$reports = [];

// Fetch data from the result set
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

// Log the number of reports fetched
error_log("Number of reports fetched: " . count($reports));

// Return the data as a JSON response
header('Content-Type: application/json'); // Set the content type to JSON
if (empty($reports)) {
    echo json_encode(["message" => "No reports found for the given date range."]);
} else {
    echo json_encode($reports);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>