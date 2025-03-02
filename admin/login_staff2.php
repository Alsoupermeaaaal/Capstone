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

// Get user information from the database
$Staffemail = $_POST['staffemail'];
$Staffpass = $_POST['staffpass'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM staff WHERE staffemail = ? AND staffpass = ?");
$stmt->bind_param("ss", $Staffemail, $Staffpass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['loggedin'] = true;
    $_SESSION['staffid'] = $row["staffid"]; // Assuming this is the correct column name
    $_SESSION['salonid'] = $row["salonid"]; // Fetch and store salon_id
    $_SESSION['staffemail'] = $row["staffemail"];
    $_SESSION['staffpass'] = $row["staffpass"];

    // Alert and redirect using JavaScript
    echo "<script>
        alert('Login successful!');
        window.location.href = 'admin.php';
    </script>";
} else {
    echo "<script>
        alert('Incorrect Email or Password');
        window.location.href = 'login_staff.php';
    </script>";
}

$stmt->close();
$conn->close();
?>