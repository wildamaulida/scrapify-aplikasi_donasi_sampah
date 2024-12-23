<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "scrapify";

$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data leaderboard
$sql = "
    SELECT 
        pengguna.pengguna_id AS id_pengguna, 
        pengguna.nama, 
        SUM(donasi_sampah.poin) AS total_poin
    FROM 
        pengguna
    JOIN 
        donasi_sampah ON pengguna.pengguna_id = donasi_sampah.pengguna_id
    GROUP BY 
        pengguna.pengguna_id, pengguna.nama
    ORDER BY 
        total_poin DESC;
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Leaderboard</title>
    <style>
      /* Global Styling */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        background-color: #f4f7f9;
      }

      /* Sidebar Styling */
      .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #333, #575757);
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        padding-top: 20px;
        transition: all 0.3s ease;
        z-index: 10;
      }

      .sidebar a {
        text-decoration: none;
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        font-size: 16px;
      }

      .sidebar a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(10px);
      }

      .sidebar a.active {
        background-color: #575757;
      }

      /* Content Styling */
      .content {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
        transition: all 0.3s ease;
      }

      .header {
        background: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .logout-button {
        background: linear-gradient(135deg, #d9534f, #c9302c);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .logout-button:hover {
        background: linear-gradient(135deg, #c9302c, #b52b27);
      }

      /* Table Styling */
      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
      }

      th,
      td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
      }

      th {
        background: #5bc0de;
        color: white;
        font-weight: bold;
      }

      tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      tr:hover {
        background-color: #eaf6ff;
        transition: all 0.3s ease;
      }

      .rank {
        font-weight: bold;
        color: #5bc0de;
      }

      /* Mobile Responsiveness */
      @media (max-width: 768px) {
        .sidebar {
          left: -250px;
        }

        .sidebar.open {
          left: 0;
        }

        .content {
          margin-left: 0;
          width: 100%;
        }

        .menu-toggle {
          display: block;
          background: #333;
          color: white;
          border: none;
          padding: 10px;
          font-size: 16px;
          cursor: pointer;
          position: fixed;
          top: 10px;
          left: 10px;
          z-index: 11;
          border-radius: 5px;
        }
      }

      @media (min-width: 769px) {
        .menu-toggle {
          display: none;
        }
      }
    </style>
  </head>

  <body>
    <!-- Content -->
    <div class="content">
      <div class="header">
        Admin Scrapify
        <button class="logout-button">Logout</button>
      </div>

      <h2>Leaderboard</h2>

      <!-- Table for displaying leaderboard -->
      <table>
        <thead>
          <tr>
            <th>ID Pengguna</th>
            <th>Nama</th>
            <th>Total Poin</th>
            <th>Peringkat</th>
          </tr>
        </thead>
        <tbody>
        <?php
                if ($result->num_rows > 0) {
                    $rank = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id_pengguna'] . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['total_poin'] . "</td>";
                        echo "<td class='rank'>" . $rank++ . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
                }
                $conn->close();
                ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
