<?php
require_once "config.php";
if (isset($_POST['deleteForm'])){
  $sql = "DELETE FROM Inventory WHERE ItemID = ".$_POST['DeleteItemID'];
  $mysqli -> query($sql);
}
if (isset($_POST['insertItem'])){
  $name = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["insName"])));
  $quantity = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["insQuantity"])));
  $category = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["insCategory"])));
  $price = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["insPrice"])));
  $material = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["insMaterial"])));
  $sql= "SELECT ItemID FROM Inventory ORDER BY ItemID DESC LIMIT 1";
  $result = $mysqli->query($sql);
  $row = $result->fetch_assoc();
  $ItemID = $row['ItemID']+1;
  $sql = "INSERT INTO Inventory (ItemID, Name, Category, Material, Quantity, Price) VALUES ('$ItemID','$name','$category','$material','$quantity','$price')";
  if ($mysqli->query($sql)){
    echo("<script>alert('Item inserted.')</script>");
  }
  else{
    echo("Error in insertion of item. Please try again later.");
  }
}
if (isset($_POST['updateItem'])){
  $ItemID = $_POST['updID'];
  $name = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["updName"])));
  $quantity = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["updQuantity"])));
  $category = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["updCategory"])));
  $price = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["updPrice"])));
  $material = $mysqli -> real_escape_string(stripslashes(strip_tags($_POST["updMaterial"])));
  $sql = "UPDATE Inventory SET Name = '".$name."', Quantity = ".$quantity.", Category = '".$category."', Price = ".$price.", Material = '".$material."' WHERE ItemID = ".$ItemID;
  if ($mysqli->query($sql)){
    echo("<script>alert('Item updated.')</script>");
  }
  else{
    echo("Error in updating item. Please try again later.");
  }
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Inventory Management Console | Sham Machinery Stores</title>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <link rel = "stylesheet" href = "style.css">
  </head>
  <body>
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
      <a href = "Contact.php" class = "nLink">Contact</a>
      </div>
      <center>
      <!--CONTENT-->
    <div class = "content"><br><br>
      <h1 class = "websiteTitle">Inventory Management Console</h1><br>
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
            <th>Item ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Material</th>
            <th>Quantity</th>
            <th>Price (in &#8377;)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <?php
        $result = $mysqli->query("SELECT ItemID, Name, Category, Material, Quantity, Price FROM Inventory");
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $confirmMessage = "Are you sure you want to delete ".$row['Name']." from the database?";
            echo "<tr class = 'item'><td class = 'itemID'>".$row["ItemID"]."</td><td class = 'itemName'>".$row["Name"]."</td><td class = 'itemCategory'>".$row["Category"]."</td><td class = 'itemMaterial'>".$row["Material"]."</td><td class = 'itemQuantity'>".$row["Quantity"]."</td><td class = 'itemPrice'>".$row["Price"]."</td><td><button type = 'submit' name = 'deleteForm' form = '".$row['ItemID']."' class = 'DeleteBtn'>Delete</button></td></tr>";
            echo '<form method = "POST" id = "'.$row['ItemID'].'" onsubmit = "return confirm(\''.$confirmMessage.'\')">
            <input style = "display:none" name = "DeleteItemID" value = "'.$row['ItemID'].'">
            </form>';
          }
        } else {
          echo "0 results";
        }
        ?>
      </table>
      <p style = "font-size:24px;color:grey;display:none;" id = "noResults">No results. Try searching for something else?</p>
      <br><br>
      <div style = "display:flex;flex-direction:row;justify-content: space-around;width:90%">
      <div class = "IMCFormCont">
        <span style = "color:white;font-size:20px;font-weight:bold">New Item</span><br><br>
        <form method = "POST" id = "InsertForm" class = "IMCForm">
          <label for = "insName">Name</label><input name = "insName" required><br><br>
          <label for = "insQuantity">Quantity</label><input name = "insQuantity" type = "number" required><br><br>
          <label for = "insCategory">Category</label><input name = "insCategory" required><br><br>
          <label for = "insMaterial">Material</label><input name = "insMaterial" required><br><br>
          <label for = "insPrice">Price</label><input name = "insPrice" type = "number" required><br><br>
          <button class = "submit" type = "submit" form = "InsertForm" name = "insertItem">Submit</button>
        </form>
      </div>
      <div class = "IMCFormCont">
      <span style = "color:white;font-size:20px;font-weight:bold">Update Item</span><br><br>
      <form method = "POST" id = "UpdateForm" class = "IMCForm">
        <label for = "updID">Item ID</label><input name = "updID" id = "updID" type = "number" required oninput = "fillDetails(this.value)"><br><br>
        <label for = "updName">Name</label><input name = "updName" id = "updName" required><br><br>
        <label for = "updQuantity">Quantity</label><input name = "updQuantity" id = "updQuantity" type = "number" required><br><br>
        <label for = "updCategory">Category</label><input name = "updCategory" id = "updCategory" required><br><br>
        <label for = "updMaterial">Material</label><input name = "updMaterial" id = "updMaterial" required><br><br>
        <label for = "updPrice">Price</label><input name = "updPrice" type = "number" id = "updPrice" required><br><br>
        <button class = "submit" type = "submit" form = "UpdateForm" name = "updateItem">Submit</button>
      </form>
      </div>
      </div>
      <br><br><br>
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
  var n = (field == "price") ? 5:4;
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
  //Validate
  function validate(id){
    var ItemIds = document.getElementsByClassName("itemID");
  }
  //Fill details
  function fillDetails(id){
    var ItemIds = document.getElementsByClassName("itemID");
    var ItemNames = document.getElementsByClassName("itemName");
    var ItemQuantities = document.getElementsByClassName("itemQuantity");
    var ItemCategories = document.getElementsByClassName("itemCategory");
    var ItemMaterials = document.getElementsByClassName("itemMaterial");
    var ItemPrices = document.getElementsByClassName("itemPrice");
    if (id > ItemIds.length){
      document.getElementById("updID").value = ItemIds.length;
      id = ItemIds.length;
    }
    else if (id <= 0 && id != ""){
      document.getElementById("updID").value = 1;
      id = 1;
    }
    for (i = 0;i < ItemIds.length;i++){
      if (parseInt(ItemIds[i+1].innerHTML) == parseInt(id)){
        document.getElementById("updName").value = ItemNames[i+1].innerHTML;
        document.getElementById("updQuantity").value = ItemQuantities[i+1].innerHTML;
        document.getElementById("updCategory").value = ItemCategories[i+1].innerHTML;
        document.getElementById("updMaterial").value = ItemMaterials[i+1].innerHTML;
        document.getElementById("updPrice").value = ItemPrices[i+1].innerHTML;
        break;
      }
      else if (parseInt(ItemIds[0].innerHTML) == parseInt(id)){
      document.getElementById("updName").value = ItemNames[0].innerHTML;
      document.getElementById("updQuantity").value = ItemQuantities[0].innerHTML;
      document.getElementById("updCategory").value = ItemCategories[0].innerHTML;
      document.getElementById("updMaterial").value = ItemMaterials[0].innerHTML;
      document.getElementById("updPrice").value = ItemPrices[0].innerHTML;
      }
      else{
        document.getElementById("updName").value = null;
        document.getElementById("updQuantity").value = null;
        document.getElementById("updCategory").value = null;
        document.getElementById("updMaterial").value = null;
        document.getElementById("updPrice").value = null;
      }
    }
    }
    </script>
  </body>
</html>