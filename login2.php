<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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

// Get user information from the POST request
$Owneremail = $_POST['owneremail'];
$Ownerpass = $_POST['ownerpass'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM registration_info WHERE owneremail = ?");
$stmt->bind_param("s", $Owneremail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password_db = $row["ownerpass"];

    // Verify the password
    if (password_verify($Ownerpass, $hashed_password_db)) {
        // Set session variables without storing the password
        $_SESSION['loggedin'] = true;
        $_SESSION['owneremail'] = $row["owneremail"];
        $_SESSION['ownerfname'] = $row["ownerfname"];
        $_SESSION['ownerlname'] = $row["ownerlname"];
        $_SESSION['ownernum'] = $row["ownernum"];
        $_SESSION['ownerID'] = $row["ownerID"];
        $_SESSION['password_length'] = strlen($Ownerpass); // Store the length of the password

        echo "<script>
            alert('Login successful!');
            window.location.href = 'Homenew.php';
        </script>";
    } else {
        echo "<script>
            alert('Incorrect Email or Password');
            window.location.href = 'index.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Incorrect Email or Password');
        window.location.href = 'index.php';
    </script>";
}

$stmt->close();
$conn->close();
?>