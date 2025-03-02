<?php
header('Content-Type: application/json');

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$startDate = $_GET["start"];
$endDate   = $_GET["end"];
$salon_id = $_GET["salon_id"];

$conn = new mysqli($servername, $username, $password, $dbname);

// Generate the date range
$schedules = generateDateRange($startDate, $endDate);

// Query to get booked dates
$sql = "SELECT book.date, COUNT(*) as booking_count FROM book WHERE book.date BETWEEN '$startDate' AND '$endDate' AND salonid = '$salon_id' and book.is_cancelled = 0 GROUP BY book.date";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$array = [
    1 => 9,
    2 => 9,
    3 => 10
];

// Update the schedules with booking information
while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    $bookingCount = $row['booking_count'];
    
    // Mark as 'Full' if there are 4 bookings, otherwise 'Available'
    if ($bookingCount >= $array[$salon_id]) {
        $schedules[$date]['status'] = 'full';
    } else {
        $schedules[$date]['status'] = 'available';
    }
}

// Format the schedules for output
$formattedSched = flattenArray($schedules);
echo json_encode($formattedSched);
exit;

function generateDateRange($startDate, $endDate) {
    $dates = array();
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('+1 day'); // Include the end date

    while ($start < $end) {
        $currDate = $start->format('Y-m-d'); 
        $dates[$currDate] = [
            "title" => "Available",
            "start" => $currDate,
            "status" => "available" // Default status
        ];
        $start->modify('+1 day'); 
    }
    return $dates;
}

function flattenArray($datesArray) {
    $transformedArray = [];
    foreach ($datesArray as $date => $data) {
        $newTitle = ($data['status'] === 'full') ? "Fully Booked" : "Available";
        
        $transformedArray[] = [
            "title" => $newTitle,
            "start" => $data['start'],
            "extendedProps" => [
                "status" => $data['status']
            ]
        ];
    }
    return $transformedArray;
}