<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>SNIM - Login</title>
    <link rel="icon" href="icon/icon.ico">
    <link rel="stylesheet" href="css/index.css">

  </head>
  <body>
    <div class="container">
      <form method="post">
        <img src="photos/snim.png" alt="">
        <h1>Login</h1>
        <label for="utilisateur"><b>Nom d'utilisateur</b></label>
        <input type="text" autocomplete="off" autofocus placeholder="Saisissez votre nom d'utilisateur" name="utilisateur" required>
        <label for="mdp"><b>Mot de passe</b></label>
        <input type="password" placeholder="Entrer le mot de passe" name="mdp" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </body>
</html>
<?php
include 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = mysqli_real_escape_string($conn, $_POST['utilisateur']);
  $password = SHA1(mysqli_real_escape_string($conn, $_POST['mdp']));

  $query = "SELECT * FROM utilisateurs WHERE utilisateur = '$username' AND mdp = '$password' LIMIT 1";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION["usertype"] = $user['role'];
    if (empty($_SESSION["usertype"])) {
      header("Location: login.php");
      exit();
    }
    header("Location: dashboard.php");
    exit();

  } else {
    echo "<script>alert('Nom d\'utilisateur ou mot de passe et incorrect')</script>";
    echo "<script>window.location.href = 'index.php'</script>";
  }
}
?>
