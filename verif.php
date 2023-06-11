<?php
session_start();
$host = 'www.webacademie-project.tech';
							$dbname = 'twitter_academy_db';
							$username = 'wac209_user';
							$password = 'wac209';
							try {
								$bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
								$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							} catch (PDOException $e) {
							}
if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['cle']) AND !empty($_GET['id'])){
    $getid = $_GET['id'];
    $getcle = $_GET['cle'];
    $recupUser = $bdd->prepare('SELECT count(*) as oui FROM users WHERE id = ?');
    $recupUser->execute(array($getid));
    $resultat=$recupUser->fetch();
    if($resultat['oui'] > 0){
        $userInfo = $recupUser->fetch();
            $updateConfirmation = $bdd->prepare('UPDATE users SET email_verify = 1 WHERE id = :id');
            $updateConfirmation->execute(['id' => $getid]);
            $_SESSION['cle'] = $getcle;
            echo "<script>alert('La mise à jour est terminée. Veuillez fermer la fenêtre manuellement.'); window.opener = null; window.close();</script>";


    }
}
else{
    echo "Aucun utilisateur trouvé";
}
?>