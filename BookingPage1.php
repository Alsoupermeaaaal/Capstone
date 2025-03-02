<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Im-Paws-Sible</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="bookingpage123.css">
    
    <!-- calendarjs -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js"></script>
    <style>
        #calendar {
            /* max-width: 600px; */
            margin: 40px auto; 
            background-color: #f9f9f9; /* Light gray background */
            border: 1px solid #ccc;   /* Optional border */
            border-radius: 8px;       /* Optional rounded corners */
        }
        @media (min-width: 768px) {
        #id_pick_service {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    </style>
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
    
    $pets = array();
    $stmt = $conn->prepare("SELECT petid, petname FROM petinfo WHERE ownerID = ?");
    $stmt->bind_param("i", $ownerID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pets[] = array('petid' => $row['petid'], 'petname' => $row['petname']);
    }
    
    
    $salons = array();
    $stmt = $conn->prepare("SELECT salonid, shopname FROM salon");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $salons[] = array('salonid' => $row['salonid'], 'shopname' => $row['shopname']);
    }
    
    $services = [];
    $stmt = $conn->prepare("SELECT serviceid, servicename, price FROM services ");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $services[] = array('serviceid' => $row['serviceid'], 
                            'servicename' => $row['servicename'],
                            'price' => $row["price"]);
    }
    
    ?>
    <body>
       <!-- Mobile Nav -->
    <div class="navbar" style="z-index:100;">
        <a href="Homenew.php" ><i class="fa-solid fa-house"></i><br>Home</a>
        <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
        <a href="BookingPage1.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
        <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
        <a href="Serv.php"><i class="fa-solid fa-briefcase"></i> </i><br>Services</a>
    </div>

    <!-- Web Nav -->
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


        <!-- Content -->
        <section class="first">
            <div class="label1"><span style="color: #FFD700;">Woof!</span> Let’s Get Your <br> Appointment Started!</div>
            <img  class="booking_img" src="booking_img.png" alt="">
        </section>

        <div class="navtop" style="justify-content:space-evenly;">
            <img class="logo_nav_top" src="logo-nav.png" >
            <div style="margin:5rem;"></div>
            <a href="Yourprofile.php"><i class="fa-solid fa-user" style="color: white;"></i><br></a>
        </div>

        <section class="background_book">
            <div class=" book_word"> BOOK AN <br> APPOINTMENT </div>
            
            <div class=" booking_box" id="booking">
            <form method="POST" action="BookingPage2.php">
                <div class="booking_items">Pick a Pet</div>
                
                <div id="pet_select0">
                    <select class="pet_select" name="pet_id">
                        <?php foreach ($pets as $pet) { ?>
                            <option value="<?php echo $pet['petid']; ?>"><?php echo $pet['petname']; ?></option>
                        <?php } ?>
                    </select>
                </div>    
                
                <div class="booking_items">Pick a Salon</div>
                
                <div id="salon_select0">
                <select class="salon_select" id="salon_select" name="salon_id" onchange="loadServices()">
                    <option value="" disabled selected>Pick a Salon</option>
                    <?php foreach ($salons as $salon) { ?>
                        <option value="<?php echo $salon['salonid']; ?>">
                            <?php echo $salon['shopname']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="booking_items">Pick a Service</div>

            <div id="id_pick_service" style="display: none; grid-template-columns: 1fr; gap: 10px;">
                <!-- Services will be dynamically loaded here -->
            </div>

                

                <hr class="line2"></hr>
                
                <div class="booking_items">Pick a Date</div>
                
                <div class="calendar-container">   
                    <div class="calendar-headerjs"> 
                        <div id="calendar"> </div>
                        <input type="hidden" id="selectedDate" name="selected_date">    
                    </div>    
                    
                </div>  
                    <div class="booking_items">Pick Time</div>
                    <div class="slots calendar-time" id="timeSlots2" style="color:white;"><?php include ("timeSlots2.php")?></div>

                <hr class="line3"></hr>
                
                <div class="booking_items">Payment Method</div>
                
                <select class="payment_select" name="payment_method">
                    <option value="gcash">Gcash</option>
                    <option value="cash">Cash</option>
                </select>
                    
                <hr class="line4"></hr>
                
                <div class="fee">
                    <h3 class="amount" id="total">Total Amount: Php0.00</h3>
                </div>
            
            
                <a><button type="submit" class="button_next">Next</button></a>
                <!-- Modal Structure -->
                <div id="popupModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p>Please complete all details to proceed.</p>
                    </div>
                </div>
            </form> 
            
            </div>
        </section>

        <script>
            
            document.addEventListener('DOMContentLoaded', function () {
                const salonSelect = document.getElementById('salon_select');
                const serviceSelect = document.getElementById('id_pick_service');
                const serviceForm = document.getElementById('id_pick_service');
                const serviceCheckbox = document.querySelectorAll('.service_checkbox');
                const totalAmount = document.getElementById('total');

                //generate the default timeslot on first load
                generateTimeSlots(1);
                
                //select chain of salon to service
                salonSelect.addEventListener('change', function () {
                    const salonId = this.value;
                
                    serviceSelect.innerHTML = '';
                    serviceSelect.disabled = true;
                    if (salonId) {
                        fetch(`ajax/services.php?salon_id=${salonId}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                let options = '';
                                data.forEach(services => {
                                    options += `<label><input type="checkbox" class="service_checkbox" name="serviceid[]" value="${services.serviceid}" data-amount="${services.price}" onchange="updateTotal"><span>${services.servicename} (₱${services.price}.00)</span></label>`;
                                });
                                
                                serviceSelect.innerHTML = options;
                                serviceSelect.disabled = false;
                                
                                totalAmount.textContent = "0.00"; //reset total to zero every change of salon
                                
                                //reselect all dynamically created checkboxes
                                const serviceCheckCreated = document.querySelectorAll('.service_checkbox');
                                serviceCheckCreated.forEach(checkbox => {
                                    checkbox.addEventListener('change', updateTotal);
                                });

                                generateTimeSlots(salonId);
                            })
                            .catch(error => {
                                console.error('Error fetching subcategories:', error);
                            });
                    } else {
                        
                        serviceSelect.innerHTML = '<option value="">Select a subcategory</option>';
                        serviceSelect.disabled = true;
                    }
                    serviceCheckbox.forEach(checkbox => {
                        checkbox.addEventListener('change', updateTotal);
                    });
                });

                /*
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
                */
                

                serviceCheckbox.forEach(checkbox => {
                    checkbox.addEventListener('change', updateTotal);
                });
                
                

            });                

            updateTotal = () => {
                const serviceCheckbox = document.querySelectorAll('.service_checkbox');
                const totalAmount = document.getElementById('total');
                
                let total = 0;
                serviceCheckbox.forEach(checkbox => {
                    if (checkbox.checked) {
                        total += parseFloat(checkbox.dataset.amount);
                    }
                });
                totalAmount.textContent = total.toFixed(2);
            };

        // fetch('/capstone/get-booked-dates.php')
        //     .then(response => response.json())
        //     .then(data => {
        //         // Update salonBookedDates with the fetched data
        //         salonBookedDates = data;

        //         // Render the calendar
        //         renderCalendar();
        //     })
        //     .catch(error => console.error('Error fetching booked dates:', error));



        
    
const modal = document.getElementById("popupModal");
const closeModal = document.getElementsByClassName("close")[0];

    // document.querySelector('.button_next').addEventListener('click', function(event) {
    //     event.preventDefault(); // Prevent default form submission
    //     console.log(`MAMA MO`);
    //     // Check if all required fields are filled
    //     const petSelect = document.querySelector('.pet_select').value;
    //     const salonSelect = document.querySelector('#salon_select').value;
    //     const servicesChecked = document.querySelectorAll('input[name="services"]:checked').length > 0;
    //     const dateSelected = document.querySelector('#date').value;
    //     const timeSelected = document.querySelector('#meeting-time').value;
    //     const paymentSelect = document.querySelector('.payment_select').value;

    //     var checkboxes = document.querySelectorAll('input[name="services"]:checked');

    //     // Initialize an array to store the selected values
    //     var selectedItems = [];

    //     // Loop through the checkboxes and get their values
    //     checkboxes.forEach(function(checkbox) {
    //         selectedItems.push(checkbox.value);
    //         });

    //         // Validate inputs
    //         if (!petSelect || !salonSelect || !servicesChecked || !dateSelected || !timeSelected || !paymentSelect || !servicesChecked) {
    //         modal.style.display = "block"; // Show the modal
    //     } else {
    //         // Proceed to the next page
    //         window.location.href = `book1.php?salon=${salonSelect}&pet=${petSelect}&date=${dateSelected}
    //         &meeting-time=${timeSelected}&payment=${paymentSelect}&service=${selectedItems}`;
    //     }
    // });


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
                const todayDate = `${year}-${month}-${day}`;
                datePicker.setAttribute('min', todayDate);
            }

            // Call the function when the page loads
            window.onload = setMinDate;
            
            function loadServices() {
    const salonSelect = document.getElementById('salon_select');
    const serviceContainer = document.getElementById('id_pick_service');

    if (salonSelect.value) {
        // Show the service container
        serviceContainer.style.display = "grid";

        // Optionally, you can make an AJAX call to load services for the selected salon
        // For now, the PHP logic already filters the data for the selected salon
    } else {
        // Hide services if no salon is selected
        serviceContainer.style.display = "none";
    }
}

            
        </script>


        <!-- <script src="Date.js"></script> -->
        <script src="assets/js/calendarGrid.js?2"></script> 
    </body>
    </html>