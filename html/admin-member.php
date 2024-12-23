<?php
include "koneksi.php";
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data pengguna dengan total poin dari tabel donasi_sampah
$sql = "
    SELECT 
        p.pengguna_id, 
        p.nama, 
        p.email, 
        p.tanggal_lahir, 
        p.kontak, 
        COALESCE(SUM(d.poin), 0) AS total_poin
    FROM 
        pengguna p
    LEFT JOIN 
        donasi_sampah d 
    ON 
        p.pengguna_id = d.pengguna_id
    GROUP BY 
        p.pengguna_id, p.nama, p.email, p.tanggal_lahir, p.kontak
";
$result = $conn->query($sql);

// Hitung total pengguna dan total poin
$totalUsers = 0;
$totalPoints = 0;
$topPointUser = ['nama' => '-', 'total_poin' => 0];

if ($result && $result->num_rows > 0) {
    $totalUsers = $result->num_rows; // Total pengguna dari query
    while ($row = $result->fetch_assoc()) {
        $totalPoints += $row['total_poin'];
        if ($row['total_poin'] > $topPointUser['total_poin']) {
            $topPointUser = ['nama' => $row['nama'], 'total_poin' => $row['total_poin']];
        }
    }
    // Reset result pointer for reuse
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Member Page</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <style>
        :root {
            --primary:rgb(25, 71, 21);
            --primary-light:rgb(76, 128, 83);
            --secondary: #0ea5e9;
            --accent:rgb(92, 143, 81);
            --success:rgb(10, 57, 27);
            --warning: #eab308;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --bg-primary: #f8fafc;
            --bg-secondary: #f1f5f9;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
        } 

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-title {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .stat-icon {
            float: right;
            color: var(--primary-light);
            opacity: 0.8;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
        }

        th {
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        td {
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }

        tr {
            transition: background-color 0.2s;
        }

        tr:hover {
            background-color: var(--bg-primary);
        }

        .points-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--warning);
            font-weight: 500;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #dcfce7;
            color: var(--success);
        }

        .badge-warning {
            background-color: #fef9c3;
            color: var(--warning);
        }

        .table-footer {
            padding: 1rem;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            font-size: 0.875rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .page-button {
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--border);
            background: white;
            border-radius: 6px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .page-button:hover {
            background: var(--bg-secondary);
            border-color: var(--primary-light);
        }

        .page-button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        @media (max-width: 1024px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-container {
                border-radius: 8px;
            }
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
                margin-top: 60px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .title {
                font-size: 1.25rem;
            }

            th, td {
                padding: 0.75rem;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <?php include 'admin-navbar.php'; ?>
    
    <div class="content">
        <div class="page-header">
            <h1 class="title text-lg font-bold">List Member</h1>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users fa-lg stat-icon"></i>
                <div class="stat-title">Total Members</div>
                <div class="stat-value"><?php echo $totalUsers; ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-star fa-lg stat-icon"></i>
                <div class="stat-title">Total Points</div>
                <div class="stat-value"><?php echo $totalPoints; ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-trophy fa-lg stat-icon"></i>
                <div class="stat-title">Top Points</div>
                <div class="stat-value"><?php echo $topPointUser['nama']; ?> (<?php echo $topPointUser['total_poin']; ?> poin)</div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Kontak</th>
                        <th>Total Poin</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
          <?php $no = 1; // Inisialisasi nomor urut ?>
          <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['pengguna_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_lahir']); ?></td>
                        <td><?php echo htmlspecialchars($row['kontak']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_poin']); ?></td>
                        
                        
                        
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data pengguna.</td>
                </tr>
            <?php endif; ?>
                </tbody>
            </table>

            <div class="table-footer">
                <p>Showing <?php echo $result->num_rows; ?> of <?php echo $totalUsers; ?> members</p>
                <div class="pagination">
                    <button class="page-button" disabled>&lt;</button>
                    <button class="page-button active">1</button>
                    <button class="page-button" disabled>&gt;</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>