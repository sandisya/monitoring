<?php
require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../includes/db.php';

$mpdf = new \Mpdf\Mpdf();
$query = $conn->query("SELECT * FROM perangkat ORDER BY nama");
$html = '<h2>Data Perangkat</h2><table width="100%" border="1" cellpadding="5">
  <tr style="background:#f2f2f2;">
    <th>No</th><th>Nama</th><th>Jenis</th><th>IP</th><th>MAC</th><th>Lokasi</th><th>Status</th><th>Tgl Instalasi</th>
  </tr>';
$no = 1;
while ($r = $query->fetch_assoc()) {
  $html .= '<tr><td>' . $no++ . '</td><td>' . htmlspecialchars($r['nama']) . '</td><td>' . $r['jenis_perangkat'] . '</td><td>' . $r['ip_address'] . '</td><td>' . $r['mac_address'] . '</td><td>' . $r['lokasi'] . '</td><td>' . $r['status'] . '</td><td>' . $r['tanggal_instalasi'] . '</td></tr>';
}
$html .= '</table>';

$mpdf->WriteHTML($html);
$mpdf->Output('laporan_perangkat.pdf', 'D'); // Download langsung
exit;
?>
