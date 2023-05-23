<?php

// Connect to the database
include 'conn.php';

// Get the department ID from the AJAX request
$department = mysqli_real_escape_string($conn, $_POST['department']);

// Query the database to get the related services
$sql = "SELECT service FROM services WHERE departement = '$department'";
$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}

// Build an array of services
$services = array();
while ($row = mysqli_fetch_assoc($result)) {
  $services[] = $row;
}


// Return the data in JSON format
echo json_encode(array('services' => $services));
