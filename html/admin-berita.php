<?php
include 'koneksi.php'; // Memasukkan konfigurasi koneksi database

// Fungsi untuk mengamankan input
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

try {
    // Hapus data
    if (isset($_GET['hapus'])) {
        $id = intval($_GET['hapus']);
        $stmt = $conn->prepare("DELETE FROM berita WHERE berita_id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Data berhasil dihapus!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Ambil data untuk edit
    if (isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $stmt = $conn->prepare("SELECT * FROM berita WHERE berita_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $judul = clean_input($row['judul']);
        $isi = clean_input($row['isi']);
        $gambar = $row['gambar'];
        $tanggal = clean_input($row['tanggal']);
        $penulis = clean_input($row['penulis']);

        $stmt->close();
    } else {
        $judul = $isi = $gambar = $tanggal = $penulis = '';
    }

    // Proses form submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $judul = clean_input($_POST['judul']);
        $isi = clean_input($_POST['isi']);
        $penulis = clean_input($_POST['penulis']);
        $tanggal = clean_input($_POST['tanggal']);

        // Validasi file upload
        $gambar_query = "";
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = basename($_FILES['gambar']['name']);
            $gambar_tmp = $_FILES['gambar']['tmp_name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . $gambar;

            // Validasi ekstensi file
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                if (move_uploaded_file($gambar_tmp, $target_file)) {
                    $gambar_query = ", gambar = ?";
                } else {
                    throw new Exception("Gagal mengupload gambar.");
                }
            } else {
                throw new Exception("Format gambar tidak valid.");
            }
        }

        // Jika ID edit ada, maka update data
        // Jika ID edit ada, maka update data
if (isset($_GET['edit'])) {
  $id = intval($_GET['edit']);
  
  // Query dasar tanpa update gambar
  $sql = "UPDATE berita SET judul = ?, isi = ?, tanggal = ?, penulis = ?";

  // Tambahkan bagian query jika gambar diunggah
  if ($gambar_query) {
      $sql .= ", gambar = ?";
  }

  // Tambahkan kondisi WHERE
  $sql .= " WHERE berita_id = ?";

  $stmt = $conn->prepare($sql);

  // Bind parameter sesuai query yang dihasilkan
  if ($gambar_query) {
      // Jika gambar diunggah
      $stmt->bind_param("sssssi", $judul, $isi, $tanggal, $penulis, $gambar, $id);
  } else {
      // Jika gambar tidak diunggah
      $stmt->bind_param("ssssi", $judul, $isi, $tanggal, $penulis, $id);
  }
} else {
  // Insert data baru
  $sql = "INSERT INTO berita (judul, isi, gambar, tanggal, penulis) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $penulis);
}


        if ($stmt->execute()) {
            echo "Data berhasil disimpan!";
            // Kosongkan variabel form setelah berhasil
            $judul = $isi = $gambar = $tanggal = $penulis = '';
            // Redirect untuk mencegah form resubmit
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
        $stmt->close();
    }

    // Query untuk mengambil data berita
    $sql = "SELECT * FROM berita";
    $result = $conn->query($sql);

} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scrapify - Manajemen Berita</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #059669;
            --secondary-color: #10b981;
            --accent-color: #34d399;
            --danger-color: #ef4444;
            --succes-color:rgb(0, 140, 255);
            --background-light: #f0f9f5;
            --text-dark: #065f46;
            --transition: all 0.3s ease;
        } 

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
            color: #1f2937;
            min-height: 100vh;
            
        }

        .container {
            margin-left: 220px;
        }        

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
            margin-top: 60px;
        }

        .content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;

        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: -60px;

        }

        .header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .main-content {
            display: flex;
            flex: 1;
            gap: 20px;
            padding: 20px;
            transition: var(--transition);
        }

        .form-section {
            flex: 0 0 400px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .news-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            color: #1f2937;
        }

        .form-group label {
            font-weight: bold;
            color: var(--text-dark);
        }

        .news-form input,
        .news-form textarea {
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
        }

        .news-form input:focus,
        .news-form textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .image-upload {
            border: 2px dashed var(--primary-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .image-upload:hover {
            background-color: var(--background-light);
        }

        .image-upload i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .image-preview-container {
        display: flex;
        justify-content: center; /* Pusatkan secara horizontal */
        align-items: center; /* Pusatkan secara vertikal */
        margin-top: 15px; /* Tambahkan jarak dari elemen lainnya */
        width: 100%; /* Buat kontainer melebar */
    }

    #imagePreview {
        display: block; /* Pastikan gambar ditampilkan sebagai block */
        max-width: 300px; /* Batasi lebar maksimal */
        max-height: 300px; /* Batasi tinggi maksimal */
        width: auto; /* Biarkan proporsi asli gambar */
        height: auto; /* Biarkan proporsi asli gambar */
        border: 2px solid #ccc; /* Tambahkan border */
        border-radius: 10px; /* Sudut melengkung */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan */
    }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
        }

        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .list-section {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .news-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            color: #000000;
        }

        .news-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .news-table th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .news-table th:last-child {
            border-radius: 0 8px 8px 0;
        }

        .news-table tr {
            transition: var(--transition);
        }

        .news-table tbody tr:hover {
            background-color: var(--background-light);
            transform: translateY(-2px);
        }

        .news-table td {
            padding: 12px;
            background-color: white;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .news-table td:first-child {
            border-left: 1px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
        }

        .news-table td:last-child {
            border-right: 1px solid #e2e8f0;
            border-radius: 0 8px 8px 0;
        }

        .news-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            transition: var(--transition);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .edit-btn {
            background-color:rgb(38, 179, 59);
            color: white;
        }

        .delete-btn {
            margin-top: 5px;

            background-color: var(--danger-color);
            color: white;
        }

        /* Additional Responsive Styles */
        @media screen and (max-width: 1024px) {
            .container {
                margin-left: 0;
                padding: 10px;
            }

            .main-content {
                flex-direction: column;
                gap: 15px;
            }

            .form-section {
                flex: none;
                width: 100%;
                max-width: none;
            }
        }

        @media screen and (max-width: 768px) {
            .container {
                margin-top: 0;
            }

            .header {
                margin-top: 0;
            }

            .content {
                padding: 0;
                margin-top: 60px;
            }

            .news-table {
                display: block;
                overflow-x: auto;
            }

            .news-table thead {
                display: none;
            }

            .news-table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
            }

            .news-table td {
                display: block;
                text-align: right;
                padding: 10px;
                position: relative;
                padding-left: 120px;
            }

            .news-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 100px;
                font-weight: bold;
                text-align: left;
            }

            .action-buttons {
                justify-content: flex-end;
                flex-wrap: wrap;
            }

            .action-btn {
                width: auto;
                min-width: 100px;
            }

            .image-upload {
                padding: 15px;
            }

            .image-preview-container {
                margin-top: 10px;
            }

            #imagePreview {
                max-width: 100%;
                height: auto;
            }
        }

        @media screen and (max-width: 480px) {
            .main-content {
                padding: 10px;
            }

            .form-section,
            .list-section {
                padding: 15px;
            }

            .news-form .form-group {
                margin-bottom: 12px;
            }

            .news-form input,
            .news-form textarea {
                padding: 8px;
            }

            .image-upload {
                padding: 10px;
            }

            .image-upload i {
                font-size: 1.5rem;
            }

            .submit-btn {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                margin: 2px 0;
            }

            .news-table td {
                padding-left: 100px;
            }

            .news-table td:before {
                width: 90px;
            }

            .news-table img {
                width: 100%;
                height: auto;
                max-width: 200px;
            }
        }
        

    </style>
