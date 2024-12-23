<?php
include "koneksi.php";

// Query untuk menghitung total pengguna
$query_pengguna = "SELECT COUNT(pengguna_id) AS total_pengguna FROM pengguna";
$result_pengguna = $conn->query($query_pengguna);
$total_pengguna = 0;
if ($result_pengguna) {
    $data_pengguna = $result_pengguna->fetch_assoc();
    $total_pengguna = $data_pengguna['total_pengguna'];
}

// Query untuk menghitung total setoran sampah
$query_setoran = "SELECT SUM(berat) AS total_berat FROM donasi_sampah";
$result_setoran = $conn->query($query_setoran);
$total_setoran = 0;
if ($result_setoran) {
    $data_setoran = $result_setoran->fetch_assoc();
    $total_setoran = $data_setoran['total_berat'];
}

// Query untuk menghitung transaksi yang tertunda (status 'Belum Terverifikasi')
$query_transaksi = "SELECT COUNT(donasi_id) AS total_transaksi FROM donasi_sampah WHERE status = 'Belum Terverifikasi'";
$result_transaksi = $conn->query($query_transaksi);
$total_transaksi = 0;
if ($result_transaksi) {
    $data_transaksi = $result_transaksi->fetch_assoc();
    $total_transaksi = $data_transaksi['total_transaksi'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Scrapify</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
    }

    .sidebar {
      width: 250px;
      background: rgba(34, 197, 94, 0.8);
      color: white;
      z-index: 20;
      transition: transform 0.3s ease-in-out;
    }

    .sidebar.hidden {
      transform: translateX(-100%);
    }

    @media (min-width: 768px) {
      .sidebar {
        transform: translateX(0);
      }
    }

    .main-content {
      padding: 1rem;
      background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
    }
  </style>
</head>
<body x-data="{ sidebarOpen: false }">
  <!-- Sidebar -->
  <?php include 'admin-navbar.php'; ?>

  <!-- Main Content -->
  <div class="flex flex-col md:ml-64">
    <header class="flex items-center justify-between p-4 bg-white shadow-md md:hidden">
      <button
        @click="sidebarOpen = !sidebarOpen"
        class="text-green-800 focus:outline-none">
        <i class="fas fa-bars"></i>
      </button>
      <h1 class="text-lg font-bold">Dashboard Admin</h1>
    </header>

    <div class="main-content">
      <header class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-green-800">Dashboard Admin</h2>
      </header>

      <!-- Dashboard Cards -->
      <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
        <div class="p-4 bg-white rounded-lg shadow-md">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-green-600"><?php echo $total_pengguna; ?></h3>
              <p class="text-sm text-gray-500">Total User</p>
            </div>
          </div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-md">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-green-600"><?php echo $total_setoran; ?> kg</h3>
              <p class="text-sm text-gray-500">Setoran Sampah</p>
            </div>
          </div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-md">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-green-600"><?php echo $total_transaksi; ?></h3>
              <p class="text-sm text-gray-500">Transaksi Tertunda</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="bg-white rounded-lg shadow-md p-4">
        <h3 class="mb-4 text-lg font-semibold text-green-800">Grafik Setoran Sampah</h3>
        <canvas id="trashDepositChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const ctx = document.getElementById("trashDepositChart").getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"],
          datasets: [
            {
              label: "Setoran Sampah (kg)",
              data: [120, 150, 180, 200, 220, 250],
              backgroundColor: "rgba(34, 197, 94, 0.6)",
              borderColor: "rgb(34, 197, 94)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    });
  </script>
</body>
</html>