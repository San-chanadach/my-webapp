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

    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM event WHERE DATE(last_updated) = CURDATE() AND id_account = :id_account AND event_support IS NOT NULL");
    $checkStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
    $checkStmt->execute();

    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        header('Location: event_support_done.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (isset($_POST['project']) && ($_POST['project'] === 'มา' || $_POST['project'] === 'ไม่มา')) {
        $selectedOption = ($_POST['project'] === 'มา') ? 1 : 0; // ถ้า 'มา' เป็น 1 และถ้า 'ไม่มา' เป็น 0

        // เพิ่มการดึงค่าที่เลือกจาก Dropdown มาเป็นข้อมูล
        $selectedSubEventId = $_POST['sub_event_id'];
        $eventNote = '';

        if (!empty($selectedSubEventId)) {
            $subEventStmt = $pdo->prepare("SELECT name FROM sub_event WHERE id_sub_event = :id_sub_event");
            $subEventStmt->bindParam(':id_sub_event', $selectedSubEventId, PDO::PARAM_INT);
            $subEventStmt->execute();

            $subEventRow = $subEventStmt->fetch(PDO::FETCH_ASSOC);

            if ($subEventRow) {
                $eventNote = $subEventRow['name'];
            }
        }

        try {
            $updateStmt = $pdo->prepare("INSERT INTO event (event_support, event_note, id_account, last_updated) VALUES (:update_value, :event_note, :id_account, NOW())");
            $updateStmt->bindParam(':update_value', $selectedOption, PDO::PARAM_INT);
            $updateStmt->bindParam(':event_note', $eventNote, PDO::PARAM_STR);
            $updateStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
            $updateStmt->execute();

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>งานสนับสนุน</title>
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
    <div class="container-xxl bg-white p-0">
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
                                <h1 class="text-white animated zoomIn">งานสนับสนุน</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Navbar & Hero End -->

            <!-- Portfolio Start -->
            <div class="container-xxl py-5">
                <div class="container px-lg-5">
                    <div class="card mx-auto" style="max-width: 400px;">
                        <div class="card-body text-center">
                            <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                                <h2 class="mb-2">ลงทะเบียน</h2>
                            </div>
                            <form method="POST">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="project" id="project1" value="มา">
                                    <label class="form-check-label" for="project1">มา</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="project" id="project2" value="ไม่มา">
                                    <label class="form-check-label" for="project2">ไม่มา</label>
                                </div>
                                <div class="wrap-input100 validate-input">
                                    <select id="sub_event_dropdown" class="input100 text-center" name="sub_event_id" required>
                                        <option value="">--เลือกรายละเอียด--</option>
                                        <?php
                                        require_once 'login/connect.php';
                                        $sql = "SELECT id_sub_event, name FROM sub_event WHERE type = 'event_support'";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute();
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['id_sub_event'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="text-center mt-4">
                                    <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
                                    <button type="submit" name="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Portfolio End -->

            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top pt-2"><i class="bi bi-arrow-up"></i></a>
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
            document.addEventListener('DOMContentLoaded', function() {
                const projectRadio = document.getElementsByName('project');
                const subEventDropdown = document.getElementById('sub_event_dropdown');

                function checkDropdownVisibility() {
                    if (projectRadio[0].checked) {
                        subEventDropdown.style.display = 'block';
                    } else {
                        subEventDropdown.style.display = 'none';
                    }
                }
                checkDropdownVisibility();
                for (let i = 0; i < projectRadio.length; i++) {
                    projectRadio[i].addEventListener('change', checkDropdownVisibility);
                }
            });
        </script>
</body>

</html>