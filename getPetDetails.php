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

// Get the pet name from the URL parameter
$petName = $_GET['petname'];

// Get the owner ID from the session
$ownerID = $_SESSION['ownerID'];

// Retrieve pet details from the database
$stmt = $conn->prepare("SELECT * FROM petinfo WHERE petname = ? AND ownerID = ?");
$stmt->bind_param("si", $petName, $ownerID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $petDetails = $result->fetch_assoc();
    
    // Retrieve appointment history for the selected pet
    $petID = $petDetails['petid'];
    $appointmentsStmt = $conn->prepare("SELECT b.bookid, b.serviceid, b.date, b.paymentmethod, b.paymentprice, 
        CASE 
            WHEN b.is_cancelled = 1 AND b.status = 0 THEN 'Cancelled'
            WHEN b.is_cancelled = 0 AND b.status = 1 THEN 'Completed'
            WHEN b.is_cancelled = 0 AND b.status = 0 THEN 'Ongoing'
            ELSE 'Unknown'
        END AS appointment_status,
        GROUP_CONCAT(s.servicename SEPARATOR ', ') AS services
    FROM book b 
    JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0 
    WHERE b.petid = ? 
    GROUP BY b.bookid");
    
    $appointmentsStmt->bind_param("i", $petID);
    $appointmentsStmt->execute();
    $appointmentsResult = $appointmentsStmt->get_result();

    $appointments = array();
    while ($appointment = $appointmentsResult->fetch_assoc()) {
        $appointments[] = $appointment;
    }

    // Combine pet details and appointments
    $petDetails['appointments'] = $appointments;
    echo json_encode($petDetails);
} else {
    $error = array('error' => 'Pet not found');
    echo json_encode($error);
}

// Close the database connection
$conn->close();
?>
