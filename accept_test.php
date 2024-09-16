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

// function getEventDataForEventType($columnName)
// {
//     try {
//         $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//         $currentDate = date('Y-m-d');

//         $query = "SELECT $columnName AS event_count, last_updated FROM event WHERE DATE(last_updated) = :currentDate AND $columnName = 1";
//         $stmt = $pdo->prepare($query);
//         $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
//         $stmt->execute();
//         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         return $result;
//     } catch (PDOException $e) {
//         return "Error: " . $e->getMessage();
//     }
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>รายงานรับมอบของหลวง</title>
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
    <link href="css/slider.css" rel="stylesheet">
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
                            <h1 class="text-white animated zoomIn">รายงานรับมอบของหลวง</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->
        <div class="container-xxl py-5">
            <div class="owl-carousel" id="projectSlider">
                <div class="container px-lg-5">
                    <div class="card mx-auto" style="max-width: 400px;">
                        <div class="card-body text-center">
                            <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                                <h2 class="mb-2">ยานพาหนะ</h2>
                            </div>
                            <form method="POST">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ชื่อ</th>
                                            <th>หมายเลข</th>
                                            <th>ใช้ได้</th>
                                            <th>ชำรุด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
                                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $previousEquipmentNumber = null; // เพื่อตรวจสอบค่าก่อนหน้า
                                        $sql = "SELECT equipment_name, equipment_number FROM equipment ORDER BY equipment_number DESC";
                                        $stmt = $pdo->query($sql);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $equipmentName = $row['equipment_name'];
                                            $equipmentNumber = $row['equipment_number'];
                                            if ($equipmentNumber !== $previousEquipmentNumber) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $equipmentName; ?></td>
                                                    <td><?php echo $equipmentNumber; ?></td>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox" name="projects[]" value="1">
                                                    </td>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox" name="projects[]" value="2">
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                            $previousEquipmentNumber = $equipmentNumber;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <!---------------------------------------------------- การ์ดที่ 2 ---------------------------------------------------->
                <div class="container px-lg-5">
                    <div class="card mx-auto" style="max-width: 400px;">
                        <div class="card-body text-center">
                            <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                                <h2 class="mb-2">วัสดุอุปกรณ์</h2>
                            </div>
                            <form method="POST">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ชื่อ</th>
                                            <th>หมายเลข</th>
                                            <th>ใช้ได้</th>
                                            <th>ชำรุด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
                                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $previousEquipmentNumber = null; // เพื่อตรวจสอบค่าก่อนหน้า
                                        $sql = "SELECT equipment_name, equipment_number FROM equipment ORDER BY equipment_number DESC";
                                        $stmt = $pdo->query($sql);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $equipmentName = $row['equipment_name'];
                                            $equipmentNumber = $row['equipment_number'];
                                            // ตรวจสอบค่าก่อนหน้าและแสดงเฉพาะข้อมูลล่าสุดถ้า equipment_number ซ้ำกัน
                                            if ($equipmentNumber !== $previousEquipmentNumber) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $equipmentName; ?></td>
                                                    <td><?php echo $equipmentNumber; ?></td>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox" name="projects[]" value="1">
                                                    </td>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox" name="projects[]" value="2">
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                            $previousEquipmentNumber = $equipmentNumber;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-center mt-4">
                                    <button type="submit" name="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a class="btn prev-card">
                    <img src="/MobileDim/img/prev.png">
                </a>
                <a class="btn next-card">
                    <img src="/MobileDim/img/next.png">
                </a>
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

    <script>
        $(document).ready(function() {
            $("#projectSlider").owlCarousel({
                items: 1,
                loop: true,
                margin: 10,
                nav: false,
                dots: true,
            });
            $('.prev-card').click(function() {
                $("#projectSlider").trigger('prev.owl.carousel');
            });

            $('.next-card').click(function() {
                $("#projectSlider").trigger('next.owl.carousel');
            });
        });
    </script>
</body>

</html>