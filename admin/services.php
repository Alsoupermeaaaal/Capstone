<?php
session_start();

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

// Get the logged-in admin's salon ID from the session
$salonid = isset($_SESSION['salonid']) ? $_SESSION['salonid'] : '';

?>

<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Mission Im-Paws-Sible Services</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="services.css" />
</head>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        border-radius: 50%;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
    }

    input:checked + .slider {
        background-color: #4CAF50;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>

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
                            <span class="title nav">Admin</span>
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
    <main class="home">
        <header class="dashboard">Services</header>
        <div class="first">
            <button class="addbtn" onclick="openModal()">Add A Service</button>
        </div>
        <div class="second" style="margin-bottom: 100px;">
            <div class="appts">List of Services</div>
            <hr />
            <table class="appointments_table">
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Amount</th>
                    <th>Actions</th>
                    <th>Service Availability</th>
                </tr>
                <?php
                    $sql = "SELECT * FROM services WHERE salonid = '$salonid'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr id='service-" . $row['serviceid'] . "'>";
                            echo "<td>" . $row['serviceid'] . "</td>";
                            echo "<td>" . $row['servicename'] . "</td>";
                            echo "<td id='amount-" . $row['serviceid'] . "'>" . $row['price'] . "</td>";
                            echo "<td>";
                            echo "<button class='editbtn' onclick='openEditModal(\"" . $row['serviceid'] . "\", \"" . $row['servicename'] . "\", \"" . $row['price'] . "\")'>";
                            echo "<i class='fa-solid fa-pen-to-square'></i>";
                            echo "</button>";
                            echo "<button class='deletebtn' onclick='deleteService(\"" . $row['serviceid'] . "\")'>";
                            echo "<i class='fa-solid fa-trash'></i>";
                            echo "</button>";
                            echo "</td>";
                            echo "<td>";
                            // Toggle switch (sliding on/off)
                            echo "<label class='switch'>";
                            echo "<input type='checkbox' id='toggle-" . $row['serviceid'] . "' onchange='toggleLabel(this)'>";
                            echo "<span class='slider'></span>";
                            echo "</label>";
                            echo "<span id='status-" . $row['serviceid'] . "' class='status-label'>" . ($row ? 'Available' : 'Unavailable') . "</span>";

                        }
                    }
                ?>

            </table>
        </div>

        <!-- The Modal -->
        <div id="addServiceModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 class="textt">Add A New Service</h2>
                <form id="serviceForm" method="post">
                    <label class="label" for="serviceName">Enter Service Name</label><br>
                    <input class="serviceName" type="text" id="serviceName" name="serviceName"><br>
                    <label class="label" for="amount">Enter Amount</label><br>
                    <input class="serviceamount" type="text" id="amount" name="amount"><br><br>
                    <button type="submit" class="submitbtn">Submit</button>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editServiceModal" class="modal1">
            <div class="modal-content1">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2 class="textt">Edit Service</h2>
                <form id="editServiceForm" method="post">
                    <label class="label" for="editServiceId">Service ID</label><br>
                    <input class="serviceName" type="text" id="editServiceId" name="editServiceId" readonly><br>
                    
                    <label class="label" for="editServiceName">Service Name</label><br>
                    <input class="serviceName" type="text" id="editServiceName" name="editServiceName" readonly><br>
                    
                    <label class="label" for="editAmount">Enter New Amount</label><br>
                    <input class="serviceamount" type="text" id="editAmount" name="editAmount"><br><br>
                    
                    <button type="submit" class="updatebtn">Update</button>
                </form>
            </div>
        </div>

    </main>

    <script>
        // Function to add a new service when the form is submitted
