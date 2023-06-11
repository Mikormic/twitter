<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://fonts.cdnfonts.com/css/huglove" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

    <link rel="icon" type="image/x-icon" href="mytwittlogo.png" />
    <link rel="shortcut icon" type="image/x-icon" href="mytwittlogo.png" />
    <!-- EMOJI -->
    <link href="lib/css/emoji.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Inclure les fichiers CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <!-- Inclure les fichiers JavaScript de Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://kit.fontawesome.com/dc01741cf6.js" crossorigin="anonymous"></script>
    <script src="./script/nightynight.js"></script>

    <title>DeadBird</title>

</head>

<body class="bulabulabula">

    <div class="container">

        <div class="row">

            <div class="col-3">

                <div class="culll">

                    <nav class="navbar-dark bulabulabula">

                        <div class="container-fluid">

                            <a class="" href="redir.php"><img class="img-fluid logo" src="assets/mytwittlogo.png" alt="" sizes="" srcset=""> </a>

                            <li class="nav-item">
                                <a href="accueil.php?page=8" class="activee"> <i class="fa-solid fa-house"></i> Accueil</a>
                            </li>

                            <li class="nav-item">
                                <a href="explorer.php"> <i class="fa-regular fa-hashtag"></i> Explorer</a>
                            </li>

                            <li class="nav-item">
                                <a href="#"><i class="fa-regular fa-bell"></i> Notifications</a>
                            </li>

                            <li class="nav-item">
                                <a href="message.php"><i class="fa-solid fa-regular fa-envelope"></i> Messages</a>
                            </li>

                            <li class="nav-item">
                                <a href="#"><i class="fa-regular fa-bookmark"></i> Signets</a>
                            </li>
                            <?php
                            if (isset($_SESSION['id'])) { ?>
                                <li class="nav-item">
                                    <a href="profil.php?id_profil=<?= $_SESSION['id'] ?>"><i class="fa-regular fa-user"></i> Profil</a>
                                </li>
                            <?php }
                            if (isset($_SESSION['id'])) { ?>

                                <li class="nav-item">
                                    <a href="logout.php"><i class="fa-solid fa-notes-medical"></i> Déconnexion</a>
                                </li>
                            <?php }
                            if (isset($_SESSION['id'])) {

                                echo '<div class="buttontweet mt-2" data-bs-toggle="modal" data-bs-target="#myModal">
                                <button>DeadBirder</button>
                                </div>';
                            }
                            ?>
                            <label for="night-mode-checkbox">Mode clair</label>
                            <input type="checkbox" id="night-mode-checkbox">
                        </div>
                    </nav>
                </div>
            </div>