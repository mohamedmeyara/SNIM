<?php
  require("fpdf.php");
  $id=$_GET['id'];
  class PDF extends FPDF
  {
    // En-tête
    function Header()
{
    $date=date("d/m/Y");
    $this->Image('snim.png',10,6,30);
    $this->SetFont('Times','B',19);
    $this->SetTextColor(0,0,0,0.8);
    $this->Cell(90);
    $this->Cell(40,10,utf8_decode('Société Nationale Industrielle et Minière de Mauritanie'),0,1,'C');
    $this->Cell(144);
    $this->SetFont('Times','',19);
    $this->Cell(40,10,utf8_decode("Date : $date"),0,0,'C');
    $this->Ln(15);
}

function Footer()
{
    $this->SetY(-12);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "snim";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql_statu_present="SELECT COUNT(*) FROM presences WHERE stagiaire_code=$id AND (presence_status='Present' OR presence_status='En retard')";
$result_statu_present = mysqli_fetch_array(mysqli_query($conn, $sql_statu_present))[0];

$sql_statu_absent="SELECT COUNT(*) FROM presences WHERE stagiaire_code=$id AND presence_status='Absent'";
$result_statu_absent = mysqli_fetch_array(mysqli_query($conn, $sql_statu_absent))[0];

$sql="SELECT * FROM presences WHERE stagiaire_code=$id ORDER BY date ASC ";
$result = $conn->query($sql);
$info="SELECT nom,ecole,date_debut,date_fin FROM les_stagiaires WHERE code=$id";
$stagiaires = $conn->query($info);
foreach($stagiaires as $stagiaire){
  $nom=$stagiaire['nom'];
  $ecole=$stagiaire['ecole'];
  $dd=$stagiaire['date_debut'];
  $df=$stagiaire['date_fin'];
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('times', '', 16, '', true);
$pdf->SetFillColor(179, 179, 179);
$pdf->Cell(120,10,utf8_decode("Nom : $nom"),0,0);
$dd_f=strtotime($dd);
$dd_N_f=date('d/m/Y', $dd_f);

$df_f=strtotime($df);
$df_N_f=date('d/m/Y', $df_f);

$pdf->Cell(94,10,utf8_decode("Date Debut : $dd_N_f"),0,1);
$pdf->Cell(127,10,utf8_decode("Ecole / University : $ecole"),0,0);
$pdf->Cell(90,10,utf8_decode("Date Fin : $df_N_f"),0,1);
$pdf->SetFont('times', 'B', 16, '', true);
$pdf->Cell(190,10,"FICHE DE PRESENCE",0,1,'C');
$pdf->Cell(125,7,"Jours Present : $result_statu_present jour(s)",0,0);
$pdf->Cell(95,7,"Jours Absent : $result_statu_absent jour(s)",0,0);
$pdf->Ln(10);
$pdf->Cell(95,8,"Date ",1,0,'C',true);
$pdf->Cell(95,8,"Statut",1,1,'C',true);
$pdf->SetFont('helvetica', '', 16, '', true);

while ($row = $result->fetch_assoc()) {
  $pdf->Cell(95,7,$row['date'],1,0,'C');
    $pdf->Cell(95,7,$row[utf8_decode('presence_status')],1,1,'C');
}
$pdf->Ln(10);
$pdf->Cell(95,10,"Signature de stagiaire : ",0,0,'L');
$pdf->Cell(95,10,"Signature de responsable : ",0,1,'L');
$pdf->Output();
?>