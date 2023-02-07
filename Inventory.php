<?php
require_once "config.php";
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Inventory | Sham Machinery Stores</title>
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
      <h1 class = "websiteTitle">Inventory</h1><br>
      <br><img src = "searchIcon.png" style = "width:35px;height:35px;vertical-align:middle;"><input type="text" id = "searchBar" oninput = "Search((this.value).toLowerCase())" placeholder = "Enter your search query here...">
      <div class="dropdown" style = "display:inline-block;margin:3px">
      <br>
        <button class="dropbtn" id = "sortBtn">Sort by</button>
        <div class="dropdown-content">
          <a href="javascript:void(0);" onclick = "SortBy('price');">Price</a>
          <a href="javascript:void(0);" onclick = "SortBy('quantity');">Quantity</a>
        </div>
      </div>
      <br>Search by:<br>
      <div style = "width:80%; padding:5px">
        <input type = "radio" value = "name" name = "Srch" checked = "checked" id = "radioName" onclick = "Search((document.getElementById('searchBar').value).toLowerCase())"> Name
        <input type = "radio" value = "material" name = "Srch" id = "radioMaterial" onclick = "Search((document.getElementById('searchBar').value).toLowerCase())"> Material
        <input type = "radio" value = "category" name = "Srch" id = "radioCategory" onclick = "Search((document.getElementById('searchBar').value).toLowerCase())"> Category
      </div><br>
      <table class = "inventoryTable" id = "inventoryTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Material</th>
            <th>Quantity</th>
            <th>Price (in &#8377;)</th>
          </tr>
        </thead>
        <?php
        $result = $mysqli->query("SELECT Name, Category, Material, Quantity, Price FROM Inventory");
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr class = 'item'><td class = 'itemName'>".$row["Name"]."</td><td class = 'itemCategory'>".$row["Category"]."</td><td class = 'itemMaterial'>".$row["Material"]."</td><td class = 'itemQuantity'>".$row["Quantity"]."</td><td class = 'itemPrice'>".$row["Price"]."</td></tr>";
          }
        } else {
          echo "0 results";
        }
        ?>
      </table>
      <p style = "font-size:24px;color:grey;display:none;" id = "noResults">No results. Try searching for something else?</p>
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

      //Search function
      function Search(item){
      var items = document.getElementsByClassName("item");
      var r = 0;
      var noResults = document.getElementById("noResults");
      if (document.getElementById("radioName").checked){
        searchField = document.getElementsByClassName("itemName");
      }
      if (document.getElementById("radioCategory").checked){
        searchField = document.getElementsByClassName("itemCategory");
      }
      if (document.getElementById("radioMaterial").checked){
        searchField = document.getElementsByClassName("itemMaterial");
      }
      for (i = 0;i<=items.length;i++){
      items[i].style.display = "table-row";
      if (((searchField[i].innerHTML).toLowerCase()).indexOf(item) > -1) {
        r += 1;
        items[i].style.display = "table-row";
      } else {
        items[i].style.display = "none";
      }
    }
    if(r == 0){
      noResults.style.display = "block";
    } else{
      noResults.style.display = "none";
    }
  }

  //Sort function
  function SortBy(field) {
  document.getElementById("sortBtn").innerHTML = "Sorting by " + field;
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("inventoryTable");
  switching = true;
  var n = (field == "price") ? 4:3;
  while (switching) {
      switching = false;
      rows = table.getElementsByTagName("tr");
      for (i = 1; i <= rows.length; i++) {
          shouldSwitch = false;
          x = (rows[i].getElementsByTagName("td"))[n];
          y = (rows[i + 1].getElementsByTagName("td"))[n];
            if (Number(x.innerHTML) > Number(y.innerHTML)) {
                shouldSwitch = true;
                break;
              }
            }
            if (shouldSwitch) {
              rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
              switching = true;
            }
          }
        }
    </script>
  </body>
</html>