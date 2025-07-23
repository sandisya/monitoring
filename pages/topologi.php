<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$query = $conn->query("SELECT id, nama, ip_address, jenis_perangkat, lokasi FROM perangkat WHERE status = 'Aktif'");
$perangkat = [];
while ($row = $query->fetch_assoc()) {
    $perangkat[] = $row;
}
// Ambil relasi
$relasi = [];
$res = $conn->query("SELECT dari_id, ke_id FROM relasi_perangkat");
while ($row = $res->fetch_assoc()) {
    $relasi[] = $row;
}

// Ambil posisi node
$posisi = [];
$res = $conn->query("SELECT id, pos_x, pos_y FROM perangkat WHERE pos_x IS NOT NULL AND pos_y IS NOT NULL");
while ($row = $res->fetch_assoc()) {
    $posisi[$row['id']] = $row;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Topologi Jaringan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/vis-network/styles/vis-network.min.css">
</head>
<body class="bg-gray-100">
    <body class="relative bg-gray-100">
    <style>
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: url(../bg.jpg);
            background-size: cover;
            background-position: center;
            filter: blur(2px); /* Atur seberapa blur */
            z-index: -1; /* Letakkan di belakang konten */
        }
    </style>


<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 p-6">
    <h1 class="text-3xl font-bold text-white mb-8 border border-blue-600 bg-blue-500 p-4 rounded-lg shadow">
    Topologi Jaringan
</h1>


    <div id="network" class="w-full h-[600px] bg-white shadow rounded p-4"></div>

    <script>
    const nodes = new vis.DataSet([
    <?php foreach ($perangkat as $index => $p): 
        $pos = $posisi[$p['id']] ?? null;
    ?>{
        id: <?= $p['id'] ?>,
        label: `<?= addslashes($p['nama']) ?>\n<?= $p['ip_address'] ?>`,
        title: `<?= addslashes($p['jenis_perangkat']) ?> - <?= addslashes($p['lokasi'] ?? '-') ?>`,
        shape: "box",
        color: "#749BC2",
        <?= $pos ? "x: {$pos['pos_x']}, y: {$pos['pos_y']}," : "" ?>
    }<?= $index < count($perangkat) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
]);


    const edges = [
        <?php foreach ($relasi as $r): ?>
            { from: <?= $r['dari_id'] ?>, to: <?= $r['ke_id'] ?> },
        <?php endforeach; ?>
    ];

    const container = document.getElementById('network');
    const data = { nodes, edges };

    const options = {
        nodes: {
            font: { size: 16, face: "Tahoma" },
            shape: "box",
            size: 20
        },
        edges: {
            arrows: "to",
            smooth: { type: "dynamic" }
        },
        physics: false, // Disable movement
        interaction: {
            hover: true,
            tooltipDelay: 100,
            dragNodes: true
        },
        manipulation: {
            enabled: true,
            addEdge: function (data, callback) {
                if (data.from === data.to) {
                    alert("Tidak bisa menghubungkan ke node yang sama.");
                    callback(null);
                    return;
                }

                if (confirm(`Hubungkan dari ${data.from} ke ${data.to}?`)) {
                    fetch('simpan_relasi.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            alert("Relasi berhasil disimpan.");
                            callback(data);
                        } else {
                            alert("Gagal menyimpan relasi: " + result.message);
                            callback(null);
                        }
                    })
                    .catch(err => {
                        console.error("AJAX error:", err);
                        alert("Terjadi kesalahan saat mengirim data.");
                        callback(null);
                    });
                } else {
                    callback(null);
                }
            }
        }
    };

    const network = new vis.Network(container, data, options);

    // Saat posisi node diubah, kirim ke server
    network.on("dragEnd", function (params) {
        if (params.nodes.length > 0) {
            const positions = network.getPositions(params.nodes);
            fetch("simpan_posisi.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(positions)
            });
        }
    });
</script>

</div>
</body>
</html>
