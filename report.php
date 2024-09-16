<?php

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}

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

require 'vendor/autoload.php'; // Include PhpSpreadsheet library
require 'login/connect.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>รายงาน</title>
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
                                <h1 class="text-white animated zoomIn">รายงาน</h1>
                                <hr class="bg-white mx-auto mt-0" style="width: 90px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Navbar & Hero End -->

            <div class="container-xxl py-5">
                <!-- <div class="owl-carousel" id="projectSlider"> -->
                <div class="container px-lg-5 mx-auto">
                    <div class="card mx-auto" style="max-width: 400px;">
                        <h2 class="mb-2 text-center">รายงานสถานี</h2>
                        <div class="card-body text-center">
                            <div class="input-group mt-4">
                                <form method="POST" action="report_xls2.php">
                                    สถานี:<br>
                                    <select name="selected_station" class="form-control">
                                        <option value="">-- เลือกสถานี --</option>
                                        <?php
                                        // ดึงวันที่ที่มีอยู่ในคอลัมน์ last_updated จากฐานข้อมูล
                                        $sql = "SELECT station_name AS selected_station, station_belong FROM station";
                                        $result = $connect->query($sql);

                                        // แสดงวันที่เป็นตัวเลือก
                                        while ($row_data = $result->fetch_assoc()) {
                                            echo '<option value="' . $row_data['selected_station'] . '">' . $row_data['station_belong'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <br>วันที่<br>
                                    <input type="text" name="selected_date1" id="datepicker1" class="form-control" placeholder="เลือกวันที่">
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-success">Export->Excel</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="container px-lg-5 mx-auto">
                    <div class="card mx-auto" style="max-width: 400px;">
                        <h2 class="mb-2 text-center">รายงานยานพาหนะและอุปกรณ์</h2>
                        <div class="card-body text-center">
                            <div class="input-group mt-4">
                                <form method="POST" action="report_xls.php">
                                    สถานี:<br>
                                    <select name="selected_station" class="form-control">
                                        <option value="">-- เลือกสถานี --</option>
                                        <?php
                                        // ดึงวันที่ที่มีอยู่ในคอลัมน์ last_updated จากฐานข้อมูล
                                        $sql = "SELECT station_name AS selected_station, station_belong FROM station";
                                        $result = $connect->query($sql);

                                        // แสดงวันที่เป็นตัวเลือก
                                        while ($row_data = $result->fetch_assoc()) {
                                            echo '<option value="' . $row_data['selected_station'] . '">' . $row_data['station_belong'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <br>วันที่<br>
                                    <input type="text" name="selected_date2" id="datepicker2" class="form-control" placeholder="เลือกวันที่">
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-success">Export->Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
            </div>

            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="lib/wow/wow.min.js"></script>
            <script src="lib/easing/easing.min.js"></script>
            <script src="lib/waypoints/waypoints.min.js"></script>
            <script src="lib/owlcarousel/owl.carousel.min.js"></script>
            <script src="lib/isotope/isotope.pkgd.min.js"></script>
            <script src="lib/lightbox/js/lightbox.min.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="js/main.js"></script>
            <!-- สคริปต์ JavaScript สำหรับปฎิทิน -->
            <script>
                $(function() {
                    $("#datepicker1").datepicker({
                        dateFormat: "yy-mm-dd",
                        onSelect: function(dateText, inst) {
                            $("input[name='selected_date1']").val(dateText + ' 12:00:00');
                        }
                    });
                });
            </script>

            <script>
                $(function() {
                    $("#datepicker2").datepicker({
                        dateFormat: "yy-mm-dd",
                        onSelect: function(dateText, inst) {
                            $("input[name='selected_date2']").val(dateText + ' 12:00:00');
                        }
                    });
                });
            </script>

</html>