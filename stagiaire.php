<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['usertype'])) {
  header("Location: index.php");
session_destroy();
  exit();
}
$usertype = $_SESSION["usertype"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Gestion des stagiaires">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="icon/icon.ico">
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@300;400&display=swap');
  </style>
  <title>SMIN - Stagiaire</title>
</head>
  <body>
    <nav>
      <aside>
        <img src="photos/snim.png" alt="Snim's Logo">
				<hr>
        <ul>
          <?php 
          $value = $_GET['c'];
          $Nom = $_GET['n'];
          ?>
          <li><a href="dashboard.php"><input type="submit" value="Dashboard"></a></li>
          <li><a href="stagiaires.php"><div class="specific"><input type="submit" value="Stagiaires"></div></a></li>
          <li><a href="raports.php"><input type="submit" value="Rapports"></a></li>
          <?php if ($usertype == 'admin'): ?>
            <li><a href="utilisateurs.php"><input type="submit" value="Utilisateurs"></a></li>
            <?php endif; ?>
        </ul>
				<hr>
      </aside>
    </nav>
    <section class="main">
    <div class="bar">
            <form method="post">
              <button name="logout" class="logout-button">
                <img src="photos/logout.svg" alt="Logout">
                <span>Logout</span>
              </button>
            </form>
      </div>
      <?php
      if(isset($_POST['logout'])){
        session_destroy();
        header("Location: index.php");
        exit();
      }?>
			<div class="container">
        <?php
        $sql = "SELECT * FROM les_stagiaires WHERE code=$value";
        $result = $conn->query($sql);
        ?>
    <div class="stagiaire">
    <div class='trainer-box'>
    <h2>Stagiaire : <?php echo $Nom ?></h2>
    <table class="detail">
        <tr>
        <th>Nom Complete</th>
          <th>NNI</th>
          <th>École</th>
          <th>Niveau</th>
          <th>Structure</th>
          <th>Date debut</th>
          <th>Date fin</th>
          <th>Sujet du stage</th>
          <th>Technologies utilisées</th>
          <th>Statue de stage</th>
        </tr>
        <tbody id="trainers">
      <?php
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='first'>" . $row["nom"] . "</td>";
            echo "<td >" . $row["nni"] . "</td>";
            echo "<td class='first'>" .  $row["ecole"] . "</td>";
            echo "<td >" . $row["niveau"] . "</td>";
            $struc=$row['direction']."-".$row['departement']."-".$row['service'];
            echo "<td class='first'>" . $struc . "</td>";
            $date_debut = strtotime($row['date_debut']);
            $format_date_Debut = date('d/m/Y', $date_debut);
            echo "<td >" . $format_date_Debut . "</td>";
            $date_fin = strtotime($row['date_fin']);
            $format_date_fin = date('d/m/Y', $date_fin);
            echo "<td class='first'>" . $format_date_fin . "</td>";
            echo "<td >" . $row["sujet"] . "</td>";
            echo "<td class='first'>" . $row["technologies"] . "</td>";
            $end_date = $row["date_fin"];
            $today = new DateTime();
            $check_date=$end_date < $today->format('Y-m-d');
            if($check_date){
              $statue="Stage termine";
            }else{
              $statue="Stage en course";
            }
            echo "<td >" . $statue . "</td>";

            echo "</tr>";
        }
        $aujourdhui=date("d/m/Y");
        ?>
      </tr>
    </table>
    <div class="champ-edit">
    <a href="miseajour.php?c=<?php echo $value?>"><input type="submit" id="Modifier" name="Modifier" value="Modifier"></a>
      
    </div>
    </div>

    </div>
    </div>
    <div class="presense">
      <h2>Fiche de présence</h2>
      <div class="presense_container">
        <div class="presense_champ">
          <form method="post">
            <label for="date">Date :</label>
            <input type="text" name="date" value="<?php echo $aujourdhui?>" id="date">
            <label for="statue">Statue :</label>
            <select name="statue" id="statue">
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="En retard">En retard</option>
          </select>
          <input type="submit" id="Sauvegarder" name="Sauvegarder" value="Sauvegarder">
          <?php
          if(isset($_POST['Sauvegarder'])){
            $stagiaire_code=$value;
            $date_presence=$_POST['date'];
            $statue_de_presence=$_POST['statue'];
            $stagiaire_prsence="INSERT INTO presences (stagiaire_code,date,presence_status) VALUES ('$stagiaire_code','$date_presence','$statue_de_presence')";
            $presence = mysqli_query($conn , $stagiaire_prsence);
            echo "<script>alert('Les données ont été ajoutées avec succès');</script>";
            echo "<script>window.location.href = 'stagiaire.php?c=$stagiaire_code&n=$Nom'</script>";
          }
        ?>
        </div>
      </div>
      <a href="presence.php?c=<?php echo $value?>&n=<?php echo $Nom?>"><input type="button" id="Presence" name="Presence" value="Regarder la présence"></a>
      <?php
      ?>
    </form>
    </div>
    <h2>Les compétences visés</h2>
    <div class="competance">
      <div class="champ-competance">
        <form method="post">
        <label for="competences">Compétences visées :</label>
        <textarea name="competences" required id="competences" rows="5"></textarea>
        <label for="niveau" >Niveau:</label>
        <select required id="comp" name="comp">
          <option value="Debutant">Debutant</option>
          <option value="Intermediaire">Intermediaire</option>
          <option value="Expert">Expert</option>
        </select>
        <input type="submit" id="competance" name="competance" value="Sauvegarder">
        <a href="stagiaire_competence.php?c=<?php echo $value?>&n=<?php echo $Nom?>"><input type="button" id="stagiaire_competance" name="stagiaire_competance" value="Regarder les competences"></a>
        </form>
      </div>
    </div>
      </section>
      <?php
          if(isset($_POST['competance'])){
            $stagiaire_code_comp=$value;
            $competence=$_POST['competences'];
            $niveau_comp=$_POST['comp'];
            $stagiaire_competence="INSERT INTO competances (stagiaire_code,competance,Niveau_de_competance) VALUES ('$stagiaire_code_comp','$competence','$niveau_comp')";
            $N_competence = mysqli_query($conn , $stagiaire_competence);
            echo "<script>alert('Les données ont été ajoutées avec succès');</script>";
            echo "<script>window.location.href = 'stagiaire.php?c=$stagiaire_code_comp&n=$Nom'</script>";
          }
        ?>
</body>
</html>