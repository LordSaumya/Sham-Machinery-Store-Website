<?php
require_once "config.php";

if (isset($_POST['inquiryForm'])){
    $inpEmail = $mysqli-> real_escape_string(stripslashes(strip_tags($_POST["email"])));
    $inpName = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["name"])));
    $inpInquiry = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["inquiry"])));
    $sql1 = "INSERT INTO Inquiries VALUES('$inpEmail','$inpName','$inpInquiry')";
    if ($mysqli -> query($sql1)){
      $notif = "Your inquiry has been submitted.";
    }
  }

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Contact | Sham Machinery Stores</title>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <link rel = "stylesheet" href = "style.css">
  </head>
  <body>
    <!--HEADER-->
    <!--HEADER-->
    <div class = "header">
      <span class = "title">Sham Machinery Stores</span>
      <div class = "socialMediaBtnContainer"><a href = "https://www.instagram.com/sham_machinerystores/"><img src = "instaLogo.png" class = "socialMediaBtn"></a></div>
    </div>
    <!--NAVBAR-->
    <div class = "navbar" id = "navbar">
      <a href = "index.html" class = "homebtn"><img src = "home-icon.png" style = "width:23px;"></a><span class = "divider">|</span>
      <a href = "Inventory.php" class = "nLink">Inventory</a><span class = "divider">|</span>
      <a href = "About Us.html" class = "nLink">About Us</a><span class = "divider">|</span>
      <a href = "Contact.php" class = "nLink">Contact</a><span class = "divider">|</span>
      <div style = "height:100%"><div id="buttonDiv" style = "display:inline-block"></div></div>
        </div><center>
      <!--CONTENT-->
    <div class = "content"><br><br>
      <h1 class = "websiteTitle">Contact</h1><br>
      <iframe style = "width:80%;height:400px;box-shadow:0 0 20px grey;" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJy6ooStmY0DsRAVOcYfcuqQM&key=AIzaSyCvfaQ6B4bQltNpQLz_2DAY96Vd-hu9xFU"></iframe>
      <div style = "text-align:center;width:70%;font-size: 18px"><br><br>
      Contact us here:<br><br>
        <b>Phone Number: </b><a href = "tel:+919328731415" style = "text-decoration:none;color:lightgrey" data-rel="external">+91 88883 33348<a><br><br>
        <b>Email address: </b><a href = "mailto:sham.rathi007@gmail.com" style = "text-decoration:none;color:lightgrey" target="new">sham.rathi007@gmail.com</a><br><br>
      </div>
      <br><br>Have questions? Drop them here!<br><br>
      <form class = "submitInquiry" method = "POST" enctype = "multipart/form-data">
          <label>Name:</label><br><input name = "name" type = "text" placeholder = "Your name" required><br><br>
          <label>Email Address: </label><br><input name = "email" type = "email" placeholder = "Your email address" required><br><br>
          <label>Inquiry: </label><br><textarea name = "inquiry" rows = "5" placeholder = "Inquiry" required></textarea><br><br>
          <button type = "submit" class = "submit" name = "inquiryForm">Submit</button>
        </form>
        <span style = "color:green;font-family:Trebuchet MS;font-size:24px;">
        <?php
        if (isset($notif)){
          echo("<br>".$notif."<br>");
        }
        ?>
      </span>
        <br><br>
    </div>
    </center>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
      //Google Sign-in
      function handleCredentialResponse(response) {
        var email = (JSON.parse(atob((response.credential).split('.')[1])))["email"];
        if (email == "sham.rathi007@gmail.com" || email == "saumyashah717@gmail.com"){
          alert("Thank you for logging in. You will be redirected to the admin panel shortly.");
          location.replace("./InventoryManagementConsole.php");
        }
        else{
          alert("You are logged in as " + email +", but you don't have administrative privileges.")
        }
      }
      window.onload = function () {
        google.accounts.id.initialize({
          client_id: "332635862647-fon7ro79s295r5pe04v1o9uo0qd6ago8.apps.googleusercontent.com",
          callback: handleCredentialResponse
        });
        google.accounts.id.renderButton(
          document.getElementById("buttonDiv"),
          { theme: "outline", size: "large" }
        );
        google.accounts.id.prompt();
      }

      //Sticky navbar
      window.onscroll = function() {stickyNavbar(window.pageYOffset)};
      var navbar = document.getElementById("navbar");
      var sticky = navbar.offsetTop;
      function stickyNavbar(y) {
        if (y >= sticky) {
          navbar.classList.add("sticky")
        } else {
          navbar.classList.remove("sticky");
        }
      }
  </script>
  </body>
</html>