// Function to add a new service when the form is submitted
document.getElementById("serviceForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting and refreshing the page

    // Get the values from the input fields
    const serviceName = document.getElementById("serviceName").value;
    const amount = document.getElementById("amount").value;

    // Send a POST request to add the new service
    fetch('add_service.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            serviceName: serviceName,
            amount: amount,
            salonid: <?php echo $salonid; ?>
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create a new row in the table
            const table = document.querySelector(".appointments_table");
            const newRow = document.createElement("tr");
            newRow.setAttribute("id", `service-${data.serviceid}`);
            newRow.innerHTML = `
                <td>${data.serviceid}</td>
                <td>${serviceName}</td>
                <td id="amount-${ data.serviceid}">${amount}</td>
                <td>
                    <button class="editbtn" onclick="openEditModal('${data.serviceid}', '${serviceName}', '${amount}')">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="deletebtn" onclick="deleteService('${data.serviceid}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;

            // Append the new row to the table
            table.appendChild(newRow);

            // Clear the input fields
            document.getElementById("serviceName").value = "";
            document.getElementById("amount").value = "";

            // Close the modal
            closeModal();
        } else {
            alert("Failed to add service");
        }
    })
    .catch(error => console.error('Error:', error));
});

        // For Delete button
        // Function to delete a service instantly without a prompt
        function deleteService(serviceId) {
            // Confirm with the user before deleting
            const confirmDelete = confirm("Are you sure you want to delete this service?");
            
            if (confirmDelete) {
                // Send a POST request to delete the service
                fetch('delete_service.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        serviceId: serviceId
                    })
                })
                .then(response => response .json())
                .then(data => {
                    if (data.success) {
                        // Find the row by its service ID and remove it from the DOM
                        const row = document.getElementById(`service-${serviceId}`);
                        if (row) {
                            row.remove(); // Instantly removes the row
                            alert("Service deleted successfully!");
                        } else {
                            alert("Service not found!");
                        }
                    } else {
                        alert("Failed to delete service");
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // For Edit Modal
        // Function to open the edit modal and pre-fill the form with current values
        function openEditModal(serviceId, serviceName, currentAmount) {
            // Display the modal
            document.getElementById("editServiceModal").style.display = "block";
            
            // Fill the form fields with the service data
            document.getElementById("editServiceId").value = serviceId;
            document.getElementById("editServiceName").value = serviceName;
            document.getElementById("editAmount").value = currentAmount;
        }

        // Function to close the modal
        function closeEditModal() {
            document.getElementById("editServiceModal").style.display = "none";
        }

        // Function to update the service amount when the form is submitted
        function updateService(event) {
            event.preventDefault(); // Prevent the form from submitting and refreshing the page
            
            // Get the values from the form
            const serviceId = document.getElementById("editServiceId").value;
            const newAmount = document.getElementById("editAmount").value;

            // Send a POST request to update the service
            fetch('update_service.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    serviceId: serviceId,
                    newAmount: newAmount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the amount in the table
                    document.getElementById(`amount-${serviceId}`).innerText = newAmount;

                    // Close the modal after updating
                    closeEditModal();
                } else {
                    alert("Failed to update service");
                }
            })
            .catch(error => console.error('Error:', error));
        }

        document.getElementById("editServiceForm").addEventListener("submit", updateService);

        // For Add Button MODAL
        // Open Modal
        function openModal() {
            document.getElementById("addServiceModal").style.display = "block";
        }

        // Close Modal
        function closeModal() {
            document.getElementById("addServiceModal").style.display = "none";
        }

        // Close modal if clicked outside of it
        window.onclick = function(event) {
            var modal = document.getElementById("addServiceModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        const profileLink = document.querySelector(".nav-link.profile > a");
        profileLink.addEventListener("click", (e) => {
            e.preventDefault();
            const dropdown = profileLink.nextElementSibling;
            dropdown.classList.toggle("show");
        });

        // Close dropdown if clicked outside
        document.addEventListener("click", (e) => {
            if (!profileLink.contains(e.target)) {
                document
                    .querySelector(".dropdown-menu.show")
                    ?.classList.remove("show");
            }
        });
        function toggleLabel(checkbox) {
            var statusLabel = document.getElementById("status-" + checkbox.id.split('-')[1]);
            statusLabel.textContent = checkbox.checked ? "Available" : "Unavailable";
        }

        // Run on page load
        document.addEventListener("DOMContentLoaded", function() {
            var checkboxes = document.querySelectorAll("input[type='checkbox']");
            checkboxes.forEach(function(checkbox) {
                toggleLabel(checkbox); // Set initial label based on checkbox state
            });
        });



    </script>

    
</body>
</html>