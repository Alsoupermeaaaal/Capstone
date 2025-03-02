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

// Fetch services data from the database, ensuring the correct order by salonid, servicename, and price
$sql = "SELECT salonid, servicename, price FROM services ORDER BY salonid, servicename, price";
$result = $conn->query($sql);

// Initialize an array to organize services by salon
$salons = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $salons[$row['salonid']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Font and CSS Links -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="serv123.css">

    <title>Mission Im-Paws-Sible</title>
</head>
<body>
<!-- Mobile Nav -->
<div class="navbar">
    <a href="Homenew.php"><i class="fa-solid fa-house"></i><br>Home</a>
    <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
    <a href="BookingPage1.php"><i class="fa-solid fa-plus"> </i><br>Book</a>
    <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
    <a href="Serv.php"><i class="fa-solid fa-briefcase"></i><br>Services</a>
</div>

<!-- Web Nav -->
<header class="header">  
    <div class="logo">
        <a href="#">
            <img class="logo" src="logo-nav.png" alt="logo">
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
    <img src="servicesbanners.png" alt="Beautiful Scenery">
    <div class="text-overlay">
      <h1>Pet Salon</h1>
      <h2>Pricelists</h2>
    </div>
</div>

<!-- Service Dropdown and Containers -->
<div class="service-container">
    <div class="service-header">
        <h2>Services</h2>
        <div class="dropdown1">
            <select id="salonDropdown" onchange="showPriceCards()" class="salonDropDown">
                <option value="" selected disabled>Select Salon</option> <!-- Added default "Select Salon" option -->
                <?php
                // Define the salon names associated with salon IDs
                $salonNames = [
                    1 => "Vetter Health Animal Clinic and Livestock Consultancy",
                    2 => "David's Pet Grooming Salon",
                    3 => "Kanji's Pet Grooming Services"
                ];

                // Loop through salon IDs and output the corresponding salon names
                foreach ($salonNames as $salonId => $salonName) {
                    echo '<option value="' . strtolower(str_replace(' ', '-', $salonName)) . '">' . htmlspecialchars($salonName) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<!-- Dynamic Service Containers -->
<?php foreach ($salons as $salonId => $services): ?>
    <div id="<?= strtolower(str_replace(' ', '-', $salonNames[$salonId])) ?>" class="price-cards-container-wrap" style="display:none; margin-bottom:10rem" >
        <?php foreach ($services as $service): ?>
            <div class="price-card">
                <div class="card-content">
                    <p><span><?= htmlspecialchars($service['servicename']) ?></span><span><?= number_format($service['price'], 2) ?></span></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<script>
// JavaScript function to show/hide price cards based on selected salon
function showPriceCards() {
    // Hide all containers
    var containers = document.querySelectorAll('.price-cards-container-wrap');
    containers.forEach(function(container) {
        container.style.display = 'none';
    });

    // Show the selected container
    var selectedSalon = document.getElementById('salonDropdown').value;
    if (selectedSalon) {
        document.getElementById(selectedSalon).style.display = 'flex';
    }
}
</script>

</body>
</html>