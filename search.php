<?php
session_start();
if (!isset($_SESSION['usertype'])) {
  header("Location: index.php");
session_destroy();
  exit();
}
$usertype = $_SESSION["usertype"];
// Connection  database
include 'conn.php';


// Recherche valuer
$search = $conn->real_escape_string($_POST['search']);
if(!empty($search)){

// recherche
$sql = "SELECT * FROM stagiaire WHERE name LIKE '%$search%' OR departement LIKE '%$search%' OR school LIKE '%$search%' OR level LIKE '%$search%'";
$result = $conn->query($sql);

// Generate table
$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<tr>';
        $output .= '<td>' . $row['id'] . '</td>';
        $output .= '<td>' . $row['name'] . '</td>';
        $date_debut = strtotime($row['start_date']);
        $format_date_Debut = date('d/m/Y', $date_debut);
        $output .= '<td>' . $format_date_Debut . '</td>';
        $date_fin = strtotime($row['end_date']);
        $format_date_fin = date('d/m/Y', $date_fin);
        $output .= '<td>' . $format_date_fin . '</td>';
        $output .= '<td>' . $row['level'] . '</td>';
        $output .= '<td>' . $row['departement'] . '</td>';
        $output .= '<td><a href="stagiaire.php?stagiaier_id=' . $row['id'] . '&name=' . $row['name'] . '"><button id="edit">Consulte</button></a></td>';
        if ($usertype == 'admin') {
            $output .= '<td><a href="stagiaires.php?del=' . $row['id'] . '"><button id="sup">Supprime</button></a></td>';
        }
        $output .= '</tr>';
    }
} else {
    $output .= '<tr><td colspan="7">No results found</td></tr>';
}

echo $output;}


?>
