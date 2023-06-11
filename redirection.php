<?php
session_start();
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
$utilisateur = 1;
if(isset($_SESSION['id'])){
    $utilisateur = $_SESSION['id'];
}
if (isset($_GET['id_profil'])) {
    $id = $_GET['id_profil'];
}
if (isset($_GET['id_tweet'])) {
    $id_tweet = $_GET['id_tweet'];
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
}
if (isset($_POST['valider'])) {
    $tweet = $_POST['tweet_content'];
    $url = $_GET['url'];

    if ($tweet != "") {
        $tweet = str_replace("'", "\\'", $tweet);
        $sql = "INSERT INTO tweet (id_user, message) VALUES ('$utilisateur', '$tweet')";
        // On prépare la requête
        $query = $conn->prepare($sql);
        // On exécute
        $query->execute();
        unset($_POST['valider']);
        foreach ($_POST as $key => $value) {
            unset($_POST);
        }
        if ($url != "profil.php") {
            header("Location: $url");
        } else {
            header("Location: $url" . "?id_profil=" . $id);
        }
    }
}
// --------------------------------------------------------------------


// Boucle qui permet de liker dans la db si le bouton like a été pressé et qui vide les données accumulés dans le $_POST juste apres
if (isset($_POST['like'])) {
    $url = $_GET['url'];
    $sql = "INSERT INTO likes(id_tweet, id_user) VALUES ('$id_tweet','$utilisateur')";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute();
    unset($_POST['like']);

    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }
}
// --------------------------------------------------------------------


// Boucle qui permet de unliker dans la db si le bouton unlike a été pressé et qui vide les données accumulés dans le $_POST juste apres
if (isset($_POST['unlike'])) {
    $url = $_GET['url'];

    $sql = "DELETE FROM likes where id_tweet=$id_tweet and id_user=$utilisateur";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute();
    unset($_POST['unlike']);

    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }
}
// --------------------------------------------------------------------


// Boucle qui permet de RT dans la db si le bouton RT a été pressé et qui vide les données accumulés dans le $_POST juste apres
if (isset($_POST['retweet'])) {
    $id_tweet = $_GET['id_tweet'];
    $url = $_GET['url'];

    $sql = "SELECT message,id,id_retweet FROM tweet where id=$id_tweet";
    $query = $conn->prepare($sql);
    $query->execute();
    $result = $query->fetch();
    if ($result['id_retweet'] == "") {
        $idtweet = $result['id'];
    } else { ?>
        <?php
        $idtweet = $result['id_retweet'];
    }
    $message = $result['message'];
    $message = str_replace("'", "\\'", $message);

    $sql = "INSERT INTO tweet(id_retweet,id_user,message) VALUES ('$idtweet','$utilisateur','$message')";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute();
    unset($_POST['retweet']);

    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }
}
// --------------------------------------------------------------------


// Boucle qui permet de unRT dans la db si le bouton unrt a été pressé et qui vide les données accumulés dans le $_POST juste apres
if (isset($_POST['unretweet'])) {

    $url = $_GET['url'];
    $sql = "DELETE FROM tweet where id_retweet=$id_tweet and id_user=$utilisateur";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute();
    unset($_POST['unretweet']);

    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }
}
// --------------------------------------------------------------------


// Boucle qui permet de unRT dans la db si le bouton unrt a été pressé et qui vide les données accumulés dans le $_POST juste apres
if (isset($_POST['unretweetrt'])) {

    $url = $_GET['url'];

    $sql = "DELETE FROM tweet where id=$id_tweet and id_user=$utilisateur";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute();
    unset($_POST['unretweet']);

    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }
}
// --------------------------------------------------------------------


