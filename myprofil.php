<?php


// Fonction qui permet de convertir les mois de la db en jour/mois/annnées
function date_en_lettres($date)
{
    $mois = array(
        'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
    );
    $jour = date('j', strtotime($date));
    $mois = $mois[date('n', strtotime($date)) - 1];
    $annee = date('Y', strtotime($date));
    return "$jour $mois $annee";
}
// --------------------------------------------------------------------

require_once("header.php");
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
$id = $_GET['id'];
if (isset($_GET['id2'])) {
    $id2 = $_GET['id2'];
}
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Boucle qui permet de liker dans la db si le bouton like a été pressé et qui vide les données accumulées dans le $_POST juste après
if (isset($_POST['like']) && $id2) {
    $sql = "INSERT INTO likes (id_tweet, id_user) VALUES (:idtweet, :iduser)";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute(['idtweet' => $id2, 'iduser' => 1]);
    unset($_POST['like']);
    header("refresh:0");

}
// --------------------------------------------------------------------


// Boucle qui permet de unliker dans la db si le bouton unlike a été pressé et qui vide les données accumulées dans le $_POST juste après
if (isset($_POST['unlike']) && $id2) {
    $sql = "DELETE FROM likes WHERE id_tweet = :idtweet AND id_user = :iduser";
    // On prépare la requête
    $query = $conn->prepare($sql);
    // On exécute
    $query->execute(['idtweet' => $id2, 'iduser' => 1]);
    unset($_POST['unlike']);
    header("refresh:0");

}
// --------------------------------------------------------------------

