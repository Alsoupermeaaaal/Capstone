<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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

// Retrieve pet information for the currently logged-in account
$sql = "SELECT * FROM petinfo WHERE ownerID = '$ownerID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pets = array();
    while($row = $result->fetch_assoc()) {
        $petID = $row['petid'];
        $petName = $row['petname'];
        $petBirth = $row['petbirth'];
        $petGender = $row['petgender'];
        $petSpecies = $row['petspecies'];
        $petBreed = $row['petbreed'];
        $petPhoto = $row['petphoto'];

        // Retrieve appointment history for each pet
        $appointmentsSql = "SELECT b.bookid, b.date, b.paymentmethod, b.paymentprice, b.status, 
                            GROUP_CONCAT(s.servicename SEPARATOR ', ') AS services 
                            FROM book b 
                            JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0 
                            WHERE b.petID = '$petID' 
                            GROUP BY b.bookid";
        $appointmentsResult = $conn->query($appointmentsSql);
        $appointments = array();

        if ($appointmentsResult->num_rows > 0) {
            while ($appointmentRow = $appointmentsResult->fetch_assoc()) {
                $appointments[] = $appointmentRow;
            }
        }

        $pets[] = array(
            'petID' => $petID,
            'petName' => $petName,
            'petBirth' => $petBirth,
            'petGender' => $petGender,
            'petSpecies' => $petSpecies,
            'petBreed' => $petBreed,
            'petPhoto' => $petPhoto,
            'appointments' => $appointments
        );
    }
} else {
    $pets = array();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="addpetnew2.css">
    <title>Mission Im-Paws-Sible</title>
    
</head>
<body>
    <main>
        <!-- Mobile Nav -->
        <div class="navbar">
            <a href="Homenew.php"><i class="fa-solid fa-house"></i><br>Home</a>
            <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
            <a href="BookingPage1.php"><i class="fa-solid fa-plus"></i><br>Book</a>
            <a href="addpetnew.php"><i class="fa-solid fa-paw"></i><br>Pets</a>
            <a href="Serv.php"><i class="fa-solid fa-briefcase"></i><br>Services</a>
        </div>

        <div class="navtop" style="justify-content:space-evenly;">
    <img class="logo_nav_top" src="logo-nav.png" >
    <div style="margin:5rem;"></div>
    <a href="Yourprofile.php"><i class="fa-solid fa-user" style="color: white;"></i><br></a>
</div>

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

        <div id="bottom_container">
            <!-- SIDE NAV -->
            <div class="sidenav">
                <h1 style="text-align: center; font-family: 'Lora', serif;">Your Pets</h1>
                <button id="addPetButton" class="addpet" style="height:40px; width:85%;">Add a Pet</button>
                <div id="petList"></div>
            </div>
            <div class="main-content">
                <!-- Add pet Form -->
                <div id="petDetailsForm" class="pet-details-form" style="display: none;">
                    <h2>Add New Pet Details</h2>
                    <form id="petForm" enctype="multipart/form-data">
                        <div class="center-button">
                            <label for="fileInput" class="pet-button-picture" id="uploadButton">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            <input style="display:none;" type="file" id="fileInput" name="fileInput" accept="image/*">
                            <div class="text-pet">Add Photo</div>
                        </div>
                        <label for="petNameInput">Name:</label>
                        <input type="text" id="petNameInput" name="petname" required><br>

                        <label for="petBirthdayInput">Birthday:</label>
                        <input type="date" id="petBirthdayInput" name="petbirth" required><br>

                        <label for="petSpeciesInput">Species:</label>
                        <input type="text" id="petSpeciesInput" name="petspecies" required><br>

                        <label for="petBreedInput">Breed:</label>
                        <input type="text" id="petBreedInput" name="petbreed" required><br>

                        <label for="petSexInput">Sex:</label>
                        <select id="petSexInput" name="petgender" required>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select><br>

                        <button type="submit" class="submit-button">Add Pet</button>
                        <div id="successMessage" style="display: none; color: green;"></div>
                    </form>
                </div>
                <!-- Content -->
                <div id="petDetails">
                    <div class="pet-details">
                        <h2 style="text-align: center; font-family: 'Lora', sans-serif; color: #602147;">Pet Information</h2>
                        <img id="petImage" src="" alt="Pet" style="width:150px;height:150px;">
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Name:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petName"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Birthday:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petBirthday"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Sex:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petSex"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Species:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petSpecies"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Breed:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petBreed"></span></p>
                    </div>
                    <div class="pet-history">
                        <h2>Pet History</h2>
                        <table class="appointments_table">
                            <tr>
                                <th>Reference Number</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Total Fee</th>
                                <th>Appointment Status</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    function getPetList() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "getPetList.php", true);
        xhr.send();
        xhr.onload = function() {
            if (xhr.status === 200) {
                var petList = JSON.parse(xhr.responseText);
                var html = "";
                for (var i = 0; i < petList.length; i++) {
                    html += "<a href='#' data-petname='" + petList[i].petname + "'>" + petList[i].petname + "</a><br>";
                }
                document.getElementById("petList").innerHTML = html;
            } else {
                console.log("Error getting pet list: " + xhr.statusText);
            }
        };
    }

    const petList = document.getElementById('petList');
    petList.addEventListener('click', function(event) {
        if (event.target.tagName === 'A') {
            const petName = event.target.textContent;
            console.log(`Pet Name: ${petName}`);
            getPetDetails(petName);
        }
    });

    function getPetDetails(petName) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `getPetDetails.php?petname=${petName}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                try {
                    const petDetails = JSON.parse(xhr.responseText);
                    displayPetDetails(petDetails);
                    document.getElementById('petDetailsForm').style.display = 'none';
                    document.getElementById('petDetails').style.display = 'block';
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    console.log('Response:', xhr.responseText);
                }
            } else {
                console.log('Error getting pet details:', xhr.statusText);
            }
        };
        xhr.send();
    }

    function displayPetDetails(petDetails) {
        console.log(petDetails);
        const petImage = document.getElementById('petImage');
        const petName = document.getElementById('petName');
        const petBirthday = document.getElementById('petBirthday');
        const petSex = document.getElementById('petSex');
        const petSpecies = document.getElementById('petSpecies');
        const petBreed = document.getElementById('petBreed');
        const appointmentsTable = document.querySelector('.appointments_table');

        petImage.src = petDetails.petphoto;
        petName.textContent = petDetails.petname;
        petBirthday.textContent = petDetails.petbirth;
        petSex.textContent = petDetails.petgender;
        petSpecies.textContent = petDetails.petspecies;
        petBreed.textContent = petDetails.petbreed;

        appointmentsTable.innerHTML = `
            <tr>
                <th>Reference Number</th>
                <th>Service</th>
                <th>Date</th>
                <th>Payment Method</th>
                <th>Total Fee</th>
                <th>Appointment Status</th>
            </tr>
        `;

        petDetails.appointments.forEach(appointment => {
            console.log(appointment);
            const row = appointmentsTable.insertRow();
            row.innerHTML = `
                <td>${appointment.bookid}</td>
            <td>${appointment.services || 'No services available'}</td> <!-- Display all services -->
            <td>${appointment.date}</td>
            <td>${appointment.paymentmethod}</td>
            <td>${appointment.paymentprice}</td>
            <td>${appointment.appointment_status}</td>
            `;
        });
    }

    // document.getElementById('uploadButton').addEventListener('click', function() {
    //     document.getElementById('fileInput').click();
    // });

    // document.getElementById('fileInput').addEventListener('change', function(event) {
    //     const file = event.target.files[0];
    //     if (file) {
    //         console.log('Selected file:', file.name);
    //     }
    // });
    document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("fileInput");
    const uploadButton = document.getElementById("uploadButton");

    fileInput.addEventListener("change", (event) => {
        // Retrieve the uploaded file
        const file = event.target.files[0];
        if (file) {
            console.log("File selected:", file);

            // Disable the button to prevent further clicks
            uploadButton.style.pointerEvents = "none"; // Prevent clicks on the label
            uploadButton.style.opacity = "0.5"; // Optional: visually indicate disabled state
        }
    });
});

    document.getElementById('addPetButton').addEventListener('click', function() {
        const petForm = document.getElementById('petDetailsForm');
        const petDetails = document.getElementById('petDetails');
        petForm.style.display = 'block';
        petDetails.style.display = 'none';
    });

    document.getElementById('petForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'addpet.php', true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                console.log('Pet added successfully!');
                const successMessage = document.getElementById('successMessage');
                successMessage.textContent = 'Pet added successfully!';
                successMessage.style.display = 'block';

                // Clear form fields
                document.getElementById('petNameInput').value = '';
                document.getElementById('petBirthdayInput').value = '';
                document.getElementById('petSpeciesInput').value = '';
                document.getElementById('petBreedInput').value = '';
                document.getElementById('petSexInput').value = '';
                document.getElementById('fileInput').value = '';

                // Refresh the pet list
                getPetList();

                // Hide the form and show the updated pet list
                document.getElementById('petDetailsForm').style.display = 'none';
                document.getElementById('petDetails').style.display = 'block';
            } else {
                console.log('Error adding pet:', xhr.statusText);
            }
        };
        xhr.send(formData);
    });

    window.onload = getPetList;
    </script>
</body>
</html>

