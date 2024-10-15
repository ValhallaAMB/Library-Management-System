<?php
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "library";

// Attempt MySQL server connection
$con = mysqli_connect($serverName, $userName, $password);

// Check connection
if ($con === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check if the database exists
if (mysqli_select_db($con, $dbName)) {
  // echo "Connected to existing database: $dbName" . "<br>";
} else {
  // Create the database if it doesn't exist
  $createDatabaseQuery = "CREATE DATABASE $dbName";

  if (mysqli_query($con, $createDatabaseQuery)) {
    // echo "Database created successfully." . "<br>";
    mysqli_select_db($con, $dbName);
  } else {
    die("Failed creating database: " . mysqli_error($con));
  }
}