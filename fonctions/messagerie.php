<?php
// Connexion à la base de données
session_start();
$host = 'www.webacademie-project.tech';
$dbname = 'twitter_academy_db';
$username = 'wac209_user';
$password = 'wac209';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Récupération de l'ID de l'utilisateur courant
$meid = $_SESSION['id'];

// Récupération de l'ID de l'utilisateur avec lequel on discute
$user_id = $_POST['user_id'];

// Insertion du message dans la base de données
if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $sql = "INSERT INTO private_message (id_sender, id_receiver, message, date_send) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$meid, $user_id, $message]);
}

// Récupération de tous les messages échangés entre les deux utilisateurs
$sql = "SELECT * FROM private_message WHERE (id_sender = ? AND id_receiver = ?) OR (id_sender = ? AND id_receiver = ?) ORDER BY date_send ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$meid, $user_id, $user_id, $meid]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construction du HTML des messages

$html = '';
foreach ($messages as $message) {
    $date = new DateTime($message['date_send']);
$heure = $date->format('H:i:s');
if($message['id_sender'] == $meid){
    $html .= '<div class="message envoyé" style="display: flex; justify-content: end; margin-bottom: 10px;">';
}
else{
    $html .= '<div class="message recu" style="display: flex; justify-content: start; margin-bottom: 10px;">';
}
    $html .= '<div class="message';

    // Vérification de l'expéditeur et destinataire du message
    if ($message['id_sender'] == $meid) {
        $html .= ' sent bg-primary" style="border-radius: 10px; padding: 0 10px">';
    } else {
        $html .= ' received bg-secondary" style="border-radius: 10px; padding: 0 10px">';
    }

    // Affichage du message et de la date
    if ($message['message'] !== '') {
        $html .= '<p style="color: white !important;">' . $message['message'] . " " . $heure . '</p>';
    }
    $html .= '</div>';
    $html .= '</div>';

}


// Renvoi du HTML en réponse à la requête AJAX
echo $html;
?>

