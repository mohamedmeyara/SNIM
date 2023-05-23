<?php
//Debut du session
session_start();
include 'conn.php';

// Verifie si l'utilisateur a faire login ou not
if (!isset($_SESSION['usertype'])) {
  header("Location: index.php");
session_destroy();
  exit();
}

// Captee le role de l'utilisateur
$usertype = $_SESSION["usertype"];
$_SESSION["usertype"] = $usertype;

//Genrate code
function generateStagiaireCode($conn) {
  $suffixes = range(1, 1000);

  do {
    $suffix = $suffixes[array_rand($suffixes)];
    $code = $suffix;

    $sql_code = "SELECT code FROM les_stagiaires WHERE code = '$code'";
    $result_code = $conn->query($sql_code);

  } while ($result_code->num_rows > 0);

  return $code;
}

// L'ajout d'un stagiaire

if (isset($_POST['Ajouter'])){
  $code=generateStagiaireCode($conn);
  $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
  $nni = filter_var($_POST['nni'], FILTER_SANITIZE_NUMBER_INT);
  $ecole = filter_var($_POST['ecole'], FILTER_SANITIZE_STRING);
  $niveau = filter_var($_POST['niveau'], FILTER_SANITIZE_STRING);
  $direction = filter_var($_POST['direction'], FILTER_SANITIZE_STRING);
  $departement = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
  $service = filter_var($_POST['service'], FILTER_SANITIZE_STRING);
  $date_debut = filter_var($_POST['date_debut'], FILTER_SANITIZE_STRING);
  $date_fin = filter_var($_POST['date_fin'], FILTER_SANITIZE_STRING);
  $sujet = filter_var($_POST['sujet'], FILTER_SANITIZE_STRING);
  $technologies = filter_var($_POST['technologies'], FILTER_SANITIZE_STRING);

$sql = "INSERT INTO les_stagiaires (code, nom, nni, ecole, niveau, direction, departement, service, date_debut, date_fin, sujet, technologies)
        VALUES ('$code','$nom', '$nni', '$ecole', '$niveau', '$direction', '$departement', '$service', '$date_debut', '$date_fin', '$sujet', '$technologies')";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Le stagiaire est ajouté, son code est : $code');</script>";
  echo "<script>window.location.href = 'dashboard.php'</script>";

} else {
  echo "<script>alert('Errer');</script>";

}

}
//nomber de stagiare
$count = "SELECT * FROM les_stagiaires";
$result = mysqli_query($conn, $count);
$nomber_de_stagers = mysqli_num_rows($result);

//nomber de stage expire
$today = new DateTime();
$count1 = "SELECT COUNT(*) AS count FROM les_stagiaires WHERE date_fin < '" . $today->format('Y-m-d') . "'";
$result1 = $conn->query($count1);
if ($result1->num_rows >= 0) {
  $row = $result1->fetch_assoc();
  $stages_expire = $row["count"];
}
//nomber de stage en course
$sql = "SELECT COUNT(*) AS count2 FROM les_stagiaires WHERE date_fin >= '" . $today->format('Y-m-d') . "'";
$result = $conn->query($sql);

if ($result->num_rows >= 0) {
    $row = $result->fetch_assoc();
    $stages_en_cours = $row["count2"];
}
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
  <title>SMIN - Dashboard</title>

