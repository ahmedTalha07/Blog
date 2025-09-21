<?php
$host     = "db.fr-pari1.bengt.wasmernet.com";
$port     = 10272; // Wasmer's port for your DB
$dbname   = "blog_db";
$username = "fc7ea64c7184800009e479091f98";
$password = "068cfc7e-a64c-72cd-8000-a8aab7857d4a"; // put the password you got from Wasmer

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}