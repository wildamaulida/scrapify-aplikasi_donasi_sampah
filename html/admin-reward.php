<?php
include "koneksi.php";

// Query untuk mengambil semua data dari tabel reward dan nama dari tabel pengguna
$query = "SELECT r.id, r.pengguna_id, r.poin_convert, r.wallet, r.nomor_ewallet, r.rupiah, r.status, r.tanggal, p.nama 
          FROM reward r 
          JOIN pengguna p ON r.pengguna_id = p.pengguna_id 
          ORDER BY r.tanggal DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->store_result();

// Bind hasil dengan variabel
$stmt->bind_result($id, $pengguna_id, $poin_convert, $wallet, $nomor_ewallet, $rupiah, $status, $tanggal, $nama);

// Proses aksi accept atau reject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reward_id = $_POST['reward_id'];
    $action = $_POST['action'];

    // Update status berdasarkan aksi
    $new_status = ($action == 'accept') ? 'Disetujui' : 'Ditolak';
    $update_query = "UPDATE reward SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("si", $new_status, $reward_id);
    $stmt_update->execute();
    $stmt_update->close();
    echo "Status berhasil diperbarui.";
    header("Location: admin-reward.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Reward Konversi Poin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #43a047;
            --secondary-color: #388e3c;
            --accent-color: #ff7043;
            --text-dark: #2d3436;
            --text-light: #636e72;
            --background-light: #f5f6fa;
            --sidebar-width: 250px; /* Added for consistent sidebar width */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
        }

        /* Content Area with left margin for navbar */
        .content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
            color:rgb(7, 49, 29);
        }

        /* Modern Header */
        .header {
            background-color: var(--background-light);
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: var(--text-dark);
            font-size: 24px;
        }

        /* Enhanced Table Design */
        .reward-section {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .reward-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
        }

        .reward-table th {
            background-color: var(--primary-color);
            color: var(--background-light);
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .reward-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .reward-table tr {
            color: black;
        }

        .reward-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Interactive Buttons */
        .status-button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 0 5px;
        }

        .status-button.accept {
            background-color: #4caf50;
            color: var(--background-light);
        }

        .status-button.reject {
            background-color: #e53935;
            color: var(--background-light);
        }

        .status-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(5, 5, 5, 0.2);
        }

        /* Status Badge */
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #ffeaa7;
            color: rgb(209, 146, 70);
        }

        .status-verified {
            background-color: #c8f7c5;
            color: var(--primary-color);
        }

        .status-rejected {
            background-color: #ffcdd2;
            color: #e53935;
        }

        /* New styles for approved and rejected */
        .status-approved {
            background-color: #c8f7c5; /* Green for approved */
            color: var(--primary-color);
        }

        .status-rejected {
            background-color: #ffcdd2; /* Red for rejected */
            color: #e53935;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content {
              margin-top: 60px;
                margin-left: 0; /* Remove margin on mobile */
                padding: 15px;
            }

            .reward-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <!-- Area for including navbar -->
    <?php include 'admin-navbar.php'; ?> 

    <div class="content">
        <div class="reward-section animate_animated animate_fadeIn">
            <h2>Pengelolaan Konversi Poin</h2>
            <p class="text-light">Daftar Pengajuan Konversi Poin Pengguna</p>

            <table class="reward-table">
                <thead>
                    <tr>
    
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Poin</th>
                        <th>E-Wallet</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($stmt->fetch()): ?>
            <tr>
                <td><?php echo $pengguna_id; ?></td>
                <td><?php echo htmlspecialchars($nama); ?></td>
                <td><?php echo $poin_convert; ?></td>
                <td><?php echo $nomor_ewallet; ?></td>
                <td>
                    <span id="status-<?php echo $id; ?>" class="status-badge 
                        <?php echo ($status == 'Disetujui') ? 'status-approved' : (($status == 'Ditolak') ? 'status-rejected' : 'status-pending'); ?>">
                        <?php echo $status; ?>
                    </span>
                    <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="reward_id" value="<?php echo $id; ?>">
                        <button class="status-button accept" type="submit" name="action" value="accept">Setujui</button>
                    </form>
                    <form method="POST" style="display:inline;">
                    <input type="hidden" name="reward_id" value="<?php echo $id; ?>">
                    <button class="status-button reject" type="submit" name="action" value="reject">Tolak</button>
                    </form>
                    </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function verifyConversion(id) {
            if (confirm('Apakah Anda yakin ingin memverifikasi konversi poin ini?')) {
                const statusElement = document.getElementById("status-" + id);
                statusElement.textContent = "Disetujui";
                statusElement.className = "status-badge status-verified";
                alert(Konversi Poin ID ${id} berhasil diverifikasi!);
            }
        }

        function rejectConversion(id) {
            if (confirm('Apakah Anda yakin ingin menolak konversi poin ini?')) {
                const statusElement = document.getElementById("status-" + id);
                statusElement.textContent = "Ditolak";
                statusElement.className = "status-badge status-rejected";
                alert(Konversi Poin ID ${id} ditolak!);
            }
        }

        
    </script>
</body>
</html>