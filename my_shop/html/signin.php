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
if ($_SERVER['REQUEST_METHOD'] ==='POST'){
    $email = htmlentities(trim($_POST['email']));
    $mdp = $_POST['mdp'];
    $recup = $dbb->prepare('SELECT * FROM users WHERE email=?');
    $recup->execute(array($email));
    $user = $recup->fetch();
    if ($user && password_verify($mdp, $user['password'])) {
        $_SESSION['pseudo'] = $user['username'];
        $_SESSION['id'] = $user['id'];
        header('location: index.php');
        exit;
    } else {
        $id = "Identifiant incorrecte";
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
            <h1><span>C</span>onnexion</h1>
            <img src="../image/image.jpeg" alt="signup">
        </div>

        <div class="right">
            <form action="" method="POST">
                <div class="formele">
                <?php if (isset($id)): ?>
                <div class="error-message">
                <p>
                <?php   
                echo $id;
                ?>
                </p>
                </div>
            <?php endif; ?>
                    <label for="email">Email</label>
                    <br>
                    <input type="email" name="email" placeholder="email" required>
                </div>
                <div class="formele">
                    <label for="mdp">Mot de passe</label>
                    <br>
                    <input type="password" name="mdp" placeholder="mot de passe" required>
                </div>
                <button type="submit" class="bouton">S'inscrire</button>
                <h5 class="dire">Pas encore de compte? <a href="signup.php">S'inscrire</a></h5>
            </form>
        </div>
    </section>
</body>
</html>