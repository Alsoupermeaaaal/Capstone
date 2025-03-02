<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mission Im-Paws-Sible </title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="home13.css">

</head>

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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ownerID = $_SESSION['ownerID'];

// Prepare the SQL query to get all ongoing bookings
$sql = "SELECT b.bookid, p.petname, b.serviceid, s.shopname, b.date, b.time
        FROM book b
        JOIN petinfo p ON b.petid = p.petid
        JOIN salon s ON b.salonid = s.salonid
        WHERE b.ownerID = ? AND b.status = 0 AND b.is_cancelled = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ownerID);
$stmt->execute();
$result = $stmt->get_result();

$ongoingBookings = []; // Initialize variable for ongoing bookings

while ($row = $result->fetch_assoc()) {
    $ongoingBookings[] = $row; // Store ongoing bookings
}

// Close the statement
$stmt->close();

// Now fetch service names while the connection is still open
$serviceNames = [];
foreach ($ongoingBookings as $booking) {
    $serviceIds = explode(',', $booking['serviceid']);
    foreach ($serviceIds as $serviceId) {
        $serviceId = intval($serviceId);
        $serviceResult = $conn->query("SELECT servicename FROM services WHERE serviceid = $serviceId");
        if ($serviceRow = $serviceResult->fetch_assoc()) {
            $serviceNames[$booking['bookid']][] = $serviceRow['servicename'];
        }
    }
}

// Close the connection
$conn->close();
?>

<body>
<!-- Mobile Nav -->
<div style="z-index: 50;" class="navbar">
    <a href="Homenew.php" ><i class="fa-solid fa-house"></i><br>Home</a>
    <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
    <a href="BookingPage1.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
    <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
    <a href="Serv.php"><i class="fa-solid fa-briefcase"></i><br>Services</a>
</div>
<!--Web Nav -->
<header class="header">  
<div class="logo">
    <a href="#">
        <img class="logo"src="logo-nav.png" alt="logo" >
    </a>
</div>
<nav>
    <ul class="main-nav">
        <li><a href="Homenew.php">Home</a></li>
        <li><a href="Serv.php">Services</a></li>
        <li><a href="LocationNew.php">Location</a></li>
        <li class="book_button"><a href="BookingPage1.php"><button>BOOK NOW!</button></a></li>
        <li class="dropdown">
            <a href="#"><i class="fa-solid fa-user circle-icon"></i></a>
            <div class="dropdown-content">
            <a href="Yourprofile.php">Profile</a>
            <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>
</header>
<div class="navtop" style="justify-content:space-evenly;">
    <img class="logo_nav_top" src="logo-nav.png" >
    <div style="margin:5rem;"></div>
    <a href="Yourprofile.php"><i class="fa-solid fa-user" style="color: white;"></i><br></a>
</div>

<!-- Full-Width Image with Text and Button -->
<div class="full-width-image-container">
    <img src="HomepagePicture.png" alt="Beautiful Scenery">
    <div class="text-overlay">
      <h1>Book your</h1>
      <h2>Pet's Appointment</h2>
      <h3>Today!</h3>
  
      <div href="BookingPage1.php" onclick="window.location.href='BookingPage1.php';"><button  class="book-button">Book Now!</button></div>
    </div>
</div>
  
<div class="upper-container">
    <div class="Name">
 <h1>Hello, <?php echo htmlspecialchars($_SESSION['ownerfname']); ?>!</h1>
    </div>
</div>
<div class="ongoingword">
    <div class="wordon">Your Ongoing Appointments</div>
