<?php
// Connect to database
$conn = mysqli_connect('localhost', 'root', '', 'capstone');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the selected salon from the query string
$salon = $_GET['salon'];

// Query to fetch booked dates
$query = "SELECT date, time FROM book WHERE salonid = $salon AND is_cancelled = 0 AND status = 'booked'";
$result = mysqli_query($conn, $query);

$bookedDates = array();

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $time = $row['time'];

    if (!isset($bookedDates[$date])) {
        $bookedDates[$date] = array();
    }

    $bookedDates[$date][] = $time;
}

mysqli_close($conn);

// Output the booked dates in JSON format
echo json_encode($bookedDates);
?>