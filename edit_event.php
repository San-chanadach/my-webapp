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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (isset($_POST['projects']) && is_array($_POST['projects'])) {
        $selectedProjects = $_POST['projects']; // เก็บตัวเลือกที่ถูกเลือกในอาร์เรย์

        try {
            $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $id_account = $_SESSION['id_account'];
            $today = date('Y-m-d');

            $columns = ['event_201', 'event_202', 'event_203', 'event_204', 'event_205', 'event_206', 'event_501'];

            $initialTotal = 8; // ค่าผลรวมเดิม

            foreach ($columns as $key => $column) {
                $projectNumber = $key + 201;
                if (in_array(strval($projectNumber), $selectedProjects)) {
                    $selectedValue = 1;
                } else {
                    $selectedValue = 0;
                }
                $currentTotal = getSum($column);
                if ($selectedValue < $currentTotal) {
                    $searchStmt = $pdo->prepare("SELECT COUNT(*) AS count FROM event WHERE id_account = :id_account AND $column = 1 AND DATE(last_updated) = :today");
                    $searchStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
                    $searchStmt->bindParam(':today', $today, PDO::PARAM_STR);
                    $searchStmt->execute();
                    $result = $searchStmt->fetch(PDO::FETCH_ASSOC);

                    $currentTotal = $result['count'];
                }
                $updateValue = max(0, $selectedValue - ($initialTotal - $currentTotal));
                $updateStmt = $pdo->prepare("UPDATE event SET $column = :updateValue, last_updated = NOW() WHERE id_account = :id_account AND DATE(last_updated) = :today");
                $updateStmt->bindParam(':updateValue', $updateValue, PDO::PARAM_INT);
                $updateStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
                $updateStmt->bindParam(':today', $today, PDO::PARAM_STR);
                $updateStmt->execute();
            }

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
function getEventDataForEventType($columnName)
{
    try {
        $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $currentDate = date('Y-m-d');

        $query = "SELECT $columnName AS event_count, last_updated FROM event WHERE DATE(last_updated) = :currentDate AND $columnName = 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>แก้ไขข้อมูล</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar & Hero Start -->
    <div class="container-xxl position-relative p-0">
    <div class="navbar">
            <a href="index.php" class="navbar-logo">
                <img src="/MobileDim/img/home.png" alt="Check Icon">
            </a>
        <div class="container-xxl py-5 bg-primary hero-header mb-5">
            <div class="container my-5 py-5 px-lg-5">
                <div class="row g-5 py-5">
                    <div class="col-12 text-center">
                        <h1 class="text-white animated zoomIn">แก้ไขข้อมูล</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <div class="container-xxl py-5">
        <div class="container px-lg-5">
            <div class="card mx-auto">
                <div class="card-body text-center">
                    <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                    </div>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 201</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_201');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=201&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 202</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_202');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=202&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 203</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_203');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=203&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 204</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_204');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=204&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 205</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_205');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=205&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 206</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_206');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=206&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <table class="table">
                        <tr>
                            <th>ระงับเหตุ 501</th>
                            <th>วันที่และเวลา</th>
                        </tr>
                        <?php
                        $event201Data = getEventDataForEventType('event_501');
                        foreach ($event201Data as $event) {
                            echo '<tr>';
                            echo '<td>' . ($event['event_count'] == 1 ? '<img src="/MobileDim/img/check2.png" alt="Check Icon" />' : $event['event_count']) . '</td>';
                            echo '<td>' . $event['last_updated'] . '</td>';
                            echo '<td><a href="delete.php?event=501&timestamp=' . $event['last_updated'] . '" class="btn btn-danger">ลบ</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container-xxl py-5">
        <div class="container px-lg-5">
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body text-center">
                    <div class="text-center" data-wow-delay="0.1s">
                    </div>
                    <form method="POST">
                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>