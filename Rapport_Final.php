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

$sql1="SELECT * FROM competances WHERE stagiaire_code=$id";
$result1 = $conn->query($sql1);

$sql="SELECT * FROM presences WHERE stagiaire_code=$id ORDER BY date ASC ";
$result = $conn->query($sql);

$sql_statu_present="SELECT COUNT(*) FROM presences WHERE stagiaire_code=$id AND (presence_status='Present' OR presence_status='En retard')";
$result_statu_present = mysqli_fetch_array(mysqli_query($conn, $sql_statu_present))[0];

$sql_statu_absent="SELECT COUNT(*) FROM presences WHERE stagiaire_code=$id AND presence_status='Absent'";
$result_statu_absent = mysqli_fetch_array(mysqli_query($conn, $sql_statu_absent))[0];

$sql="SELECT * FROM presences WHERE stagiaire_code=$id ORDER BY date ASC ";
$result = $conn->query($sql);
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
$pdf->Cell(95,10,utf8_decode("Departement"),1,0,'C',true);
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

$pdf->SetFont('times', 'B', 16, '', true);
$pdf->Cell(190,10,utf8_decode("Les compétences visés"),0,1,'C');
$pdf->Ln(5);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(95,8,utf8_decode("Compétences visés"),1,0,'C',true);
$pdf->Cell(95,8,"Niveau",1,1,'C',true);
$pdf->SetTextColor(0,0,0);

$pdf->SetFont('helvetica', '', 16, '', true);
if(mysqli_num_rows($result1)>0){
  while ($row = $result1->fetch_assoc()) {
    $pdf->Cell(95,7,$row[utf8_decode('competance')],1,0,'C');
    $pdf->Cell(95,7,$row['niveau_de_competance'],1,1,'C');
  }
}else{
  $pdf->Cell(190,10,"Les champs sont vide",1,0,'C');
}

$pdf->Ln(95);
$pdf->SetFont('times', 'B', 16, '', true);
$pdf->Cell(190,10,"FICHE DE PRESENCE",0,1,'C');
$pdf->Cell(125,8,"Jours Present : $result_statu_present jour(s)",0,0);
$pdf->Cell(95,8,"Jours Absent : $result_statu_absent jour(s)",0,0);
$pdf->Ln(10);

$pdf->SetTextColor(255,255,255);

$pdf->Cell(95,8,"Date ",1,0,'C',true);
$pdf->Cell(95,8,"Statut",1,1,'C',true);
$pdf->SetFont('helvetica', '', 16, '', true);
$pdf->SetTextColor(0,0,0);
if(mysqli_num_rows($result)>0){
  while ($row = $result->fetch_assoc()) {

    $pdf->Cell(95,8,$row['date'],1,0,'C');
      $pdf->Cell(95,8,$row[utf8_decode('presence_status')],1,1,'C');
  }
}else{
  $pdf->Cell(190,10,"Les champs sont vide",1,0,'C');
  $pdf->Ln(10);
}

$pdf->Ln(10);
$pdf->Cell(95,10,"Signature de stagiaire : ",0,0,'L');
$pdf->Cell(95,10,"Signature de responsable : ",0,1,'L');
$pdf->Output();
?>