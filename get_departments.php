<?php
// Connect to the database
include 'conn.php'; 

// Get the direction ID from the AJAX request
$direction = mysqli_real_escape_string($conn, $_POST['direction']);

// Query the database to get the related departments
$sql = "SELECT nom FROM departements WHERE direction = '$direction'";
$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}

// Build an array of departments
$departments = array();
while ($row = mysqli_fetch_assoc($result)) {
  $departments[] = $row;
}


// Return the data in JSON format
echo json_encode(array('departments' => $departments));
