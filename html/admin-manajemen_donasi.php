<?php
include "koneksi.php";
// Menangani perubahan status
if (isset($_GET['donasi_id']) && isset($_GET['status'])) {
    $donationId = $_GET['donasi_id'];
    $status = $_GET['status'];
    

    // Update status donasi
    $sql = "UPDATE donasi_sampah SET status = ? WHERE donasi_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $donationId);
    $result = $stmt->execute();
    $stmt->close();

    header("Location: admin-manajemen_donasi.php"); // Redirect setelah perubahan status
    exit();
}

// Ambil data donasi
$sql = "SELECT * FROM donasi_sampah";
$result = $conn->query($sql);
$donations = [];
while ($row = $result->fetch_assoc()) {
    $donations[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manajemen Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <style>
    body {
      display: flex;
      margin: 0;
      min-height: 100vh;
    }

    .main-content {
      margin-left: 220px;
      flex: 1;
      padding: 20px;
      background: linear-gradient(to bottom, #f9fafb, #e5e7eb);
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
        margin-top: 60px;
      }

      .main-content {
        margin-left: 0;
        padding: 10px;
      }
    }
  </style>
</head>

<body class="bg-gray-100 font-sans">
  <!-- Include Sidebar -->
  <?php include '../html/admin-navbar.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container mx-auto">
      <div class="bg-white shadow-2xl rounded-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-800 to-green-900 p-6 flex flex-col md:flex-row justify-between items-center">
          <h2 class="text-2xl md:text-3xl font-bold text-white text-center">Manajemen Donasi</h2>
        </div>

        <!-- Donation Table -->
        <div class="overflow-x-auto p-6">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID Donasi</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID Pengguna</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Poin</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Berat</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kode Resi</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody id="donationTableBody" class="divide-y divide-gray-200">
            <?php foreach ($donations as $donation): ?>
                <tr>
                <td class="px-6 py-4 whitespace-nowrap text-black"><?php echo $donation['donasi_id']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-black"><?php echo $donation['tanggal_donasi']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-black"><?php echo $donation['pengguna_id']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-black"><?php echo $donation['poin']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-black"><?php echo $donation['berat']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-black"><?php echo $donation['kode_resi']; ?></td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full 
                      <?php
                        if ($donation['status'] == "Belum Terverifikasi") {
                          echo "bg-yellow-100 text-yellow-800";
                        } elseif ($donation['status'] == "Disetujui") {
                          echo "bg-green-100 text-green-800";
                        } else {
                          echo "bg-red-100 text-red-800";
                        }
                      ?>">
                      <?php echo $donation['status']; ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap space-x-2">
                  <a href="?donasi_id=<?php echo $donation['donasi_id']; ?>&status=Disetujui" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs">Setujui</a>
                    <a href="?donasi_id=<?php echo $donation['donasi_id']; ?>&status=Ditolak" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">Tolak</a>
                  </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Table Footer -->
        <div class="bg-gray-50 p-6 flex flex-col md:flex-row justify-between items-center border-t">
          <span class="text-sm text-gray-800" id="tableFooter">Menampilkan <?php echo count($donations); ?> of <?php echo count($donations); ?> donasi</span>
          <div class="space-y-2 md:space-y-0 md:space-x-2 flex flex-col md:flex-row">
            <button class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-300" id="prevPage">Sebelumnya</button>
            <button class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-300" id="nextPage">Berikutnya</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    feather.replace();
    renderTable();
  </script>
</body>
</html>