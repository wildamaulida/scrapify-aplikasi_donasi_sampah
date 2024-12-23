<?php
include "koneksi.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit'])) {
    if (isset($_POST['judul'], $_POST['isi_artikel'], $_POST['tanggal'], $_POST['penulis'])) {
        $judul = $_POST['judul'];
        $isi_artikel = $_POST['isi_artikel'];
        $tanggal = $_POST['tanggal'];
        $penulis = $_POST['penulis'];
        
        $gambar = null;
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = time() . '_' . $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
        }
        
        $query = "INSERT INTO edukasi (judul, isi, tanggal, penulis, gambar) 
                  VALUES ('$judul', '$isi_artikel', '$tanggal', '$penulis', '$gambar')";
        $conn->query($query);
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $result = $conn->query("SELECT gambar FROM edukasi WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) {
            unlink("uploads/" . $row['gambar']);
        }
    }

    $query = "DELETE FROM edukasi WHERE id=$id";
    if ($conn->query($query)) {
        echo "Data berhasil dihapus.";
    } else {
        echo "Terjadi kesalahan saat menghapus data.";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $isi_artikel = $_POST['isi_artikel'];
    $tanggal = $_POST['tanggal'];
    $penulis = $_POST['penulis'];

    $gambar = $_POST['gambar_lama'];
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
        
        // Delete old image if exists
        if (!empty($_POST['gambar_lama']) && file_exists("uploads/" . $_POST['gambar_lama'])) {
            unlink("uploads/" . $_POST['gambar_lama']);
        }
    }

    $query = "UPDATE edukasi SET judul='$judul', isi='$isi_artikel', tanggal='$tanggal', 
              penulis='$penulis', gambar='$gambar' WHERE id=$id";
    $conn->query($query);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$article = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM edukasi WHERE id=$id");
    $article = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Edukasi</title>
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
            max-width: 400px;
        }

        .news-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .news-form input,
        .news-form textarea {
            width: 100%;
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
            position: relative;
            overflow: hidden;
            border: 2px dashed var(--primary-color);
            border-radius: 8px;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
            max-width: 100%;
        }

        .image-upload:hover {
            background-color: var(--background-light);
        }

        #imagePreview {
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }

        #uploadArea {
            display: flex;
            padding-left: 15px;
            align-items: center;
            justify-content: center;
            color: black;
        }

        #uploadArea i {
            padding-left: 15px;
            font-size: 1.5rem;
            color: black;
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
            gap: 10px;
            justify-content: flex-start;
        }

        .action-btn {
            padding: 4px 10px;
            border: 2px solid;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .edit-btn:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border-color: #f44336;
        }

        .delete-btn:hover {
            background-color: #da190b;
            border-color: #da190b;
        }

        @media screen and (max-width: 1024px) {
            .main-content {
                flex-direction: column;
            }
            .form-section {
                flex: none;
                width: 100%;
                max-width: none;
            }
        }

        /* Additional Responsive Styles */
        @media screen and (max-width: 768px) {
            .container {
                margin-left: 0;
                margin-top: 0;
            }

            .content {
                width: 100%;
                margin-top: 60px;
            }

            .header {
                margin-top: 0;
                padding: 15px;
            }

            .main-content {
                padding: 10px;
            }

            .form-section,
            .list-section {
                padding: 15px;
                margin-bottom: 20px;
                width: 100%;
            }

            .news-table {
                display: block;
                overflow-x: auto;
            }

            .news-table th,
            .news-table td {
                min-width: 120px;
                white-space: nowrap;
            }

            .news-table td:last-child {
                min-width: 150px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .action-btn {
                width: 100%;
                padding: 8px;
                font-size: 14px;
            }

            .news-form input,
            .news-form textarea {
                padding: 10px;
            }

            .image-upload {
                padding: 10px;
            }

            #imagePreview {
                max-height: 150px;
            }

            .header h1 {
                font-size: 1.2rem;
            }
        }

        @media screen and (max-width: 480px) {
            .main-content {
                padding: 5px;
            }

            .form-section,
            .list-section {
                padding: 10px;
                border-radius: 8px;
            }

            .news-table th,
            .news-table td {
                padding: 8px;
                font-size: 14px;
            }

            .news-table img {
                width: 60px;
                height: 60px;
            }

            .submit-btn {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <?php include 'admin-navbar.php'; ?>

    <div class="container">
        <div class="content">
            <div class="header">
                <h1>Manajemen Edukasi</h1>
            </div>

            <div class="main-content">
                <div class="form-section">
                    <form id="newsForm" class="news-form" action="" method="POST" enctype="multipart/form-data">
                        <?php if (isset($article)): ?>
                            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                            <input type="hidden" name="gambar_lama" value="<?php echo $article['gambar']; ?>">
                        <?php endif; ?>

                        <input
                            type="text"
                            name="judul"
                            id="judul"
                            placeholder="Judul Artikel"
                            required
                            value="<?php echo isset($article) ? $article['judul'] : ''; ?>"
                        />

                        <textarea
                            id="isi_artikel"
                            name="isi_artikel"
                            placeholder="Isi Artikel"
                            rows="6"
                            required
                        ><?php echo isset($article) ? $article['isi'] : ''; ?></textarea>

                        <div class="image-upload">
                            <input type="file" id="imageUpload" name="gambar" accept="image/*" style="display: none;" />
                            <div id="uploadArea">
                                <i class="fas fa-cloud-upload-alt"></i> Unggah Gambar
                            </div>
                            <?php if (isset($article) && $article['gambar']): ?>
                                <img id="imagePreview" src="uploads/<?php echo $article['gambar']; ?>" alt="Preview" style="display: block; margin-top: 10px;" />
                            <?php else: ?>
                                <img id="imagePreview" style="display: none" />
                            <?php endif; ?>
                        </div>

                        <input 
                            type="date" 
                            name="tanggal" 
                            id="articleDate" 
                            required 
                            value="<?php echo isset($article) ? $article['tanggal'] : ''; ?>"
                        />
                        
                        <input
                            type="text"
                            id="articleAuthor"
                            name="penulis"
                            placeholder="Penulis"
                            required
                            value="<?php echo isset($article) ? $article['penulis'] : ''; ?>"
                        />

                        <button class="submit-btn" type="submit" name="<?php echo isset($article) ? 'edit' : ''; ?>">
                            <?php echo isset($article) ? "Update Artikel" : "Simpan"; ?>
                        </button>
                    </form>
                </div>

                <div class="list-section">
                    <table class="news-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Penulis</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="newsTableBody">
                            <?php
                                $result = $conn->query("SELECT * FROM edukasi");
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='py-2 px-4 border-b'>" . $row['judul'] . "</td>";
                                    echo "<td class='py-2 px-4 border-b'>" . $row['tanggal'] . "</td>";
                                    echo "<td class='py-2 px-4 border-b'>" . $row['penulis'] . "</td>";
                                    echo "<td class='py-2 px-4 border-b'><img src='uploads/" . $row['gambar'] . "' alt='Article Image' class='article-image'></td>";
                                    echo "<td class='py-2 px-4 border-b'>
                                            <div class='action-buttons'>
                                                <a href='" . $_SERVER['PHP_SELF'] . "?edit=" . $row['id'] . "' class='action-btn edit-btn'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a>
                                                <a href='" . $_SERVER['PHP_SELF'] . "?hapus=" . $row['id'] . "' 
                                                   class='action-btn delete-btn' 
                                                   onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\")'>
                                                    <i class='fas fa-trash'></i> Hapus
                                                </a>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageUpload = document.getElementById('imageUpload');
            const imagePreview = document.getElementById('imagePreview');
            const uploadArea = document.getElementById('uploadArea');

            uploadArea.addEventListener('click', function() {
                imageUpload.click();
            });

            imageUpload.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>