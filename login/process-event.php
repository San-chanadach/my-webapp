<?php
session_start();
$open_connect = 1;

// ตรวจสอบการรับค่าจาก JavaScript
if (isset($_POST['event'])) {
    $eventName = $_POST['event'];

    // สร้างการเชื่อมต่อกับฐานข้อมูล MySQL
    $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ตรวจสอบสิทธิ์และอื่น ๆ ตามที่คุณต้องการ
    if (!isset($_SESSION['id_account']) || !isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'admin') {
        echo "คุณไม่มีสิทธิ์เข้าถึง";
        exit;
    }

    // เป็นตัวอย่างการ insert เข้าตาราง event
    // และใส่ค่าสิทธิ์ของผู้ใช้ (id_account) และค่า event_name
    $id_account = $_SESSION['id_account'];
    $updateStmt = $pdo->prepare("INSERT INTO event (event_name, id_account, last_updated) VALUES (:event_name, :id_account, NOW())");
    $updateStmt->bindParam(':event_name', $eventName, PDO::PARAM_STR);
    $updateStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
    $updateStmt->execute();

    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
} else {
    echo "ไม่มีข้อมูลที่จะบันทึก";
}
?>
