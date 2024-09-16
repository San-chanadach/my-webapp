<?php
function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array(
        "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
        "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    );
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

$objPHPExcel = PHPExcel_IOFactory::load("daily.xlsx");

if (isset($_POST['selected_date1'])) {
    $selectedDate = date("Y-m-d", strtotime($_POST['selected_date1']));
    $selectedStation = $_POST['selected_station'];

    $sql = "SELECT a.fullname, a.position, COUNT(a.username_account) AS count_username_account, SUM(e.respects_flag) 
AS respects_flag, SUM(e.five_s) AS five_s, SUM(e.event_201) AS event_201, SUM(e.event_202) 
AS event_202, SUM(e.event_203) AS event_203, SUM(e.event_204) AS event_204, SUM(e.event_205) 
AS event_205, SUM(e.event_206) AS event_206, SUM(e.event_501) AS event_501, SUM(e.event_guard) 
AS event_guard, SUM(e.prevent) AS prevent, SUM(e.meet_1) AS meet_1, SUM(e.event_support) 
AS event_support FROM account a LEFT JOIN event e ON a.id_account = e.id_account 
WHERE DATE(e.last_updated) = '" . $selectedDate . "' GROUP BY a.fullname, a.position";

    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        //ตั้งชื่อคอลัมน์ในแผ่นงาน Excel
        $thisdate =
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'วันที่ ' . DateThai($selectedDate . ' 12:00:00'))
            ->setCellValue('B3', $selectedStation);

        $row = 8;
        $count = 1;
        while ($row_data = $result->fetch_assoc()) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $count)
                ->setCellValue('B' . $row, $row_data['fullname'])
                ->setCellValue('C' . $row, $row_data['position']);

            if ($row_data['respects_flag'] == 1)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $row, 'P');
            else
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $row, 'P');

            if ($row_data['five_s'] == 1)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $row, 'P');
            else
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $row, 'P');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('H' . $row, $row_data['event_201'])
                ->setCellValue('I' . $row, $row_data['event_202'])
                ->setCellValue('J' . $row, $row_data['event_203'])
                ->setCellValue('K' . $row, $row_data['event_204'])
                ->setCellValue('L' . $row, $row_data['event_205'])
                ->setCellValue('M' . $row, $row_data['event_206'])
                ->setCellValue('N' . $row, $row_data['event_501'])
                ->setCellValue('O' . $row, $row_data['event_guard'])
                ->setCellValue('P' . $row, $row_data['prevent'])
                ->setCellValue('Q' . $row, $row_data['meet_1'])
                ->setCellValue('R' . $row, $row_data['event_support']);
            $row++;
            $count++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('รายงานประจำวัน');
        $objPHPExcel->setActiveSheetIndex(0);

        $file_export = "รายงานประจำวัน-" . DateThai($selectedDate . ' 12:00:00') . ".xlsx";

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_export . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
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
