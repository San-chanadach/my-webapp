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
            $columns = ['event_201', 'event_202', 'event_203', 'event_204', 'event_205', 'event_206', 'event_501'];

            foreach ($columns as $column) {
                // คอลัมน์ที่จะถูกอัปเดต
                $projectNumber = substr($column, -3);
            
                if (in_array($projectNumber, $selectedProjects)) {
                    $updateStmt = $pdo->prepare("INSERT INTO event ($column, id_account, last_updated) VALUES (1, :id_account, NOW())");
                    $updateStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
                    $updateStmt->execute();
                } else {
                    $updateStmt = $pdo->prepare("INSERT INTO event ($column, id_account, last_updated) VALUES (0, :id_account, NOW())");
                    $updateStmt->bindParam(':id_account', $id_account, PDO::PARAM_INT);
                    $updateStmt->execute();
                }
            }
            header('Location: incident_event.php');
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

function getSum($columnName)
{
    try {
        $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $currentDate = date('Y-m-d');

        $query = "SELECT SUM($columnName) AS total FROM event WHERE DATE(last_updated) = :currentDate";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>งานระงับเหตุ</title>
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
                        <h1 class="text-white animated zoomIn">งานระงับเหตุ</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <div class="container-xxl py-5">
        <div class="container px-lg-5">
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body text-center">
                    <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                        <h2 class="mb-2">ลงทะเบียน</h2>
                    </div>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project1" value="201">
                                    <label class="form-check-label" for="project1">ระงับเหตุ 201</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project2" value="202">
                                    <label class="form-check-label" for="project2">ระงับเหตุ 202</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project3" value="203">
                                    <label class="form-check-label" for="project3">ระงับเหตุ 203</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project4" value="204">
                                    <label class="form-check-label" for="project4">ระงับเหตุ 204</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project5" value="205">
                                    <label class="form-check-label" for="project5">ระงับเหตุ 205</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project6" value="206">
                                    <label class="form-check-label" for="project6">ระงับเหตุ 206</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="projects[]" id="project7" value="501">
                                    <label class="form-check-label" for="project7">ระงับเหตุ 501</label>
                                </div>
                            </div>
                            <div class="col-md-4"></div> 
                            <div class="col-md-4"></div> 
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
    <div class="container-xxl py-5">
        <div class="container px-lg-5">
            <div class="card mx-auto">
                <div class="card-body text-center">
                    <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                    </div>
                    <table class="table">
                        <tr>
                            <td><strong>ระงับเหตุ 201</strong></td>
                            <td><?php echo getSum('event_201'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                        <tr>
                            <td><strong>ระงับเหตุ 202</strong></td>
                            <td><?php echo getSum('event_202'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                        <tr>
                            <td><strong>ระงับเหตุ 203</strong></td>
                            <td><?php echo getSum('event_203'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                        <tr>
                            <td><strong>ระงับเหตุ 204</strong></td>
                            <td><?php echo getSum('event_204'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                        <tr>
                            <td><strong>ระงับเหตุ 205</strong></td>
                            <td><?php echo getSum('event_205'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                        <tr>
                            <td><strong>ระงับเหตุ 206</strong></td>
                            <td><?php echo getSum('event_206'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                        <tr>
                            <td><strong>ระงับเหตุ 501</strong></td>
                            <td><?php echo getSum('event_501'); ?></td>
                            <td>(ครั้ง)</td>
                        </tr>
                    </table>
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