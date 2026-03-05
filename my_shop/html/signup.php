<?php
session_start();
require('data.php');
$dbb = DNS::getInstance();
/*try{
        $dbb = new PDO('mysql:host=localhost;dbname=my_shop', 'root', 'root');
        $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e){
        echo $e->getMessage();
    }*/
$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] ==='POST'){
    $name = htmlentities(trim($_POST['pseudo']));
    $email = trim($_POST['email']);
    $mdp = $_POST['mdp'];
    $con_mdp = $_POST['mdpc'];

    if (empty($name)){
        $erreurs['nom'] = "nom invalide";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs['email'] = "Invalide email";
    }

    if ((strlen($mdp)) < 8){
        $erreurs['mdp'] = "le mot de passe doit contenir au moins 8 carracteres";
    }elseif($mdp !== $con_mdp){
        $erreurs['mdp'] = "invalide mopt de passe";
    }
   
    if (count($erreurs) < 1){
        $hash = password_hash($mdp, PASSWORD_BCRYPT);
        $recup = $dbb->prepare('SELECT * FROM users WHERE username=? OR email=?');
        $recup->execute(array($name, $email));
        if ($recup->rowCount() > 0) {
            $util = "Ce utilisateur existe deja ! veiller ressayer";
        } else {
            $insert = $dbb->prepare('INSERT INTO users(username, email, password, admin) VALUE(?, ?, ?, ?)');
            $insert->execute(array($name, $email, $hash, true));

            $recupe = $dbb->prepare('SELECT * FROM users WHERE username=?');
            $recupe->execute(array($name));
            $user = $recupe->fetch();

            $_SESSION['pseudo'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            header('location: index.php');
            exit;

        }   
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My_shop</title>
    <link rel="stylesheet" href="../css/sig.css">
</head>
<body>
    <section class="signup">
        <div class="left">
            <h1><span>I</span>nscription</h1>
            <img src="../image/images.png" alt="signup">
        </div>

        <div class="right">
            <?php if (isset($util)): ?>
                <div class="error-message">
                <p>
                <?php   
                echo $util;
                ?>
                </p>
                </div>
            <?php endif; ?>
            <?php if (count($erreurs) > 0): ?>
                <div class="error-message">
                <p>
                <?php   
                echo $util;
               foreach ($erreurs as $val => $keys){
                    echo $keys . ".<br>";
                }
                ?>
                </p>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="formele">
                    <label for="nom">Nom</label>
                    <br>
                    <input type="text" name="pseudo" placeholder="nom" required>
                </div>
                <div class="formele">
                    <label for="email">Email</label>
                    <br>
                    <input type="email" name="email" placeholder="email" required>
                </div>
                <div class="formele">
                    <label for="mdp">Mot de passe</label>
                    <br>
                    <input type="password" name="mdp" placeholder="mot de passe" required>
                </div>
                <div class="formele">
                    <label for="mdpc">Confirmation</label>
                    <br>
                    <input type="password" name="mdpc" placeholder="mot de passe" required>
                </div>
                <button type="submit" class="bouton">S'inscrire</button>
                <h5 class="dire">Déja un compte? <a href="signin.php">Se connecter</a></h5>
            </form>
        </div>
    </section>
</body>
</html>