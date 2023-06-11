
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" type="image/x-icon" href="assets/mytwittlogo.png" />
    
    <link rel="shortcut icon" type="image/x-icon" href="assets/mytwittlogo.png" />

   <!-- Inclure les fichiers CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Inclure les fichiers JavaScript de Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="d-flex justify-content-center row">

        <div class="container-fluid">

            <div class="row mt-3">

                <div class="col-2">

                    

                </div>

                <div class="col-8 text-center">

                    <img class="img-fluid logo-inscription mt-4" src="assets/mytwittlogo.png" alt="" sizes="" srcset="">

                </div>
                <div class="col-2"></div>

            </div>


        </div>

        <div class="container mx-auto d-block inscenter mx-auto">

            <div class="centeremail text-center text-white">

                <h3 class="fw-bold">Confirmer votre adresse email</h5>
                

                <form action="" method="post">

                    <div class="form-group mt-5" id="email-group">
                        <input type="email" class="form-control bg-transparent text-white mt-3" id="email" name="email" autocomplete="off">

                        <div class="button-ins mx-4 mt-5 mb-3">
                            <button type="submit" name="valider">Envoyer</button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
    
</body>

</html>
<?php
require "PHPMailer/PHPMailerAutoload.php";
session_start();
$host = 'www.webacademie-project.tech';
							$dbname = 'twitter_academy_db';
							$username = 'wac209_user';
							$password = 'wac209';
							try {
								$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							} catch (PDOException $e) {
							}
if (isset($_POST['valider'])) {
    if (!empty($_POST['email'])) {
        $cle = rand(1000000, 9000000);
        $email = $_POST['email'];
        $insererUser = $conn->prepare('INSERT INTO users(email, email_verify, banner, avatar, password, name, username) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $insererUser->execute(array($email, 0, "https://tinyurl.com/2t8a2sst", "https://tinyurl.com/4umrunyw", "attente de vérification", "attente de vérification", "attente de vérification"));

        $recupUser = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $recupUser->execute(array($email));
        if ($recupUser->rowCount() > 0) {
            $userInfos = $recupUser->fetch();
            $_SESSION['id'] = $userInfos['id'];
        }
    } else {
        echo "Veuillez mettre votre email";
    }
    function smtpmailer($to, $from, $from_name, $subject, $body)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = 'deadbirdnoreturn@gmail.com';
        $mail->Password = 'qzhsrfefzxfavroh';

        $mail->IsHTML(true);
        $mail->From = $from;
        $mail->FromName = $from_name;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);

        if (!$mail->Send()) {
            $error = "Please try Later, Error Occured while Processing...";
            return $error;
        } else {
            $error = "Thanks You !! Your email is sent.";
            return $error;
        }
    }
}



$to = $email;
$from = 'deadbirdnoreturn@gmail.com';
$name = 'el equip del culo';
$subj = 'Email de Verifiaction';
$msg = 'http://localhost:8000/verif.php?id=' . $_SESSION['id'] . '&cle=' . $cle;

$error = smtpmailer($to, $from, $name, $subj, $msg);

?>