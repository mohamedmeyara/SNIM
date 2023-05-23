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
  </style>
  <title>SMIN - Rapports</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#search").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#trainers tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
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
          <li><a href="stagiaires.php"><input type="submit" value="Stagiaires"></a></li>
          <li><a href="raports.php"><div class="specific"><input type="submit" value="Rapports"></div></a></li>
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
        <h2>Rapports des stagiaires</h2>
        <input type="text" id="search" placeholder="Recherche Par Nom...">
      </div>
    <table>
        <tr>
          <th>Nom</th>
          <th>Rapport d'info</th>
          <th>Rapport de presence</th>
          <th>Rapport de competences </th>
          <th>Rapport Final</th>
        <tbody id="trainers">
      <?php
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
       $sql = "SELECT code,nom FROM les_stagiaires LIMIT $stagiaires_a_afficher OFFSET $offset";
       $result = $conn->query($sql);

          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='nom'>" . $row["nom"] . "</td>";?>
            <td><a target="_blank" href="pdf/info.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Info</Button></a></td>
            <td><a target="_blank" href="pdf/presence_pdf.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Presence</Button></a></td>
            <td><a target="_blank" href="pdf/competances_pdf.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Competance</Button></a></td>
            <td><a target="_blank" href="pdf/Rapport_Final.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Final</Button></a></td>
              <?php echo "</tr>";
        }
        
    ?>
      </tr>
      
    </table>
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
    <?php
    ?>
    </div>
    </div>
    <h2>Recherche </h2>
    <div class="search_box">
      <form method="post">
        <label for="recherche">Recherche :</label>
        <input type="text" name="recherche" id="recherche" placeholder="Recherche ....">
        <input type="submit" value="Recherche">
      </form>
    </div>
    <?php
if (isset($_POST['recherche'])) {
  $recherche = $_POST['recherche'];
  $sql_recherche = "SELECT * FROM les_stagiaires WHERE code ='$recherche' OR nom LIKE '%$recherche%' OR niveau LIKE '%$recherche%' OR ecole LIKE '%$recherche%'";
  $result_recherche = $conn->query($sql_recherche);
  if ($result_recherche->num_rows > 0) {?>
      <div class="box" id="box">
    <div class='trainers-box'>
      <div class="above">
        <h2>Rapport(s) trouvés</h2>
        <a href="raports.php"><button>Fermer</button></a>
      </div>
    <table>
        <tr>
          <th>Nom</th>
          <th>Rapport d'info</th>
          <th>Rapport de presence</th>
          <th>Rapport de competences </th>
          <th>Rapport Final</th>
        <tbody id="trainers">
          <?php
                    while ($row = $result_recherche->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td class='nom'>" . $row["nom"] . "</td>";?>
                      <td><a target="_blank" href="pdf/info.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Info</Button></a></td>
                      <td><a target="_blank" href="pdf/presence_pdf.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Presence</Button></a></td>
                      <td><a target="_blank" href="pdf/competances_pdf.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Competance</Button></a></td>
                      <td><a target="_blank" href="pdf/Rapport_Final.php?id=<?php echo $row['code']?>"><Button id='raport'>Rapport Final</Button></a></td>
                        <?php echo "</tr>";
                  };
                } else {
                  echo "Aucun entraîneur trouvé avec ce mot-clé.";
                }
              };
                  
              ?>
                </tr>
                
              </table>
    </section>

</body>
</html>
