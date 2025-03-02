<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>u181432410_mips</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap">
<link rel="stylesheet" href="book.css">
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

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the ownerID of the currently logged-in user
$ownerID = $_SESSION['ownerID'];

$stmt = $conn->prepare("SELECT petid, petname FROM petinfo WHERE ownerID = ?");
$stmt->bind_param("i", $ownerID);
$stmt->execute();
$result = $stmt->get_result();

// Create an array to store the pet information
$pets = array();


while ($row = $result->fetch_assoc()) {
    $pets[] = array('petid' => $row['petid'], 'petname' => $row['petname']);
}


$stmt = $conn->prepare("SELECT salonid, shopname FROM salon");
$stmt->execute();
$result = $stmt->get_result();

// Create an array to store the pet information
$salons = array();


while ($row = $result->fetch_assoc()) {
    $salons[] = array('salonid' => $row['salonid'], 'shopname' => $row['shopname']);
}

?>

<body>
    <!-- Mobile Nav -->
    <div style="z-index: 50;" class="navbar">
    <a href="Homenew.php" ><i class="fa-solid fa-house"></i><br>Home</a>
    <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
    <a href="book.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
    <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
    <a href="Serv.php"><i class="fa-solid fa-briefcase"></i> </i><br>Services</a>
    <a href="Yourprofile.php"><i class="fa-solid fa-user"></i><br>Profile</a>
    </div>

    <!-- Web Nav -->
    <header class="header">
    <div class="logo">
        <a href="#">
            <img class="logo" src="logo.png" alt="logo">
        </a>
    </div>
    <nav class="nav">
        <ul class="main-nav">
            <li><a href="Homenew.php">Home</a></li>
            <li><a href="Serv.php">Services</a></li>
            <li><a href="LocationNew.php">Location</a></li>
            <li class="book_button"><a href="book.php"><button>Book Now!</button></a></li>
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
    
    <!-- Content -->
    <section class="first">
        <div class="label1"><span style="color: #FFD700;">Woof!</span> Let’s Get Your <br> Appointment Started!</div>
        <img  class="booking_img" src="booking_img.png" alt="">
    </section>
    
    <div class="navtop">

        <img  class="logo_nav_top" src="logo-nav.png" >
    </div>
    
    <section class="background_book" >
        <div class=" book_word"> BOOK AN <br> APPOINTMENT</div>
        <div class=" booking_box" id="booking">
        <form method="POST" action="book1.php">
            <div class="booking_items">Pick a Pet</div>
                <div id="pet_select0">
                <select class="pet_select">
                    <?php foreach ($pets as $pet) { ?>
                        <option value="<?php echo $pet['petid']; ?>"><?php echo $pet['petname']; ?></option>
                    <?php } ?>
                </select>
                </div>    
                <div class="booking_items">Pick a Salon</div>
                <div id="salon_select0">
                    <select class="salon_select" id="salon_select">
                    <?php foreach ($salons as $salon) { ?>
                        <option value="<?php echo $salon['salonid']; ?>"><?php echo $salon['shopname']; ?></option>
                    <?php } ?>
                    </select>
                </div>
            
                <div class="booking_items">Pick a Service</div>
                <div id="id_pick_service">
                <?php foreach ($services[$selectedSalon] as $service) { ?>
                    <label>
                        <input type="checkbox" name="serviceid[]" value="<?php echo $service['value']; ?>">
                        <?php echo $service['text']; ?>
                    </label><br>
                <?php } ?>
                </div>
            
            

            <div class="booking_items">Pick a Date</div>
                <div id="date_select">
                    <input class="date_picker" type="date" id="date" name="date">
                </div>

            <div class="booking_items">Pick a Time</div>
                <div id="time_select">
                    <input class="time_picker" type="time" id="meeting-time" name="meeting-time">
                </div>
                
            <div class="booking_items">Payment Method</div>
                <div id="payment_select0">
                    <select class="payment_select">
                        <option>Gcash</option>
                        <option>Cash</option>
                    </select>
                </div>
                
            </div>
            <!-- href="book1.html" -->
            <a ><button class=" button_next">Next</button></a>
            <!-- Modal Structure -->
            <div id="popupModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Please complete all details to proceed.</p>
                </div>
            </div>
        </form>
    </section>

    <script>
        const services = {

    1: [
        { value: 1, text: 'Online Consultation', amount: 400 },  
        { value: 2, text: 'Consultation', amount: 400},
        { value: 3, text: 'Treatment', amount: 400 },
        { value: 4, text: 'Vaccine & Deworming', amount: 300},
        { value: 5, text: 'Full Grooming' , amount: 700 },
        { value: 6, text: 'Sanitary', amount: 400},
        { value: 7, text: 'Face Trim', amount: 149 },
        { value: 8, text: 'Nail Trim', amount: 199 },
        { value: 9, text: 'Ear Cleaning', amount: 119 },
        { value: 10, text: 'Laboratory Test' , amount: 500},
        { value: 11, text: 'Surgery', amount: 1000},
        { value: 12, text: 'Confinement', amount: 500 },
        { value: 13, text: 'Boarding', amount: 300 },    
        { value: 14, text: 'Whelping Assistant', amount: 400 },
        ],
        
    2: [
        { value: 15, text: 'Full Groom Small', amount: 400 },
        { value: 16, text: 'Full Groom Medium', amount: 500 },
        { value: 17, text: 'Full Groom Large', amount: 700},
        { value: 18, text: 'Full Groom Extra Large', amount: 1200 },
        { value: 19, text: 'Bath and Blow Dryer Small', amount: 350 },
        { value: 20, text: 'Bath and Blow Dryer Medium', amount: 400 },
        { value: 21, text: 'Bath and Blow Dryer Large', amount: 550 },
        { value: 22, text: 'Bath and Blow Dryer Extra Large', amount: 650},
        { value: 23, text: 'Heavy Dematting Small', amount: 150 },
        { value: 24, text: 'Heavy Dematting Medium', amount: 150 },
        { value: 25, text: 'Heavy Dematting Large', amount: 200},
        { value: 26, text: 'Heavy Dematting Extra Large', amount: 300 },
        { value: 27, text: 'Nail Trimming Small-Medium', amount: 50 },
        { value: 28, text: 'Nail Trimming Large', amount: 100 },
        { value: 29, text: 'Teeth Brushing Small-Medium', amount: 50 },
        { value: 30, text: 'Teeth Brushing Large', amount: 100 },
        { value: 31, text: 'Ear Cleaning Small-Medium', amount: 50 },
        { value: 32, text: 'Ear Cleaning Large', amount: 100 },
        { value: 33, text: 'Face Trim', amount: 100 },
        { value: 34, text: 'Sanitary Trim Small-Medium', amount: 50 },
        { value: 35, text: 'Sanitary Trim Large', amount: 100 }
],
    3: [
        { value: 36, text: 'Standard Groom (Dogs Only) Small', amount: 399 },
        { value: 37, text: 'Standard Groom (Dogs Only) Medium', amount: 549 },
        { value: 38, text: 'Standard Groom (Dogs Only) Large', amount: 649 },
        { value: 39, text: 'Standard Groom (Dogs Only) Giant', amount:799 },
        { value: 40, text: 'Full Groom (Dogs Only) Small', amount: 499},
        { value: 41, text: 'Full Groom (Dogs Only) Medium', amount: 649 },
        { value: 42, text: 'Full Groom (Dogs Only) Large', amount: 749},
        { value: 43, text: 'Full Groom (Dogs Only) Giant', amount: 899},
        { value: 44, text: 'Full Groom (Cats Only) Small Kitten', amount: 549 },
        { value: 45, text: 'Full Groom (Cats Only) Adult', amount: 649 },
        { value: 46, text: 'Toothbrush', amount: 99 },
        { value: 47, text: 'Ear Cleaning', amount: 119 },
        { value: 48, text: 'Face Trim', amount: 149},
        { value: 49, text: 'Nail Trim', amount: 119},
        { value: 50, text: 'Tear Stain Clean', amount: 149},
        { value: 51, text: 'Anal Sac Express', amount: 149 },
        { value: 52, text: 'Fur Trim', amount: 199 },
        { value: 53, text: 'Fur Style/ Shave', amount: 299 },
        { value: 54, text: 'Dematting', amount: 199},
        { value: 55, text: 'Full Bath', amount: 249},
        { value: 56, text: 'Med. Bath', amount: 349 }
    ]
};

