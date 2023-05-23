<?php
//Debut de la session
session_start();
// Verifie la login de l'utilisateru  et captilation du role de l'utilisateur
if (!isset($_SESSION['usertype'])) {
  header("Location: index.php");
  session_destroy();
  exit();
}
$usertype = $_SESSION["usertype"];
?>
<!DOCTYPE html>
<html lang="fr">
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
  <title>SMIN - Stagiaires</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
$(document).ready(function(){
  $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#trainers tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
});

</script>

</head>
  <body>
    <nav>
      <aside>
        <img src="photos/snim.png" alt="Snim's Logo">
				<hr>
        <ul>
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
    <div class="box">
    <div class='trainers-box'>
      <div class="above">
      <h2>Liste des stagiaires </h2>
        <input type="text" id="search" placeholder="Recherche ...">

      </div>
      <?php
    // Pagination des stagiaires
    include 'conn.php';
    $stagiaires_a_afficher = 6;
    $nomber_des_stagiaires_query = "SELECT COUNT(*) FROM les_stagiaires";
    $nomber_des_stagiaires = mysqli_fetch_array(mysqli_query($conn, $nomber_des_stagiaires_query))[0];
    $total_pages = ceil($nomber_des_stagiaires / $stagiaires_a_afficher);

    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $page_current = $_GET['page'];
    } else {
        $page_current = 1;
    }

    $offset = ($page_current - 1) * $stagiaires_a_afficher;
    $sql = "SELECT * FROM les_stagiaires ORDER BY date_debut DESC LIMIT $stagiaires_a_afficher OFFSET $offset  ";
    $result = $conn->query($sql);
?>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th class="d">Date debut</th>
            <th class="d">Date fin</th>
            <th class="bu">Niveau</th>
            <th class="dep">Ecole</th>
            <th class="bu">Consulte</th>
            <?php if ($usertype == 'admin'): ?>
                <th class='bu'>Supprime</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="trainers">
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['nom'] ?></td>
                <?php
                $date_debut = strtotime($row['date_debut']);
                $format_date_Debut = date('d/m/Y', $date_debut);
                ?>
                <td><?= $format_date_Debut ?></td>
                <?php
                $date_fin = strtotime($row['date_fin']);

                $format_date_fin = date('d/m/Y', $date_fin);
                ?>
                <td><?= $format_date_fin ?></td>
                <td><?= $row['niveau'] ?></td>
                <td><?= $row['ecole'] ?></td>
                <td>
                    <a href="stagiaire.php?c=<?= $row['code'] ?>&n=<?= $row['nom'] ?>">
                        <button id='edit'>Consulte</button>
                    </a>
                </td>
                <?php if ($usertype == 'admin'): ?>
                    <td>
                        <a href="stagiaires.php?del=<?= $row['code'] ?>">
                            <button id='sup'>Supprime</button>
                        </a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<!-- Nomber des pages en bas et gestion du links -->
<div class="pagination">
    <?php   $max_links = 5;
  $link_offset = floor($max_links / 2);
  if ($page_current > 1) {
    echo "<a href='?page=1'><<</a>";
    $prev_page = $page_current - 1;
    echo "<a href='?page=$prev_page'>Préc</a>";
  }
  $start_link = max(1, $page_current - $link_offset);
  $end_link = min($total_pages, $start_link + $max_links - 1);
  if ($start_link > 1) {
    echo "<span class='ellipsis'>&hellip;</span>";
  }
  for ($i = $start_link; $i <= $end_link; $i++) {
    if ($i == $page_current) {
      echo "<span class='current'>$i</span>";
    } else {
      echo "<a href='?page=$i'>$i</a>";
    }
  }
  if ($end_link < $total_pages) {
    echo "<span class='ellipsis'>&hellip;</span>";
  }
  if ($page_current < $total_pages) {
    $next_page = $page_current + 1;
    echo "<a href='?page=$next_page'>Suivant</a>";
    echo "<a href='?page=$total_pages'>>></a>";
  }
  ?>
</div>
    </div>
    </div>
    </div>
    <h2>Recherche </h2>
    <div class="search_box">
      <form method="post">
        <label for="recherche">Recherche :</label>
        <input type="text" name="recherche" placeholder="Recherche..." id="recherche">
        <input type="submit" value="Recherche">
      </form>
    </div>
    <?php
if (isset($_POST['recherche'])) {
  $recherche = $_POST['recherche'];
  $sql_recherche = "SELECT * FROM les_stagiaires WHERE code = '$recherche' OR nom LIKE '%$recherche%' OR service LIKE '%$recherche%' OR ecole LIKE '%$recherche%' OR niveau LIKE '%$recherche%' LIMIT $stagiaires_a_afficher OFFSET $offset ";
  $result_recherche = $conn->query($sql_recherche);

  // Display the results in a table
  if ($result_recherche->num_rows > 0) {?>
  <div class="box" id="box">
  <div class='trainers-box'>
  <div class="above">
        <h2>Stagiaire(s) trouvés</h2>
        <input type="text" id="search" placeholder="Recherche ...">
        <a href="stagiaires.php"><button>Fermer</button></a>
      </div>
    <table>
    <thead>
        <tr>
            <th>Nom</th>
            <th class="d">Date debut</th>
            <th class="d">Date fin</th>
            <th class="bu">Niveau</th>
            <th class="dep">Ecole</th>
            <th class="bu">Consulte</th>
            <?php if ($usertype == 'admin'): ?>
                <th class='bu'>Supprime</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="trainers">
        <?php while ($row2 = $result_recherche->fetch_assoc()): ?>
            <tr>
                <td><?= $row2['nom'] ?></td>
                <?php
                $date_debut = strtotime($row2['date_debut']);

                $format_date_Debut = date('d/m/Y', $date_debut);
                ?>
                <td><?= $format_date_Debut ?></td>
                <?php
                $date_fin = strtotime($row2['date_fin']);

                $format_date_fin = date('d/m/Y', $date_fin);
                ?>
                <td><?= $format_date_fin ?></td>
                <td><?= $row2['niveau'] ?></td>
                <td><?= $row2['ecole'] ?></td>
                <td>
                    <a href="stagiaire.php?stagiaier_id=<?= $row['code'] ?>&name=<?= $row['nom'] ?>">
                        <button id='edit'>Consulte</button>
                    </a>
                </td>
                <?php if ($usertype == 'admin'): ?>
                    <td>
                        <a href="stagiaires.php?del=<?= $row['code'] ?>">
                            <button id='sup'>Supprime</button>
                        </a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>
</div>
  <?php } else {
    echo "Aucun entraîneur trouvé avec ce mot-clé.";
  }
}
?>

    </section>
<?php
    // Suppresion de stagiaire
    if(isset($_GET['del'])){
      $targetid = $_GET['del'];
      $sup="DELETE FROM les_stagiaires WHERE code = '$targetid' ";
      $supquery = mysqli_query($conn , $sup);
      echo "<script>alert('Le stagiaire est supprime');</script>";
      echo "<script>window.location.href = 'stagiaires.php'</script>";
    }
?>

</body>
</html>
