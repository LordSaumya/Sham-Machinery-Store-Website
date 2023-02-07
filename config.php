<?php
$servername = "localhost";
$username = "id17934124_admin";
$password = "<J+=(-=4X5vlrCwK";
$database = "id17934124_shammachinerystores";
$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>