<?php
require('data.php');
$dbb = DNS::getInstance();
/*try{
        $dbb = new PDO('mysql:host=localhost;dbname=my_shop', 'root', 'root');
        $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e){
        echo $e->getMessage();
    }*/
if ($_SERVER['REQUEST_METHOD'] ==='POST'){
    $name = htmlentities($_POST['name']);
    $describ = htmlentities($_POST['describ']);
    $price = intval($_POST['price']);

    if (!file_exists('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // Récupère l'extension de l'image (.jpg, .png...)
    $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

    // Génère un nom unique
    $nom = uniqid() . '.' . $extension;
    // Exemple : 64f3a2b1c9d12.jpg

    $destination = 'uploads/' . $nom;

    move_uploaded_file($_FILES['img']['tmp_name'], $destination);

    $stmt = $dbb->prepare("INSERT INTO produits(name, price, picture, description) VALUES (?)");
    $stmt->execute(array($name, $price, $destination, $describ));

    

}

?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="name">
        <input type="text" name="describ">
        <input type="number" name="price">
        <input type="file" name="img" accept="image/*">
        <button type="submit">envoyer</button>
    </form>
</body>
</html>