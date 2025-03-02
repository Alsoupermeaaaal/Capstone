<?php
header('Content-Type: application/json');

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$startDate = $_GET["date"];
$salon_id = $_GET["salon_id"];
$schedules = [];

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get booked times for the selected date
$sql = "SELECT GROUP_CONCAT(DATE_FORMAT(book.time, '%H:%i')) AS time FROM `book` WHERE book.date = ? AND salonid = ? and is_cancelled = 0 GROUP BY book.date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $startDate, $salon_id); // Bind parameters
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Return the booked times as an array
    $schedules = explode(',', $row['time']);
}

echo json_encode($schedules);
exit;