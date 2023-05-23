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
  <title>SMIN - Mise a jour</title>
</head>
  <body>
    <nav>
      <aside>
        <img src="photos/snim.png" alt="Snim's Logo">
				<hr>
        <ul>
          <li><a href="dashboard.php"><input type="submit" value="Dashboard"></a></li>
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
        $stagiaire_code = $_GET['c'];
        include 'conn.php';
        $sql = "SELECT * FROM les_stagiaires where code='$stagiaire_code' ";
        $result = $conn->query($sql);
        ?>
    <div class="stagiaire">
    <div class='trainer-box'>
    <h2>Modifier le Stagiaire</h2>
    <form method="post">
      <table class="detail">
          <tr>
          <th>Nom Complete</th>
            <th>NNI</th>
            <th>École</th>
            <th>Niveau</th>
            <th>Direction</th>
            <th>Departement</th>
            <th>Service</th>
            <th>Date debut</th>
            <th>Date fin</th>
            <th>Sujet du stage</th>
            <th>Technologies utilisées</th>
          </tr>
          <tbody id="trainers">
        <?php
            while ($row = $result->fetch_assoc()) {
              echo "<tr class='modifier'>";?>
              <td class="value_modifier"><input type="text" name="Nouveau_nom" id="Nouveau_nom" value= "<?php echo $row["nom"]?>"></td>
              <td class="value_modifier"><input type="text" name="Nouveau_nni" id="Nouveau_nni" value= "<?php echo $row["nni"]?>"></td>
              <td class="value_modifier"><input type="text" name="Nouveau_ecole" id="Nouveau_school" value= "<?php echo $row["ecole"]?>"></td>
              <td class="value_modifier">
                <select name="Nouveau_niveau" id="Nouveau_niveau">
                <optgroup label="Niveau Current">
                <option value="<?php echo $row["niveau"]?>"><?php echo $row["niveau"]?></option>
                </optgroup>
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
              </select></td>
              <td class="value_modifier">
                <select name="direction" id="direction">
              <option value="<?php echo $row["direction"]?>"><?php echo $row["direction"]?></option>

                  <?php
                  //Ramplir la list from db
                  $current_dir= $row["direction"];
                  $sql_dir = "SELECT nom FROM directions WHERE nom!='$current_dir'";
                  $result_dir = mysqli_query($conn, $sql_dir);
                  while ($row1 = mysqli_fetch_assoc($result_dir)) {
                  echo '<option value="' . $row1['nom'] . '">' . $row1['nom'] . '</option>';
                  }
                  ?>
            </select>
              </td>
              <td class="value_modifier">
              <select id="department" name="department">
              <option value="<?php echo $row["departement"]?>"><?php echo $row["departement"]?></option>
              </select>
              </td>
              <td class="value_modifier">
              <select id="service" name="service">
                <option value="<?php echo $row["service"]?>"><?php echo $row["service"]?></option>
              </select>
              </td>
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
              <td class="value_modifier"><input type="text" name="Nouveau_date_debut" id="Nouveau_start_date" value= "<?php echo $row["date_debut"]?>"></td>
              <td class="value_modifier"><input type="text" name="Nouveau_date_fin" id="Nouveau_end_date" value= "<?php echo $row["date_fin"]?>"></td>
              <td class="value_modifier"><input type="text" name="Nouveau_sujet" id="Nouveau_sujet" value= "<?php echo $row["sujet"]?>"></td>
              <td class="value_modifier"><input type="text" name="Nouveau_technologies" id="Nouveau_technologies" value= "<?php echo $row["technologies"]?>"></td>
              <?php
          }?>
        </tr>
      </table>
      
      <div class="champ-edit">
        <input type="submit" id="Nouveau_data" name="Nouveau_data" value="Mise a jour">
      </div>
    </form>
    </div>

    </div>
    </div>
      <?php
            if(isset($_POST['Nouveau_data'])){
              $N_Nom = $_POST['Nouveau_nom'];
              $N_NNI = $_POST['Nouveau_nni'];
              $N_Ecole = $_POST['Nouveau_ecole'];
              $N_niveau = $_POST['Nouveau_niveau'];
              $N_dir = $_POST['direction'];
              $N_dep = $_POST['department'];
              $N_service = $_POST['service'];
              $N_Dd = $_POST['Nouveau_date_debut'];
              $N_Df = $_POST['Nouveau_date_fin'];
              $N_sujet = $_POST['Nouveau_sujet'];
              $N_Tech = $_POST['Nouveau_technologies'];
              $N_donnes = "UPDATE les_stagiaires SET nom='$N_Nom',nni='$N_NNI',ecole='$N_Ecole',niveau='$N_niveau',direction='$N_dir',departement='$N_dep',service='$N_service',date_debut='$N_Dd',date_fin='$N_Df',sujet='$N_sujet',technologies='$N_Tech' where code='$stagiaire_code' ";
              $miseajour = mysqli_query($conn , $N_donnes);
              echo "<script>alert('les données ont été mises à jour avec succès');</script>";
              echo "<script>window.location.href = 'stagiaire.php?c=$stagiaire_code&n=$N_Nom'</script>"; 
            }
      ?>