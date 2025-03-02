<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mission Im-Paws-Sible Staff</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="logreg4.css" />
  </head>
  <body>
    <?php
    // Start the session
    session_start();

// Connect to your database
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the form has been submitted
    if (isset($_POST['staffemail']) && isset($_POST['staffpass'])) {
        // Retrieve the email and password from the form
        $staffemail = $_POST['staffemail'];
        $staffpass = $_POST['staffpass'];

        // Retrieve the staff member's information from the database
        $query = "SELECT * FROM staff WHERE staffemail = '$staffemail' AND staffpass = '$staffpass'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        // Check if the staff member's login credentials are correct
        if ($row) {
            // Retrieve the staff ID from the database
            $staff_id = $row['staffid'];

            // Retrieve the salon ID from the database
            $salon_id = $row['salonid'];

            // Store the staff ID and salon ID in the session
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['salonid'] = $salon_id;
            $_SESSION['loggedin'] = true;

            // Redirect the staff member to the dashboard page
            header('Location: admin.php');
            exit;
        } else {
            // Display an error message if the login credentials are incorrect
            echo "Invalid email or password";
        }
    }

    // Close the connection
    mysqli_close($conn);
    ?>
    <section class="section">
      <div  class="loggoo">
        <img
          src="logo_login.png"
          alt="Image"
          class="logo_login"
          style="width: 30%; top: 15%"
        />
        <img src="logo_nav1.png" class="logo_login1" />
      </div>

      <div class="card-front">
        <form
          class="login-form"
          id="login-form"
          action=""
          method="post"
        >
        <div class="staffportal" style="font-family: Lora, serif;"> Welcome to Staff's Portal</div>
          <div class="login_container">
            <div class="form_field">
              <h4 class="mb" style="font-family:Lora, serif;">Email Address</h4>
  
              <input
                type="email"
                name="staffemail"
                id="staffemail"
                class="form_input"
                placeholder="Your Email"
                autocomplete="off"
                required
              />
              <i class="input-icon uil uil-at"></i>
            </div>
            <div class="form_field">
              <h4 class="pb" style="font-family:Lora, serif;">Password</h4>
              <div style="position: relative; width: 100%;">
                <input
                id="password-field"
                type="password"
                name="staffpass"
                id="staffpass"
                class="form_input1"
                placeholder="Your Password"
                autocomplete="off"
                required
              />

              </div>
              
            </div>
  
            <input
              type="submit"
              class="btn"
              value="Login"
            />
            
          </div>
          
          
        </form>
      </div>
    </section>

  </body>

  <script src="Login.js"></script>
</html>