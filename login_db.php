<?php
session_start();
require('dbmain.php');

$errors = array();

if (isset($_POST['login_user'])) {
    $email_account = mysqli_real_escape_string($connection, $_POST['email_account']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    if (empty($email_account) || empty($password)) {
        array_push($errors, "กรุณากรอก email_account และรหัสผ่าน");
    }

    if (count($errors) == 0) {
        $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['email_account'] = $email_account;
            $_SESSION['success'] = "You are now logged in";
            header("Location: index.php");
        } else {
            array_push($errors, "อีเมลหรือรหัสผ่านไม่ถูกต้อง");
            $_SESSION['error'] = "ไม่สามารถเข้าสู่ระบบได้";
            header("Location: login.php");
        }
    }
}
?>
