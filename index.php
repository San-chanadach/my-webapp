<?php
session_start();
$open_connect = 1;

if (!isset($_SESSION['id_account'])) {
    header('Location: /MobileDim/login.php');
    exit;
}
if (isset($_SESSION['id_account']) && $_SESSION['role_account'] === 'admin') {
} else {
    header('Location: /MobileDim/index2.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=192.168.0.70;dbname=mydim", "root", "supal898");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $currentDate = date('Y-m-d');

    // ดึงค่า respects_flag จากฐานข้อมูลเฉพาะข้อมูลวันที่ปัจจุบัน
    $query = "SELECT COUNT(*) as flag_count FROM event WHERE DATE(last_updated) = :currentDate AND respects_flag = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $respectsFlagValue = ($result['flag_count'] > 0) ? 1 : 0;

    // ดึงค่า five_s จากฐานข้อมูลเฉพาะข้อมูลวันที่ปัจจุบัน
    $query = "SELECT five_s FROM event WHERE DATE(last_updated) = :currentDate AND five_s = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $fiveSValue = ($result['five_s'] > 0) ? 1 : 0;    

    // ดึงค่า event_guard จากฐานข้อมูลเฉพาะข้อมูลวันที่ปัจจุบัน
    $query = "SELECT event_guard FROM event WHERE DATE(last_updated) = :currentDate AND event_guard = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $eventGuardValue = ($result['event_guard'] > 0) ? 1 : 0;

    // ดึงค่า meet_1 จากฐานข้อมูลเฉพาะข้อมูลวันที่ปัจจุบัน
    $query = "SELECT meet_1 FROM event WHERE DATE(last_updated) = :currentDate AND meet_1 = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $meet1Value = ($result['meet_1'] > 0) ? 1 : 0;

    // ดึงค่า prevent จากฐานข้อมูลเฉพาะข้อมูลวันที่ปัจจุบัน
    $query = "SELECT prevent FROM event WHERE DATE(last_updated) = :currentDate AND prevent = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $preventValue = ($result['prevent'] > 0) ? 1 : 0;

    // ดึงค่า event_support จากฐานข้อมูลเฉพาะข้อมูลวันที่ปัจจุบัน
    $query = "SELECT event_support FROM event WHERE DATE(last_updated) = :currentDate AND event_support = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $eventSupportValue = ($result['event_support'] > 0) ? 1 : 0;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>RapidDIM</title>
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
            <div class="container-xxl py-5 bg-primary hero-header mb-5">
                <div class="container my-5 py-5 px-lg-5">
                    <div class="section-title position-relative text-center mb-5 pb-2 wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="text-white animated zoomIn">ระบบรายงานประจำวัน</h6>
                        <h2 class="mt-2">RapidDIM</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <!-- Service Start -->
        <div class="container-xxl py-5">
            <div class="container px-lg-5">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.1s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded<?php if 
                        ($respectsFlagValue == 1) echo ' respects-flag-active'; ?>">
                            <a href="flagcheck.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/fireman.png">
                                </div>
                                <h5 class="mb-3">เคารพธงชาติ</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
                    <div class="service-item d-flex flex-column justify-content-center text-center rounded<?php if 
                        ($fiveSValue == 1) echo ' respects-flag-active'; ?>">
                            <a href="five_s.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/fire.png">
                                </div>
                                <h5 class="mb-3">5ส.</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.1s">
                    <div class="service-item d-flex flex-column justify-content-center text-center rounded<?php if 
                        ($eventGuardValue == 1) echo ' respects-flag-active'; ?>">
                            <a href="event_guard.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/shield.png">
                                </div>
                                <h5 class="mb-3">กู้ภัย</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.1s">
                    <div class="service-item d-flex flex-column justify-content-center text-center rounded<?php if 
                        ($preventValue == 1) echo ' respects-flag-active'; ?>">
                            <a href="event_prevent.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/shield.png">
                                </div>
                                <h5 class="mb-3">งานป้องกัน</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
                    <div class="service-item d-flex flex-column justify-content-center text-center rounded<?php if 
                        ($meet1Value == 1) echo ' respects-flag-active'; ?>">
                            <a href="meeting.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/meeting.png">
                                </div>
                                <h5 class="mb-3">ร่วมประชุม</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
                    <div class="service-item d-flex flex-column justify-content-center text-center rounded<?php if 
                        ($eventSupportValue == 1) echo ' respects-flag-active'; ?>">
                            <a href="event_support.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/station.png">
                                </div>
                                <h5 class="mb-3">งานสนับสนุน</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded custom2-bg-class">
                            <a href="incident_event.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/house.png">
                                </div>
                                <h5 class="mb-3">งานระงับเหตุ</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded custom2-bg-class">
                            <a href="accept.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/house.png">
                                </div>
                                <h5 class="mb-3">งานรับมอบ</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded custom-bg-class">
                            <a href="edit_event.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/file.png">
                                </div>
                                <h5 class="mb-3">แก้ไขข้อมูล</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded custom-bg-class">
                            <a href="report.php">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/report.png">
                                </div>
                                <h5 class="mb-3">รายงาน</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
                        <div class="service-item d-flex flex-column justify-content-center text-center rounded custom-bg-class">
                            <a href="login.php?logout=1">
                                <div class="service-icon flex-shrink-0">
                                    <img src="/MobileDim/img/logout.png">
                                </div>
                                <h5 class="mb-3">ออกจากระบบ</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->
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