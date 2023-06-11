<?php
require_once("header.php");
?>
<html>

<body>
        <div class="col-6">
                <div class="d-flex justify-content-between w-100">
                        <?php
                        function date_en_lettres($date)
                        {
                                $mois = array(
                                        'janvier',
                                        'février',
                                        'mars',
                                        'avril',
                                        'mai',
                                        'juin',
                                        'juillet',
                                        'août',
                                        'septembre',
                                        'octobre',
                                        'novembre',
                                        'décembre'
                                );
                                $jour = date('j', strtotime($date));
                                $mois = $mois[date('n', strtotime($date)) - 1];
                                $annee = date('Y', strtotime($date));
                                return "$jour $mois $annee";
                        }
                        $host = 'www.webacademie-project.tech';
                        $dbname = 'twitter_academy_db';
                        $username = 'wac209_user';
                        $password = 'wac209';
                        session_start();
                        $utilisateur = $_SESSION['id'];
                        if (isset($_GET['id_tweet'])) {
                                $id_tweet = $_GET['id_tweet'];
                        }
                        try {
                                $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        } catch (PDOException $e) {
                        }
                        // requete qui va recuperer toutes les infos pour afficher le tweet comme il faut
                        $sql = "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user,id_retweet FROM tweet left join users on tweet.id_user=users.id where tweet.id=$id_tweet AND id_reply_tweet is null order by date_send desc";
                        $query = $conn->prepare($sql);
                        $query->execute();
                        $result = $query->fetchAll();
                        // --------------------------------------------------------------------

                        $query = "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user,id_retweet FROM tweet left join users on tweet.id_user=users.id where tweet.id=$id_tweet AND id_reply_tweet is null order by date_send desc";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $newArray = [];
                        $_SESSION["allTweets"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        for ($i = 0; $i < count($_SESSION["allTweets"]); $i++) {
                                $tweetWords = explode(" ", $_SESSION["allTweets"][$i]["message"]);
                                $tweet = "";
                                foreach($tweetWords as $word){
                                        if(str_starts_with($word, "#")){
                                            $hashtag = substr($word, 1);
                                            $word = " <a class='mx-2' href='hashtag.php?tag=" . $hashtag . "'>" . $word . "</a>";
                                        }
                                        elseif (str_ends_with($word, ".jpg") || str_ends_with($word, ".png") || str_starts_with($word, "https://tinyurl.com/") || str_ends_with($word, ".gif")) {
                                            $hashtag = substr($word,1);
                    
                                            $word = "<img class='img-rounded img-fluide mx-2' src='$word' width='100px' height=100px>";
                                        }
                                        elseif (str_starts_with($word, "@")) {
                                                $arobase = substr($word,1);
                                                $word = " <a class='mx-2' href='arobase.php?arobase=" . $arobase . "'>" . $word . "</a>";
                                        }
                                        $tweet .= $word . " ";
                    
                                    }
                                $_SESSION["allTweets"][$i]["message"] = $tweet;
                        }
                        ?>
                        <div class="boitetweet" id="bouratata">

                                <?php



                                foreach ($result as $key => $value) {
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
                                        $nbrlike = $value['lid'];
                                        $id = $value['id_retweet'];


                                        if ($value['avatar'] == "") {
                                                $value['avatar'] = "https://tinyurl.com/4umrunyw";
                                        }


                                        // --------------------------------------------------------------------
                                        if ($value['id_retweet'] != '') {
                                                $id = $value['id_retweet'];
                                                $sql = "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user,id_retweet FROM tweet left join users on tweet.id_user=users.id where id_reply_tweet is null and tweet.id=$id order by date_send desc";
                                                $query = $conn->prepare($sql);
                                                $query->execute();
                                                $resultat = $query->fetch();
                                                $date_bd = $resultat['date_send'];
                                                $timestamp = strtotime($date_bd) + 3600;
                                                $date_bd = date("Y-m-d H:i:s", $timestamp);
                                                $date_actuelle = date('Y-m-d H:i:s');
                                                $diff = strtotime($date_actuelle) - strtotime($date_bd);
                                                $secondes = $diff;
                                                $minutes = floor($diff / 60);
                                                $heures = floor($diff / 3600);
                                                $jours = floor($diff / 86400);
                                ?>
                                                <div>
                                                        <p class="rt"> <i class="fa-solid fa-skull"> </i>
                                                                <?= $value['name'] ?> a retweet
                                                        </p>
                                                </div>
                                                <?php

                                                ?>
                                                <div class="champtweet">
                                                        <div class="row">
                                                                <div class="col-1">
                                                                        <a href="profil.php?id_profil=<?= $resultat['id_user'] ?>"><img class="rounded-circle bg-light mt-1" src=<?= $resultat['avatar'] ?> alt="" width="50px" height="50px"></a>
                                                                </div>
                                                                <div class="col-10">
                                                                        <div class="d-flex">
                                                                                <a href="profil.php?id_profil=<?= $resultat['id_user'] ?>">
                                                                                        <p class="pseudo">
                                                                                                <?= $resultat['name'] ?>
                                                                                        </p>
                                                                                </a>
                                                                                <a href="profil.php?id_profil=<?= $resultat['id_user'] ?>">
                                                                                        <p class="arobase indice">@
                                                                                                <?= $resultat['username'] ?>
                                                                                        </p>
                                                                                </a>
                                                                                <?php
                                                                                // Ici j'ai un arbre de if qui affiche la date du tweet envoyé en fonction du temps écouté
                                                                                if ($jours > 1) { ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= date_en_lettres($date_bd) ?>
                                                                                        </p>
                                                                                <?php
                                                                                } elseif ($jours == 1) {
                                                                                ?>
                                                                                        <p class="temporis indice">· hier</p>
                                                                                <?php
                                                                                } elseif ($heures > 0) {
                                                                                ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= $heures ?>h
                                                                                        </p>
                                                                                <?php
                                                                                } elseif ($minutes > 0) {
                                                                                ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= $minutes ?> min
                                                                                        </p>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= $secondes ?>s
                                                                                        </p>
                                                                                <?php }
                                                                                // --------------------------------------------------------------------

                                                                                ?>

                                                                        </div>
                                                                        <p>
                                                                                <?= $_SESSION["allTweets"][$key]["message"] ?>
                                                                        </p>
                                                                        <div class="inter">
                                                                                <?php
                                                                                // Requete pour savoir combien de reponses a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                                $sqls = "SELECT COUNT(id_reply_tweet) as test FROM tweet WHERE id_reply_tweet = $id";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $resultat = $querys->fetch();
                                                                                // --------------------------------------------------------------------
                                                                                ?>
                                                                                <a href=""><i id="comment" class="fa-regular fa-comment">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></a>
                                                                                <?php
                                                                                // Requete pour savoir combien de retweet a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                                $sqls = "SELECT COUNT(id_retweet) as test FROM tweet WHERE id_retweet = $id";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $resultat = $querys->fetch();
                                                                                // --------------------------------------------------------------------
                                                                                $sqls = "SELECT COUNT(*) as test FROM tweet WHERE id_retweet = $id and id_user=$utilisateur";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $isrt = $querys->fetch();
                                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton rt si le tweet n'a pas été liké ou le bouton unrt si le tweet a déja été liké
                                                                                if ($isrt['test'] == 0) {
                                                                                ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $nbrlike ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="retweet"><i id="deathtwett" class="fa-solid fa-skull">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
                                                                                        </form>
                                                                                <?php } else { ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $nbrlike ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="unretweetrt"><i id="undeathtwett" class="fa-solid fa-skull">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
                                                                                        </form>
                                                                                <?php }
                                                                                ?>
                                                                                <?php
                                                                                // Requete pour savoir combien de like a chaque tweet et l'afficher a coté de l'icone coeur
                                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $id";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $resultat = $querys->fetch();
                                                                                // --------------------------------------------------------------------

                                                                                // Ici je fais une requete pour savoir si le tweet a déjà été liké par l'utilisateur en ligne ou pas
                                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $id and id_user=$utilisateur";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $islike = $querys->fetch();
                                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton like si le tweet n'a pas été liké ou le bouton unlike si le tweet a déja été liké
                                                                                if ($islike['test'] == 0) {
                                                                                ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $id ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="like"><i id="like" class="fa-solid fa-heart-pulse">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
                                                                                        </form>
                                                                                <?php } else { ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $id ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="unlike"><i id="unlike" class="fa-solid fa-heart-pulse">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
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


                                        // Else ou le tweet n'est pas un retweet
                                        else {
                                        ?>
                                                <div class="champtweet">
                                                        <div class="row">
                                                                <div class="col-1">
                                                                        <a href="profil.php?id_profil=<?= $value['id_user'] ?>"><img class="rounded-circle bg-light mt-1" src=<?= $value['avatar'] ?> alt="" width="50px" height="50px"></a>
                                                                </div>
                                                                <div class="col-10">
                                                                        <div class="d-flex">
                                                                                <a href="profil.php?id_profil=<?= $value['id_user'] ?>">
                                                                                        <p class="pseudo">
                                                                                                <?= $value['name'] ?>
                                                                                        </p>
                                                                                </a>
                                                                                <a href="profil.php?id_profil=<?= $value['id_user'] ?>">
                                                                                        <p class="arobase indice">@
                                                                                                <?= $value['username'] ?>
                                                                                        </p>
                                                                                </a>
                                                                                <?php
                                                                                // Ici j'ai un arbre de if qui affiche la date du tweet envoyé en fonction du temps écouté
                                                                                if ($jours > 1) { ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= date_en_lettres($date_bd) ?>
                                                                                        </p>
                                                                                <?php
                                                                                } elseif ($jours == 1) {
                                                                                ?>
                                                                                        <p class="temporis indice">· hier</p>
                                                                                <?php
                                                                                } elseif ($heures > 0) {
                                                                                ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= $heures ?>h
                                                                                        </p>
                                                                                <?php
                                                                                } elseif ($minutes > 0) {
                                                                                ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= $minutes ?> min
                                                                                        </p>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                        <p class="temporis indice">·
                                                                                                <?= $secondes ?>s
                                                                                        </p>
                                                                                <?php }
                                                                                // --------------------------------------------------------------------

                                                                                ?>

                                                                        </div>
                                                                        <p>
                                                                                <?= $_SESSION["allTweets"][$key]["message"] ?>
                                                                        </p>
                                                                        <div class="inter">
                                                                                <?php
                                                                                // Requete pour savoir combien de reponses a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                                $nbrlike = $value['lid'];

                                                                                $sqls = "SELECT COUNT(id_reply_tweet) as test FROM tweet WHERE id_reply_tweet = $nbrlike";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $resultat = $querys->fetch();
                                                                                // --------------------------------------------------------------------
                                                                                ?>
                                                                                <a href="reponses.php?id_tweet=<?= $nbrlike ?>"><i id="comment" class="fa-regular fa-comment"><?= $resultat['test'] ?></i></a>
                                                                                <?php
                                                                                $nbrlike = $value['lid'];
                                                                                // Requete pour savoir combien de retweet a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                                $sqls = "SELECT COUNT(id_retweet) as test FROM tweet WHERE id_retweet = $nbrlike";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $resultat = $querys->fetch();
                                                                                // --------------------------------------------------------------------
                                                                                // Ici je fais une requete pour savoir si le tweet a déjà été rt par l'utilisateur en ligne ou pas
                                                                                $sqls = "SELECT COUNT(*) as test FROM tweet WHERE id_retweet = $nbrlike and id_user=$utilisateur";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $islike = $querys->fetch();
                                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton rt si le tweet n'a pas été liké ou le bouton unrt si le tweet a déja été liké
                                                                                if ($islike['test'] == 0) {
                                                                                ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="retweet"><i id="deathtwett" class="fa-solid fa-skull">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
                                                                                        </form>
                                                                                <?php } else { ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="unretweet"><i id="undeathtwett" class="fa-solid fa-skull">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
                                                                                        </form>
                                                                                <?php }







                                                                                ?>
                                                                                <?php
                                                                                // Requete pour savoir combien de like a chaque tweet et l'afficher a coté de l'icone coeur
                                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $nbrlike";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $resultat = $querys->fetch();
                                                                                // --------------------------------------------------------------------

                                                                                // Ici je fais une requete pour savoir si le tweet a déjà été liké par l'utilisateur en ligne ou pas
                                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $nbrlike and id_user=$utilisateur";
                                                                                $querys = $conn->prepare($sqls);
                                                                                $querys->execute();
                                                                                $islike = $querys->fetch();
                                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton like si le tweet n'a pas été liké ou le bouton unlike si le tweet a déja été liké
                                                                                if ($islike['test'] == 0) {
                                                                                ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="like"><i id="like" class="fa-solid fa-heart-pulse">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
                                                                                        </form>
                                                                                <?php } else { ?>
                                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                                <button type="submit" name="unlike"><i id="unlike" class="fa-solid fa-heart-pulse">
                                                                                                                <?= $resultat['test'] ?>
                                                                                                        </i></button>
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


                                        // ------------------------------------------------------------------------------------



                                        ?>



                                <?php

                                }
                                ?>
                                <div class="col-10">
                                        <form method="post" action="redirection.php?url=reponses.php?id_tweet=<?= $id_tweet ?>&id_tweet=<?= $id_tweet ?>">
                                                <div class="emoji-picker-container">
                                                        <input type="text" name="tweet_content" id="emoji-input" size="" placeholder="Tweetez votre réponse." data-emojiable="true" autocomplete="off">
                                                </div>

                                                <div class="row button-twett mt-2 mb-2 pt-2">

                                                        <div class="col">
                                                                <i type="button" class="fa-regular fa-image imagen"></i>
                                                        </div>
                                                        <div class="col">
                                                                <i type="button" class="fa-brands fa-square-git imagen"></i>
                                                        </div>

                                                        <div class="col">
                                                                <i type="button" class="fa-solid fa-list imagen"></i>
                                                        </div>

                                                        <div class="col">
                                                                <i type="button" class="fa-regular fa-calendar imagen"></i>
                                                        </div>
                                                        <div class="col">
                                                                <i type="button" class="fa-solid fa-location-dot imagen"></i>
                                                        </div>
                                                        <div class="col">
                                                                <div id="progress">
                                                                        <progress value='<?php $nc ?>' max=144></progress>
                                                                        <span id="charCount"><?php $nc = 0; ?></span>
                                                                </div>

                                                        </div>

                                                        <div class="col">
                                                                <button type="submit" name="reponse" id="boutonique">Répondre</button>
                                                        </div>

                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>

                <?php
                $sql = "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user,id_retweet FROM tweet left join users on tweet.id_user=users.id where id_reply_tweet=$id_tweet order by date_send desc";
                $query = $conn->prepare($sql);
                $query->execute();
                $result = $query->fetchAll();
                // --------------------------------------------------------------------
                $query = "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user,id_retweet FROM tweet left join users on tweet.id_user=users.id where id_reply_tweet=$id_tweet order by date_send desc";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $newArray = [];
                $_SESSION["allTweets"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for ($i = 0; $i < count($_SESSION["allTweets"]); $i++) {
                        $tweetWords = explode(" ", $_SESSION["allTweets"][$i]["message"]);
                        $tweet = "";
                        foreach ($tweetWords as $word) {
                                if (str_starts_with($word, "#")) {
                                        $hashtag = substr($word, 1);
                                        $word = " <a href='hashtag.php?tag=" . $hashtag . "'>" . $word . "</a>";
                                } elseif (str_ends_with($word, ".jpg") || str_ends_with($word, ".png") || str_starts_with($word, "https://tinyurl.com/") || str_ends_with($word, ".gif")) {
                                        $hashtag = substr($word, 1);

                                        $word = "<img class='img-rounded img-fluide' src='$word' width='100px' height=100px>";
                                }
                                $tweet .= $word . " ";
                        }
                        $_SESSION["allTweets"][$i]["message"] = $tweet;
                }


                foreach ($result as $key => $value) {
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
                        $nbrlike = $value['lid'];
                        $id = $value['id_retweet'];


                        if ($value['avatar'] == "") {
                                $value['avatar'] = "https://tinyurl.com/4umrunyw";
                        }


                        // --------------------------------------------------------------------
                        if ($value['id_retweet'] != '') {
                                $id = $value['id_retweet'];
                                $sql = "SELECT users.name,username,message,avatar,date_send,tweet.id as lid,id_reply_tweet,id_user,id_retweet FROM tweet left join users on tweet.id_user=users.id where id_reply_tweet is null and tweet.id=$id order by date_send desc";
                                $query = $conn->prepare($sql);
                                $query->execute();
                                $resultat = $query->fetch();
                                $date_bd = $resultat['date_send'];
                                $timestamp = strtotime($date_bd) + 3600;
                                $date_bd = date("Y-m-d H:i:s", $timestamp);
                                $date_actuelle = date('Y-m-d H:i:s');
                                $diff = strtotime($date_actuelle) - strtotime($date_bd);
                                $secondes = $diff;
                                $minutes = floor($diff / 60);
                                $heures = floor($diff / 3600);
                                $jours = floor($diff / 86400);
                ?>
                                <div>
                                        <p class="rt"> <i class="fa-solid fa-skull"> </i>
                                                <?= $value['name'] ?> a retweet
                                        </p>
                                </div>
                                <?php

                                ?>
                                <div class="champtweet">
                                        <div class="row">
                                                <div class="col-1">
                                                        <a href="profil.php?id_profil=<?= $resultat['id_user'] ?>"><img class="rounded-circle bg-light mt-1" src=<?= $resultat['avatar'] ?> alt="" width="50px" height="50px"></a>
                                                </div>
                                                <div class="col-10">
                                                        <div class="d-flex">
                                                                <a href="profil.php?id_profil=<?= $resultat['id_user'] ?>">
                                                                        <p class="pseudo">
                                                                                <?= $resultat['name'] ?>
                                                                        </p>
                                                                </a>
                                                                <a href="profil.php?id_profil=<?= $resultat['id_user'] ?>">
                                                                        <p class="arobase indice">@
                                                                                <?= $resultat['username'] ?>
                                                                        </p>
                                                                </a>
                                                                <?php
                                                                // Ici j'ai un arbre de if qui affiche la date du tweet envoyé en fonction du temps écouté
                                                                if ($jours > 1) { ?>
                                                                        <p class="temporis indice">·
                                                                                <?= date_en_lettres($date_bd) ?>
                                                                        </p>
                                                                <?php
                                                                } elseif ($jours == 1) {
                                                                ?>
                                                                        <p class="temporis indice">· hier</p>
                                                                <?php
                                                                } elseif ($heures > 0) {
                                                                ?>
                                                                        <p class="temporis indice">·
                                                                                <?= $heures ?>h
                                                                        </p>
                                                                <?php
                                                                } elseif ($minutes > 0) {
                                                                ?>
                                                                        <p class="temporis indice">·
                                                                                <?= $minutes ?> min
                                                                        </p>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                        <p class="temporis indice">·
                                                                                <?= $secondes ?>s
                                                                        </p>
                                                                <?php }
                                                                // --------------------------------------------------------------------

                                                                ?>

                                                        </div>
                                                        <p>
                                                                <?= $_SESSION["allTweets"][$key]["message"] ?>
                                                        </p>
                                                        <div class="inter">
                                                                <?php
                                                                // Requete pour savoir combien de reponses a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                $sqls = "SELECT COUNT(id_reply_tweet) as test FROM tweet WHERE id_reply_tweet = $id";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $resultat = $querys->fetch();
                                                                // --------------------------------------------------------------------
                                                                ?>
                                                                <a href="reponses.php?id_tweet=<?= $id ?>"><i id="comment" class="fa-regular fa-comment">
                                                                                <?= $resultat['test'] ?>
                                                                        </i></a>
                                                                <?php
                                                                // Requete pour savoir combien de retweet a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                $sqls = "SELECT COUNT(id_retweet) as test FROM tweet WHERE id_retweet = $id";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $resultat = $querys->fetch();
                                                                // --------------------------------------------------------------------
                                                                $sqls = "SELECT COUNT(*) as test FROM tweet WHERE id_retweet = $id and id_user=$utilisateur";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $isrt = $querys->fetch();
                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton rt si le tweet n'a pas été liké ou le bouton unrt si le tweet a déja été liké
                                                                if ($isrt['test'] == 0) {
                                                                ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $nbrlike ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="retweet"><i id="deathtwett" class="fa-solid fa-skull">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
                                                                        </form>
                                                                <?php } else { ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $nbrlike ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="unretweetrt"><i id="undeathtwett" class="fa-solid fa-skull">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
                                                                        </form>
                                                                <?php }
                                                                ?>
                                                                <?php
                                                                // Requete pour savoir combien de like a chaque tweet et l'afficher a coté de l'icone coeur
                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $id";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $resultat = $querys->fetch();
                                                                // --------------------------------------------------------------------

                                                                // Ici je fais une requete pour savoir si le tweet a déjà été liké par l'utilisateur en ligne ou pas
                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $id and id_user=$utilisateur";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $islike = $querys->fetch();
                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton like si le tweet n'a pas été liké ou le bouton unlike si le tweet a déja été liké
                                                                if ($islike['test'] == 0) {
                                                                ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $id ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="like"><i id="like" class="fa-solid fa-heart-pulse">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
                                                                        </form>
                                                                <?php } else { ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $id ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="unlike"><i id="unlike" class="fa-solid fa-heart-pulse">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
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


                        // Else ou le tweet n'est pas un retweet
                        else {
                        ?>
                                <div class="champtweet">
                                        <div class="row">
                                                <div class="col-1">
                                                        <a href="profil.php?id_profil=<?= $value['id_user'] ?>"><img class="rounded-circle bg-light mt-1" src=<?= $value['avatar'] ?> alt="" width="50px" height="50px"></a>
                                                </div>
                                                <div class="col-10">
                                                        <div class="d-flex">
                                                                <a href="profil.php?id_profil=<?= $value['id_user'] ?>">
                                                                        <p class="pseudo">
                                                                                <?= $value['name'] ?>
                                                                        </p>
                                                                </a>
                                                                <a href="profil.php?id_profil=<?= $value['id_user'] ?>">
                                                                        <p class="arobase indice">@
                                                                                <?= $value['username'] ?>
                                                                        </p>
                                                                </a>
                                                                <?php
                                                                // Ici j'ai un arbre de if qui affiche la date du tweet envoyé en fonction du temps écouté
                                                                if ($jours > 1) { ?>
                                                                        <p class="temporis indice">·
                                                                                <?= date_en_lettres($date_bd) ?>
                                                                        </p>
                                                                <?php
                                                                } elseif ($jours == 1) {
                                                                ?>
                                                                        <p class="temporis indice">· hier</p>
                                                                <?php
                                                                } elseif ($heures > 0) {
                                                                ?>
                                                                        <p class="temporis indice">·
                                                                                <?= $heures ?>h
                                                                        </p>
                                                                <?php
                                                                } elseif ($minutes > 0) {
                                                                ?>
                                                                        <p class="temporis indice">·
                                                                                <?= $minutes ?> min
                                                                        </p>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                        <p class="temporis indice">·
                                                                                <?= $secondes ?>s
                                                                        </p>
                                                                <?php }
                                                                // --------------------------------------------------------------------

                                                                ?>

                                                        </div>
                                                        <p>
                                                                <?= $_SESSION["allTweets"][$key]["message"] ?>
                                                        </p>
                                                        <div class="inter">
                                                                <?php
                                                                // Requete pour savoir combien de reponses a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                $sqls = "SELECT COUNT(id_reply_tweet) as test FROM tweet WHERE id_reply_tweet = $nbrlike";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $resultat = $querys->fetch();
                                                                // --------------------------------------------------------------------
                                                                ?>
                                                                <a href=""><i id="comment" class="fa-regular fa-comment"><?= $resultat['test'] ?></i></a>
                                                                <?php
                                                                $nbrlike = $value['lid'];
                                                                // Requete pour savoir combien de retweet a chaque tweet et l'afficher a coté de l'icone tete de mort
                                                                $sqls = "SELECT COUNT(id_retweet) as test FROM tweet WHERE id_retweet = $nbrlike";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $resultat = $querys->fetch();
                                                                // --------------------------------------------------------------------
                                                                // Ici je fais une requete pour savoir si le tweet a déjà été rt par l'utilisateur en ligne ou pas
                                                                $sqls = "SELECT COUNT(*) as test FROM tweet WHERE id_retweet = $nbrlike and id_user=$utilisateur";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $islike = $querys->fetch();
                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton rt si le tweet n'a pas été liké ou le bouton unrt si le tweet a déja été liké
                                                                if ($islike['test'] == 0) {
                                                                ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="retweet"><i id="deathtwett" class="fa-solid fa-skull">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
                                                                        </form>
                                                                <?php } else { ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="unretweet"><i id="undeathtwett" class="fa-solid fa-skull">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
                                                                        </form>
                                                                <?php }







                                                                ?>
                                                                <?php
                                                                // Requete pour savoir combien de like a chaque tweet et l'afficher a coté de l'icone coeur
                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $nbrlike";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $resultat = $querys->fetch();
                                                                // --------------------------------------------------------------------

                                                                // Ici je fais une requete pour savoir si le tweet a déjà été liké par l'utilisateur en ligne ou pas
                                                                $sqls = "SELECT COUNT(*) as test FROM likes WHERE id_tweet = $nbrlike and id_user=$utilisateur";
                                                                $querys = $conn->prepare($sqls);
                                                                $querys->execute();
                                                                $islike = $querys->fetch();
                                                                // Ici je fais un arbre de if qui permet d'afficher le bouton like si le tweet n'a pas été liké ou le bouton unlike si le tweet a déja été liké
                                                                if ($islike['test'] == 0) {
                                                                ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="like"><i id="like" class="fa-solid fa-heart-pulse">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
                                                                        </form>
                                                                <?php } else { ?>
                                                                        <form method="post" action="redirection.php?id_tweet=<?= $value['lid'] ?>&url=reponses.php?id_tweet=<?= $id_tweet ?>">
                                                                                <button type="submit" name="unlike"><i id="unlike" class="fa-solid fa-heart-pulse">
                                                                                                <?= $resultat['test'] ?>
                                                                                        </i></button>
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


                        // ------------------------------------------------------------------------------------



                        ?>



                <?php

                }
                ?>
        </div>
        <div class="col-3">
                <div class="rightside">
                        <form method="post" action="recherche.php">
                                <div class="trending" id="stickyx">
                                        <input type="text" class="" name="recherche" placeholder="Recherche DeadBird"></input>
                        </form>
                </div>
                <?php

                if (!isset($_SESSION['id'])) {

                        echo '<div class="new">
                                                                                                                                                                        <h5>Nouveau sur Deadbird ?</h5>
                                                                                                                                                                        <p class="px-4">Inscrivez-vous pour profiter de votre propre fil personnalisé !</p>
                                                                                                                                                                        <div class="text-center mx-4">
                                                                                                                                                                        <div class="buttonlog mx-auto mt-3" data-bs-toggle="modal" data-bs-target="#myRegisterModal">
                                                                                                                                                                        <button class="">Inscription</button>
                                                                                                                                                                        </div>
                                                                                                                                                                        <div class="buttonlog mx-auto mt-3" data-bs-toggle="modal" data-bs-target="#myLoginModal">
                                                                                                                                                                        <button>Connexion</button>
                                                                                                                                                                        </div>
                                                                                                                                                                        </div>
                                                                                                                                                                        
                                                                                                                                                                        </div>';
                }
                ?>

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
<script src="script/emoji.js"></script>
<script src="script/modalinscription.js"></script>
<script src="script/modal.js"></script>
<script src="lib/js/config.min.js"></script>
<script src="lib/js/util.min.js"></script>
<script src="lib/js/jquery.emojiarea.min.js"></script>
<script src="lib/js/emoji-picker.min.js"></script>
<script src="script/charcount.js"></script>

</html>