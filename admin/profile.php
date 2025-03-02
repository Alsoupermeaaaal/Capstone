<?php
session_start(); // Start the session to access session variables

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

// Assuming the staff ID is stored in the session after login
$staff_id = $_SESSION['staff_id'];

// Query to get the salon ID from the staff table
$sql = "SELECT salonid FROM staff WHERE staffid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$stmt->bind_result($salon_id);
$stmt->fetch();
$stmt->close();

// Query to get the salon name from the salon table
$sql = "SELECT shopname FROM salon WHERE salonid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $salon_id);
$stmt->execute();
$stmt->bind_result($shopname);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mission Im-Paws-Sible Staff</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="profile1.css" />
  </head>
  <body>
     
    <!-- Mobile Nav -->
    <div class="navbar">
      <a href="admin.php"><i class="fa-solid fa-house"></i><br />Dashboard</a>
      <a href="report.php"><i class="fa-solid fa-newspaper"></i><br />Reports</a>
      <a href="services.php"><i class="fa-solid fa-briefcase"></i><br />Services</a>
      <a href="profile.php"><i class="fa-solid fa-user"></i><br />Admin</a>
    </div>

    <!-- Web Nav -->
    <nav class="sidebar">
        <header>
            <div class="logo">
                <img class="logo_1" src="logo-nav.png" />
            </div>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-link">
                    <li class="nav-link">
                        <a href="admin.php">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path d="M575.8 255.5C575.8 273.5 560.8 287.6 543.8 287.6H511.8L512.5 447.7C512.5 450.5 512.3 453.1 512 455.8V472C512 494.1 494.1 512 472 512H456C454.9 512 453.8 511.1 452.7 511.9C451.3 511.1 449.9 512 448.5 512H392C369.9 512 352 494.1 352 472V384C352 366.3 337.7 352 320 352H256C238.3 352 224 366.3 224 384V472C224 494.1 206.1 512 184 512H128.1C126.6 512 125.1 511.9 123.6 511.8C122.4 511.9 121.2 512 120 512H104C81.91 512 64 494.1 64 472V360C64 359.1 64.03 358.1 64.09 357.2V287.6H32.05C14.02 287.6 0 273.5 0 255.5C0 246.5 3.004 238.5 10.01 231.5L266.4 8.016C273.4 1.002 281.4 0 288.4 0C295.4 0 303.4 2.004  309.5 7.014L564.8 231.5C572.8 238.5 576.9 246.5 575.8 255.5L575.8 255.5z" />
                            </svg>
                            <span class="title nav">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="report.php">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M96 96c0-35.3 28.7-64 64-64H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H80c-44.2 0-80-35.8-80-80V128c0-17.7 14.3-32 32-32s32 14.3 32 32V400c0 8.8 7.2 16 16 16s16-7.2 16-16V96zm64 24v80c0 13.3 10.7 24 24 24H296c13.3 0 24-10.7 24-24V120c0-13.3-10.7-24-24-24H184c-13.3 0-24 10.7-24 24zm208-8c0 8.8 7.2 16 16 16h48c8.8 0 16-7.2 16-16s-7.2-16-16-16H384c-8.8 0-16 7.2-16 16zm0 96c0 8.8 7.2 16 16 16h48c8.8 0 16-7.2 16-16s-7.2-16-16-16H384c-8.8 0-16 7.2-16 16zM160 304c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16s-7.2-16-16-16H176c-8.8 0-16 7.2-16 16zm0 96c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16s-7.2-16-16-16H176c-8.8 0-16 7.2-16 16z" />
                            </svg>
                            <span class="title nav">Reports</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="services.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M184 48l144 0c4.4 0 8 3.6 8 8l0 40L176 96l0-40c0-4.4 3.6-8 8-8zm-56 8l0 40L64 96C28.7 96 0 124.7 0 160l0 96 192 0 128 0 192 0 0-96c0-35.3-28.7-64-64-64l-64 0 0-40c0-30.9-25.1-56-56-56zM512 288l-192 0 0 32c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32l0-32L0 288 0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-128z" />
                            </svg>
                            <span class="title nav">Services</span>
                        </a>
                    </li>
                </ul>
                <ul class="menu-sign">
                    <li class="nav-link profile">
                        <a href="profile.php">
                            <svg class="icon" xmlns="http://www.w3 ```html
                            .org/2000/svg" viewBox="0 0 448 512">
                                <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                            </svg>
                            <span class="title nav" >Admin</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="login_staff.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div id="all">
      <div>
        <div class="dashboard">Profile</div>
        <div class="salon_name"><?php echo htmlspecialchars($shopname); ?></div>
      </div>
      </div>

      <div class="logout" style="margin-top:15rem;" >
        <a style="color: black; text-decoration: none; " href="login_staff.php">Log Out</a>
      </div>
    </div>
  </body>
</html>
