<?php
include "koneksi.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['pengguna_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

$pengguna_id = $_SESSION['pengguna_id'];
$message = '';

// Get total points
$query = "SELECT SUM(poin) AS total_poin FROM donasi_sampah WHERE pengguna_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pengguna_id);
$stmt->execute();
$stmt->bind_result($total_poin);
$stmt->fetch();
$stmt->close();

if ($total_poin === null) {
    $total_poin = 0;
}

// Get exchange history
$history_query = "SELECT tanggal, poin_convert, rupiah, status, wallet, nomor_ewallet FROM reward WHERE pengguna_id = ? ORDER BY tanggal DESC";
$stmt_history = $conn->prepare($history_query);
$stmt_history->bind_param("i", $pengguna_id);
$stmt_history->execute();
$stmt_history->bind_result($tanggal, $poin_convert, $rupiah, $status, $wallet, $nomor_ewallet);

$history = [];
while ($stmt_history->fetch()) {
    $history[] = [
        'tanggal' => $tanggal,
        'poin_convert' => $poin_convert,
        'rupiah' => $rupiah,
        'status' => $status,
        'wallet' => $wallet,
        'nomor_ewallet' => $nomor_ewallet,
    ];
}
$stmt_history->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $poin_untuk_konversi = $_POST['poin'];
    $wallet_type = $_POST['wallet_type'];
    $ewallet_number = $_POST['ewallet_number'];

    if ($poin_untuk_konversi < 50000) {
        $message = "Jumlah poin yang ingin dikonversi harus minimal 50.000.";
    } elseif ($poin_untuk_konversi > $total_poin) {
        $message = "Poin yang ingin dikonversi melebihi jumlah poin Anda.";
    } else {
        $rupiah = $poin_untuk_konversi;

        $insert_query = "INSERT INTO reward (pengguna_id, poin_convert, wallet, nomor_ewallet, rupiah, status) 
                        VALUES (?, ?, ?, ?, ?, 'Belum Terverifikasi')";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iissi", $pengguna_id, $poin_untuk_konversi, $wallet_type, $ewallet_number, $rupiah);
        
        if ($stmt->execute()) {
            $update_query = "UPDATE donasi_sampah SET poin = poin - ? WHERE pengguna_id = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("ii", $poin_untuk_konversi, $pengguna_id);
            $stmt_update->execute();
            $stmt_update->close();

            $total_poin -= $poin_untuk_konversi;
            $message = "Konversi poin berhasil dilakukan!";
            
            // Refresh history after successful conversion
            $stmt_history = $conn->prepare($history_query);
            $stmt_history->bind_param("i", $pengguna_id);
            $stmt_history->execute();
            $stmt_history->bind_result($tanggal, $poin_convert, $rupiah, $status, $wallet, $nomor_ewallet);
            
            $history = [];
            while ($stmt_history->fetch()) {
                $history[] = [
                    'tanggal' => $tanggal,
                    'poin_convert' => $poin_convert,
                    'rupiah' => $rupiah,
                    'status' => $status,
                    'wallet' => $wallet,
                    'nomor_ewallet' => $nomor_ewallet,
                ];
            }
            $stmt_history->close();
        } else {
            $message = "Terjadi kesalahan dalam proses konversi.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Penukaran Reward - Bank Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
            min-height: 100vh;
        }
        .reward-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .reward-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <?php include 'member-navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Reward Exchange Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 reward-card">
                <h1 class="text-3xl font-bold text-green-600 text-center mb-6">Tukar Reward</h1>
                
                <!-- Points Display -->
                <div class="bg-green-50 rounded-lg p-4 text-center mb-6">
                    <p class="text-gray-600">Total Poin Anda</p>
                    <p class="text-4xl font-bold text-green-600"><?php echo number_format($total_poin, 0, ',', '.'); ?></p>
                    <p class="text-gray-500">Setara Rp <?php echo number_format($total_poin, 0, ',', '.'); ?></p>
                </div>

                <!-- Exchange Form -->
                <form id="rewardForm" method="POST" onsubmit="return validateForm(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah Poin untuk Ditukar
                            </label>
                            <input 
                                type="number" 
                                name="poin"
                                placeholder="Minimal 50.000 poin" 
                                class="w-full p-3 border-2 rounded-lg focus:outline-none focus:border-green-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih E-Wallet
                            </label>
                            <select 
                                name="wallet_type"
                                class="w-full p-3 border-2 rounded-lg focus:outline-none focus:border-green-500"
                            >
                                <option value="">Pilih E-Wallet</option>
                                <option value="Dana">Dana</option>
                                <option value="ShopeePay">ShopeePay</option>
                                <option value="OVO">OVO</option>
                                <option value="GoPay">GoPay</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor E-Wallet
                            </label>
                            <input 
                                type="text" 
                                name="ewallet_number"
                                placeholder="Masukkan nomor e-wallet Anda" 
                                class="w-full p-3 border-2 rounded-lg focus:outline-none focus:border-green-500"
                            >
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-green-500 text-white p-3 rounded-lg hover:bg-green-600 transition duration-300"
                        >
                            Tukar Reward
                        </button>
                    </div>
                </form>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Minimal penukaran 50.000 poin
                </p>
            </div>

            <!-- Exchange History Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 reward-card">
                <h2 class="text-2xl font-bold text-green-600 text-center mb-6">Riwayat Penukaran</h2>
                <?php if (count($history)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-green-50">
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Poin</th>
                                    <th class="p-3 text-left">Rupiah</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">E-Wallet</th>
                                    <th class="p-3 text-left">Nomor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $record): ?>
                                    <tr class="border-b">
                                        <td class="p-3"><?php echo date('d/m/Y', strtotime($record['tanggal'])); ?></td>
                                        <td class="p-3"><?php echo number_format($record['poin_convert'], 0, ',', '.'); ?></td>
                                        <td class="p-3">Rp <?php echo number_format($record['rupiah'], 0, ',', '.'); ?></td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 rounded-full text-xs <?php echo $record['status'] == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <?php echo $record['status']; ?>
                                            </span>
                                        </td>
                                        <td class="p-3"><?php echo $record['wallet']; ?></td>
                                        <td class="p-3"><?php echo $record['nomor_ewallet']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500">Belum ada riwayat penukaran poin.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function validateForm(event) {
            event.preventDefault();
            
            const points = document.querySelector('input[name="poin"]').value;
            const walletType = document.querySelector('select[name="wallet_type"]').value;
            const walletNumber = document.querySelector('input[name="ewallet_number"]').value;
            const totalPoints = <?php echo $total_poin; ?>;

            if (!points) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Silakan masukkan jumlah poin yang ingin ditukar'
                });
                return false;
            }

            if (points < 50000) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Poin Tidak Mencukupi',
                    text: 'Minimal penukaran adalah 50.000 poin'
                });
                return false;
            }

            if (points > totalPoints) {
                Swal.fire({
                    icon: 'error',
                    title: 'Poin Tidak Mencukupi',
                    text: 'Poin yang ingin ditukar melebihi jumlah poin yang Anda miliki'
                });
                return false;
            }

            if (!walletType) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Silakan pilih jenis E-Wallet'
                });
                return false;
            }

            if (!walletNumber) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Silakan masukkan nomor E-Wallet Anda'
                });
                return false;
            }

            Swal.fire({
                title: 'Konfirmasi Penukaran',
                text: `Anda akan menukar ${points.toLocaleString()} poin ke ${walletType}. Lanjutkan?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tukar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rewardForm').submit();
                }
            });

            return false;
        }

        // Display PHP messages using SweetAlert
        <?php if (!empty($message)): ?>
            Swal.fire({
                icon: '<?php echo strpos($message, "berhasil") !== false ? "success" : "error"; ?>',
                title: '<?php echo strpos($message, "berhasil") !== false ? "Berhasil!" : "Error!"; ?>',
                text: '<?php echo addslashes($message); ?>'
            });
        <?php endif; ?>
    </script>
</body>
</html>