<?php
require_once "config.php";
  if (isset($_POST['submitPresentation'])){
    $username = $_SESSION['username'];
    $bookName = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["bookName"])));
    $authorName = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["authorName"])));
    $summary = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["summary"])));
    $rating = $_POST['Rating'];
    $image = addslashes(file_get_contents($_FILES['photoUL']['tmp_name']));
    $sql= "SELECT PresID FROM Presentations ORDER BY PresID DESC LIMIT 1";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    $presID = $row['PresID']+1;
    $userID = $_SESSION['userID'];
    $sql = "INSERT INTO Presentations (PresID, UserID, BookName, AuthorName, Image, Summary, Rating) VALUES ('$presID','$userID','$bookName','$authorName','$image','$summary','$rating')";
    if ($mysqli->query($sql)){
      echo("<script>alert('Presentation submitted.')</script>");
    }
    else{
      echo("Error in submission of presentation. Please try again later.");
    }
  }
?>
  <!DOCTYPE HTML>
  <html>
    <head>
      <title>Submit | LibrariaPres</title>
      <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
      <meta name="viewport" content="width = device-width, initial-scale = 1">
      <link rel = "stylesheet" href = "style.css">
      <script>
  function showPhotoName(file){
    document.getElementById("fileNameOutput").innerHTML = file.files[0].name;
    document.getElementById("fileNameOutputInp").value =
    document.getElementById("photoUpload").value;
  }
  function resetStars(){
  document.getElementById("1star").src = "unStar.png";
  document.getElementById("2star").src = "unStar.png";
  document.getElementById("3star").src = "unStar.png";
  document.getElementById("4star").src = "unStar.png";
  document.getElementById("5star").src = "unStar.png";
  }
  function rate(n){
  document.getElementById("Rating").value = n;
  if (n == 1){
      
    resetStars();
  document.getElementById("1star").src = "star.png";
  }
  if (n == 2){
    resetStars();
  document.getElementById("1star").src = "star.png";
  document.getElementById("2star").src = "star.png";
  }
  if (n == 3){
    resetStars();
  document.getElementById("1star").src = "star.png";
  document.getElementById("2star").src = "star.png";
  document.getElementById("3star").src = "star.png";
  }
  if (n == 4){
    resetStars();
  document.getElementById("1star").src = "star.png";
  document.getElementById("2star").src = "star.png";
  document.getElementById("3star").src = "star.png";
  document.getElementById("4star").src = "star.png";
  }
  if (n == 5){
    resetStars();
  document.getElementById("1star").src = "star.png";
  document.getElementById("2star").src = "star.png";
  document.getElementById("3star").src = "star.png";
  document.getElementById("4star").src = "star.png";
  document.getElementById("5star").src = "star.png";
  }
  }
  function logOut(){
  var logout = confirm("Would you like to log out?");
  if (logout == true){
    window.location = "https://librariapres.000webhostapp.com/logout.php";
  }
}
      </script>
    </head>
    <body>
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
    <a href = "Submit.php" class = "plusL"><img src = "plusIcon.png" style = "width:23px;" class = "plusImg"></a><span class = "divider">|</span>
    <a href = "index.php" class = "nLink">Presentations</a><span class = "divider">|</span>
    <a href = "MyAccount.php" class = "nLink">My Account</a>
      </div><center>
        <!--CONTENT-->
      <div class = "content">
        <br><br>
        <h1 class = "websiteTitle">Submit</h1><br>
        <form class = "submitPres" method = "POST" enctype = "multipart/form-data">
          <label>Name of Book: </label><br><input name = "bookName" type = "text" placeholder = "Name of book" required><br><br>
          <label>Name of Author: </label><br><input name = "authorName" type = "text" placeholder = "Name of author" required><br><br>
          <label>Photo: </label><label for = "photoUpload" id = "photoUploadIcon"><img src = "Camera.png" style = "vertical-align:middle"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "file" name = "photoUL" style = "display:none" id = "photoUpload" accept="image/*" oninput = "showPhotoName(this)" required><label style = "font-size:14px" id = "fileNameOutput"></label><br><br>
          <label>Rating:</label><input type = "hidden" name = "Rating" id = "Rating"><br>
          <div style = "width:40%;padding:8px;background-color:RGBA(0,0,0,0.75)"><img src = "unStar.png" id = "1star" onclick = "rate(1);" title = "1 Star: Terrible"><img src = "unStar.png" id = "2star" onclick = "rate(2);" title = "2 Stars: Poor"><img src = "unStar.png" id = "3star" onclick = "rate(3);" title = "3 Stars: Okay"><img src = "unStar.png" id = "4star" onclick = "rate(4);" title = "4 Stars: Very Good"><img src = "unStar.png" id = "5star" onclick = "rate(5);" title = "5 Stars: Excellent"></div><br>
          <label>Summary: </label><br><textarea name = "summary" rows = "5" placeholder = "Summary of the book" required></textarea><br><br>
          <button type = "submit" class = "submit" name = "submitPresentation">Submit</button>
        </form>
        <br><br>
      </div>
      </center>
    </body>
  </html>