</head>
  <body>
    <nav>
      <aside>
        <img src="photos/snim.png" alt="Snim's Logo">
				<hr>
        <ul>
        <li><a href="dashboard.php"><div class="specific"><input type="submit" value="Dashboard"></div></a></li>
          <li><a href="stagiaires.php"><input type="submit" value="Stagiaires"></a></li>
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
                <img src="photos/logout" alt="Logout">
                <span>Logout</span>
              </button>
            </form>
      </div>
      <?php
      // Logout de l'utilisateur et terminer la session
      if(isset($_POST['logout'])){
        session_destroy();
        header("Location: index.php");
        exit();
      }?>
			<div class="container">
				<h2>Statistiques des stagiaires</h2>
			</div>
			<div class="dashbord">
			<div class="trainee-count">
				<h3>Nombre des stagiaires</h3>
				<span class="count" ><?php echo $nomber_de_stagers ?></span>
				<span class="label">stagiaire(s)</span>
			</div>
			<div class="trainee-count-now">
				<h3>Stage en course</h3>
				<span class="count"><?php echo $stages_en_cours ?></span>
				<span class="label">stagiaire(s)</span>
			</div>
			<div class="trainee-count-end">
				<h3>Stage expire</h3>
				<span class="count"><?php echo $stages_expire ?></span>
				<span class="label">stagiaire(s)</span>
			</div>
			</div>
      <h2>Ajouter un stagiaire</h2>
      <div class="stagaire_form" style="display:none">
      <form method="post">
      <div class="champ">
        <label for="nom">Nom Complete <span>* &nbsp;</span> :</label>
        <input type="text" name="nom" id="nom" required>
      </div>
      <div class="champ">
        <label for="nni">NNI<span>* &nbsp;</span> :</label>
        <input type="text" name="nni" id="nni" required>
      </div>
      <div class="champ">
        <label for="ecole">École / University <span>* &nbsp;</span> :</label>
        <input type="text" name="ecole" id="ecole" required>
      </div>
      <div class="champ">
        <label for="niveau">Niveau <span>* &nbsp;</span> :</label>
        <select name="niveau" id="niveau" required>
          <option value="">-- Sélectionnez un niveau --</option>
        <optgroup label="Bac+2">
          <option value="BTS">BTS</option>
        </optgroup>
        <optgroup label="Bac+3">
        <option value="LICENSE-L1">License-L1</option>
        <option value="LICENSE-L2">License-L2</option>
        <option value="LICENSE-L3">License-L3</option>
        </optgroup>
        <optgroup label="Bac+5">
        <option value="MASTER-M1">Master-M1</option>
        <option value="MASTER-M2">Master-M2</option>
        </optgroup>
        </select>
      </div>
      <div class="champ">
      <label for="direction">Directions:</label>
        <select id="direction" name="direction">
        <option value="">-- Sélectionnez un direction --</option>

          <?php
          //Ramplir la list from db
        $sql_dir = "SELECT nom FROM directions";
        $result_dir = mysqli_query($conn, $sql_dir);
        while ($row = mysqli_fetch_assoc($result_dir)) {
    echo '<option value="' . $row['nom'] . '">' . $row['nom'] . '</option>';
  }
  ?>
        </select>
      </div>
      <div class="champ">
      <label for="department">departments:</label>
      <select id="department" name="department">
        <option value="">-- Sélectionnez un département --</option>
      </select>
      </div>
      <div class="champ">
      <label for="service">services :</label>
        <select id="service" name="service">
          <option value="">-- Sélectionnez un service --</option>
        </select>
      </div>
      <script>
    var directionSelect = document.getElementById('direction');
    var departmentSelect = document.getElementById('department');
    var serviceSelect = document.getElementById('service');

    directionSelect.addEventListener('change', function() {
      var selectedDirectionId = directionSelect.options[directionSelect.selectedIndex].value;

      // Envoyer la direction est prandre les departement
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'get_departments.php');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);

          // Ramplir departement list
          departmentSelect.innerHTML = '<option value="">-- Sélectionnez un département --</option>';
          for (var i = 0; i < response.departments.length; i++) {
            var departmentOption = document.createElement('option');
            departmentOption.value = response.departments[i].nom;
            departmentOption.textContent = response.departments[i].nom;
            departmentSelect.appendChild(departmentOption);
          }

      // Envoyer la departement est prandre les services
          departmentSelect.addEventListener('change', function() {
            var selectedDepartmentId = departmentSelect.options[departmentSelect.selectedIndex].value;
            var xhr2 = new XMLHttpRequest();
            xhr2.open('POST', 'get_services.php');
            xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr2.onload = function() {
              if (xhr2.status === 200) {
                var response2 = JSON.parse(xhr2.responseText);
                // Ramplir sevices list
                serviceSelect.innerHTML = '<option value="">-- Sélectionnez un service --</option>';
                for (var i = 0; i < response2.services.length; i++) {
                  var serviceOption = document.createElement('option');
                  serviceOption.value = response2.services[i].service;
                  serviceOption.textContent = response2.services[i].service;
                  serviceSelect.appendChild(serviceOption);
                }
              }
            };
            xhr2.send('department=' + selectedDepartmentId);
          });
        }
      };
      xhr.send('direction=' + selectedDirectionId);
    });
  </script>
      <div class="champ">
        <label for="date_debut">Date de début <span>* &nbsp;</span>:</label>
        <input type="date" name="date_debut" id="date_debut" required>
      </div>
      <div class="champ">
        <label for="date_fin">Date de fin <span>* &nbsp;</span>:</label>
        <input type="date" name="date_fin" id="date_fin" required>
      </div>
      <div class="champ">
        <label for="sujet">Sujet du stage :</label>
        <input type="text" name="sujet" id="sujet" >
      </div>
      <div class="champ">
        <label for="technologies">Technologies utilisées :</label>
        <input type="text"  name="technologies" id="technologies" >
      </div>
      <div class="champ">
        <input type="submit" id="Ajouter" name="Ajouter" value="Ajouter">
      </div>
    </form>
</div>

<button id="show_form_btn" style="width: 200px; background-color: #222; color: #9d9d9d; font-weight: 200;" class="btn" onclick="showForm()">Affiche la Formulaire</button>

<!-- Affichage de la formilaire -->
<script>
function showForm() {
  var form = document.querySelector('.stagaire_form');
  form.style.display = 'block';
  var button = document.querySelector('.btn');
  button.style.display = 'none';
}
</script>

      </div>
      
    </section>

  </body>
</html>