document.getElementById('salon_select').addEventListener('change', function() {
    const selectedSalon = this.value;
    const serviceForm = document.getElementById('id_pick_service');
    serviceForm.innerHTML = '';

    if (services[selectedSalon]) {
        services[selectedSalon].forEach(service => {
            const label = document.createElement('label');
            const input = document.createElement('input');
            const span = document.createElement('span');

            input.type = 'checkbox';
            input.name = 'services';
            input.value = service.value;
            span.textContent = service.text;

            label.appendChild(input);
            label.appendChild(span);
            serviceForm.appendChild(label);
        });
    }
});

// Trigger change event to load default services
document.getElementById('salon_select').dispatchEvent(new Event('change'));

const modal = document.getElementById("popupModal");
const closeModal = document.getElementsByClassName("close")[0];

document.querySelector('.button_next').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission

    // Check if all required fields are filled
    const petSelect = document.querySelector('.pet_select').value;
    const salonSelect = document.querySelector('#salon_select').value;
    const servicesChecked = document.querySelectorAll('input[name="services"]:checked').length > 0;
    const dateSelected = document.querySelector('#date').value;
    const timeSelected = document.querySelector('#meeting-time').value;
    const paymentSelect = document.querySelector('.payment_select').value;

    var checkboxes = document.querySelectorAll('input[name="services"]:checked');

    // Initialize an array to store the selected values
    var selectedItems = [];

    // Loop through the checkboxes and get their values
    checkboxes.forEach(function(checkbox) {
        selectedItems.push(checkbox.value);
        });


    // Validate inputs
    if (!petSelect || !salonSelect || !servicesChecked || !dateSelected || !timeSelected || !paymentSelect || !servicesChecked) {
        modal.style.display = "block"; // Show the modal
    } else {
        // Proceed to the next page
        window.location.href = `book1.php?salon=${salonSelect}&pet=${petSelect}&date=${dateSelected}
        &meeting-time=${timeSelected}&payment=${paymentSelect}&service=${selectedItems}`;
    }
});

// Close the modal when the close button is clicked
closeModal.onclick = function() {
    modal.style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

    </script>

<script>
        // Function to set the min attribute to today's date
        function setMinDate() {
            const datePicker = document.getElementById('date');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const day = String(today.getDate()).padStart(2, '0');
            const todayDate = ${year}-${month}-${day};
            datePicker.setAttribute('min', todayDate);
        }

        // Call the function when the page loads
        window.onload = setMinDate;
</script>


</body>
</html>