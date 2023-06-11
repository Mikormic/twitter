<?php
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

if (isset($_GET['user'])) {
    $user = (string) trim($_GET['user']);
    $requp = $conn->prepare("SELECT * FROM users WHERE username LIKE LOWER(?) LIMIT 10");
    $requp->execute(array("$user%"));
    if ($requp->rowCount() > 0) {
        $requp = $requp->fetchAll();
        foreach ($requp as $r) {
?>
            <div class="user" id="<?= $r['id'] ?>">
                <a href="#" class="champmessage" >
                    <div class="col-3 text-center">
                        <img class="rounded-circle bg-light mt-1" src="<?= $r['avatar'] ?> " alt="" width="50px" height="50px">
                    </div>
                    <div class="col-9 text-center">
                        <div class="d-flex">
                            <p class="pseudo fw-bold"><?= $r['name'] ?> </p>
                            <p class="arobase indice"><?= $r['username'] ?> </p>
                        </div>
                    </div>
                </a>
            </div>

<?php
        }
    }
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.user').click(function(event) {
            event.stopPropagation();
            let divId = $(this).attr('id');
            let bestChatDiv = $("#bestchat");
            $('.text-white').hide();
            $("#bestchat").html($(this).clone()).show();
            let chatbox = $('<div class="row justify-content-center"><div style="display: flex; flex-direction: column" class="chat-container" id="chatbox"><div class="mb-3" id="message-area"></div><textarea id="message-input"></textarea></div></div>');
            bestChatDiv.append(chatbox);
            let messageArea = $('#message-area');
            let messageInput = $('#message-input');
            messageInput.on('keydown', function(e) {
                if (e.keyCode == 13) {
                    let messageText = messageInput.val();
                    if (messageText != "") {
                        $.ajax({
                            type: "POST",
                            url: 'fonctions/messagerie.php',
                            data: {
                                message: messageText,
                                user_id: divId
                            },
                            success: function(response) {
                                console.log(response);
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                console.log(xhr.responseText);
                            }
                        });
                        messageArea.append('<div class="d-flex message justify-content-end sent"><div class="mt-2 mx-2 bg-primary rounded-pill text-center" style="padding: 15px; color: white !important;height: auto; width: auto;">' + messageText + '</div></div>');
                        messageInput.val("");
                        messageArea.scrollTop(messageArea[0].scrollHeight);
                    }
                    return false;
                }
            });
            $.ajax({
                type: "POST",
                url: 'fonctions/messagerie.php',
                data: {
                    message: messageInput.val(),
                    user_id: divId
                },
                success: function(response) {
                    $('#message-area').html(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>