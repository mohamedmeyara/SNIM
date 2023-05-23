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
$sql="SELECT * FROM competances WHERE stagiaire_code=$id";
$result = $conn->query($sql);
$info="SELECT * FROM les_stagiaires WHERE code=$id";
$stagiaires = $conn->query($info);
foreach($stagiaires as $stagiaire){
  $nom=$stagiaire['nom'];
  $nni=$stagiaire['nni'];
  $ecole=$stagiaire['ecole'];
  $niveau=$stagiaire['ecole'];
  $struc=$stagiaire['direction']."-".$stagiaire['departement']."-".$stagiaire['service'];
  $dep=$struc;
  $dd=$stagiaire['date_debut'];
  $df=$stagiaire['date_fin'];
  $lvl=$stagiaire['niveau'];
  $sujet=$stagiaire['sujet'];
  $tech=$stagiaire['technologies'];
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('times', '', 16, '', true);
$pdf->SetFillColor(179, 179, 179);
// $pdf->Cell(16,10,"Nom : ",0,);
$pdf->Cell(110,10,"Nom : $nom",0,0);
$pdf->Cell(120,10,utf8_decode("Structure : $dep"),0,1);
$pdf->Cell(190,10,utf8_decode("Sujet : $sujet"),0,1);
$pdf->Cell(190,10,utf8_decode("Technologies Utilise : $tech"),0,1);
$pdf->SetFont('times', 'B', 16, '', true);
$pdf->Cell(190,10,utf8_decode("$nom - Compétences"),0,1,'C');
$pdf->Ln(5);
$pdf->Cell(100,10,utf8_decode("Compétences visés"),1,0,'C',true);
$pdf->Cell(90,10,"Niveau",1,1,'C',true);
$pdf->SetFont('helvetica', '', 16, '', true);

while ($row = $result->fetch_assoc()) {
  $pdf->Cell(100,10,$row[utf8_decode('competance')],1,0,'C');
  $pdf->Cell(90,10,$row['niveau_de_competance'],1,1,'C');
}
$pdf->Ln(20);
$pdf->Cell(190,10,"Signature de stagiaire : ",0,1,'L');
$pdf->Ln(10);
$pdf->Cell(190,10,"Signature de responsable : ",0,1,'L');
$pdf->Output();
?>