// Boucle qui permet de unliker dans la db si le bouton follow a été pressé et qui vide les données accumulées dans le $_POST juste après
if (isset($_POST['suivre']) && $id) {
    $url = $_GET['url'];

    // Premiere requete qui va ajouter l'id de l'user en ligne dans la liste des abonnés de l'utilisateur qui se fait suivre
    $tata = "SELECT id_follower FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    $fin = $oui->fetch();
    $liste_abonnés = $fin['id_follower'];
    if ($liste_abonnés == "") {
        $liste_abonnés = "$utilisateur";
    } else {
        $liste_abonnés = $liste_abonnés . ",$utilisateur";
    }
    $tata = "UPDATE users SET id_follower = '$liste_abonnés' WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    // --------------------------------------------------------------------

    // Deuxieme requete qui va ajouter l'id de l'utilisateur qui se fait suivre dans la liste des abonnements de l'user en ligne
    $tata = "SELECT id_following FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => 1]);
    $fin = $oui->fetch();
    $liste_abonnement = $fin['id_following'];
    if ($liste_abonnement == "") {
        $liste_abonnement = "$id";
    } else {
        $liste_abonnement = $liste_abonnement . ",$id";
    }
    $tata = "UPDATE users SET id_following = '$liste_abonnement' WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $utilisateur]);

    unset($_POST['suivre']);
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }

}
// --------------------------------------------------------------------

// Boucle qui permet de unfollow dans la db si le bouton follow a été pressé et qui vide les données accumulées dans le $_POST juste après
if (isset($_POST['unfollow']) && $id) {
    $url = $_GET['url'];

    // Premiere requete qui va enlever l'id de l'user en ligne dans la liste des abonnés de l'utilisateur qui se fait suivre
    $tata = "SELECT id_follower FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    $fin = $oui->fetch();
    $liste_abonnés = $fin['id_follower'];
    if (strlen($liste_abonnés) == 1) {
        $liste_abonnés = "";
    } else {
        $liste_abonnés = str_replace(",$utilisateur", "", $liste_abonnés);
    }
    $tata = "UPDATE users SET id_follower = '$liste_abonnés' WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    // --------------------------------------------------------------------

    // Deuxieme requete qui va ajouter l'id de l'utilisateur qui se fait suivre dans la liste des abonnements de l'user en ligne
    $tata = "SELECT id_following FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $utilisateur]);
    $fin = $oui->fetch();
    $liste_abonnement = $fin['id_following'];
    // echo $liste_abonnement;
    if (strlen($liste_abonnement) == 1) {
        $liste_abonnement = "";
    } else {
        $liste_abonnement = str_replace(",$id", "", $liste_abonnement);
    }
    $tata = "UPDATE users SET id_following = '$liste_abonnement' WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $utilisateur]);

    unset($_POST['unfollow']);
    if ($url != "profil.php") {
        header("Location: $url");
    } else {
        header("Location: $url" . "?id_profil=" . $id);
    }

}
// --------------------------------------------------------------------


if (isset($_POST['reponse'])) {
    $id_tweet = $_GET['id_tweet'];
    $tweet = $_POST['tweet_content'];
    $url = $_GET['url'];

    if ($tweet != "") {
        $tweet = str_replace("'", "\\'", $tweet);
        $sql = "INSERT INTO tweet (id_user, message,id_reply_tweet) VALUES ('$utilisateur', '$tweet',$id_tweet)";
        // On prépare la requête
        $query = $conn->prepare($sql);
        // On exécute
        $query->execute();
        unset($_POST['reponse']);
        foreach ($_POST as $key => $value) {
            unset($_POST);
        }
        if ($url != "profil.php") {
            header("Location: $url");
        } else {
            header("Location: $url" . "?id_profil=" . $id);
        }
    }
}

//---------------------------------------------------------------

if (isset($_POST['save'])) {

    $url = $_GET['url'];

    $banner = $_POST['banner'];
    $avatar = $_POST['avatar'];
    $username = $_POST['username'];
    $bio = $_POST['bio'];
    $city = $_POST['city'];
    $birthdate = $_POST['birthdate'];
    $sql = "UPDATE users SET banner='$banner', avatar='$avatar', username='$username',bio='$bio',city='$city',birthdate='$birthdate' WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->execute(['id' => $id]);
    foreach ($_POST as $key => $value) {
        unset($_POST);
    }
    header("Location: $url" . "?id_profil=" . $id);
}