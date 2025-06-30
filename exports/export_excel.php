<?php
require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../includes/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Laporan Perangkat');

// Header kolom
$header = ['No','Nama','Jenis','IP','MAC','Lokasi','Status','Tanggal Instalasi'];
$col = 'A';
foreach ($header as $h) {
  $sheet->setCellValue($col.'1', $h);
  $col++;
}

// Data perangkat
$query = $conn->query("SELECT * FROM perangkat ORDER BY nama");
$rowNum = 2;
$no = 1;
while ($r = $query->fetch_assoc()) {
  $sheet->setCellValue('A'.$rowNum, $no++);
  $sheet->setCellValue('B'.$rowNum, $r['nama']);
  $sheet->setCellValue('C'.$rowNum, $r['jenis_perangkat']);
  $sheet->setCellValue('D'.$rowNum, $r['ip_address']);
  $sheet->setCellValue('E'.$rowNum, $r['mac_address']);
  $sheet->setCellValue('F'.$rowNum, $r['lokasi']);
  $sheet->setCellValue('G'.$rowNum, $r['status']);
  $sheet->setCellValue('H'.$rowNum, $r['tanggal_instalasi']);
  $rowNum++;
}

// Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="laporan_perangkat.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
