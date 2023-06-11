<?php
require_once("header.php");
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
$utilisateur=1;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
}
$id = $_GET['id_profil'];
$tata = "SELECT id_follower FROM users WHERE id = :id";
$oui = $conn->prepare($tata);
$oui->execute(['id' => $id]);
$fin = $oui->fetch();
$nombre_abonné=$fin['id_follower'];
$liste=explode(",",$nombre_abonné);
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


?>
<html>

<body>

        
    <div class="col-6">
    <div class="mid" id="sticky">
            <p class="profmid"><?= $fin['username'] ?></p>
            <p class="indice" id="nbtweetprof"><?= $nombre_abonné ?> Abonnés</p>
        </div>
    <?php

    foreach ($liste as $key => $value) {
        $tata = "SELECT username,name,bio,id_follower,id_following,avatar FROM users WHERE id = :id";
        $oui = $conn->prepare($tata);
        $oui->execute(['id' => $value]);
        $fin = $oui->fetch();
?>

        <div class="d-flex justify-content-between w-100">
            <div class="d-flex">
                <img class="rounded-circle bg-light mt-1" src="<?= $fin['avatar'] ?>" alt="" width="50px" height="50px">
                <div class ="d-block">
                <p><?= $fin['name'] ?>
                <p>@<?= $fin['username'] ?></p>
                <p><?= $fin['bio'] ?></p></div>
 
            </div>
            <div class="buttonlog mt-3" data-bs-toggle="modal" data-bs-target="#myModalIns">
                <button class="btnfollowers">Suivre</button><i class="fa-solid fa-ellipsis"></i>
            </div>
        </div>

        <?php }
        
        ?>
    </div>
        
    <div class="col-3">
        <div class="rightside">

            <div class="trending" id="stickyx">
                <input type="text" class="" placeholder="Recherche DeadBird"></input>
            </div>

            <div class="new">

                <h5>Nouveau sur Deadbird ?</h5>
                <p class="px-4">Inscrivez-vous pour profiter de votre propre fil personnalisé !</p>
                <div class="text-center mx-4">
                    <div class="buttonlog mx-auto mt-3" data-bs-toggle="modal" data-bs-target="#myModalIns">
                        <button class="">Inscription</button>
                    </div>
                    <div class="buttonlog mx-auto mt-3" data-bs-toggle="modal" data-bs-target="#myLoginModal">
                        <button>Connexion</button>
                    </div>
                </div>

            </div>

            <div class="pourvous mt-3">
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
</body>

</html> 