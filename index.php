<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mission Im-Paws-Sible</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="loreg3.css" />
  <?php
  session_start();
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']== true) {
      
      header('refresh:2; url=Homenew.php');
      exit;
  }
  ?>
  </head>
  <body>
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
          action="login2.php"
          method="post"
        >
          <div class="login_container">
            <div class="form_field">
              <h4 class="mb">Email Address</h4>
  
              <input
                type="email"
                name="owneremail"
                id="owneremail"
                class="form_input"
                placeholder="Your Email"
                autocomplete="off"
                required
              />
              <i class="input-icon uil uil-at"></i>
            </div>
            <div class="form_field">
              <h4 class="pb">Password</h4>
              <div style="position: relative; width: 100%;">
                <input
                id="password-field"
                type="password"
                name="ownerpass"
                id="ownerpass"
                class="form_input1"
                placeholder="Your Password"
                autocomplete="off"
                required
              />

              </div>
              
            </div>
  
            <input
              href="#"
              type="submit"
              class="btn"
              value="Login"
              onclick="auth()"
            />
            <p><a href="#0" class="link"></a></p>
          </div>
          
          <div class="btn2">
            <a href="#" class="btn1" id="show-login">Create a New Account</a>
          </div>
          
        </form>
      </div>
    </section>

    <!-- Register -->
    <form
      class="signup-form"
      id="signup-form"
      action="signup.php"
      method="post"
    >
      <div class="popup">
        <div class="close-btn">&times;</div>

        <img
          src="logo-nav.png"
          style="width: 40%; display: block; margin: 0 auto; margin-top: 0.5rem"
        />
        <div class="form">
          <h2>Create Your Account</h2>
          <div class="form-element">
            <label for="fname">Owner's First Name</label>
            <input
              style="background-color: #fbf8dd"
              type="text"
              id="ownerfname"
              name="ownerfname"
              placeholder="Enter Owner's First Name"
            />
          </div>
          <div class="form-element">
            <label for="lname">Owner's Last Name</label>
            <input
              style="background-color: #fbf8dd"
              type="text"
              id="ownerlname"
              name="ownerlname"
              placeholder="Enter Owner's Last Name"
            />
          </div>
          <div class="form-element">
            <label for="email">Email</label>
            <input
              style="background-color: #fbf8dd"
              type="email"
              id="owneremail1"
              name="owneremail1"
              placeholder="Enter Email Address"
            />
          </div>
          <div class="form-element">
            <label for="password">Password</label>
            <input
              style="background-color: #fbf8dd"
              type="password"
              id="ownerpass"
              name="ownerpass"
              placeholder="Enter Password"
            />
          </div>
          <div class="form-element">
            <label for="number">Number</label>
            <input
              style="background-color: #fbf8dd"
              type="tel"
              id="ownernumber"
              name="ownernumber"
              placeholder="Enter Phone Number"
            />
          </div>

          <div class="form-element">
            <input type="submit" class="register_btn" value="Register" />
          </div>
        </div>
      </div>
    </form>
  </body>

  <script src="login.js"></script>
</html>
