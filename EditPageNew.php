<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="EditPageNew.css">
    <title>Document</title>
    <style>
        .target {
            display: none;
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
?>

<!-- Nav -->
<div  class="nav">
    <div>
        <a href="Yourprofile.php"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
        Edit Profile
    </div>
</div>
<!-- Content -->
<div class="text1">
    Owner's First Name
</div>
<div class="box1"><?php echo htmlspecialchars($_SESSION['ownerfname']); ?></div>
<div class="text2">
    Owner's Last Name
</div>
<div class="box2"><?php echo htmlspecialchars($_SESSION['ownerlname']); ?></div>

<!-- Email Address -->
<div class="email_address">
    <div class="text3">
        Email Address
    </div>
    <div class="box3"><?php echo htmlspecialchars($_SESSION['owneremail']); ?></div>
</div>
<!-- Phone Number -->
<div class="phone_number">
     <div class="text4">
        Phone Number
     </div>
     <div class="box4"><?php echo htmlspecialchars($_SESSION['ownernum']); ?></div>
            
        <!-- <a id="activateButton1" class="activateButton" class="inputs" >Change Phone Number</a>

        <form id="targetElement1" class="target">
            <div class="input_number"> 09123456789</div>
            <div  class="inputs" >Input New Phone Number</div>
            <input type="tel" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
            <div  class="inputs">Current Password</div>
            <input type="password"style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
            <input type="submit" value="Update" style="margin-top: 10px; background-color:#602147; color:white; font-family:'Sniglet'; height:40px; border: transparent; border-radius: 5px;"></input>
        </form> -->
</div>
<!-- Password -->
<div class="password"> 
    <div class="text5">
        Password
    </div>
    <div class="box5">
        <?php
            // Check if the password length is set in the session
            if (isset($_SESSION['password_length'])) {
                $password_length = $_SESSION['password_length'];
                $asterisks = str_repeat('*', $password_length);
                echo $asterisks; // Display the password as asterisks
            } else {
                echo "Password not set"; // Handle the case where the password length is not available
            }
        ?>
    </div>
    <input type="hidden" id="actual_password" value="<?php echo isset($_SESSION['ownerpass']) ? htmlspecialchars($_SESSION['ownerpass']) : ''; ?>" />
    <!-- <a id="activateButton2" class="activateButton">Change Password</a>

    <form id="targetElement2" class="target" action="change_password.php" method="post">
        <div class="inputs">Input Current Password</div>
        <input type="password" name="current_password" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">

        <div class="inputs">Input New Password</div>
        <input type="password" name="new_password" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5 ```php
px;">

        <div class="inputs">Confirm New Password</div>
        <input type="password" name="confirm_new_password" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">

        <input type="submit" value="Update" style="margin-top: 10px; background-color:#602147; color:white; font-family:'Sniglet'; height:40px; border: transparent; border-radius: 5px;"></input>
    </form> -->
</div>

<script>
    function toggleActiveClass(buttonId, targetId) {
        document.getElementById(buttonId).addEventListener("click", function() {
            const item = document.getElementById(targetId);
            item.style.display = item.style.display === "block" ? "none" : "block";
        });
    }
    
    // Attach event listeners
    toggleActiveClass("activateButton1", "targetElement1");
    toggleActiveClass("activateButton2", "targetElement2");
</script>
</body>
</html>