<?php
require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../includes/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Laporan Perangkat');

// Header kolom
$header = ['No', 'Nama', 'Jenis', 'IP', 'MAC', 'Lokasi', 'Status', 'Tanggal Instalasi'];
$col = 'A';
foreach ($header as $h) {
    $sheet->setCellValue($col . '1', $h);
    $col++;
}

// Styling header
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFD9D9D9']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
];

$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
$sheet->getRowDimension(1)->setRowHeight(25);

// Data perangkat
$query = $conn->query("SELECT * FROM perangkat ORDER BY nama");
$rowNum = 2;
$no = 1;
while ($r = $query->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $no++);
    $sheet->setCellValue('B' . $rowNum, $r['nama']);
    $sheet->setCellValue('C' . $rowNum, $r['jenis_perangkat']);
    $sheet->setCellValue('D' . $rowNum, $r['ip_address']);
    $sheet->setCellValue('E' . $rowNum, $r['mac_address']);
    $sheet->setCellValue('F' . $rowNum, $r['lokasi']);
    $sheet->setCellValue('G' . $rowNum, $r['status']);
    $sheet->setCellValue('H' . $rowNum, $r['tanggal_instalasi']);
    $rowNum++;
}

// Styling border seluruh tabel
$dataRange = 'A1:H' . ($rowNum - 1);
$sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Alignment center kolom "No"
$sheet->getStyle('A2:A' . ($rowNum - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Auto-size kolom
foreach (range('A', 'H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="laporan_perangkat.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
