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
$id_account = $_SESSION['id_account'];
try {
    $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $selectedOptions1 = isset($_POST['project1']) ? $_POST['project1'] : [];
    foreach ($selectedOptions1 as $selectedOption1) {
        // ตรวจสอบว่ามีข้อมูลที่ถูกเลือกจากฟอร์มหรือไม่
        if (isset($_POST['selected_row']) && is_numeric($_POST['selected_row'])) {
            $selectedRow = $_POST['selected_row'];
            $stmt = $pdo->prepare("INSERT INTO vehicle (vehicle_status, id_account, vehicle_name, number_plate, car_mileage, last_updated) 
                                    SELECT :update_value, :id_account, vehicle_name, number_plate, car_mileage, NOW() 
                                    FROM vehicle 
                                    WHERE vehicle_status = :selected_option 
                                    AND some_unique_id_column = :selected_row
                                    ORDER BY number_plate DESC 
                                    LIMIT 1");
            $stmt->bindParam(':update_value', $selectedOption1, PDO::PARAM_INT);
            $stmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
            $stmt->bindParam(':selected_option', $selectedOption1, PDO::PARAM_INT);
            $stmt->bindParam(':selected_row', $selectedRow, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: accept.php');
            exit;
        } else {
            // กรณีไม่มีข้อมูลที่ถูกเลือกจากฟอร์ม
            echo "Please select a row before submitting.";
        }
    }
}
