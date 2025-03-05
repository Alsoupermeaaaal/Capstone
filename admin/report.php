<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Mission Im-Paws-Sible Report</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="report.css">
</head>
<body>
    
<?php
session_start();
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables for the report
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

$reports = []; // Initialize an empty array for reports

// Retrieve the logged-in admin's salon ID from the session
$salonId = $_SESSION['salonid'] ?? null; // Ensure salonid is stored in the session

// Check if both startDate and endDate are provided
if ($startDate && $endDate && $salonId) {
    // Prepare the SQL query based on the date range and salon ID
    $sql = "SELECT 
                b.bookID, 
                CONCAT(r.ownerfname, ' ', r.ownerlname) AS ownerName, 
                p.petname AS petName, 
                b.salonid, 
                GROUP_CONCAT(s.servicename SEPARATOR ', ') AS serviceNames, b.date, 
                b.time, 
                b.paymentmethod, 
                b.is_cancelled, 
                b.cancel_date, 
                b.status, 
                b.paymentprice 
            FROM book b
            JOIN registration_info r ON b.ownerID = r.ownerID
            JOIN petinfo p ON b.petid = p.petid
            JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0
            WHERE b.date >= ? AND b.date <= ? AND b.salonid = ?
            GROUP BY b.bookID, r.ownerfname, r.ownerlname, p.petname, b.salonid, b.date, b.time, b.paymentmethod, b.is_cancelled, b.cancel_date, b.status, b.paymentprice
            ORDER BY b.date DESC, b.time DESC"; // Order by date and time in descending order

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500); // Internal server error
        echo json_encode(["error" => "Failed to prepare SQL statement."]);
        error_log("SQL Error: " . mysqli_error($conn)); // Log the error
        exit;
    }

    $stmt->bind_param("ssi", $startDate, $endDate, $salonId); // Bind salonId as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging output
    error_log("SQL Query: " . $sql);
    error_log("Start Date: " . $startDate);
    error_log("End Date: " . $endDate);
    error_log("Salon ID: " . $salonId);

    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        // Log the raw data fetched
        error_log("Fetched Row: " . json_encode($row));

        // Determine the status based on the is_cancelled and status fields
        if ($row['is_cancelled'] == 1) {
            $row['status'] = 'Cancelled';
        } elseif ($row['is_cancelled'] == 0 && $row['status'] == 0) {
            $row['status'] = 'Ongoing'; // Correctly identify ongoing bookings
        } elseif ($row['is_cancelled'] == 0 && $row['status'] == 1) {
            $row['status'] = 'Completed'; // Correctly identify completed bookings
        }

        // Format the time to show only HH:MM
        $row['time'] = date("H:i", strtotime($row['time']));

        $reports[] = $row;
    }

    // Log the number of reports fetched
    error_log("Number of Reports: " . count($reports));

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

 
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


    <main >
    <h1 class="head">Report </h1>
    
    <div class="button-container">
        <!-- Form for selecting date range and report type -->
        <form method="GET" action="report.php" class="report-form">
    <label for="startDate">Start Date:</label>
    <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>" required>
    
    <label for="endDate">End Date:</label>
    <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>" required>
    <br>

    <select class="dropdown btn">
        <option value="ongoing">Ongoing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
        <option value="cancelled">View All Report</option>
    </select>

    <button type="submit" class="btn">Generate Report</button>
    
    <!-- Clear Button -->
    <button type="button" class="btn" onclick="clearDates()">Clear</button>
    
    <?php if (!empty($reports)): ?>
    <!-- Button to download the report as a PDF -->
    <button onclick="downloadPDF()" class="btn">Download PDF</button>
    <?php endif; ?>

    

</form>

        
    </div>

    <?php if (empty($reports)): ?>
        <p class="noreport">No reports available. Please select a date range to generate reports.</p>
    <?php else: ?>
        <div class="second">
            <hr />
            <table id="reportTable" class="appointments_table">
                <thead>
                    <tr>
                        <th>Date of Appointment</th>
                        <th>Time of Appointment</th>
                        <th>Owner Name</th>
                        <th>Pet Name</th>
                        <th style="width: 250px;">Service Names</th> <!-- Update header to reflect multiple service names -->
                        <th>Payment Method</th>
                        <th>Is Cancelled</th>
                        <th>Cancel Date</th>
                        <th>Status</th>
                        <th>Payment Price</th>
                    </tr>
                </thead>
            <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    
                    <td><?php echo htmlspecialchars($report['date'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($report['time'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($report['ownerName'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($report['petName'] ?? ''); ?></td>
                    <td>
                        <ul style="list-style-position: inside; margin: 0; padding: 0;">
                            <?php foreach (explode(',', $report['serviceNames'] ?? '') as $service): ?>
                                <li><?php echo htmlspecialchars($service); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>

                    <td><?php echo htmlspecialchars($report['paymentmethod'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($report['is_cancelled'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($report['cancel_date'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($report['status']); ?></td>
                    <td><?php echo htmlspecialchars($report['paymentprice'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<script>
function downloadPDF() {
    const startDate = "<?php echo htmlspecialchars($startDate); ?>";
    const endDate = "<?php echo htmlspecialchars($endDate); ?>";
    const salonId = "<?php echo htmlspecialchars($salonId); ?>";

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'generate_report.php';
    form.target = '_blank'; // Open in new tab

    const startDateInput = document.createElement('input');
    startDateInput.type = 'hidden';
    startDateInput.name = 'startDate';
    startDateInput.value = startDate;
    form.appendChild(startDateInput);

    const endDateInput = document.createElement('input');
    endDateInput.type = 'hidden';
    endDateInput.name = 'endDate';
    endDateInput.value = endDate;
    form.appendChild(endDateInput);

    const salonIdInput = document.createElement('input');
    salonIdInput.type = 'hidden';
    salonIdInput.name = 'salonid';
    salonIdInput.value = salonId;
    form.appendChild(salonIdInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function clearDates() {
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
}

</script>
</body>
</html>