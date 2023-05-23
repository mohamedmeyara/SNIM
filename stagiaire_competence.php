<?php
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
  .pdf{
  width: 30%;
  margin: 0 auto;
  margin-top: 10px;
  
}
.pdf input{
  background-color: #222;
  color: #fff;
  border-radius: 8px;
  font-size:25px;
  margin-bottom: 10px;

}
  </style>
  </style>
  <title>SMIN - Competence</title>
</head>
  <body>
    <nav>
      <aside>
        <img src="photos/snim.png" alt="Snim's Logo">
				<hr>
        <ul>
          <li><a href="dashboard.php"><input type="submit" value="Dashboard"></a></li>
          <li><a href="stagiaires.php"><input type="submit" value="Stagiaires"></a></li>
          <li><a href="raports.php"><div class="specific"><input type="submit" value="Rapports"></div></a></li>
          <?php if ($usertype == 'admin'): ?>
            <li><a href="utilisateurs.php"><input type="submit" value="Utilisateurs"></a></li>
            <?php endif; ?>
        </ul>
				<hr>
      </aside>
    </nav>
    <?php
        $stagiaire_code= $_GET['c'];
        $Nom_stagiaire = $_GET['n'];
        include 'conn.php';
        $sql = "SELECT * FROM competances where stagiaire_code='$stagiaire_code' ";
        $result = $conn->query($sql);
        ?>
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
    <div class="stagiaire">
    <div class='trainer-box'>
    <div class="trainer-presence">
  <h2><?php echo "$Nom_stagiaire <span>-</span> competences"?></h2>
  <h2>Les compétences visés</h2>
  <table>
    <thead>
      <tr >
        <th>compétence</th>
        <th>Niveau de compétence</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while ($row = $result->fetch_assoc()) {
      ?>
      <tr id="check">
        <td><?php echo $row['competance'] ?></td>
        <td><?php echo $row['niveau_de_competance'] ?></td>
      </tr>
          <?php } ?>
    </tbody>
  </table>
  <div class="pdf">
        <form action="pdf/competances_pdf.php?id=<?php echo $stagiaire_code?>" target="_blank" method="post">
          <input type="submit" id="presence_pdf" name="presence_pdf" value="Generate PDF">
        </form>
      </div>
</div>

    </div>

    </div>
    </div>
</section>
