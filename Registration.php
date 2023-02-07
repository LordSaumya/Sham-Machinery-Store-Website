<?php
require_once "config.php";

if (isset($_SESSION["loggedIn"])){
  echo('<script type="text/javascript">window.location=\''."https://librariapres.000webhostapp.com/index.php".'\';</script>');
}

if (isset($_POST['signup'])){
  unset($error);
  $username = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["Susername"])));
  $password = $mysqli -> real_escape_string(($_POST["Spassword"]));
  $email = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["Semail"])));
  
  $sql = 'SELECT * FROM Accounts WHERE Name = "'.$username.'"';
  $result = $mysqli->query($sql);
  if ($result->num_rows > 0){
  $error = "This username is already taken!<br>";
  }
  else{
  $sql = 'SELECT * FROM Accounts WHERE Email = "'.$email.'"';
  $result = $mysqli->query($sql);
  if ($result->num_rows > 0){
  $error = "This email is being used by another account!<br>";
  }
  else{
    $sql= "SELECT UserID FROM Accounts ORDER BY UserID DESC LIMIT 1";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    $LastId = $row['UserID']+1;
    $sql = "INSERT INTO Accounts (UserID, Name, Password, Email) VALUES ('$LastId','$username','$password','$email')";
   if ($mysqli->query($sql)){
     $_SESSION["loggedIn"] = TRUE;
     $_SESSION["username"] = $username;
     $_SESSION["userID"] = $LastId;
     $_SESSION["email"] = $email;
     $subject = "LibrariaPres Account Notification";
     $to = $email;
     $headers = "From:LibrariaPres@fountainheadschools.org\r\n";
     $headers .= "Reply-To:saumyashah717@gmail.com\r\n";
     $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
     $mailPswd = substr_replace($password,"XXXX",0,4);
     $msg = '
     <html>
     <body>
     <p style = "font-size:23px;font-family:Tahoma;padding:5px;color:#48494B">
     Dear '.$username.',<br>
     <img src = "headImg.png"><br><br>
     Thank you for registering on <a style = "text-decoration:none" href = "https://librariapres.000webhostapp.com/index.php">LibrariaPres</a>!<br>
     Here are the details of your account:<br>
     <b>Username: </b>'.$username.'<br>
     <b>Password: </b>'.$mailPswd.'<br>
     <b>Email: </b>'.$email.'<br>
     <br><br>
     Thanks,<br>
     LibrariaPres Customer Service<br><br><br>
     <span style = "font-size:16px">For any queries, reply to this mail.<br>
     If you have not created this account, reply to this mail.</span>
     </p>
     </body>
     </html>
     ';
     mail($to,"LibrariaPres | Registration", $msg, $headers);
     die('<script type="text/javascript">window.location=\''."https://librariapres.000webhostapp.com/index.php".'\';</script>');
   }
   else{
     echo("Error creating account. Please try again later.");
   }
  }
}
}
else if (isset($_POST["login"])){
  unset($error2);
  $username = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["Lusername"])));
  $password = $mysqli -> real_escape_string($_POST["Lpassword"]);
  $sql = "SELECT * FROM Accounts WHERE Name = '$username'";
  $result = $mysqli->query($sql);
  if ($result->num_rows == 0){
    $error2 = "There is no account associated with this username!<br>";
  }
  else{
    $sql = "SELECT * FROM Accounts WHERE Name = '".$username."'AND Password = '".$password."'";
    $result = $mysqli->query($sql);
    if($result->num_rows == 1){
      $row = $result->fetch_row();
      $_SESSION["loggedIn"] = TRUE;
      $_SESSION["username"] = $row[1];
      $_SESSION["userID"] = $row[0];
      $_SESSION["email"] = $row[3];
      die('<script type="text/javascript">window.location=\''."https://librariapres.000webhostapp.com/index.php".'\';</script>');
    }
    else{
      $error2 = "Invalid username or password!<br>";
    }
  }
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Registration | LibrariaPres</title>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <link rel = "stylesheet" href = "style.css">
    <script>
function changeForm(x){
  if (x == "l"){
    document.getElementById("websiteTitle").innerHTML = "Login";
    document.getElementById("sForm").style.display = "none";
    document.getElementById("lForm").style.display = "block";
  }
  else{
    document.getElementById("websiteTitle").innerHTML = "Sign Up";
    document.getElementById("sForm").style.display = "block";
    document.getElementById("lForm").style.display = "none";
  }
}
window.onscroll = function() {stickyNavbar(window.pageYOffset)};

// Get the navbar
var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;
// Add the sticky class to the navbar when you
//reach its scroll position. Remove "sticky" when you
//leave the scroll position
function stickyNavbar(y) {
  if (y >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
</script>
  </head>
  <body onload = "changeForm('s')">
    <!--LOADING DIV-->
    <div id = "loading"><img src = "https://thumbs.gfycat.com/PerfumedColossalGadwall-max-1mb.gif">
    <br><br><br><span>Loading</span><span id = "loadingText"></span>
    </div>
<script>
   var loadingDots = setInterval(function() {
    var wait = document.getElementById("loadingText");
    if (wait.innerHTML.length > 3){
        wait.innerHTML = "";
    }
    else{
        wait.innerHTML += ".";
    }
    }, 1000);
  document.onreadystatechange = function() {
    if (document.readyState !== "complete") {
        document.getElementById("loading").style.display = "block";
        document.getElementsByTagName("body")[0].style.overflowY = "hidden";
    } else {
        document.getElementById("loading").style.display = "none";
        document.getElementsByTagName("body")[0].style.overflowY = "auto";
        clearInterval(loadingDots);
        OrderBy(false);
    }
};
</script>
<!--/LOADING DIV-->
    <!--HEADER-->
    <div class = "header">
      <span class = "title">LibrariaPres</span>
    </div>
    <!--NAVBAR-->
    <div class = "navbar" id = "navbar">
  <a class = "nLink" style = "width:46%" onclick = "changeForm('s')">Sign Up</a><span class = "divider">|</span>
  <a class = "nLink" style = "width:46%" onclick = "changeForm('l')">Login</a>
    </div><center>
      <!--CONTENT-->
    <div class = "content">
      <br><br>
      <h1 class = "websiteTitle" id = "websiteTitle">Sign Up</h1><br>
      <form class = "submitPres" id = "sForm" method = "POST">
        <label>Username: </label><br><input name = "Susername" type = "text" placeholder = "Username" required><br><br>
        <label>Email: </label><br><input name = "Semail" type = "email" placeholder = "Email Address" required><br><br>
        <label>Password: </label><br><input name = "Spassword" type = "password" placeholder = "Password" pattern = ".{5,}" required><br><br>
        <span class= "error">
          <?php
          if (isset($error)){
            echo ($error);
          }
          ?>
        </span>
        <button type = "submit" class = "submit" name = "signup">Sign Up</button>
      </form>
      <form class = "submitPres" id = "lForm" method = "POST">
        <label>Username: </label><br><input name = "Lusername" type = "text" placeholder = "Username" required><br><br>
        <label>Password: </label><br><input name = "Lpassword" type = "password" placeholder = "Password" pattern = ".{5,}" required><br><br>
        <span class= "error">
          <?php
          if (isset($error2)){
            echo ($error2);
          }
          ?>
        </span>
        <button type = "submit" class = "submit" name = "login">Login</button>
      </form>
    </div>
    </center>
  </body>
</html>