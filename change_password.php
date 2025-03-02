<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the current password, new password, and confirm new password from the form
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_new_password = $_POST['confirm_new_password'];

echo "Received form data:<br>";
var_dump($_POST);

// Check if the current password is correct
$ownerID = $_SESSION['ownerID'];
echo "Owner ID: " . $ownerID . "<br>";

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to database successfully!<br>";

// Verify the current password
$sql = "SELECT ownerpass FROM registration_info WHERE ownerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ownerID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_password_db = $row['ownerpass'];

echo "Current password from database: " . $current_password_db . "<br>";

if ($current_password == $current_password_db) {
    echo "Current password is correct!<br>";
    // Check if the new password and confirm new password match
    if ($new_password === $confirm_new_password) {
        echo "New password and confirm new password match!<br>";
        // Hash the new password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        echo "Hashed password: " . $new_password_hash . "<br>";

        // Update the password in the database
        $sql = "UPDATE registration_info SET ownerpass = ? WHERE ownerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password_hash, $ownerID);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Affected rows: " . $stmt->affected_rows . "<br>";

            // Update the session password
            $_SESSION['ownerpass'] = $new_password_hash;
            echo "Updated session password: " . $_SESSION['ownerpass'] . "<br>";

            // Redirect to the profile page with a success message
            header('Location: Yourprofile.php?password_updated=success');
            exit;
        } else {
            echo "Update failed!<br>";
            var_dump($stmt->error);

            // Redirect to the profile page with an error message
            header('Location: Yourprofile.php?password_updated=error');
            exit;
        }
    } else {
        echo "New password and confirm new password do not match!<br>";
        // Redirect to the profile page with an error message
        header('Location: Yourprofile.php?password_updated=error');
        exit;
    }
} else {
    echo "Current password is incorrect!<br>";
    // Redirect to the profile page with an error message
    header('Location: Yourprofile.php?password_updated=error');
    exit;
}

$conn->close();

// Enable error reporting to see any PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>