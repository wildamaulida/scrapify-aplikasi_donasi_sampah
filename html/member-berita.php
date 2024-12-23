<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scrapify";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data berita
$sql = "SELECT * FROM berita ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scrapify - Berita</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #059669;
            --secondary-color: #10b981;
            --accent-color: #34d399;
            --background-light: #f0f9f5;
            --text-dark: #065f46;
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
            color: var(--text-dark);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 32px;
            font-weight: bold;
        }

        .search-container {
            margin-bottom: 30px;
        }

        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 12px 20px;
            margin: 0 auto;
            display: block;
            border: 2px solid var(--primary-color);
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(5,150,105,0.3);
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        .news-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-card-content {
            padding: 20px;
        }

        .news-card-title {
            font-size: 20px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .news-card-meta {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .news-card-excerpt {
            color: #444;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .read-more {
            display: inline-block;
            padding: 8px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .read-more:hover {
            background-color: var(--secondary-color);
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            border-radius: 15px;
            position: relative;
            max-height: 85vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                transform: translateY(50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }

        .modal-title {
            font-size: 24px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .modal-meta {
            display: flex;
            gap: 20px;
            color: #666;
        }

        .modal-body {
            line-height: 1.8;
            color: #333;
        }

        .modal-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .close-btn {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 28px;
            color: #666;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            color: #000;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header h1 {
                font-size: 24px;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 10% auto;
                padding: 20px;
            }

            .modal-title {
                font-size: 20px;
            }

            .modal-meta {
                flex-direction: column;
                gap: 10px;
            }
        }

        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>
<body>
    <?php include 'member-navbar.php'; ?>

    <div class="container">
        <div class="header">
            <h1>Berita Kegiatan/Event</h1>
        </div>

        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari berita..." onkeyup="searchNews()">
        </div>

        <div class="news-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $berita_id = $row['berita_id'];
                    $judul = $row['judul'];
                    $isi = $row['isi'];
                    $penulis = $row['penulis'];
                    $tanggal = $row['tanggal'];
                    $gambar = $row['gambar'];

                    echo "
                    <div class='news-card'>
                        <img src='uploads/$gambar' alt='$judul'>
                        <div class='news-card-content'>
                            <h3 class='news-card-title'>$judul</h3>
                            <div class='news-card-meta'>
                                <span><i class='fas fa-user'></i> $penulis</span>
                                <span><i class='fas fa-calendar'></i> $tanggal</span>
                            </div>
                            <p class='news-card-excerpt'>" . substr($isi, 0, 150) . "...</p>
                            <button class='read-more' onclick='openModal($berita_id)'>Baca Selengkapnya</button>
                        </div>
                    </div>

                    <div id='modal-$berita_id' class='modal'>
                        <div class='modal-content'>
                            <span class='close-btn' onclick='closeModal($berita_id)'>&times;</span>
                            <div class='modal-header'>
                                <h2 class='modal-title'>$judul</h2>
                                <div class='modal-meta'>
                                    <span><i class='fas fa-user'></i> $penulis</span>
                                    <span><i class='fas fa-calendar'></i> $tanggal</span>
                                </div>
                            </div>
                            <img src='uploads/$gambar' alt='$judul' class='modal-image'> <!-- Perbaikan path gambar -->
                            <div class='modal-body'>
                                $isi
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-center text-gray-500'>Belum ada berita yang tersedia.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function searchNews() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const newsCards = document.querySelectorAll('.news-card');

            newsCards.forEach(card => {
                const title = card.querySelector('.news-card-title').textContent.toLowerCase();
                const excerpt = card.querySelector('.news-card-excerpt').textContent.toLowerCase();

                if (title.includes(searchInput) || excerpt.includes(searchInput)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function openModal(id) {
            const modal = document.getElementById('modal-' + id);
            modal.style.display = 'block';
            document.body.classList.add('modal-open');
        }

        function closeModal(id) {
            const modal = document.getElementById('modal-' + id);
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                const modals = document.getElementsByClassName('modal');
                for (let modal of modals) {
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                    }
                }
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.getElementsByClassName('modal');
                for (let modal of modals) {
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                    }
                }
            }
        });
    </script>
</body>
</html>