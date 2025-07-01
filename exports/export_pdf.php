<?php
require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../includes/db.php';

$mpdf = new \Mpdf\Mpdf([
    'default_font' => 'dejavusans',
    'format' => 'A4',
    'margin_top' => 15,
    'margin_bottom' => 15
]);

// CSS untuk tampilan PDF
$style = '
<style>
  h2 {
    text-align: center;
    margin-bottom: 20px;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    font-size: 12px;
  }
  th, td {
    border: 1px solid #000;
    padding: 6px 8px;
    text-align: center;
    vertical-align: middle;
  }
  th {
    background-color: #f2f2f2;
    font-weight: bold;
  }
  .online {
    color: green;
    font-weight: bold;
  }
  .offline {
    color: red;
    font-weight: bold;
  }
</style>
';

$query = $conn->query("SELECT * FROM perangkat ORDER BY nama");

$html = $style;
$html .= '<h2>Laporan Data Perangkat</h2>';
$html .= '<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Jenis</th>
      <th>IP Address</th>
      <th>MAC Address</th>
      <th>Lokasi</th>
      <th>Status</th>
      <th>Tanggal Instalasi</th>
    </tr>
  </thead>
  <tbody>';

$no = 1;
while ($r = $query->fetch_assoc()) {
    // Optional: Warna status
    $statusClass = strtolower($r['status']) === 'online' ? 'online' : 'offline';

    $html .= '<tr>
      <td>' . $no++ . '</td>
      <td>' . htmlspecialchars($r['nama']) . '</td>
      <td>' . htmlspecialchars($r['jenis_perangkat']) . '</td>
      <td>' . htmlspecialchars($r['ip_address']) . '</td>
      <td>' . htmlspecialchars($r['mac_address']) . '</td>
      <td>' . htmlspecialchars($r['lokasi']) . '</td>
      <td class="' . $statusClass . '">' . htmlspecialchars($r['status']) . '</td>
      <td>' . htmlspecialchars($r['tanggal_instalasi']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output('laporan_perangkat.pdf', 'D'); // Download langsung
exit;
?>
