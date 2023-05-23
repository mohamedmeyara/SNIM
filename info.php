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
    $this->Ln(30);
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

$info="SELECT * FROM les_stagiaires WHERE code=$id";
$stagiaires = $conn->query($info);
foreach($stagiaires as $stagiaire){
  $nom=$stagiaire['nom'];
  $nni=$stagiaire['nni'];
  $ecole=$stagiaire['ecole'];
  $niveau=$stagiaire['niveau'];
  $struc=$stagiaire['direction']."-".$stagiaire['departement']."-".$stagiaire['service'];
  $dep=$struc;
  $date_debut = strtotime($stagiaire['date_debut']);
  $format_date_debut = date('d/m/Y', $date_debut);
  $dd=$format_date_debut;
  $date_fin = strtotime($stagiaire['date_fin']);
  $format_date_fin = date('d/m/Y', $date_fin);
  $df=$format_date_fin;
  $sujet=$stagiaire['sujet'];
  $tech=$stagiaire['technologies'];
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('times', 'B', 18, '', true);
$pdf->Cell(190,10,utf8_decode("Les infos de stagiaire : $nom"),0,1,'C');
$pdf->Ln(10);
$pdf->SetFillColor(34, 34, 34);
$pdf->SetTextColor(255,255,255);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('times', '', 16, '', true);
$pdf->Cell(95,10,utf8_decode("Nom"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$nom"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("NNI"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$nni"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Ecole / University"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$ecole"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Niveau"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$niveau"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Structure"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$dep"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Date Debut"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$dd"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Date Fin"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$df"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Sujet de stage"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$sujet"),1,1,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,10,utf8_decode("Technologies Utilisées"),1,0,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(95,10,utf8_decode("$tech"),1,1,'C');

$pdf->Ln(10);
$pdf->Cell(95,10,"Signature de stagiaire : ",0,0,'L');
$pdf->Cell(95,10,"Signature de responsable : ",0,1,'L');
$pdf->Output();
?>