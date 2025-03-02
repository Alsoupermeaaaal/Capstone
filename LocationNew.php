<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="location123.css">

    <title>Mission Im-Paws-Sible</title>

</head>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
  echo "<script>alert('You need to log in to view this page.');</script>";
  header('refresh:2; url=index.php');
  exit;
}
?>

<body>
<!-- Mobile Nav -->
<div class="navbar">
    <a href="Homenew.php" ><i class="fa-solid fa-house"></i><br>Home</a>
    <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
    <a href="BookingPage1.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
    <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
    <a href="Serv.php"><i class="fa-solid fa-briefcase"></i> </i><br>Services</a>
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

<div class="container">
    <div class="fullbanner">
    <section id="banner" class="content">
        <div id="banner-text">
            <div id="banner-textin">
                <h1>Discover Pet Salons with <span class="highlight">MIPS Booking!</span></h1>
            </div>
        </div>
        <div id="banner-img">
            <img class="banner-pic"src="meme.jpg" alt="logo" >
        </div>
    </div>
    </section>

    <div class="navtop" style="justify-content:space-evenly;">
    <img class="logo_nav_top" src="logo-nav.png" >
    <div style="margin:5rem;"></div>
    <a href="Yourprofile.php"><i class="fa-solid fa-user" style="color: white;"></i><br></a>
</div>
    
    <div class="location_label">LOCATION</div>
        <section class="salon-card">
            <div class="salon-details2">
                <div id="salon-detailsinner">
                <h3 class="salon_name">Vetter Health Animal Clinic and Livestock Consultancy</h3>
                <p class="salon_add">9063 Dominador Mangubat Blvd, Dasmariñas, Cavite</p>
                <!-- <div id="fselect">
                <select>
                    <option value="">Appointment Type</option>
                    <option value="">Check-Up</option>
                    <option value="">Vaccination</option>
                </select>
                <select>
                    <option value="">Date</option>
                    <option value="">Today</option>
                    <option value="">Tomorrow</option>
                </select>
                </div> -->
                <!-- <button id = "fbutton" class="button" style="background-color: #602147; color: #FFD700; font-family: MoreSugar; " >Book Now</button> -->
                </div>
            </div>
            <div class="salon-map">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3865.7326568660856!2d120.94661927588116!3d14.326955586127571!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d5f6860587ff%3A0x43b300abb48dd41!2sVetterHealth%20Animal%20Clinic!5e0!3m2!1sen!2sph!4v1719119634744!5m2!1sen!2sph" 
                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>

        <section class="salon-card2">
            <div class="salon-map2">
                <iframe class="map2" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.303056108293!2d120.95006197588252!3d14.409685486054356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d3be9db3ffb1%3A0xc4ffb4ea88c7310c!2sDavid&#39;s%20pet%20grooming%20salon!5e0!3m2!1sen!2sph!4v1719119704074!5m2!1sen!2sph" 
                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="salon-details2">
                <div id="salon-detailsinner">
                <h3 class="salon_name">David's Pet Grooming Salon</h3>
                <p class="salon_add1">Stall 6, pacifica bldg, Buhay na Tubig St, Imus, 4103 Cavite</p>
                <!-- <div id="fselect">
                <select>
                    <option value="">Appointment Type</option>
                    <option value="">Check-Up</option>
                    <option value="">Vaccination</option>
                </select>
                <select>
                    <option value="">Date</option>
                    <option value="">Today</option>
                    <option value="">Tomorrow</option>
                </select>
                </div> -->
                <!-- <button id = "fbutton" class="button" style="background-color: #602147; color: #FFD700; font-family: MoreSugar;" >Book Now</button> -->
                </div>
            </div>
        </section>

        <section class="salon-card">
            <div class="salon-details">
                <div id="salon-detailsinner">
                <h3 class="salon_name">Kanji's Pet Grooming Services</h3>
                <p class="salon_add2">Unit 4, Burol Main, Mantele Apartelle, Dasmariñas, 4114 Cavite</p>
                <!-- <select>
                    <option value="">Appointment Type</option>
                    <option value="">Check-Up</option>
                    <option value="">Vaccination</option>
                </select>
                <select>
                    <option value="">Date</option>
                    <option value="">Today</option>
                    <option value="">Tomorrow</option>
                </select> -->
                <!-- <button  id = "fbutton" class="button" style="background-color: #602147; color: #FFD700; font-family: MoreSugar;" >Book Now</button> -->
                </div>
            </div>
            <div class="salon-map">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3865.7225388631796!2d120.94555448497732!3d14.32754275035683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d53415fb8067%3A0xcb1b949959029b7c!2sKanji&#39;s%20Pet%20Grooming%20Services!5e0!3m2!1sen!2sph!4v1719119722376!5m2!1sen!2sph" 
                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </div>



</body>
</html>