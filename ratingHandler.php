<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])  || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a rating.']);
    exit;
}

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get rating value from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = intval($_POST['rating']);
    $ownerID = $_SESSION['ownerID'] ?? 0;
    $salonID = intval($_POST['salonID']); // Get salonID from POST

    if ($rating < 1  || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating value.']);
        exit;
    }

    // Insert rating into the database
    $stmt = $conn->prepare("INSERT INTO ratings (ownerID, salonid, ratings) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iii", $ownerID, $salonID, $rating);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Rating submitted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error submitting rating: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>