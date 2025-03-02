<?php
// Start the session
session_start();

// Enable error reporting for debugging (optional, can be removed in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include __DIR__ . '/db_connect.php'; // Adjust the path as necessary

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if staff ID is set in the session
if (!isset($_SESSION['staff_id'])) {
    header('Location: login_staff.php'); // Redirect to login page
    exit;
}

// Get the salon ID from the session
$salonId = $_SESSION['salonid'];

// Prepare the SQL query to fetch unique pet information along with owner details
$query = "
    SELECT DISTINCT
        p.petid, 
        p.petname, 
        CONCAT(r.ownerfname, ' ', r.ownerlname) AS owner_name, 
        p.petbirth, 
        p.petgender AS pet_gender, 
        p.petspecies, 
        p.petbreed, 
        r.owneremail, 
        r.ownernum
    FROM petinfo p
    JOIN book b ON p.petid = b.petid
    JOIN registration_info r ON p.ownerid = r.ownerid
    WHERE b.salonid = ?"; // Assuming salonid is the column in the book table

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $salonId); // Assuming salon_id is an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the query execution was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch the results into an array
$activePatients = [];
while ($row = mysqli_fetch_assoc($result)) {
    $activePatients[] = $row;
}

// Debugging output
if (empty($activePatients)) {
    error_log("No active patients found for salon ID: " . $salonId);
} else {
    error_log("Active patients found: " . json_encode($activePatients));
}

// Count the number of unique active patients
$activePatientCount = count($activePatients);

// Prepare the response data
$response = [
    'active_patient_count' => $activePatientCount,
    'active_patients' => $activePatients
];

// Return the results as a JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$stmt->close();
mysqli_close($conn);
?>