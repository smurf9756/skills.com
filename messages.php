<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$logged_id = $_SESSION['user_id'];
$receiver_id = $_GET['user'] ?? null;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Messages</title>
    <link rel="stylesheet" href="com.css ">

<body>
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skills.php">Skills</a></li>

                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
</body>
<style>
body {
    font-family: Arial;
    margin: 0;
    display: flex;
    height: 100vh;
}

.user-list {
    width: 28%;
    background: #f5f5f5;
    border-right: 1px solid #ccc;
    overflow-y: auto;
}


.user {
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    border-bottom: 1px solid #e5e5e5;
}

.user img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.online-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    background: green;
}

.offline-dot {
    background: gray;
}

.chat-box {
    width: 72%;
    padding: 15px;
    display: flex;
    flex-direction: column;
}

.messages {
    flex: 1;
    overflow-y: auto;
    padding-bottom: 20px;
}

.message {
    padding: 8px 12px;
    margin: 8px 0;
    max-width: 60%;
    border-radius: 10px;
}

.sent {
    background: #c8e6c9;
    align-self: flex-end;
}

.received {
    background: #e3f2fd;
    align-self: flex-start;
}

.chat-input {
    display: flex;
    gap: 10px;
}

input[type=text] {
    flex: 1;
    padding: 10px;
}
</style>

<script>
function loadUsers() {
    fetch("fetch_users.php")
        .then(res => res.json())
        .then(data => {
            let html = "";
            data.users.forEach(u => {
                let dot = u.status === "online" ? "online-dot" : "offline-dot";

                html += `
                    <a href="messages.php?user=${u.id}" style="text-decoration:none;color:black;">
                        <div class='user'>
                            <img src="${u.profile_image || 'default.jpg'}">
                            <div>
                                <b>${u.fullname}</b><br>
                                <span class="${dot}"></span> ${u.status}
                            </div>
                        </div>
                    </a>`;
            });
            document.getElementById("user_list").innerHTML = html;
        });
}

function loadMessages() {
    let id = "<?= $receiver_id ?>";
    if (!id) return;

    fetch("read_messages.php?user=" + id)
        .then(res => res.json())
        .then(data => {
            let html = "";
            data.messages.forEach(m => {
                let cls = m.sender_id == <?= $logged_id ?> ? "sent" : "received";
                html += `<div class="message ${cls}">${m.message}</div>`;
            });
            document.getElementById("chat_messages").innerHTML = html;
        });
}

function sendMessage() {
    let msg = document.getElementById("msg").value;
    let id = "<?= $receiver_id ?>";

    fetch("send_message.php", {
        method: "POST",
        body: new URLSearchParams({
            receiver_id: id,
            message: msg
        })
    }).then(res => res.json()).then(() => {
        document.getElementById("msg").value = "";
        loadMessages();
    });
}

setInterval(loadUsers, 3000);
setInterval(loadMessages, 2000);
</script>
</head>

<body>

    <div class="user-list" id="user_list"></div>

    <div class="chat-box">
        <div class="messages" id="chat_messages"></div>

        <?php if ($receiver_id): ?>
        <div class="chat-input">
            <input type="text" id="msg" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
        </div>
        <?php endif; ?>
    </div>

</body>

</html>