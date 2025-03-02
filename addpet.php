<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=index.php');
    exit;
}
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

// Get the form data
$petName = $_POST['petname'];
$petBirthday = $_POST['petbirth'];
$petSpecies = $_POST['petspecies'];
$petBreed = $_POST['petbreed'];
$petSex = $_POST['petgender'];
$fileInput = $_FILES['fileInput'];

// Handle file upload
$target_dir = "uploads/";
$target_file = $target_dir . basename($fileInput["name"]);
$image_path = $target_file; // store the file path in the database

// Insert the data into the database
$sql = "INSERT INTO petinfo (ownerID, petname, petbirth, petgender, petspecies, petbreed, petphoto) 
VALUES ('".$_SESSION['ownerID']."', '$petName', '$petBirthday', '$petSex', '$petSpecies', '$petBreed', '$image_path')";
if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error: ". $sql. "<br>". mysqli_error($conn);
}

// Move the uploaded file to the target directory
if (move_uploaded_file($fileInput["tmp_name"], $target_file)) {
  echo "File uploaded successfully";
} else {
  echo "Error uploading file";
}

// Close the database connection
$conn->close();
?>