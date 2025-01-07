<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['pengguna_id'])) {
    die("Anda belum login.");
}

$pengguna_id = $_SESSION['pengguna_id'];

// Menampilkan data donasi berdasarkan pengguna_id
$sql = "SELECT tanggal_donasi, jenis_sampah, berat, alamat, status, tanggal_penjemputan, poin, gambar 
        FROM donasi_sampah 
        WHERE pengguna_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pengguna_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomorResi = 'DONASI-' . rand(100000, 999999);
    $tanggal = $_POST['tanggal'];
    $jenis_sampah = $_POST['jenis_sampah'];
    $berat_sampah = $_POST['berat'];
    $alamat = $_POST['alamat'];
    $tanggal_jemput = $_POST['tanggal_penjemputan'];
    $status = "Belum terverifikasi";

    // Validasi input wajib
    if (empty($tanggal) || empty($jenis_sampah) || empty($berat_sampah) || empty($alamat) || empty($tanggal_jemput)) {
        echo "<p>Semua kolom wajib diisi.</p>";
        exit;
    }

    // Hitung poin
    $poin = $berat_sampah * 5000;

    // Upload gambar
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = true;

        // Check if file is an actual image
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check === false) {
            echo "<p>File bukan gambar.</p>";
            $uploadOk = false;
        }

        // Check file size
        if ($_FILES["gambar"]["size"] > 5000000) {
            echo "<p>Ukuran file terlalu besar. Maksimal 5MB.</p>";
            $uploadOk = false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            echo "<p>Hanya file dengan format JPG, JPEG, dan PNG yang diizinkan.</p>";
            $uploadOk = false;
        }

        // Try uploading file
        if ($uploadOk && move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $target_file;
        } else if ($uploadOk) {
            echo "<p>Terjadi kesalahan saat mengunggah file.</p>";
        }
    }

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO donasi_sampah (tanggal_donasi, berat, jenis_sampah, tanggal_penjemputan, alamat, poin, status, gambar, kode_resi, pengguna_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssssssi", $tanggal, $berat_sampah, $jenis_sampah, $tanggal_jemput, $alamat, $poin, $status, $gambar, $nomorResi, $pengguna_id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">
        alert("Donasi berhasil disimpan!");
        window.location.href = "member-manajemen_donasi.php"; // atau halaman yang sesuai setelah sukses
      </script>';

    } else {
        echo "<p>Terjadi kesalahan: " . $conn->error . "</p>";
    }
