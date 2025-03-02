<?php
// Connect to the database
$conn = mysqli_connect("localhost", "username", "password", "capstone");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the salon ID from the session
$salon_id = $_SESSION['salonid'];

// Retrieve data from the book table based on the salon ID
$query = "SELECT * FROM book WHERE salonid = '$salon_id'";
$result = mysqli_query($conn, $query);

// Check if there are results
if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo "No data found";
}

// Close the connection
mysqli_close($conn);
?>