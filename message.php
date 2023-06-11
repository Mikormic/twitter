<?php
session_start();
require_once('header.php');
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
}
$me = $_SESSION['id'];
?>

<div class="col-3">

    <div class="" id="sticky">

        <p class="fw-bold">Messages</p>

        <div style="width: 208px;" class="trending mt-4">
            <input style="width: 232px;" type="text" class="" placeholder="Recherche DeadBird"></input>
        </div>

    </div>

    <div class="container-fluid">

        <div class="mid3">
            <?php
            $requp = $conn->prepare("SELECT * FROM users LIMIT 10");
            $requp->execute();
            if ($requp->rowCount() > 0) {
                $result = $requp->fetchAll();
                foreach ($result as $r) {
            ?>
                    <a href="" class="champmessage">

                        <div class="champmessage p-2">

                            <div class="row">
                                <div class="user" id="<?= $r['id'] ?>">
                                    <div class="col-3 text-center">

                                        <img class="rounded-circle bg-light mt-1" src="<?= $r['avatar'] ?> " alt="" width="50px" height="50px">

                                    </div>

                                    <div class="col-9 text-center">

                                        <div class="d-flex">

                                            <p class="pseudo fw-bold"><?= $r['name'] ?> </p>
                                            <p class="arobase indice"><?= $r['username'] ?> </p>

                                            <!-- <p class="temporis indice mx-3">25 min</p> -->

                                        </div>

                                        <div class="text-center">

                                        </div>
                                        <?php
                                        $requp = $conn->prepare("SELECT message FROM `private_message` WHERE id_sender = $me AND id_receiver = $r[id] ORDER BY message DESC LIMIT 1;");
                                        $requp->execute();
                                        if ($requp->rowCount() > 0) {
                                            $lastmess = $requp->fetchAll();
                                            foreach ($lastmess as $lm) {
                                                ?>
                                                <p id="text"> <?= $lm['message'] ?> </p>
                                                <?php
                                            }}
                                        ?>


                                    </div>
                                </div>

                            </div>

                        </div>

                    </a>
            <?php
                }
            }
            ?>
            <!-- </div> -->

        </div>

    </div>

</div>

<div class="col-5 d-flex justify-content-center">

    <div class="container mx-auto d-block right-message">

        <div class="center text-white">

            <h3 class="fw-bold">Sélectionnez un messages.</h3>

            <p class="">Faites un choix dans vos conversations existantes , commencez en une nouvelle ou ne changez
                rien.</p>

            <div class="">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
                <div class="trending mt-4">
                    <input type="text" name="inputuser" class="form-control" id="search-user" value="" placeholder="Recherche un ou des DeadBirder"></input>
                </div>
                <div style="margin-top: 20px">
                    <div id="result-search">

                    </div>
                </div>
                <div class="button-message" data-bs-toggle="modal" data-bs-target="#myModalIns">
                    <button class="new_message">Nouveau message</button>
                </div>
            </div>

        </div>


    </div>
    <div class="container" id="bestchat">
    </div>

</div>
<script>
    $(document).ready(function() {
        $("#bestchat").hide();
        $("#search-user").hide();
        $(".new_message").click(function() {
            $(".new_message").hide();
            $("#search-user").show();
        });
        $('#search-user').keyup(function() {
            let value = $(this).val();
            value = value.toLowerCase();
            $('#result-search').html('');
            let utilisateur = value;
            if (utilisateur != "") {
                $.ajax({
                    type: "GET",
                    url: 'fonctions/recherche_utilisateur.php',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data) {
                        if (data != "") {
                            $('#result-search').append(data);
                            console.log(divId);
                        } else {
                            document.getElementById('result-search').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>";
                        }
                    }
                })
            }
        })
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>