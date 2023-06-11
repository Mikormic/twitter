<?php
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
$id = $_GET['id_profil'];
$arobase = $_GET['arobase'];

if(isset($_GET['arobase'])){
$tata = "SELECT id as test FROM users WHERE username = '$arobase'";
$oui = $conn->prepare($tata);
$oui->execute();
$nombre = $oui->fetch();

$id=$nombre['test'];
}

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


// requete qui va recuperer toutes les infos pour afficher le profil comme il faut
$sql = "SELECT username, avatar, bio, city, birthdate, banner 
FROM users 
WHERE users.id = :id";
$query = $conn->prepare($sql);
$query->execute(['id' => $id]);
$result = $query->fetch();


?>


<div class="modal fade" id="ModalEditProfil" tabindex="-1" role="dialog" aria-labelledby="ModalEditProfil" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content modal-co">

            <div class="container">

                <div class="bg-transparent">

                    <div class="container-fluid">

                        <div class="row mt-3">

                            <div class="col-2">

                                <button type="button" class="btn-close float-start btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>

                            <div class="col-8 text-center">

                                <img class="img-fluid logo-modal" src="assets/mytwittlogo.png" alt="" sizes="" srcset="">

                            </div>
                            <div class="col-2"></div>

                        </div>

                        <div class="container mt-2 text-center">
                            <h3>Editer Profil</h3>
                        </div>


                    </div>

                </div>



                <div class="modal-body text-center">

                    <div class="container-fluid d-flex flex-column align-items-center justify-content-center">

                        <form method="post" action="redirection.php?id_profil=<?= $id ?>&url=profil.php">
                            <div class="form-floating mt-3">

                                <input type="text" class="form-control bg-transparent text-white mt-3" id="" name="banner" placeholder="url baniere" value="<?= $result['banner'] ?>">
                                <label for="" class="text-red">Banière</label>

                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" class="form-control bg-transparent text-white mt-3" id="" name="avatar" placeholder="urlpp" value="<?= $result['avatar'] ?>">
                                <label for="">Photo de profil</label>
                            </div>

                            <div class="form-floating mt-3">

                                <input type="text" class="form-control bg-transparent text-white mt-3" id="" name="username" placeholder="tndumec" value="<?= $result['username'] ?>">
                                <label for="" class="text-red">Pseudo</label>

                            </div>

                            <div class="form-floating mt-3">

                                <input type="text" class="form-control bg-transparent text-white mt-3" id="" name="bio" placeholder="...." value="<?= $result['bio'] ?>">
                                <label for="" class="text-red">Biographie</label>

                            </div>

                            <div class="form-floating mt-3">

                                <input type="text" class="form-control bg-transparent text-white mt-3" id="" name="city" placeholder="" value="<?= $result['city'] ?>">
                                <label for="" class="text-red">Localisation</label>

                            </div>

                            <div class="form-floating mt-3">

                                <input type="date" class="form-control bg-transparent text-white mt-3" id="" name="birthdate" placeholder="" value="<?= $result['birthdate'] ?>">
                                <label for="" class="text-red">Date de naissace</label>

                            </div>

                            <div class="button-modal-co mx-4 mt-3">
                                    <button type="submit" name="save" class="btn btn-light mt-3">Enregistrer</button>
                            </div>


                        </form>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>