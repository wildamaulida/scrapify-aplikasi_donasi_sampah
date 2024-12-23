<?php
include "koneksi.php";
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari tabel edukasi
$sql = "SELECT * FROM edukasi"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Edukasi tentang Sampah</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap");

        body {
            font-family: "Roboto", sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Adjusted heading styles */
        .hero-banner {
            background: linear-gradient(135deg, #00A67E, #00865A);
            padding: 20px 10px;
            text-align: center;
            border-radius: 15px;
            margin: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hero-banner h1 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Main Content Adjustments for Navbar */
        main {
            margin-top: 0px;
            /* Match navbar height */
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 30px;
            text-align: center;
            animation: fadeIn 1.2s ease;
        }

        /* Enhanced Article Grid */
        .article-grid {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 10px 0 40px;
            flex-wrap: wrap;
        }

        .article-box {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 40px);
            min-width: 280px;
            max-width: 350px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .article-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .article-box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .article-content {
            padding: 20px;
        }

        .article-box h3 {
            color: #004d40;
            font-size: 1.3rem;
            margin: 10px 0;
        }

        .article-box p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .read-more-btn {
            background-color: #00796b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .read-more-btn:hover {
            background-color: #004d40;
        }

        /* Enhanced AI Section */
        .ai-section {
            background: white;
            padding: 20px;
            border-radius: 20px;
            margin: 40px auto 40px;
            width: 100%;
            max-width: 1090px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .ai-section h2 {
            color: #004d40;
            font-size: 1.8rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .input-container {
            display: flex;
            gap: 15px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .input-container input {
            flex: 1;
            padding: 15px 25px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .input-container button {
            padding: 15px 30px;
            background: linear-gradient(135deg, #00796b, #004d40);
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 121, 107, 0.3);
        }

        .response {
            margin-top: 25px;
            padding: 25px;
            background: white;
            border-radius: 15px;
            color: #333;
            display: none;
            text-align: left;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            line-height: 1.6;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: white;
            margin: 5% auto;
            padding: 40px;
            width: 90%;
            max-width: 800px;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            max-height: 80vh;
            overflow-y: auto;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #004d40;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #00796b;
        }

        .modal-content img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin: 20px 0;
        }

        .modal-content h2 {
            color: #004d40;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .modal-content p {
            line-height: 1.8;
            color: #444;
            margin-bottom: 15px;
        }

        footer {
            margin-top: auto;
            padding: 20px;
            background: #004d40;
            color: white;
            text-align: center;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .hero-banner h1 {
                font-size: 1rem;
            }

            .ai-section {
                padding: 20px;
                margin: -20px auto 30px;
            }

            .input-container {
                flex-direction: column;
            }

            .input-container button {
                width: 100%;
                justify-content: center;
            }

            .hero-banner h1 {
                font-size: 1.5rem;
            }

            .hero-banner {
                padding: 20px 10px;
                margin: 20px;
            }

        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include 'member-navbar.php'; ?>

    <div class="hero-banner">
        <h1>Edukasi Pengelolaan Sampah</h1>
    </div>

    <main>
    <div class="article-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 p-8">
    <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $gambar = $row['gambar'];
                $judul = $row['judul'];
                $deskripsi = $row['isi'];
                $id = $row['id'];

                // Ambil ringkasan dari isi artikel (misalnya, 100 karakter pertama)
                $ringkasan = substr($deskripsi, 0, 100) . '...'; // Mengambil 100 karakter pertama
        ?>
            <div class="article-box bg-white rounded-lg shadow-md overflow-hidden">
                <img src="uploads/<?php echo $gambar; ?>" alt="<?php echo $judul; ?>" class="w-full h-48 object-cover">
                <div class="article-content">
                    <h3 class="text-xl font-semibold mb-2"><?php echo $judul; ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo $ringkasan; ?></p> <!-- Menampilkan ringkasan -->
                    <button class="read-more-btn" onclick="openModal('<?php echo $row['id']; ?>')">Lihat Selengkapnya</button>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p class='col-span-full text-center'>Tidak ada artikel.</p>";
        }
        ?>
    </div>


        <div class="ai-section">
            <h2><i class="fas fa-robot"></i> Tanya AI tentang Sampah</h2>
            <div class="input-container">
                <input type="text" id="question"
                    placeholder="Apa yang ingin Anda ketahui tentang pengelolaan sampah?" />
                <button onclick="getAnswer()"><i class="fas fa-paper-plane"></i> Tanya AI</button>
            </div>
            <div id="response" class="response"></div>
        </div>
    </main>

    <!-- Modal -->
    <div id="articleModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
                function openModal(articleId) {
            const modal = document.getElementById('articleModal');
            const modalContent = document.getElementById('modalContent');
            let content = '';

            // Ambil data artikel berdasarkan ID
            fetch(`get_article.php?id=${articleId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        content = `
                            <h2>${data.judul}</h2>
                            <img src="uploads/${data.gambar}" alt="${data.judul}">
                            <p>${data.isi}</p>
                        `;
                        modalContent.innerHTML = content;
                        modal.style.display = 'block';
                    } else {
                        modalContent.innerHTML = `<p>Artikel tidak ditemukan.</p>`;
                        modal.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching article:', error);
                    modalContent.innerHTML = `<p>Terjadi kesalahan saat mengambil artikel.</p>`;
                    modal.style.display = 'block';
                });
        }

        function closeModal() {
            const modal = document.getElementById('articleModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('articleModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        async function getAnswer() {
            const question = document.getElementById("question").value;
            const responseDiv = document.getElementById("response");

            if (!question) {
                alert("Harap masukkan pertanyaan.");
                return;
            }

            responseDiv.style.display = "block";
            responseDiv.textContent = "Sedang memproses pertanyaan Anda...";

            try {
                const res = await fetch("https://api.openai.com/v1/chat/completions", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: "Bearer YOUR-API-KEY" // Masukkan API key Anda
                    },
                    body: JSON.stringify({
                        model: "gpt-3.5-turbo",
                        messages: [
                            {
                                role: "system",
                                content: "Kamu adalah asisten yang fokus pada edukasi tentang sampah."
                            },
                            {
                                role: "user",
                                content: question
                            }
                        ]
                    }),
                });

                const data = await res.json();

                if (res.ok) {
                    const answer = data.choices[0].message.content;
                    const paragraphs = answer
                        .split("\n")
                        .map((line) => `<p>${line}</p>`)
                        .join("");
                    responseDiv.innerHTML = paragraphs;
                } else {
                    responseDiv.textContent = "Error: " + data.error.message;
                }
            } catch (error) {
                console.error(error);
                responseDiv.textContent = "Error: Tidak dapat mengambil respons. Silakan coba lagi nanti.";
            }
        }
    </script>
</body>

</html>