// Boucle qui permet de unliker dans la db si le bouton follow a été pressé et qui vide les données accumulées dans le $_POST juste après
if (isset($_POST['suivre']) && $id) {
    // Premiere requete qui va ajouter l'id de l'user en ligne dans la liste des abonnés de l'utilisateur qui se fait suivre
    $tata = "SELECT id_follower FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    $fin = $oui->fetch();
    $liste_abonnés=$fin['id_follower'];
    if ($liste_abonnés=="")
    {
        $liste_abonnés="1";
    }
    else
    {
        $liste_abonnés=$liste_abonnés.",1";
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
    $liste_abonnement=$fin['id_following'];
    if ($liste_abonnement=="")
    {
        $liste_abonnement="1";
    }
    else{
    $liste_abonnement=$liste_abonnement.",1";
    }
    $tata = "UPDATE users SET id_following = '$liste_abonnement' WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => 1]);
    
    unset($_POST['suivre']);
    header("refresh:0");


}
// --------------------------------------------------------------------

// Boucle qui permet de unfollow dans la db si le bouton follow a été pressé et qui vide les données accumulées dans le $_POST juste après
if (isset($_POST['unfollow']) && $id) {
    // Premiere requete qui va enlever l'id de l'user en ligne dans la liste des abonnés de l'utilisateur qui se fait suivre
    $tata = "SELECT id_follower FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    $fin = $oui->fetch();
    $liste_abonnés=$fin['id_follower'];
    if (strlen($liste_abonnés)==1)
    {
        $liste_abonnés="";
    }
    else{
    $liste_abonnés=str_replace(",1", "",$liste_abonnés);
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
    $liste_abonnement=$fin['id_following'];
    // echo $liste_abonnement;
    if (strlen($liste_abonnement)==1)
    {
        $liste_abonnement="";
    }
    else{
    $liste_abonnement=str_replace(",1", "",$liste_abonnement);
    }
    $tata = "UPDATE users SET id_following = '$liste_abonnement' WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => 1]);
    
    unset($_POST['unfollow']);
    header("refresh:0");


}
// --------------------------------------------------------------------




// requete qui va recuperer toutes les infos pour afficher le profil comme il faut
$sql = "SELECT users.name, username, message, avatar, date_send, tweet.id AS lid, id_reply_tweet, bio, city, register_date, banner 
FROM tweet 
LEFT JOIN users ON tweet.id_user = users.id 
WHERE users.id = :id 
ORDER BY date_send DESC";
$query = $conn->prepare($sql);
$query->execute(['id' => $id]);
$result = $query->fetch();
// --------------------------------------------------------------------
// requete qui va compter le nombre de tweet pour l'afficher en haut de la page
$tata = "SELECT COUNT(message) AS test FROM tweet WHERE id_user = :id";
$oui = $conn->prepare($tata);
$oui->execute(['id' => $id]);
$nombre = $oui->fetch();
// Deux petits if qui mettent les images par défaut s'il n'y en a pas dans la base de données
if ($result['avatar'] == "") {
    $result['avatar'] = "https://tinyurl.com/4umrunyw";
}
if ($result['banner'] == "") {
    $result['banner'] = "https://tinyurl.com/2t8a2sst";
}







?>
<div class="col-6">

    <div class="mid" id="sticky">
        <p class="profmid"><?= $result['username'] ?></p>
        <p class="indice" id="nbtweetprof"><?= $nombre["test"] ?> Tweets</p>
    </div>
    <div class="banniere">
        <img src="<?= $result['banner'] ?>" alt="">
    </div>
    <div class="col-2 ppprofil">
        <img class="rounded-circle bg-light mt-1 resize" src=<?= $result['avatar'] ?> alt="" width="130px" height="130px">
    </div>

    <div class="pid">
        <p class="pseudo"><?= $result['name'] ?></p>
        <p class="arobase indice">@<?= $result['username'] ?></p>
        <p class="bio"><?= $result['bio'] ?></p>
        <div class="d-flex p-18">
            <p class="joined"><i class="fa-solid fa-location-dot"></i> <?= $result['city'] ?></p>

            <?php
            // Récupérer la date depuis la base de données SQL
            $date = $result['register_date'];

            // Convertit la date au format "mm/yyyy" avec le mois en lettres en français
            $mois = array('', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
            $date_fr = $mois[date('n', strtotime($date))] . " " . date('Y', strtotime($date));
            ?>
            <p class="joined"><i class="fa-solid fa-calendar-days"></i> A rejoint DeadBird en <?= $date_fr ?></p>
        </div>

        <?php
        // Requete et petit algo pour compter le nombre de personne que l'user suit
        $tata = "SELECT id_following FROM users WHERE id = :id";
        $oui = $conn->prepare($tata);
        $oui->execute(['id' => $id]);
        $fin = $oui->fetch();
        $nombre_abonnement=$fin['id_following'];

        if ($nombre_abonnement=="")
        {
            $nombre_abonnement=0;
        }
        else
        {
            $nombre_abonnement=explode(",",$nombre_abonnement);
            $nombre_abonnement= count($nombre_abonnement);
        }
// --------------------------------------------------------------------
        // Requete et petit algo pour compter le nombre de personne qui suivent l'user
        $tata = "SELECT id_follower FROM users WHERE id = :id";
        $oui = $conn->prepare($tata);
        $oui->execute(['id' => $id]);
        $fin = $oui->fetch();
        $nombre_abonné=$fin['id_follower'];
        if ($nombre_abonné=="")
        {
            $nombre_abonné=0;
        }
        else
        {
            $nombre_abonné=explode(",",$nombre_abonné);
            $nombre_abonné= count($nombre_abonné);
        }
// --------------------------------------------------------------------
?>
        <div class="follower">
        <p><?= $nombre_abonnement ?> abonnements</p>
        <p><?= $nombre_abonné ?>  abonnés</p>
        </div>
<!-- Petit algo pour afficher soit le bouton suivre soit le bouton désabonner -->
<?php
        $tata = "SELECT id_follower FROM users WHERE id = :id";
    $oui = $conn->prepare($tata);
    $oui->execute(['id' => $id]);
    $fin = $oui->fetch();
    $liste_abonnement=$fin['id_follower'];
    if(str_contains($liste_abonnement,"1"))
    { ?>
        <div class="suivre">
            <form method="post" action="profil.php?id=<?= $id?>">
                <div class="col">
                    <button type="submit" name="unfollow">Unfollow</button>
                </div>
            </form>
        </div>

    <?php }
    else
    { ?>
        <div class="suivre">
            <form method="post" action="profil.php?id=<?= $id?>">
                <div class="col">
                    <button type="submit" name="suivre">Follow</button>
                </div>
            </form>
        </div>

<?php }

?>

        <ul>
            <li class="activetw">Tweets</li>
            <li>Tweets et réponses</li>
            <li>Médias</li>
            <li>J'aime</li>
        </ul>
        <div class="borderx"></div>
    </div>
    <?php
// requete qui va recuperer toutes les infos pour afficher le tweet comme il faut
$sql =  "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user FROM tweet left join users on tweet.id_user=users.id where id_reply_tweet is null and users.id=:id order by date_send desc";
$query = $conn->prepare($sql);
$query->execute(['id' => $id]);
$result = $query->fetchAll();
// --------------------------------------------------------------------


foreach ($result as $key => $value)
{
    if ($value['avatar']==""){
        $value['avatar']="https://tinyurl.com/4umrunyw";
    }
    // Ici je convertis la date du tweet envoyé en differente versions en fonction du temps écoulé (5s,5h,hier,02janvier 2023)
    $date_bd = $value['date_send'];
    $timestamp = strtotime($date_bd) + 3600; 
    $date_bd = date("Y-m-d H:i:s", $timestamp);
    $date_actuelle = date('Y-m-d H:i:s');
    $diff = strtotime($date_actuelle) - strtotime($date_bd);
    $secondes = $diff;
    $minutes = floor($diff / 60);
    $heures = floor($diff / 3600);
    $jours = floor($diff / 86400);
// --------------------------------------------------------------------

    ?>
    
    <div class="champtweet" id="tweetprofile">
        <div class="row">
            <div class="col-1">
            <a href="profil.php?id=<?= $value['id_user'] ?>"><img class="rounded-circle bg-light mt-1" src=<?= $value['avatar'] ?> alt="" width="50px" height="50px"></a>
            </div>
            <div class="col-10">
                <div class="d-flex">
                <a href="profil.php?id=<?= $value['id_user'] ?>"><p class="pseudo"><?= $value['name'] ?></p></a>
                <a href="profil.php?id=<?= $value['id_user'] ?>"><p class="arobase indice">@<?= $value['username'] ?></p></a>
                <?php 
                // Ici j'ai un arbre de if qui affiche la date du tweet envoyé en fonction du temps écouté
                if ($jours > 1) {?>
                    <p class="temporis indice">· <?=date_en_lettres($date_bd)?></p>
                    <?php
                } elseif ($jours == 1) {
                    ?>
                    <p class="temporis indice">· hier</p>
                    <?php
                } elseif ($heures > 0) {
                    ?>
                    <p class="temporis indice">· <?=$heures?>h</p>
                    <?php
                } elseif ($minutes > 0) {
                    ?>
                    <p class="temporis indice">· <?=$minutes?> min</p>
                    <?php
                } else {
                    ?>
                    <p class="temporis indice">· <?=$secondes?>s</p>
                    <?php    }
                    // --------------------------------------------------------------------

                    ?>
                    
</div>
                <p> <?= $value['message'] ?></p>
                <div class="inter">
                    <a href=""><i id="comment" class="fa-regular fa-comment"> 12</i></a>
                    <?php
                    $nbrlike=$value['lid'];
                    // Requete pour savoir combien de retweet a chaque tweet et l'afficher a coté de l'icone tete de mort
                    $sqls =  "SELECT COUNT(id_retweet) as test FROM tweet WHERE id = $nbrlike";
                    $querys = $conn->prepare($sqls);
                    $querys->execute();
                    $resultat = $querys->fetch();
                    // --------------------------------------------------------------------
                    ?>
                    <a href=""><i id="deathtwett" class="fa-solid fa-skull"> <?= $resultat['test'] ?></i></a>
                    <?php
                    // Requete pour savoir combien de like a chaque tweet et l'afficher a coté de l'icone coeur
                    $sqls =  "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $nbrlike";
                    $querys = $conn->prepare($sqls);
                    $querys->execute();
                    $resultat = $querys->fetch();
                    // --------------------------------------------------------------------

                    // Ici je fais une requete pour savoir si le tweet a déjà été liké par l'utilisateur en ligne ou pas
                    $sqls =  "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $nbrlike and id_user=1";
                    $querys = $conn->prepare($sqls);
                    $querys->execute();
                    $islike = $querys->fetch();
                    // Ici je fais un arbre de if qui permet d'afficher le bouton like si le tweet n'a pas été liké ou le bouton unlike si le tweet a déja été liké
                    if ($islike['test']==0){
                    ?>
                <form method="post" action="profil.php?id=<?= $value['id_user']?>&id2=<?= $value['lid'] ?>">
                    <button type="submit" name="like"><i id="like" class="fa-solid fa-heart-pulse"> <?= $resultat['test'] ?></i></button>
                </form>
                <?php }
                    else{ ?>
                <form method="post" action="profil.php?id=<?= $value['id_user']?>&id2=<?= $value['lid'] ?>">
                        <button type="submit" name="unlike"><i id="unlike" class="fa-solid fa-heart-pulse"> <?= $resultat['test'] ?></i></button>
                    </form>
                    <?php }
                    // --------------------------------------------------------------------

                ?>
                    <a href=""><i id="share" class="fa-solid fa-arrow-up-from-bracket"></i></a>
                </div>
            </div>
        </div>
    </div>

<?php

}

?>




</div>

<div class="col-3">
    <div class="rightside">
        <div class="trending" id="stickyx">
            <input type="text" class="" placeholder="Recherche DeadBird"></input>
        </div>
        <div class="lastimg pourvous text-danger">
            <div class="d-flex">
                <img src="https://i0.wp.com/marvelousgeeksmedia.com/wp-content/uploads/2022/10/ExTlXx9XMAQ6MLD.jpeg?ssl=1" alt="" id="hautg" width="111px" height="111px">
                <img src="https://i0.wp.com/marvelousgeeksmedia.com/wp-content/uploads/2022/10/ExTlXx9XMAQ6MLD.jpeg?ssl=1" alt="" class="" width="111px" height="111px">
                <img src="https://i0.wp.com/marvelousgeeksmedia.com/wp-content/uploads/2022/10/ExTlXx9XMAQ6MLD.jpeg?ssl=1" alt="" id="hautd" width="111px" height="111px">
            </div>
            <div class="d-flex">
                <img src="https://i0.wp.com/marvelousgeeksmedia.com/wp-content/uploads/2022/10/ExTlXx9XMAQ6MLD.jpeg?ssl=1" alt="" id="basg" width="111px" height="111px">
                <img src="https://i0.wp.com/marvelousgeeksmedia.com/wp-content/uploads/2022/10/ExTlXx9XMAQ6MLD.jpeg?ssl=1" alt="" width="111px" height="111px">
                <img src="https://i0.wp.com/marvelousgeeksmedia.com/wp-content/uploads/2022/10/ExTlXx9XMAQ6MLD.jpeg?ssl=1" alt="" id="basd" width="111px" height="111px">
            </div>
        </div>
        <div class="pourvous">
            <h5>Vous pourriez aimer</h5>
            <p class="text-danger">3 utilisateurs (random?)</p>
        </div>
        <div class="pourvous">
            <h5>Tendances pour vous</h5>
            <div class="toptendance">
                <p class="indice">Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>#OnePiece</p>
                <p class="indice">9M Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Gaming Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>#KCORP</p>
                <p class="indice">69M Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>Palestine</p>
                <p class="indice">420M Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances Malade-Mental<i class="fa-solid fa-ellipsis"></i></p>
                <p>Pierre Palmade</p>
                <p class="indice">0 Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>Pokémon</p>
                <p class="indice">467 k Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>Shlaquette</p>
                <p class="indice">13 M Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>Mathias Fernandes</p>
                <p class="indice">3 Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances Musique<i class="fa-solid fa-ellipsis"></i></p>
                <p>Hamza</p>
                <p class="indice">1 B Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances Gaming<i class="fa-solid fa-ellipsis"></i></p>
                <p>Hideo Kojima</p>
                <p class="indice">9 B Tweets</p>
            </div>
            <div class="toptendance">
                <p class="indice">Tendances<i class="fa-solid fa-ellipsis"></i></p>
                <p>Jason Momoa</p>
                <p class="indice"> 2x∞ Tweets</p>
            </div>

        </div>
    </div>
</div>