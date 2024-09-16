<?php
session_start();
$open_connect = 1;

if (!isset($_SESSION['id_account'])) {
    header('Location: /MobileDim/login.php');
    exit;
}

if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'admin') {
    header('Location: /MobileDim/permission_denied.php');
    exit;
}

require_once 'login/connect.php';

if (isset($_GET['event']) && isset($_GET['timestamp'])) {
    $event = $_GET['event'];
    $timestamp = $_GET['timestamp'];

    $formattedTimestamp = date("Y-m-d H:i:s", strtotime($timestamp));

    try {
        $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $updateStmt = $pdo->prepare("UPDATE event SET event_$event = 0 WHERE last_updated = :timestamp");
        $updateStmt->bindParam(':timestamp', $formattedTimestamp, PDO::PARAM_STR);
        $updateStmt->execute();

        header('Location: edit_event.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: edit_event.php');
    exit;
}


?>
