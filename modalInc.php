<?php
function validateAge($birthday, $age = 15)
{
    // $birthday can be UNIX_TIMESTAMP or just a string-date.
    if (is_string($birthday)) {
        $birthday = strtotime($birthday);
    }
    // check
    // 31536000 is the number of seconds in a 365 days year.
    if (time() - $birthday < $age * 31536000) {
        return false;
    } else {
        return true;
    }
}

if (isset($_POST['valider'])) {
    //vérifie si tous les champs sont bien  pris en compte:
    //on peut combiner isset() pour valider plusieurs champs à la fois
    if (!isset($_POST['name'], $_POST['email'], $_POST['year'], $_POST['month'], $_POST['day'], $_POST['password'])) {
        echo "Un des champs n'est pas reconnu.";
    } else {
        //on vérifie le contenu de tous les champs, savoir si ils sont correctement remplis avec les types de valeurs qu'on souhaitent qu'ils aient
        if (!preg_match("#^[\p{L}0-9]{1,50}$#iu", $_POST['username'])) {
            echo "Le pseudo est incorrect, doit contenir seulement des lettres minuscules et/ou des chiffres, d'une longueur minimum de 1 caractère et de 15 maximum.";
        } else {
            //on vérifie le mot de passe:
            if (strlen($_POST['password']) < 8) {
                echo "Le mot de passe doit être d'une longueur minimum de 8 caractères";
            } else {
                if (validateAge($_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day']) == false) {
                    echo "lol puceau";
                } else {
                    //on vérifie que l'adresse est correcte:
                    if (!preg_match("#^[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?@[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?\.[a-z]{2,30}$#i", $_POST['email'])) {
                        echo "L'adresse mail est incorrecte.";
                    } else {
                        if (strlen($_POST['email']) < 7 or strlen($_POST['email']) > 50) {
                            echo "Le mail doit être d'une longueur minimum de 7 caractères et de 50 maximum.";
                        } else {
                            $host = 'www.webacademie-project.tech';
                            $dbname = 'twitter_academy_db';
                            $username = 'wac209_user';
                            $password = 'wac209';
                            try {
                                $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            } catch (PDOException $e) {
                            }
                            $password = hash('ripemd160', $_POST['password'] . "vive le projet tweet_academy");
                            $name = $_POST['name'];
                            $username = $_POST['username'];
                            $email = $_POST['email'];
                            $birthdate = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
                            $birthdate = date('Y-m-d', strtotime($birthdate));
                            $sql = "UPDATE users SET password='$password', name='$name', username='$username', birthdate='$birthdate' WHERE email='$email'";
                            $query = $conn->prepare($sql);
                            // On exécute
                            if ($query->execute()) {
                                session_start();
                            } else {
                                echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
                            }
                        }
                    }
                }
            }
        }
    }
}

?>

<?php

//--------------------------------------------------
$error = '';
//--------------------------------------------------
if (isset($_POST['connexion'])) { //SI validation du formulaire

    $sql = ("SELECT * FROM users WHERE email = '$_POST[email]' ");
    $query = $conn->prepare($sql);
    // On exécute
    if ($query->execute()) {
        echo "E-mail ou mot de passe incorrect";
    } else {
        echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
    }

    if ($query->rowCount() >= 1) {

        $users = $query->fetch(PDO::FETCH_ASSOC);

        $password =  hash('ripemd160', $_POST['password'] . "vive le projet tweet_academy");

        if ($password == $users['password']) {
            // if( password_verify( $_POST['password'] , $membre['password'] ) ){
            //password_verify( arg1, arg2 ) : retourne true ou false et permet de comparer une chaine à une chaine cryptée
            //arg1 : le mot de passe (ici posté par l'utilisateur)
            //arg2 : le mot de passe crypté par la fonction password_hash() (ici, le mdp en BDD correspondant au pseudo posté)

            //Insertion des infos ($membre) de la personne qui se connecte dans le fichier de session
            $_SESSION['id'] = $users['id'];
            session_start();
            header('location:profil.php?id_profil='.$_SESSION['id']);
            exit;
        } else { //SINON, c'est que le mdp n'est pas bon

            $error .= "<div class='alert alert-danger'>Mot de passe incorrect ! </div>";
        }
    } else { //SINON, c'est que le pseudo n'existe pas en bdd

        $error .= "<div class='alert alert-danger'>Email incorrect ! </div>";
    }
}

//----------------------------------------------------------------------
?>




<div class="modal fade" id="myModal">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="container modal-class">

                <div class="row pt-4">
                    <div class="col-2 text-center">
                        <img class="rounded-circle bg-light mt-2" src="<?= $result['avatar'] ?>" alt="" width="50px" height="50px">
                    </div>

                    <div class="col-10">

                        <form method="post">

                            <div class="form-group mb-5">
                                <input type="text" name="tweet_content" id="myInput" size="" placeholder="Quoi de neuf ?" autocomplete="off">
                            </div>

                            <div class="row button-twett-modal mt-2 mb-2 pt-3 text-center">

                                <div class="col-6 text-center">

                                    <div class="row text-center">

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
                                            <i type="button" class="fa-regular fa-face-smile imagen"></i>
                                        </div>

                                        <div class="col">
                                            <i type="button" class="fa-regular fa-calendar imagen"></i>
                                        </div>

                                        <div class="col">
                                            <i type="button" class="fa-solid fa-location-dot imagen"></i>
                                        </div>

                                    </div>


                                </div>



                                <div class="col-6 text-center">

                                    <div class="row text-center">

                                        <div class="col">

                                            <div id="progress">

                                                <progress value='<?php $nc ?>' max=144></progress>
                                                <span id="charCount">
                                                    <?php $nc = 0; ?>
                                                </span>

                                            </div>

                                        </div>

                                        <div class="col">
                                            <button type="submit" name="valider" id="boutonique" disabled>Deadbirder</button>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Modal pour le formulaire de connexion -->
<div class="modal fade" id="myLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content modal-co" id="bluebird">

            <div class="container ">

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
                            <h3>Connectez-vous a DeadBird</h3>
                        </div>


                    </div>

                </div>



                <div class="modal-body body-modal text-center">

                    <div class="container-fluid d-flex flex-column align-items-center justify-content-center">

                        <hr>

                        <form method="post">

                            <div class="form-group mt-5" id="email-group">

                                <!-- <div class="form-floating mt-3">

                                    <input type="email" class="form-control bg-transparent text-white mt-3" id="email"
                                        name="email" placeholder="Adresse email" autocomplete="off">
                                    <label for="email" class="">Adresse email</label>

                                </div> -->

                                <!-- <button type="button" class="btn btn-default text-white" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button> -->
                                <!-- <div class="button-modal-co mx-4 mt-3">
                                    <button type="button" class="btn btn-light mt-3" id="nextBtn">Suivant</button>
                                </div> -->

                                <!-- </div> -->

                                <!-- <div class="form-group" id="password-group" style="display:none;"> -->

                                <div class="form-floating mt-3">

                                    <input type="email" class="form-control bg-transparent" id="email" name="email" placeholder="Adresse email">
                                    <label for="email" class="text-red">Adresse email</label>

                                </div>

                                <div class="form-floating mt-3">
                                    <input type="password" class="form-control bg-transparent text-white mt-3" id="password" name="password" placeholder="Mot de passe">
                                    <label for="password">Mot de passe</label>
                                </div>

                                <div class="button-modal-co mx-4 mt-1">
                                    <button type="submit" name="connexion" class="btn btn-light mt-3">Connexion</button>
                                </div>

                            </div>

                        </form>

                        <hr>

                    </div>

                </div>

                <div class="modal_footer text-center">

                    <div class="container-fluid d-flex justify-content-center mb-5">

                        <p>Vous n'avez pas de compte ?</p> <a href=""> Inscrivez-vous</a>
                    </div>

                </div>

            </div>


        </div>

    </div>

</div>

<!----MODAL INSCRIPTION ---->

<div class="modal fade" id="myRegisterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg text-center justify-content-center">

        <div class="modal-content modal-re">

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
                            <h3>Rejoignez DeadBird</h3>
                        </div>


                    </div>

                </div>

                <div class="modal-body text-center">

                    <div class="container-fluid d-flex flex-column align-items-center justify-content-center">

                        <form method="post" action="accueil.php?page=8">

                            <div id="inscription">

                                <div id="inscription1">

                                    <h3>Etape 1/3</h3>

                                    <div class="form-group mt-5" id="email-group">

                                        <div class="form-floating mt-3">

                                            <input type="text" class="form-control bg-transparent text-white mt-3" id="name" name="name" autocomplete="off">
                                            <label for="name" class="">Nom et Prénom</label>

                                        </div>

                                        <div class="form-floating mt-3">

                                            <input type="email" class="form-control bg-transparent text-white mt-3" id="email" name="email" autocomplete="off">
                                            <label for="email" class="">Adresse email</label>

                                        </div>

                                        <div class="row date-titre">
                                            <h4 class="mt-4">Date de naissance</h4>
                                        </div>


                                        <div class="row" id="date">

                                            <div class="col-4">

                                                <div class="form-floating mt-3">

                                                    <select name="month" class="form-control bg-transparent text-white mt-3" id="month" autocomplete="off">
                                                        <option disabled="" value="" class="r-kemksi"></option>
                                                        <option value="01" class="r-kemksi">Janvier</option>
                                                        <option value="02" class="r-kemksi">Février</option>
                                                        <option value="03" class="r-kemksi">Mars</option>
                                                        <option value="04" class="r-kemksi">Avril</option>
                                                        <option value="05" class="r-kemksi">Mai</option>
                                                        <option value="06" class="r-kemksi">Juin</option>
                                                        <option value="07" class="r-kemksi">Juillet</option>
                                                        <option value="08" class="r-kemksi">Août</option>
                                                        <option value="09" class="r-kemksi">Septembre</option>
                                                        <option value="10" class="r-kemksi">Octobre</option>
                                                        <option value="11" class="r-kemksi">Novembre</option>
                                                        <option value="12" class="r-kemksi">Décembre</option>
                                                    </select>
                                                    <label for="month" class="">Mois</label>

                                                </div>

                                            </div>

                                            <div class="col-4">

                                                <div class="form-floating mt-3">

                                                    <select name="day" class="form-control bg-transparent text-white mt-3" id="day" autocomplete="off">
                                                        <option disabled="" value="" class="r-kemksi"></option>
                                                        <option value="01" class="r-kemksi">1</option>
                                                        <option value="02" class="r-kemksi">2</option>
                                                        <option value="03" class="r-kemksi">3</option>
                                                        <option value="04" class="r-kemksi">4</option>
                                                        <option value="05" class="r-kemksi">5</option>
                                                        <option value="06" class="r-kemksi">6</option>
                                                        <option value="07" class="r-kemksi">7</option>
                                                        <option value="08" class="r-kemksi">8</option>
                                                        <option value="09" class="r-kemksi">9</option>
                                                        <option value="10" class="r-kemksi">10</option>
                                                        <option value="11" class="r-kemksi">11</option>
                                                        <option value="12" class="r-kemksi">12</option>
                                                        <option value="13" class="r-kemksi">13</option>
                                                        <option value="14" class="r-kemksi">14</option>
                                                        <option value="15" class="r-kemksi">15</option>
                                                        <option value="16" class="r-kemksi">16</option>
                                                        <option value="17" class="r-kemksi">17</option>
                                                        <option value="18" class="r-kemksi">18</option>
                                                        <option value="19" class="r-kemksi">19</option>
                                                        <option value="20" class="r-kemksi">20</option>
                                                        <option value="21" class="r-kemksi">21</option>
                                                        <option value="22" class="r-kemksi">22</option>
                                                        <option value="23" class="r-kemksi">23</option>
                                                        <option value="24" class="r-kemksi">24</option>
                                                        <option value="25" class="r-kemksi">25</option>
                                                        <option value="26" class="r-kemksi">26</option>
                                                        <option value="27" class="r-kemksi">27</option>
                                                        <option value="28" class="r-kemksi">28</option>
                                                        <option value="29" class="r-kemksi">29</option>
                                                        <option value="30" class="r-kemksi">30</option>
                                                        <option value="31" class="r-kemksi">31</option>
                                                    </select>
                                                    <label for="day" class="">Jour</label>

                                                </div>

                                            </div>

                                            <div class="col-4">

                                                <div class="form-floating mt-3">

                                                    <select class="form-control bg-transparent text-white mt-3" autocomplete="off" aria-invalid="false" aria-labelledby="SELECTOR_3_LABEL" id="SELECTOR_3" data-testid="" name="year">
                                                        <option disabled="" value="" class="r-kemksi"></option>
                                                        <option value="2023" class="r-kemksi">2023</option>
                                                        <option value="2022" class="r-kemksi">2022</option>
                                                        <option value="2021" class="r-kemksi">2021</option>
                                                        <option value="2020" class="r-kemksi">2020</option>
                                                        <option value="2019" class="r-kemksi">2019</option>
                                                        <option value="2018" class="r-kemksi">2018</option>
                                                        <option value="2017" class="r-kemksi">2017</option>
                                                        <option value="2016" class="r-kemksi">2016</option>
                                                        <option value="2015" class="r-kemksi">2015</option>
                                                        <option value="2014" class="r-kemksi">2014</option>
                                                        <option value="2013" class="r-kemksi">2013</option>
                                                        <option value="2012" class="r-kemksi">2012</option>
                                                        <option value="2011" class="r-kemksi">2011</option>
                                                        <option value="2010" class="r-kemksi">2010</option>
                                                        <option value="2009" class="r-kemksi">2009</option>
                                                        <option value="2008" class="r-kemksi">2008</option>
                                                        <option value="2007" class="r-kemksi">2007</option>
                                                        <option value="2006" class="r-kemksi">2006</option>
                                                        <option value="2005" class="r-kemksi">2005</option>
                                                        <option value="2004" class="r-kemksi">2004</option>
                                                        <option value="2003" class="r-kemksi">2003</option>
                                                        <option value="2002" class="r-kemksi">2002</option>
                                                        <option value="2001" class="r-kemksi">2001</option>
                                                        <option value="2000" class="r-kemksi">2000</option>
                                                        <option value="1999" class="r-kemksi">1999</option>
                                                        <option value="1998" class="r-kemksi">1998</option>
                                                        <option value="1997" class="r-kemksi">1997</option>
                                                        <option value="1996" class="r-kemksi">1996</option>
                                                        <option value="1995" class="r-kemksi">1995</option>
                                                        <option value="1994" class="r-kemksi">1994</option>
                                                        <option value="1993" class="r-kemksi">1993</option>
                                                        <option value="1992" class="r-kemksi">1992</option>
                                                        <option value="1991" class="r-kemksi">1991</option>
                                                        <option value="1990" class="r-kemksi">1990</option>
                                                        <option value="1989" class="r-kemksi">1989</option>
                                                        <option value="1988" class="r-kemksi">1988</option>
                                                        <option value="1987" class="r-kemksi">1987</option>
                                                        <option value="1986" class="r-kemksi">1986</option>
                                                        <option value="1985" class="r-kemksi">1985</option>
                                                        <option value="1984" class="r-kemksi">1984</option>
                                                        <option value="1983" class="r-kemksi">1983</option>
                                                        <option value="1982" class="r-kemksi">1982</option>
                                                        <option value="1981" class="r-kemksi">1981</option>
                                                        <option value="1980" class="r-kemksi">1980</option>
                                                        <option value="1979" class="r-kemksi">1979</option>
                                                        <option value="1978" class="r-kemksi">1978</option>
                                                        <option value="1977" class="r-kemksi">1977</option>
                                                        <option value="1976" class="r-kemksi">1976</option>
                                                        <option value="1975" class="r-kemksi">1975</option>
                                                        <option value="1974" class="r-kemksi">1974</option>
                                                        <option value="1973" class="r-kemksi">1973</option>
                                                        <option value="1972" class="r-kemksi">1972</option>
                                                        <option value="1971" class="r-kemksi">1971</option>
                                                        <option value="1970" class="r-kemksi">1970</option>
                                                        <option value="1969" class="r-kemksi">1969</option>
                                                        <option value="1968" class="r-kemksi">1968</option>
                                                        <option value="1967" class="r-kemksi">1967</option>
                                                        <option value="1966" class="r-kemksi">1966</option>
                                                        <option value="1965" class="r-kemksi">1965</option>
                                                        <option value="1964" class="r-kemksi">1964</option>
                                                        <option value="1963" class="r-kemksi">1963</option>
                                                        <option value="1962" class="r-kemksi">1962</option>
                                                        <option value="1961" class="r-kemksi">1961</option>
                                                        <option value="1960" class="r-kemksi">1960</option>
                                                        <option value="1959" class="r-kemksi">1959</option>
                                                        <option value="1958" class="r-kemksi">1958</option>
                                                        <option value="1957" class="r-kemksi">1957</option>
                                                        <option value="1956" class="r-kemksi">1956</option>
                                                        <option value="1955" class="r-kemksi">1955</option>
                                                        <option value="1954" class="r-kemksi">1954</option>
                                                        <option value="1953" class="r-kemksi">1953</option>
                                                        <option value="1952" class="r-kemksi">1952</option>
                                                        <option value="1951" class="r-kemksi">1951</option>
                                                        <option value="1950" class="r-kemksi">1950</option>
                                                        <option value="1949" class="r-kemksi">1949</option>
                                                        <option value="1948" class="r-kemksi">1948</option>
                                                        <option value="1947" class="r-kemksi">1947</option>
                                                        <option value="1946" class="r-kemksi">1946</option>
                                                        <option value="1945" class="r-kemksi">1945</option>
                                                        <option value="1944" class="r-kemksi">1944</option>
                                                        <option value="1943" class="r-kemksi">1943</option>
                                                        <option value="1942" class="r-kemksi">1942</option>
                                                        <option value="1941" class="r-kemksi">1941</option>
                                                        <option value="1940" class="r-kemksi">1940</option>
                                                        <option value="1939" class="r-kemksi">1939</option>
                                                        <option value="1938" class="r-kemksi">1938</option>
                                                        <option value="1937" class="r-kemksi">1937</option>
                                                        <option value="1936" class="r-kemksi">1936</option>
                                                        <option value="1935" class="r-kemksi">1935</option>
                                                        <option value="1934" class="r-kemksi">1934</option>
                                                        <option value="1933" class="r-kemksi">1933</option>
                                                        <option value="1932" class="r-kemksi">1932</option>
                                                        <option value="1931" class="r-kemksi">1931</option>
                                                        <option value="1930" class="r-kemksi">1930</option>
                                                        <option value="1929" class="r-kemksi">1929</option>
                                                        <option value="1928" class="r-kemksi">1928</option>
                                                        <option value="1927" class="r-kemksi">1927</option>
                                                        <option value="1926" class="r-kemksi">1926</option>
                                                        <option value="1925" class="r-kemksi">1925</option>
                                                        <option value="1924" class="r-kemksi">1924</option>
                                                        <option value="1923" class="r-kemksi">1923</option>
                                                        <option value="1922" class="r-kemksi">1922</option>
                                                        <option value="1921" class="r-kemksi">1921</option>
                                                        <option value="1920" class="r-kemksi">1920</option>
                                                        <option value="1919" class="r-kemksi">1919</option>
                                                        <option value="1918" class="r-kemksi">1918</option>
                                                        <option value="1917" class="r-kemksi">1917</option>
                                                        <option value="1916" class="r-kemksi">1916</option>
                                                        <option value="1915" class="r-kemksi">1915</option>
                                                        <option value="1914" class="r-kemksi">1914</option>
                                                        <option value="1913" class="r-kemksi">1913</option>
                                                        <option value="1912" class="r-kemksi">1912</option>
                                                        <option value="1911" class="r-kemksi">1911</option>
                                                        <option value="1910" class="r-kemksi">1910</option>
                                                        <option value="1909" class="r-kemksi">1909</option>
                                                        <option value="1908" class="r-kemksi">1908</option>
                                                        <option value="1907" class="r-kemksi">1907</option>
                                                        <option value="1906" class="r-kemksi">1906</option>
                                                        <option value="1905" class="r-kemksi">1905</option>
                                                        <option value="1904" class="r-kemksi">1904</option>
                                                        <option value="1903" class="r-kemksi">1903</option>
                                                    </select>
                                                    <label for="year" class="">Année</label>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div id="inscription2">

                                    <h3>Etape 2/3</h3>
                                    <div class="container">
                                        <div class="d-flex mt-5 ligne">

                                            <p>Envoie de la vérification de l'adresse email</p>
                                            <a class="mx-2" target="_blank" href="../typetwitter.php">clique pour
                                                t'inscrire</a>

                                        </div>
                                    </div>
                                </div>

                                <div id="inscription3">

                                    <h3>Etape 3/3</h3>

                                    <div class="form-group mt-5">

                                        <div class="form-floating mt-3">

                                            <input type="text" class="form-control bg-transparent text-white mt-3" id="username" name="username" autocomplete="off">
                                            <label for="username" class="">Username</label>

                                        </div>

                                        <div class="form-floating mt-3">

                                            <input type="password" class="form-control bg-transparent text-white mt-3" id="password" name="password" autocomplete="off">
                                            <label for="password" class="">Mot de passe</label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="button-modal-co mx-4 mt-5 mb-3">

                                <button type="button" id="next1">Suivant</button>

                                <button type="button" id="inscrire1">Suivant</button>


                                <button type="submit" name="valider" id="next2">S'inscrire</button>


                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>