</div>
<div class="appointment-container">
    <?php if (!empty($ongoingBookings)): ?>
        <?php foreach ($ongoingBookings as $booking): ?>
            <div class="booking-container">
                <div class="booking">
                    <div class="booking-item">
                        <strong class="labels">Pet Name:</strong>
                        <span class="labels1"><?php echo htmlspecialchars($booking['petname']); ?></span>
                    </div>
                    <div class="booking-item">
                        <strong class="labels">Salon:</strong>
                        <span class="labels1"><?php echo htmlspecialchars($booking['shopname']); ?></span>
                    </div>
                    <div class="booking-item">
                        <strong class="labels">Service:</strong>
                        <span class="labels1"><?php echo htmlspecialchars(implode(', ', $serviceNames[$booking['bookid']])); ?></span>
                    </div>
                    <div class="booking-item">
                        <strong class="labels">Booking Date:</strong>
                        <span class="labels1"><?php echo htmlspecialchars($booking['date']); ?></span>
                    </div>
                    <div class="booking-item">
                        <strong class="labels">Appointment Time:</strong>
                        <span class="labels1"><?php echo htmlspecialchars(date('h:i A', strtotime($booking['time']))); ?></span>
                    </div>

                    
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center;">No ongoing bookings found.</p>
    <?php endif; ?>
    <a href="Yourprofile.php?tab=appointments" class="view-button">View All Appointments</a>
</div>

<!-- Pet Care Tips Section -->
<div class="pet-care-tips">
    <h2 class="text-tips">Pet Care Tips</h2>
</div>

<div class="tips-paragraph">
    Welcome to our Pet Care Tips section! Whether you’re a seasoned pet owner or a first-time fur parent, proper care is essential to ensure your pets live happy and healthy lives. Here, we offer practical advice and useful tips on everything from nutrition and grooming to exercise and training. Our goal is to provide you with the knowledge you need to make informed decisions and give your pets the best care possible. Let’s dive into the essentials of keeping your beloved companions in top shape!
</div>

<!-- Video Embed -->
<div class="video-container">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/XE_fXjhtDEU?si=UYsNrt4NHkJ_48LA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
</div>

<!-- Pet Salon -->
<div class="pet-container">
    <div class="petsalonword">
        <h2>Pet Salon</h2>
    </div>
    
    <div class="text-salon">
        Where every pet is pampered with love and care. Our salon is dedicated to providing exceptional grooming services that keep your furry friends looking and feeling their best. From stylish cuts to soothing baths, we offer a wide range of services tailored to meet the unique needs of each pet. Our experienced and compassionate groomers ensure a stress-free experience, using only the highest quality products to guarantee the health and happiness of your pet. We treat your pets like family, because they deserve nothing less than the best. Come and experience the difference in pet care with us!
    </div>

    <div class="row">
        <div class="square">
            <div class="picture">
                <img src="Davids.jpg" alt="David's Pet Salon">
                <div class="popup">
                    <div class="popup-info">
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                            </div>
                            <span class="popup-category">Stall 6 Pacifica Plaza 4103, Imus, Philippines · Buhay na Tubig, Philippines</span>
                        </div>
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                            </div>
                            <span class="popup-category">0977 007 9908</span>
                        </div>
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <span class="popup-category">jynrdkingdavid@gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="name">
                <h2>David's Pet Salon</h2>
            </div>
        </div>

        <div class="square">
            <div class="picture">
                <img src="vetterhealth.jpg" alt="Vetter Health Animal Clinic">
                <div class="popup">
                    <div class="popup-info">
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                            </div>
                            <span class="popup-category">9063 Vetter Health Animal Clinic, Gov. D. Mangubat Ave. Brgy. Burol Main, Dasmarinas, Philippines</span>
                        </div>
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                            </div>
                            <span class="popup-category">0923 170 6371</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="name">
                <h2>Vetter Health Animal Clinic and Livestock Consultancy</h2>
            </div>
        </div>

        <div class="square">
            <div class="picture">
                <img src="Kanji.jpg" alt="Kanji's Pet Grooming">
                <div class="popup">
                    <div class="popup-info">
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                            </div>
                            <span class="popup-category">Mantele Apartelle Unit 4, Dasmariñas, Philippines, 4114</span>
                        </div>
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                            </div>
                            <span class="popup-category">0927 350 8308</span>
                        </div>
                        <div>
                            <div class="fire-icons">
                                <div class="on">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <span class="popup-category">kanjipetservices@gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="name">
                <h2>Kanji's Pet Grooming</h2>
            </div>
        </div>
    </div>
</div>
</body>
</html>