</head>
<body>
    <?php include 'admin-navbar.php'; ?>

    <div class="container">
        <div class="content">
            <div class="header">
                <h1>Manajemen Berita Scrapify</h1>
            </div>

            <div class="main-content">
    <div class="form-section">
    <form id="newsForm" class="news-form" action="<?php echo isset($_GET['edit']) ? '?edit=' . $_GET['edit'] : ''; ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="articleTitle">Judul Berita</label>
        <input type="text" id="articleTitle" name="judul" value="<?php echo isset($judul) ? $judul : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="articleContent">Isi Berita</label>
        <textarea id="articleContent" rows="6" name="isi" required><?php echo isset($isi) ? $isi : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label>Gambar Berita</label>
        <div class="image-upload" id="dropZone">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Unggah gambar di sini</p>
            <input type="file" id="imageUpload" name="gambar" accept="image/*" style="display: none;" onchange="previewImage(event)">
            <!-- Jika ada gambar sebelumnya, tampilkan -->
            <?php if (!empty($gambar)): ?>
                <img src="uploads/<?php echo $gambar; ?>" style="max-width: 300px; border: 2px solid #ccc; border-radius: 10px;" alt="Gambar Saat Ini"><br>
            <?php endif; ?>
            <!-- Preview gambar baru -->
            <div class="image-preview-container">
                <img id="imagePreview" src="" alt="Preview" style="display: none;">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="articleAuthor">Penulis</label>
        <input type="text" id="articleAuthor" name="penulis" value="<?php echo isset($penulis) ? $penulis : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="articleDate">Tanggal</label>
        <input type="date" id="articleDate" name="tanggal" value="<?php echo isset($tanggal) ? $tanggal : ''; ?>" required>
    </div>

    <!-- Hidden input untuk ID berita yang sedang diedit -->
    <?php if (isset($_GET['edit'])): ?>
        <input type="hidden" name="edit_id" value="<?php echo $_GET['edit']; ?>">
    <?php endif; ?>

    <button type="submit" class="submit-btn">
    <i class="fas fa-plus"></i><?php echo isset($_GET['edit']) ? 'Perbarui Berita' : 'Tambah Berita'; ?>
    </button>
</form>

            <!-- <button type="submit" class="submit-btn">
                <i class="fas fa-plus"></i> Tambah Berita
            </button>
        </form> -->
    </div>

    <div class="list-section">
        <table class="news-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Penulis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="newsTableBody">
            <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-label='Gambar'><img src='uploads/" . $row['gambar'] . "' width='100'></td>";
                        echo "<td data-label='Judul'>" . $row['judul'] . "</td>";
                        echo "<td data-label='Tanggal'>" . $row['tanggal'] . "</td>";
                        echo "<td data-label='Penulis'>" . $row['penulis'] . "</td>";
                        echo "<td data-label='Aksi'>
                            <div class='action-buttons'>
                                <button class='action-btn edit-btn' onclick=\"window.location.href='admin-berita.php?edit=" . $row['berita_id'] . "'\">
                                    <i class='fas fa-edit'></i> Edit
                                </button>
                                <button class='action-btn delete-btn' onclick=\"if(confirm('Yakin ingin menghapus data?')) { window.location.href='admin-berita.php?hapus=" . $row['berita_id'] . "'; }\">
                                    <i class='fas fa-trash'></i> Hapus
                                </button>
                            </div>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
              <script>
                function previewImage(event) {
    var preview = document.getElementById('imagePreview');
    var file = event.target.files[0];
    var reader = new FileReader();

    reader.onload = function() {
        preview.style.display = 'block';  // Show the preview container
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);  // Read the image file as a data URL
    }
}
document.getElementById('dropZone').addEventListener('click', function() {
        document.getElementById('imageUpload').click();
    });

    </script>

</body>
</html>