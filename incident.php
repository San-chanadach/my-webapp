<?php
session_start();
$open_connect = 1;
require('login/connect.php');

if (!isset($_SESSION['id_account']) || !isset($_SESSION['role_account'])) {
    die(header('Location: login.php'));
}

$id_account = $_SESSION['id_account'];
$query_show = "SELECT * FROM account WHERE id_account = '$id_account'";
$call_back_show = mysqli_query($connect, $query_show);
$result_show = mysqli_fetch_assoc($call_back_show);

if ($result_show) {
    $username_account = $result_show['username_account'];
    $role_account = $result_show['role_account'];
} else {
    $username_account = 'ไม่พบข้อมูลผู้ใช้';
    $role_account = 'ไม่พบข้อมูลผู้ใช้';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>งานระงับเหตุ</title>
    <link rel="stylesheet" href="css/button.css">
    <script>
        function sendEvent(eventId) {
            fetch('/MobileDim/login/process-event.php', {
                method: 'POST',
                body: JSON.stringify({ eventId: 'event_' + eventId, valueToStore: 1 }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</head>
<body>
<div class="welcome-message">
    ยินดีต้อนรับคุณ <?php echo $username_account; ?> ในฐานะ <?php echo $role_account; ?>
</div>

<div class="button-grid">
    <button onclick="sendEvent(201)">event_201</button>
    <button onclick="sendEvent(202)">event_202</button>
    <button onclick="sendEvent(203)">event_203</button>
    <button onclick="sendEvent(204)">event_204</button>
    <button onclick="sendEvent(205)">event_205</button>
    <button onclick="sendEvent(206)">event_206</button>
    <button onclick="sendEvent(501)">event_501</button>
</div>
</body>
</html>

<script>
    function sendEvent(eventId) {
        fetch('/MobileDim/login/process-event.php', {
            method: 'POST',
            body: JSON.stringify({ eventId: 'event_' + eventId, valueToStore: 1 }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // เพิ่มบรรทัดนี้
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