$sql = "SELECT tanggal_donasi, jenis_sampah, berat, alamat, tanggal_penjemputan, poin, gambar 
        FROM donasi_sampah 
        WHERE pengguna_id = ?";
        if ($result->num_rows > 0) {
    // Proses menampilkan data
} else {
    echo "<tr><td colspan='6' class='p-3 text-center'>Tidak ada data donasi.</td></tr>";
}if ($result->num_rows > 0) {
    // Proses menampilkan data
} else {
    echo "<tr><td colspan='6' class='p-3 text-center'>Tidak ada data donasi.</td></tr>";
}
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrapify - Donasi Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
        } 

        .card {
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .input-focus:focus {
            border-color: #4299E1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">

     <!-- navbar -->
     <?php include 'member-navbar.php'; ?>

    <!-- Content -->
    <div class="p-4 mt-1"> <!-- Reduced margin-top -->
        <div class="header flex justify-between items-center mb-2"> <!-- Reduced margin-bottom -->
            <h1 class="text-3xl font-bold text-gray-800 fade-in"> </h1>
        </div>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col lg:flex-row items-start justify-between p-4 space-y-4 lg:space-y-0 lg:space-x-4">
    <div class="card bg-white w-full lg:w-2/3 rounded-xl shadow-lg p-6 space-y-6">
            <h2 class="text-3xl font-bold text-green-600 text-center mb-6">Donasi Sampah</h2>
            <form action="" method="POST" enctype="multipart/form-data" id="donasi-form" class="space-y-4">
                <!-- Date -->
                <div class="form-group">
                    <label class="block text-gray-700 font-bold mb-2" for="tanggal">Tanggal Donasi</label>
                    <input type="date" id="tanggal" name="tanggal" required
                        class="input-focus w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Trash Type -->
                <div class="form-group">
                    <label class="block text-gray-700 font-bold mb-2" for="jenis-sampah">Jenis Sampah</label>
                    <select id="jenis_sampah" name="jenis_sampah" required
                        class="input-focus w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="organik">Organik</option>
                        <option value="anorganik">Anorganik</option>
                        <option value="b3">B3 (Bahan Berbahaya)</option>
                    </select>
                </div>

                <!-- Weight -->
                <div class="form-group">
                    <label class="block text-gray-700 font-bold mb-2" for="berat-sampah">Berat Sampah (kg)</label>
                    <input type="number" id="berat" name="berat" min="0" step="0.1" required
                        class="input-focus w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- Address -->
                <div class="form-group">
                    <label class="block text-gray-700 font-bold mb-2" for="alamat">Alamat Penjemputan</label>
                    <textarea id="alamat" name="alamat" rows="3" required
                        class="input-focus w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Pickup Date -->
                <div class="form-group">
                    <label class="block text-gray-700 font-bold mb-2" for="tanggal-jemput">Tanggal Penjemputan</label>
                    <input type="date" id="tanggal-jemput" name="tanggal_penjemputan" required
                        class="input-focus w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Image Upload -->
                <div class="form-group">
                    <label class="block text-gray-700 font-bold mb-2" for="gambar">Upload Gambar Sampah</label>
                    <div class="flex flex-col items-center justify-center w-full">
                        <label class="flex flex-col border-4 border-dashed border-gray-200 hover:bg-gray-100 hover:border-blue-300 group w-full">
                            <div class="flex flex-col items-center justify-center pt-7 cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-blue-500"></i>
                                <p class="lowercase text-sm text-gray-400 group-hover:text-blue-500 pt-1 tracking-wider">
                                    Select a file
                                </p>
                            </div>
                            <input type="file" id="gambar" name="gambar" accept="image/*" required class="hidden" />
                        </label>
                
                        <!-- Image Preview -->
                        <div id="preview-container" class="mt-4 w-full hidden">
                            <p class="text-gray-700 font-bold mb-2">Preview Gambar:</p>
                            <img id="preview-image" class="rounded-lg shadow-md max-w-full h-auto" alt="Preview Image" />
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-check-circle mr-2"></i> Submit Donasi
                </button>

                <!-- Summary -->
                <div id="donation-summary" class="mt-6 hidden">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h2 class="text-2xl font-bold text-center mb-6 text-green-600">Ringkasan Donasi</h2>
                        <div id="summary-content" class="space-y-4">
                            <!-- Summary will be dynamically populated here -->
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <p class="text-gray-700 font-bold">Total Sampah:</p>
                            <p id="total-weight" class="text-xl font-bold text-green-600">0 kg</p>
                        </div>
                        <button id="reset-form" class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg">
                            <i class="fas fa-recycle mr-2"></i> Donasi Lagi
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Side: Riwayat Donasi -->
        <div class="bg-white rounded-xl shadow-lg p-6 reward-card">
                <h2 class="text-3xl font-bold text-green-600 text-center mb-6">Riwayat Penukaran</h2>
            <table class="w-full border-collapse border border-gray-300">
            <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-green-50">
                                <th class="p-3 text-left">Tanggal Donasi</th>
                                <th class="p-3 text-left">Jenis Sampah</th>
                                <th class="p-3 text-left">Berat Sampah</th>
                                <th class="p-3 text-left">Poin</th>
                                <th class="p-3 text-left">Alamat Penjemputan</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-left">Tanggal Penjemputan</th>
                                <th class="p-3 text-left">Gambar Sampah</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="p-3"><?= htmlspecialchars($row['tanggal_donasi']); ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['jenis_sampah']); ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['berat']); ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['poin']); ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['alamat']); ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['status']); ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['tanggal_penjemputan']); ?></td>

                        <td class="p-3">
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="<?= htmlspecialchars($row['gambar']); ?>" alt="Gambar Sampah" style="width: 100px; height: auto;">
                            <?php else: ?>
                                Tidak ada gambar
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="p-3 text-center">Tidak ada data donasi.</td>
                </tr>
            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </thead>
                <tbody id="riwayat-donasi">
                    <!-- Riwayat donasi akan ditambahkan di sini secara dinamis -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        const gambarInput = document.getElementById('gambar');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');

        gambarInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = '';
                previewContainer.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
