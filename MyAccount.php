<?php
require_once "config.php";

if (isset($_POST['deleteForm'])){
  $sql1 = "DELETE FROM Presentations WHERE PresID = ".$_POST['DeletePresID'];
  $sql2 = "SELECT BookName, AuthorName FROM Presentations WHERE PresID = ".$_POST['DeletePresID'];
  $result = $mysqli -> query($sql2);
  $row = $result -> fetch_row();
  $BookName = $row[0];
  $AuthorName = $row[1];
  if ($mysqli -> query($sql1)){
    $deleteNotif = "Your presentation of the book '".$BookName."' by '".$AuthorName."' has been deleted.";
  }
}

if (isset($_POST["changePswd"])){
  $sql = "SELECT Password,Email FROM Accounts WHERE UserID = ".$_SESSION['userID'];
  $result = $mysqli -> query($sql);
  $row = $result -> fetch_row();
  $CurrentPassword = $row[0];
  $Email = $row[1];
  $inpCurrentPassword = $mysqli-> real_escape_string(stripslashes(strip_tags($_POST["currentPassword"])));
  $inpNewPassword = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["newPassword"])));
  if ($CurrentPassword == $inpCurrentPassword){
    $sql = "UPDATE Accounts SET Password = '".$inpNewPassword."' WHERE UserID = ".$_SESSION['userID'];
    if ($mysqli -> query($sql)){
      echo("<script>alert('Your password has been changed.')</script>");
    $subject = "LibrariaPres Password Change Notification";
     $to = $Email;
     $headers = "From:LibrariaPres@fountainheadschools.org\r\n";
     $headers .= "Reply-To:saumyashah717@gmail.com\r\n";
     $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
     $msg = '
     <html>
     <body>
     <p style = "font-size:23px;font-family:Tahoma;padding:5px;color:#48494B">
     Dear '.$_SESSION['username'].',<br>
     Your password has recently been changed.<br>
     If you do not recognise this activity, please get in touch with us as soon as possible.
     <br><br>
     Thanks,<br>
     LibrariaPres Customer Service<br><br><br>
     <span style = "font-size:16px">For any queries, please reply to this mail.</span>
     </p>
     </body>
     </html>
     ';
     mail($to,$subject, $msg, $headers);
    }
  }
  else{
    echo("<script>alert('Wrong password!');</script>");
  }
}
if (isset($_POST['changeEmail'])){
  unset($error);
  $NewEmail = filter_var($_POST['newEmail'], FILTER_SANITIZE_EMAIL);
  $sql = 'SELECT * FROM Accounts WHERE Email = "'.$NewEmail.'"';
  $result = $mysqli->query($sql);
  if ($result->num_rows > 0){
  $error = "<script>alert('This email is already being used!');</script>";
  }
  else{
      $sql = "UPDATE Accounts SET Email = '".$NewEmail."' WHERE UserID = ".$_SESSION['userID'];
  if ($mysqli->query($sql)){
    echo("<script>alert('Successfully changed email!');</script>");
    $_SESSION['email'] = $NewEmail;
    $subject = "LibrariaPres Email Change Notification";
     $to = $_SESSION['email'];
     $headers = "From:LibrariaPres@fountainheadschools.org\r\n";
     $headers .= "Reply-To:saumyashah717@gmail.com\r\n";
     $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
       $msg = '
       <html>
       <body>
       <p style = "font-size:23px;font-family:Tahoma;padding:5px;color:#48494B">
       Dear '.$_SESSION['username'].',<br>
       Your mail has been changed to '.$NewEmail.'<br>
       If you do not recognise this activity, please get in touch with us as soon as possible.
       <br><br>
       Thanks,<br>
       LibrariaPres Customer Service<br><br><br>
       <span style = "font-size:16px">For any queries, please reply to this mail.</span>
       </p>
       </body>
       </html>
       ';
       mail($to,$subject, $msg, $headers);
  }
  else{
    echo("<script>alert('Encountered trouble changing email. Please try again later.');</script>");
    echo('<script type="text/javascript">window.location=\''."https://librariapres.000webhostapp.com/index.php".'\';</script>');
  }
}
}
if (isset($_POST["changeUsername"])){
  unset($error);
  $username = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["newUsername"])));
  $sql = 'SELECT * FROM Accounts WHERE Name = "'.$username.'"';
  $result = $mysqli->query($sql);
  if ($result->num_rows > 0){
  $error = "<script>alert('This username is already taken!')</script>";
  }
  else{
    $sql = "UPDATE Accounts SET Name = '".$username."' WHERE UserID = ".$_SESSION['userID'];
    if ($mysqli -> query($sql)){
      echo("<script>alert('Your username has been changed.')</script>");
    $subject = "LibrariaPres Username Change Notification";
     $to = $_SESSION['email'];
     $headers = "From:LibrariaPres@fountainheadschools.org\r\n";
     $headers .= "Reply-To:saumyashah717@gmail.com\r\n";
     $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
     $msg = '
     <html>
     <body>
     <p style = "font-size:23px;font-family:Tahoma;padding:5px;color:#48494B">
     Dear '.$_SESSION['username'].',<br>
     Your username has recently been changed to '.$username.'\.<br>
     If you do not recognise this activity, please get in touch with us as soon as possible.
     <br><br>
     Thanks,<br>
     LibrariaPres Customer Service<br><br><br>
     <span style = "font-size:16px">For any queries, please reply to this mail.</span>
     </p>
     </body>
     </html>
     ';
     mail($to,$subject, $msg, $headers);
     $_SESSION['username'] = $username;
    }
  }
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>My Account | LibrariaPres</title>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <link rel = "stylesheet" href = "style.css">
    <style>
