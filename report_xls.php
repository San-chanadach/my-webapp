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

require 'login/connect.php';


require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

// // Create new PHPExcel object
// $objPHPExcel = new PHPExcel();
$objPHPExcel = PHPExcel_IOFactory::load("report2.xlsx");


if (isset($_POST['selected_date2'])) {
    $selectedDate = date("Y-m-d", strtotime($_POST['selected_date2']));
    $selectedStation = $_POST['selected_station'];
    // ดำเนินการดึงข้อมูลของวันที่ที่ถูกเลือก
    $sql = "SELECT v.vehicle_name, v.number_plate, v.car_mileage, v.vehicle_status
    FROM vehicle v
    WHERE DATE(v.last_updated) = '" . $selectedDate . "'";


    $result = $connect->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        //ตั้งชื่อคอลัมน์ในแผ่นงาน Excel
        $thisdate =
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F4', 'วันที่ ' . DateThai($selectedDate . ' 12:00:00'))
            ->setCellValue('C3', $selectedStation)
            ->setCellValue('C6', $selectedStation);

        $row = 8;
        $count = 1;
        while ($row_data = $result->fetch_assoc()) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $count)
                ->setCellValue('B' . $row, $row_data['vehicle_name'])
                ->setCellValue('C' . $row, $row_data['number_plate'])
                ->setCellValue('I' . $row, $row_data['car_mileage']);

            if ($row_data['vehicle_status'] == 1)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $row, 'P');
            else
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $row, 'P');
            $row++;
            $count++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('รายงานยานพาหนะ');
        $objPHPExcel->setActiveSheetIndex(0);

        $file_export = "รายงานยานพาหนะ-" . DateThai($selectedDate . ' 12:00:00') . ".xlsx";

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Disposition: attachment;filename="Report.xlsx"');
        header('Content-Disposition: attachment;filename="' . $file_export . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        //header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    } else {
        echo "ไม่มีข้อมูลสำหรับวันที่นี้ " . $selectedDate;
        exit;
    }
} else {
    // // หากไม่มีวันที่ที่ถูกส่งมาผ่าน URL
    echo "วันที่ไม่ถูกส่งมา";
    exit;
}
