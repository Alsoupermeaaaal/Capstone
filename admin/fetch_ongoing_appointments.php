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

// Prepare the SQL query to fetch ongoing, completed, and cancelled bookings for the logged-in staff's salon
$query = "
    SELECT b.bookid, p.petid AS pet_id, p.petname AS pet_name, b.salonid AS salon_id, 
            GROUP_CONCAT(s.servicename SEPARATOR ', ') AS service_names, 
            CONCAT(o.ownerfname, ' ', o.ownerlname) AS owner_name, 
            b.date, b.time, b.paymentmethod, b.paymentprice, 
            b.status, b.is_cancelled
    FROM book b
    JOIN petinfo p ON b.petid = p.petid
    JOIN registration_info o ON b.ownerid = o.ownerid
    JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0
    WHERE b.salonid = ?
    GROUP BY b.bookid, p.petid, p.petname, b.salonid, o.ownerfname, o.ownerlname, b.date, b.time, b.paymentmethod, b.paymentprice, b.status, b.is_cancelled
    ORDER BY b.date DESC, b.time DESC
";

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
$appointments = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Log the status and cancellation values for debugging
    error_log("Book ID: " . $row['bookid'] . " | Status: " . $row['status'] . " | Is Cancelled: " . $row['is_cancelled']);

    // Determine the status based on is_cancelled and status
    if ($row['is_cancelled'] == 1 && $row['status'] == 0) {
        $row['status'] = "Cancelled"; // If cancelled
    } elseif ($row['is_cancelled'] == 0 && $row['status'] == 0) {
        $row['status'] = "Ongoing"; // If ongoing
    } elseif ($row['is_cancelled'] == 0 && $row['status'] == 1) {
        $row['status'] = "Completed"; // If completed
    }

    $appointments[] = $row;
}

// Prepare a query to count ongoing appointments
$countQuery = "
    SELECT COUNT(*) AS ongoing_count 
    FROM book 
    WHERE status = 0 AND salonid = ? AND is_cancelled = 0
";

// Use prepared statements to prevent SQL injection for count query
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("i", $salonId); // Assuming salon_id is an integer
$countStmt->execute();
$countResult = $countStmt->get_result();
$ongoingCount = $countResult->fetch_assoc()['ongoing_count'];

// Return the results as a JSON response including the ongoing count
header('Content-Type: application/json');
echo json_encode(['appointments' => $appointments , 'ongoing_count' => $ongoingCount]);

// Close the database connection
$stmt->close();
$countStmt->close();
$conn->close();
?>