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

if (isset($_GET['salonid'])) {
    $salonID = $_GET['salonid'];

    $stmt = $conn->prepare("SELECT serviceid, servicename, price FROM services WHERE salonid = ?");
    $stmt->bind_param("i", $salonID);
    $stmt->execute();
    $result = $stmt->get_result();

    $services = array();
    while ($row = $result->fetch_assoc()) {
        $services[] = array('value' => $row['serviceid'], 'text' => $row['servicename'], 'amount' => $row['price']);
    }

    echo json_encode($services);
}

$conn->close();
?>