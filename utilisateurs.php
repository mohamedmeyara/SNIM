<?php
session_start();
if (!isset($_SESSION['usertype'])) {
  header("Location: index.php");
session_destroy();
  exit();
}
$usertype = $_SESSION["usertype"];
if($usertype=="user"){
  header("location: index.php");
}
include 'conn.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Gestion des stagiaires">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="icon/icon.ico">
  <title>SNIM - Utilisateurs</title>
  <style>
      @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@300;400&display=swap');  

    body {
      font-family: Arial, sans-serif;
    }
    h2{
      margin-top: 100px;
    }
    .form-container {
      max-width: 400px;
      margin: 10px auto;
      background-color: #ffffff;
      border: 1px solid #dddddd;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
      color: #333333;
    }

    .form-container input[type="text"],
    .form-container input[type="password"],
    .form-container select {
      width: 100%;
      padding: 10px;
      border: 1px solid #dddddd;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 20px;
      font-size: 1.2rem;
      text-align: center;
      outline: none;
    }

    .form-container input[type="submit"] {
      background-color: #222;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    .form-container input[type="submit"]:hover {
      background-color: #000;
    }
  </style>
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
          <li><a href="stagiaires_en_cours.php"><div class="specific"><input type="submit" value="Utilisateurs"></a></div></li>
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
      <h2>Ajouter un nouvel utilisateur</h2>
      <div class="form-container">
    <form action="" method="post">
      <label for="username">Nom d'utilisateur:</label>
      <input type="text" id="username" name="utilisateur" required>

      <label for="password">Mot de pass:</label>
      <input type="password" id="password" name="mdp" required>

      <label for="user-type">Role :</label>
      <select id="user-type" name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <input type="submit" name="save" value="Ajouter l'utilisateur">
    </form>
  </div>
  </section>
</body>
</html>
<?php
  if (isset($_POST['save'])){
    $username = filter_var($_POST['utilisateur'], FILTER_SANITIZE_STRING);
    $mdp = SHA1(filter_var($_POST['mdp'], FILTER_SANITIZE_NUMBER_INT));
    $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);  
  $sql = "INSERT INTO utilisateurs (utilisateur, mdp, role)
          VALUES ('$username','$mdp', '$role')";
  
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('L\'utilisateur est ajouter');</script>";
    echo "<script>window.location.href = 'utilisateurs.php'</script>";
  
  } else {
    echo "<script>alert('Errer');</script>";
  
  }
  
  }
?>

