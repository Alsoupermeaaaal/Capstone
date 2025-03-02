    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>u181432410_mips</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="book1.css">
        
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap">
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

    // Get values from URL parameters
    $selectedPet = $_GET['pet'] ?? '';
    $selectedSalon = $_GET['salon'] ?? '';
    $selectedDate = $_GET['date'] ?? '';
    $selectedTime = $_GET['meeting-time'] ?? '';
    $selectedPayment = $_GET['payment'] ?? '';
    $userservices = isset($_GET['service']) ? $_GET['service'] : [];



    $stmt = $conn->prepare("SELECT petname FROM petinfo WHERE petid = ?");
    $stmt->bind_param("i", $selectedPet);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $petname = $row['petname'];

    $stmt->close();

    // Define the $salons array
    $salons = array(
        array('salonid' => 1, 'shopname' => 'Vetter Health Animal Clinic and Livestock Consultancy'),
        array('salonid' => 2, 'shopname' => 'Davids Pet Grooming Salon'),
        array('salonid' => 3, 'shopname' => 'Kanjis Pet Grooming Services'),
        // Add more salons to the array as needed
    );

    $salonName = '';
    foreach ($salons as $salon) {
        if ($salon['salonid'] == $selectedSalon) {
            $salonName = $salon['shopname'];
            break;
        }
    }

    // Fetch service names from the database
$serviceNames = [];
if (!empty($userservices)) {
    $serviceIds =($userservices);
    $sql = "SELECT servicename FROM services WHERE serviceid IN ($serviceIds)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $serviceNames[] = htmlspecialchars($row['servicename']);
        }
    }
}
$serviceNames = implode(',',$serviceNames);

