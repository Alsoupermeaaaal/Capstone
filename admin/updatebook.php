<?php
// Start the session
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Enable error reporting for debugging (optional, can be removed in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the timezone to Philippine timezone
date_default_timezone_set('Asia/Manila');

// Include the database connection file
include __DIR__ . '/db_connect.php'; // Adjust the path as necessary

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "run";
// Get the current date
$currentDate = date('Y-m-d');

// Prepare the SQL query to update the status of past appointments
$query = "UPDATE book SET status = 1 WHERE date <= ? AND status = 0 AND is_cancelled = 0";

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
    
}

// Bind the current date parameter
$stmt->bind_param("s", $currentDate); // Assuming date is stored as a string in 'Y-m-d' format

// Execute the statement
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}else{
    echo"success";
}
// Check if the query execution was successful
if ($stmt->affected_rows > 0) {
    echo "Updated " . $stmt->affected_rows . " appointment(s) to status 1.";
} else {
    echo "No appointments to update. Please check the following:";
    echo "<ul>";
    echo "<li>Current Date: " . $currentDate . "</li>";
    
    // Check for existing records that should be updated
    $checkQuery = "SELECT * FROM book WHERE date < ? AND status = 0 AND is_cancelled = 0";
    $checkStmt = $conn->prepare($checkQuery);
    if (!$checkStmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $checkStmt->bind_param("s", $currentDate);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "<li>There are appointments with status 0 and not cancelled that are before the current date.</li>";
        // Output the details of the records that should be updated
        while ($row = $result->fetch_assoc()) {
            echo "<li>Appointment ID: " . $row['id'] . ", Date: " . $row['date'] . ", Status: " . $row['status'] . "</li>";
        }
    } else {
        echo "<li>No appointments found that meet the criteria.</li>";
    }

    $checkStmt->close();
    echo "</ul>";
}

// Close the statement and database connection
$stmt->close();
mysqli_close($conn);
?>