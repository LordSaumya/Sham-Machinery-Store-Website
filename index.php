<?php
session_start();
require_once "config.php";
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Presentations | LibrariaPres</title>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <link rel = "stylesheet" href = "style.css">
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
    <div class = "content"><br><br>
      <h1 class = "websiteTitle">Presentations</h1><br>
      <!--Search Bar-->
      <br><br><img src = "searchIcon.png" style = "width:35px;height:35px;vertical-align:middle;"><input type="text" id = "searchBar" oninput = "Search((this.value).toLowerCase())" placeholder = "Search by book, author, or summary...">
      <br><br>
      <span class = "OrderByOption">Newest First</span>
      <label class="switch">
        <input id = "changeOrder" type = "checkbox" oninput = "OrderBy(this.checked)">
        <span class="slider"></span>
      </label>
      <span class = "OrderByOption">Oldest First</span>
      <br><br><br>
      <div id = "oldFirst">
      <?php
      $sql = "SELECT a.Name as Name, p.BookName as BookName, p.AuthorName as AuthorName,p.Image as Image,p.Summary as Summary,p.Rating as Rating FROM Accounts AS a, Presentations AS p WHERE p.UserID = a.UserID ORDER BY p.PresID ASC;";
      $result = $mysqli -> query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo '
          <div class = "presentationContainer">
          <span class = "bookName">'.$row['BookName'].'</span><br>
          <span class = "authorName">By '.$row['AuthorName'].'</span><br><br>';
          echo '<img loading = "lazy" src="data:image/jpeg;base64,'.base64_encode( $row['Image'] ).'" class = "presentationImage"/>';
          echo '
          <p class = "summary">'.$row['Summary'].'</p>
          <p class = "rating" style = "font-size:18px;">Rating: '.$row['Rating'].'/5</p>
          <span class = "presenterName">Presented by '.$row['Name'].'</span><br>
          </div>
          ';
        }
      } else {
        echo "No presentations submitted.";
      }
      ?>
      </div>
      <div id = "newFirst">
      <?php
      $sql = "SELECT a.Name as Name, p.BookName as BookName, p.AuthorName as AuthorName,p.Image as Image,p.Summary as Summary,p.Rating as Rating FROM Accounts AS a, Presentations AS p WHERE p.UserID = a.UserID ORDER BY p.PresID DESC;";
      $result = $mysqli -> query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo '
          <div class = "presentationContainer">
          <span class = "bookName">'.$row['BookName'].'</span><br>
          <span class = "authorName">By '.$row['AuthorName'].'</span><br><br>';
          echo '<img loading = "lazy" src="data:image/jpeg;base64,'.base64_encode( $row['Image'] ).'" class = "presentationImage"/>';
          echo '
          <p class = "summary">'.$row['Summary'].'</p>
          <p class = "summary" style = "font-size:18px;">Rating: '.$row['Rating'].'/5</p>
          <span class = "presenterName">Presented by '.$row['Name'].'</span><br>
          </div>
          ';
        }
      } else {
        echo "No presentations submitted.";
      }
      ?>
      </div>
      <p style = "font-size:24px;color:darkslategrey;display:none;" id = "noResults">
        <br><br>
        No presentations match your search. Maybe try searching for something else?
      </p>
      <br>
    </div>
    </center>
    <script>
    function logOut(){
  var logout = confirm("Would you like to log out?");
  if (logout == true){
    window.location = "https://librariapres.000webhostapp.com/logout.php";
  }
}
function Search(item){
  var pres = document.getElementsByClassName("presentationContainer");
  var r = 0;
  var bookName = document.getElementsByClassName("bookName");
  var authorName = document.getElementsByClassName("authorName");
  var noResults = document.getElementById("noResults");
  for (i = 0;i<pres.length;i++){
     if ((((bookName[i].innerHTML).toLowerCase()).indexOf(item) > -1)||(((authorName[i].innerHTML).toLowerCase()).indexOf(item) > -1)) {
      r += 1;
      pres[i].style.display = "block";
     } else {
      pres[i].style.display = "none";
     }
     }
    if(r == 0){
      noResults.style.display = "block";
    } else{
      noResults.style.display = "none";
    }
  }
function OrderBy(t){
  document.getElementById("searchBar").value = null;
  Search("");
  if (t == true){
    document.getElementById("oldFirst").style.display = "block";
    document.getElementById("newFirst").style.display = "none";
  } else{
    document.getElementById("newFirst").style.display = "block";
    document.getElementById("oldFirst").style.display = "none";
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
  </body>
</html>