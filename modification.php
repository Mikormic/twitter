<?php
session_start();
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
$id = $_GET['id_profil'];
$utilisateur = $_SESSION['id'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


if (isset($_POST['save'])) {

    $banner=$_POST['banner'];
    $avatar=$_POST['avatar'];
    $username=$_POST['username'];
    $bio=$_POST['bio'];
    $city=$_POST['city'];
    $birthdate=$_POST['birthdate'];
    $sql = "UPDATE users SET banner='$banner', avatar='$avatar', username='$username',bio='$bio',city='$city',birthdate='$birthdate' WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->execute(['id' => $id]);
    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
}

// requete qui va recuperer toutes les infos pour afficher le profil comme il faut
$sql = "SELECT username, avatar, bio, city, birthdate, banner 
FROM users 
WHERE users.id = :id";
$query = $conn->prepare($sql);
$query->execute(['id' => $id]);
$result = $query->fetch();


?>


<html>

<head>
    <link rel="stylesheet" href="modification.css">
</head>
<div class="triajation">
    <form method="post" action="modification.php?id_profil=<?= $id ?>">
        <p>Banière</p><input type="text" name="banner" id="" placeholder="url baniere" value="<?= $result['banner'] ?>">
        <p>Photo de profil</p><input type="text" name="avatar" id="" placeholder="urlpp" value="<?= $result['avatar'] ?>">

        <p>Pseudo</p><input type="text" name="username" id="" placeholder="tndumec" value="<?= $result['username'] ?>">

        <p>Biographie</p><input type="text" name="bio" id=""
            placeholder="Je pense sincèrement que Mehdi Z est l'un des plus grand acteur de cette génération"
            value="<?= $result['bio'] ?>">

        <p>Localisation</p><input type="text" name="city" id="" placeholder="" value="<?= $result['city'] ?>">

        <p>Date de naissance</p><input type="date" name="birthdate" id="" placeholder="" value="<?= $result['birthdate'] ?>">
        <button type="submit" name="save">Enregistrer</button>

    </form>
    <div><a href="profil.php?id_profil=<?= $id ?>">Retour Au profil</a></div>
</div>

</html>