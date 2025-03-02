<?php
// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_GET['salon_id'])) {
    $salonId = $_GET['salon_id'];
    $services = [];
    
    $stmt = $conn->prepare("SELECT serviceid, servicename, price FROM services WHERE salonid = '".$salonId."' ");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $services[] = array('serviceid' => $row['serviceid'], 
                            'servicename' => $row['servicename'],
                            'price' => $row["price"]);
    }

    header('Content-Type: application/json');
    echo json_encode($services);
}