table{
    font-size:25px;
    font-family:Trebuchet MS;
    color:darkslategrey;
    border-collapse: collapse;
    text-align:center;
    margin-left:auto;
    margin-right:auto;
    width:80%;
    box-shadow:0px 3px 3px RGBA(110,110,110,0.5);
}
tr:hover {
        background-color: rgba(120,120,120,0.5);
}
td,th{
        border: 0.75px solid black;
        padding: 24px;
        text-align:center;
        background-color:rgba(120,120,120,0.25);
}
th{
    color:white;
    background-color:#2196F3;
}
.changeDetail{
    border:0.5px black solid;
    color:white;
    padding:12px;
    font-size:20px;
    width:70%;
    background-color:#2196F3;
}
    </style>
  </head>
  <body>
<!--LOADING DIV-->
    <div id = "loading"><img src = "https://thumbs.gfycat.com/PerfumedColossalGadwall-max-1mb.gif">
    <br><br><br><span>Loading</span><span id = "loadingText"></span>
    </div>
<script>
   var loadingDots = setInterval(function() {
    var load = document.getElementById("loadingText");
    if (load.innerHTML.length > 3){
        load.innerHTML = "";
    }
    else{
        load.innerHTML += ".";
    }
    }, 500);
  document.onreadystatechange = function() {
    if (document.readyState !== "complete") {
        document.getElementById("loading").style.display = "block";
    } else {
        document.getElementById("loading").style.display = "none";
        clearInterval(loadingDots);
        changeDetail("idk");
        changeScreen("a");
    }
};
</script>
<!--/LOADING DIV-->
    <!--HEADER-->
    <div class = "header">
      <span class = "title">LibrariaPres</span>
      <span class = "loginStatus" id = "loginStatus">
        <?php
      if (isset($_SESSION["loggedIn"])){
        echo("<span title = 'Click to log out' onclick = 'logOut()'>Logged in as ". $_SESSION['username']."</span>");
      }
      else{
        echo('<script type="text/javascript">window.location=\''."https://librariapres.000webhostapp.com/Registration.php".'\';</script>');
      }
      ?>
      <a href = "registration.php" style = "color:white"></a>
      </span>
    </div>
    <!--NAVBAR-->
    <div class = "navbar" id = "navbar">
  <a href = "index.php" class = "plusL"><img src = "home-icon.png" style = "width:23px;"></a><span class = "divider">|</span>
  <a onclick = "changeScreen('p')" class = "nLink">My Presentations</a><span class = "divider">|</span>
  <a onclick = "changeScreen('a')" class = "nLink">Account Settings</a>
    </div><center>
      <!--CONTENT-->
    <div class = "content"><br><br>
    <!--Account Settings-->
    <div id = "aScreen" style = "height:100%;width:100%">
      <h1 class = "websiteTitle">Account Settings</h1><br><br>
      <h3 class = "websiteSubtitle">Account Details:</h3><br>
      <table>
          <tr>
              <th>Field</th>
              <th>Your Details</th>
              <th>Change Details</th>
          </tr>
          <tr>
              <td><b>Name:</b></td>
              <td><?php echo($_SESSION['username'])?></td>
              <td><a href = "#UsnmBottom"><button class = "changeDetail" onclick = "changeDetail('u');">Change username</button></a></td>
          </tr>
          <tr>
              <td><b>Email:</b></td>
              <td><?php echo($_SESSION['email'])?></td>
              <td><a href = "#EmailBottom"><button class = "changeDetail" onclick = "changeDetail('e');">Change email address</button></a></td>
          </tr>
          <tr>
              <td><b>Password:</b></td>
              <td colspan = "2"><a href = "#PswdBottom"><button class = "changeDetail" onclick = "changeDetail('p');">Change password</button></a></td>
      </tr>
      </table>
      <div id = "changePasswordForm">
      <h3 class = "websiteSubtitle">Change Password:</h3><br>
      <form method = "POST" onsubmit = "return vPswd()">
        <label class = "ChangeLabel">Current password:</label><br>
        <input type = "password" class = "inputAcc" name = "currentPassword" placeholder = "Current password" required><br><br>
        <label class = "ChangeLabel">New password:</label><br>
        <input type = "password" class = "inputAcc" name = "newPassword" id = "nPass" placeholder = "New password" required><br><br>
        <label class = "ChangeLabel">Confirm new password:</label><br>
        <input type = "password" class = "inputAcc" id = "cnPass" placeholder = "Confirm new password" required><br><br><br>
        <button class = "ChangeBtn" name = "changePswd" type = "submit">Change Password</button>
      </form>
      </div>
      <div id = "changeEmailForm">
      <h3 class = "websiteSubtitle">Change Registered Email:</h3><br>
      <form method = "POST">
        <label class = "ChangeLabel">New email address:</label>
        <input type = "email" class = "inputAcc" name = "newEmail" placeholder = "Enter new email address" required><br><br><br>
        <?php
        if (isset($error)){
          echo($error);
        }
        ?>
        <button class = "ChangeBtn" name = "changeEmail" type = "submit">Change Email Address</button>
      </form>
      </div>
      <div id = "changeUsernameForm">
      <h3 class = "websiteSubtitle">Change Username:</h3><br>
      <form method = "POST">
        <label class = "ChangeLabel">New username:</label>
        <input type = "text" class = "inputAcc" name = "newUsername" placeholder = "New username" required><br><br><br>
        <?php
        if (isset($error)){
          echo ($error);
        }
        ?>
        <button class = "ChangeBtn" name = "changeUsername" type = "submit">Change Username</button>
      </form>
      </div>
    </div>
    <!--Presentations Screen-->
    <div id = "pScreen" style = "height:100%;width:100%">
      <h1 class = "websiteTitle">My Presentations</h1><br><br>
      <?php
      if ($_SESSION['userID'] == 1){
          echo('<span style = "font-family:Trebuchet MS;font-size:30px;color: gold;text-shadow: 2px 2px 4px #000000;font-weight:bold">You have administrator privileges!</span><br>');
      }
      ?>
      <span style = "color:maroon;font-family:Trebuchet MS;font-size:24px;">
        <?php
        if (isset($deleteNotif)){
          echo($deleteNotif."<br>");
        }
        ?>
      </span>
      <br>
      <?php
      if ($_SESSION["userID"] != 1){
      $sql = "SELECT a.Name as Name, p.BookName as BookName, p.AuthorName as AuthorName,p.Image as Image,p.PresID as PresID, p.Summary as Summary,p.Rating as Rating FROM Accounts AS a, Presentations AS p WHERE p.UserID = a.UserID AND a.UserID = ".$_SESSION["userID"]." ORDER BY p.PresID DESC;";
      }
      else{
        $sql = "SELECT a.Name as Name, p.BookName as BookName, p.AuthorName as AuthorName,p.Image as Image,p.PresID as PresID, p.Summary as Summary,p.Rating as Rating FROM Accounts AS a, Presentations AS p WHERE p.UserID = a.UserID ORDER BY p.PresID DESC;";
      }
      $result = $mysqli -> query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo '
          <div class = "presentationContainer">
          <span class = "bookName">'.$row['BookName'].'</span><br>
          <span class = "authorName">By '.$row['AuthorName'].'</span><br><br>';
          echo '<img loading = "lazy" src="data:image/jpeg;base64,'.base64_encode( $row['Image'] ).'" class = "presentationImage"/>';
          $confirmMessage = "Are you sure you want to delete your presentation of the book \'".$row['BookName']."\' by \'".$row['AuthorName']."\'?";
          echo '
          <p class = "summary">'.$row['Summary'].'</p>
          <p class = "rating" style = "font-size:18px;">Rating: '.$row['Rating'].'/5</p>
          <form method = "POST" id = "'.$row['PresID'].'" onsubmit = "return confirm(\''.$confirmMessage.'\')">
          <input style = "display:none" name = "DeletePresID" value = "'.$row['PresID'].'">
          </form><br><br>
          <button type = "submit" name = "deleteForm" form = "'.$row['PresID'].'" class = "DeleteBtn">Delete Post</button>
          </div>
          <br>
          ';
        }
      } else {
        echo "No presentations submitted.";
      }
      ?>
    </div>
    <br><br><br><br>
    </div>
    </center>
    <script>
    function changeDetail(n){
      var u = document.getElementById("changeUsernameForm");
      var e = document.getElementById("changeEmailForm");
      var p = document.getElementById("changePasswordForm");
      if (n == "u"){
        u.style.display = "block";
        e.style.display = "none";
        p.style.display = "none";
      }
      else if (n == "e"){
        u.style.display = "none";
        e.style.display = "block";
        p.style.display = "none";
      }
      else if (n == "p"){
        u.style.display = "none";
        e.style.display = "none";
        p.style.display = "block";
      }
      else{
        u.style.display = "none";
        e.style.display = "none";
        p.style.display = "none";
      }
    }
    function vPswd(){
      if (document.getElementById('nPass').value == document.getElementById('cnPass').value){
        return true;
      } else{
        alert("New password and confirm new password fields do not match.");
        return false;
      }
    }
    
    function logOut(){
  var logout = confirm("Would you like to log out?");
  if (logout == true){
    window.location = "https://librariapres.000webhostapp.com/logout.php";
  }
}
    function changeScreen(f){
      var pScreen = document.getElementById("pScreen");
      var aScreen = document.getElementById("aScreen");
      if (f == "p"){
        aScreen.style.display = "none";
        pScreen.style.display = "block";
      } else{
        pScreen.style.display = "none";
        aScreen.style.display = "block";
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
<p style = "display:none;bottom:0;" id = "UsnmBottom">bottom</p>
<p style = "display:none;bottom:0;" id = "EmailBottom">bottom</p>
<p style = "display:none;bottom:0;" id = "PswdBottom">bottom</p>
</body>
</html>