$conn->close();

    ?>

    <body>
        <div  class="nav">
            <div>
                <a href="book.php"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
                Booking Summary
            </div>
        </div>

        <form action="booking2.php" method="post" id="bookingForm">
        <!-- content -->
        <div class="box">
            <div class="contents">Service</div>
            <div class="services1">
                <div class="contents1_service"><?php echo ($serviceNames); ?></div>
                <i onclick="this.parentElement.style.display='none'" class="fa-solid fa-x"></i>
            </div>
            
            <!-- <a href="#" id="add_services_button" class="add_services_button">
                <i class="fa-regular fa-plus plus_sign"></i>
                <div id="add_more">Add More Services</div> -->
                <div id="div1"></div>
            </a>
            <div class="booking_items" id="booking_items" style="display: none;">
                    <!-- Services will be populated here -->
                    <form id="id_pick_service">
                    <?php foreach ($services[$selectedSalon] as $service) { ?>
                        <label>
                            <input type="checkbox" name="serviceid[]" value="<?php echo $service['value']; ?>">
                            <?php echo $service['text']; ?>
                        </label><br>
                        <input type="hidden" name="services[]" value="<?php echo htmlspecialchars($service); ?>">
                    <?php } ?>
                    </form>

                    

            </div>
            <input type="hidden" name="services" value="<?php echo htmlspecialchars($userservices); ?>">
            <hr class="line1">
            <div class="container_date_time">
                <div class="date_div">
                    <div class="contents">Date</div>
                </div>
                <div class="time_div">
                    <div class="contents">Time</div>
                </div>
            </div>
                
            <div class="contents3">  
                <div class="contents1_date"><?php echo htmlspecialchars($selectedDate); ?></div>
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                <div class="contents1_time"><?php echo htmlspecialchars($selectedTime); ?></div>
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($selectedTime); ?>">
            </div>
            <hr class="line1">
            
            <div class="contents">Pet</div>
            <div class="contents1_pet"><?php echo htmlspecialchars($petname); ?></div>
            <input type="hidden" name="petid" value="<?php echo htmlspecialchars($selectedPet); ?>">
            <hr class="line1">
            <div class="contents">Pet Salon</div>
            <div class="contents1_salon"><?php echo htmlspecialchars($salonName); ?></div>
            <input type="hidden" name="petsalon" value="<?php echo htmlspecialchars($selectedSalon); ?>">
            <hr class="line1">
            <div class="contents">Payment Method</div>
            <div class="contents1_payment"><?php echo htmlspecialchars($selectedPayment); ?></div>
            <input type="hidden" name="paymentmethod" value="<?php echo htmlspecialchars($selectedPayment); ?>">
        </div>

        <a class="cd-popup-trigger book_button">Book</a>
        <div class="cd-popup" role="alert">
            <div class="cd-popup-container">
                <i id="initial-icon" class="fa-solid fa-calendar-check"></i>
                <p class="popup-text">Are you sure you want to book an appointment?</p>
                <div id="confirmation-section" style="display: none;">
                    <i class="fa-solid fa-circle-check"></i>
                    <p class="confirmation-text">Booking Successful!</p>
                </div>
                <ul class="cd-buttons">
                    <li><button type="button" id="yes-button" onclick="confirmBooking()">Book</button></li>
                    <li><a href="#0" class="no-button">Cancel</a></li>
                </ul>
                <a href="#0" class="cd-popup-close img-replace">Close</a>
            </div>
        </div>
    </form>
        
        
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        
        const services = {

        1: [

        { value: 1, text: 'Online Consultation' },  
        { value: 2, text: 'Consultation' },
        { value: 3, text: 'Treatment' },
        { value: 4, text: 'Vaccine & Deworming' },
        { value: 5, text: 'Full Grooming' },
        { value: 6, text: 'Sanitary' },
        { value: 7, text: 'Face Trim' },
        { value: 8, text: 'Nail Trim' },
        { value: 9, text: 'Ear Cleaning' },
        { value: 10, text: 'Laboratory Test' },
        { value: 11, text: 'Surgeryy' },
        { value: 12, text: 'Confinement' },
        { value: 13, text: 'Boarding' },    
        { value: 14, text: 'Whelping Assistant' },
        ],
        
        2: [
            { value: 15, text: 'Full Groom' },
            { value: 16, text: 'Bath and Blow Dryer' },
            { value: 17, text: 'Heavy Dematting' },
            { value: 18, text: 'Nail Trimming' },
            { value: 19, text: 'Teeth Brushing' },
            { value: 20, text: 'Ear Cleaning' },
            { value: 21, text: 'Face Trim' },
            { value: 22, text: 'Sanitary Trim' }
        ],
        3: [
            { value: 23, text: 'Standard Groom (Dogs Only)' },
            { value: 24, text: 'Full Groom (Dogs Only)' },
            { value: 25, text: 'Full Groom (Cats Only)' },
            { value: 26, text: 'Teeth Brushing' },
            { value: 27, text: 'Ear Cleaning' },
            { value: 28, text: 'Face Trim' },
            { value: 29, text: 'Nail Trim' },
            { value: 30, text: 'Tear Stain Clean' },
            { value: 31, text: 'Anal Sac Express' },
            { value: 32, text: 'Fur Trim' },
            { value: 33, text: 'Fur Style Shave' },
            { value: 34, text: 'Dematting' },
            { value: 35, text: 'Full Bath' },
            { value: 36, text: 'Med Bath' }
        ]
    };

        
        document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedSalon = urlParams.get('salon');

                if (selectedSalon) {
                    populateServices(selectedSalon);
                }

                document.getElementById('add_services_button').addEventListener('click', function(event) {
                    event.preventDefault();
                    // document.getElementById('booking_items').style.display = 'block';
                    const bookingItems = document.getElementById('booking_items');
        if (bookingItems.style.display === 'block') {
            bookingItems.style.display = 'none';
        } else {
            bookingItems.style.display = 'block';
        }
                });
            });
            
            function populateServices(salon) {
                const serviceForm = document.getElementById('id_pick_service');
                serviceForm.innerHTML = '';

                if (services[salon]) {
                    services[salon].forEach(service => {
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
            }

        jQuery(document).ready(function($){
        //open popup
        $('.cd-popup-trigger').on('click', function(event){
            event.preventDefault();
            $('.cd-popup').addClass('is-visible');
        });
        
        //close popup
        $('.cd-popup').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
                event.preventDefault();
                $(this).removeClass('is-visible');
            }
        });
        //close popup when clicking the esc keyboard button
        $(document).keyup(function(event){
            if(event.which=='27'){
                $('.cd-popup').removeClass('is-visible');
            }
        });

        // show confirmation message when "Yes" button is clicked
        $('.yes-button').on('click', function(event){
            event.preventDefault();
            $('.popup-text').hide();
            $('.confirmation-text').show();
            $('.cd-buttons').hide();
        });
        $('.no-button').on('click', function(event){
            event.preventDefault();
            $('.cd-popup').removeClass('is-visible');
        });


    });

    </script>
    <script>
        function confirmBooking() {
        // Hide the booking text and icon
        document.querySelector('.popup-text').style.display = 'none';
        document.getElementById('booking-icon').style.display = 'none';
        // Show the confirmation text
        document.querySelector('.confirmation-text').style.display = 'block';
    }

    </script>
    <script>
        function confirmBooking() {
        // Hide the initial booking text and icon
        document.querySelector('.popup-text').style.display = 'none';
        document.getElementById('initial-icon').style.display = 'none';
        // Show the confirmation section
        document.getElementById('confirmation-section').style.display = 'block';

        // Submit the form
        document.getElementById('bookingForm').submit();

        
    }
    </script>

        
    </body>
    </html>