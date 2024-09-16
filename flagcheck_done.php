<?php
session_start();
$open_connect = 1;

// Check if the user is logged in
if (!isset($_SESSION['id_account'])) {
    // User is not logged in, you may want to redirect them to the login page
    header('Location: /MobileDim/login.php');
    exit;
}

// Check if the user has the necessary permissions to access this page
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'admin') {
    // Redirect the user to a different page or show an error message
    header('Location: /MobileDim/permission_denied.php');
    exit;
}

// Include the database connection file
require_once 'login/connect.php';

// Check if the user has submitted the form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Check which option the user selected
    if (isset($_POST['project']) && ($_POST['project'] === 'มา' || $_POST['project'] === 'ไม่มา')) {
        $selectedOption = ($_POST['project'] === 'มา') ? 1 : 0; // ถ้า 'มา' เป็น 1 และถ้า 'ไม่มา' เป็น 0

        try {
            $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $id_account = $_SESSION['id_account'];
            $updateStmt = $pdo->prepare("INSERT INTO event (respects_flag, id_account, last_updated) VALUES (:update_value, :id_account, NOW())");
            $updateStmt->bindParam(':update_value', $selectedOption, PDO::PARAM_INT);
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
    <title>เคารพธงชาติ</title>
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
                            <h1 class="text-white animated zoomIn">เคารพธงชาติ</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <!-- Full Screen Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content" style="background: rgba(29, 29, 39, 0.7);">
                    <div class="modal-body d-flex align-items-center justify-content-center">
                    </div>
                </div>
            </div>
        </div>
        <!-- Full Screen Search End -->

        <!-- Portfolio Start -->
        <div class="container-xxl py-5">
            <div class="container px-lg-5">
                <div class="card mx-auto" style="max-width: 400px;">
                    <div class="card-body text-center">
                        <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="mb-3">วันนี้ลงทะเบียนเรียบร้อย</h6>
                        </div>
                        <form method="POST">
                            <div class="service-icon flex-shrink-0">
                                <img src="/MobileDim/img/check.png">
                            </div>
                            <div class="text-center mt-4">
                                <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
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
</body>

</html>
