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
$ownerID = $_SESSION['ownerID']; // assuming you have a session variable set

// Retrieve pet information for the currently logged-in account
$sql = "SELECT * FROM petinfo WHERE ownerID = '$ownerID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pets = array();
    while($row = $result->fetch_assoc()) {
        $petID = $row['petid'];
        $petName = $row['petname'];
        $pets[] = array('petid' => $petID, 'petname' => $petName);
    }
    echo json_encode($pets);
} else {
    $error = array('error' => 'No pets found');
    echo json_encode($error);
}

// Close the database connection
$conn->close();
?>