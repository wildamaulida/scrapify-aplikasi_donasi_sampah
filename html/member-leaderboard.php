<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scrapify";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total Participants
$totalParticipantsQuery = "SELECT COUNT(DISTINCT pengguna_id) AS total_participants FROM donasi_sampah";
$totalParticipantsResult = $conn->query($totalParticipantsQuery);
$totalParticipants = $totalParticipantsResult->fetch_assoc()['total_participants'] ?? 0;

// Total Waste Collected
$totalWasteQuery = "SELECT SUM(berat) AS total_waste FROM donasi_sampah";
$totalWasteResult = $conn->query($totalWasteQuery);
$totalWaste = $totalWasteResult->fetch_assoc()['total_waste'] ?? 0;

// Leaderboard Data
$leaderboardQuery = "
    SELECT pengguna.nama AS user_name, SUM(donasi_sampah.berat) AS total_waste, SUM(donasi_sampah.poin) AS total_points 
    FROM donasi_sampah 
    JOIN pengguna ON donasi_sampah.pengguna_id = pengguna.pengguna_id
    GROUP BY pengguna.nama 
    ORDER BY total_points DESC
";
$leaderboardResult = $conn->query($leaderboardQuery);

// Menyimpan hasil leaderboard dalam array
$leaderboard = [];
if ($leaderboardResult) {
    while ($row = $leaderboardResult->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <title>Leaderboard Scrapify</title>
    <style>
      body {
        background-color: #f0f4f8;
      }
      .rank-badge {
        transition: transform 0.3s ease;
      }
      .rank-badge:hover {
        transform: scale(1.1);
      }
      .player-card {
        transition: all 0.3s ease;
        cursor: pointer;
      }
      .player-card:hover {
        background-color: #e6f2f2;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }
      .modal {
        background-color: rgba(0, 0, 0, 0.5);
      }
    </style>
  </head>
  <body class="flex flex-col min-h-screen">
    <!-- navbar -->
    <?php include 'member-navbar.php'; ?>

    <!-- Main Content -->
    <div class="flex-grow container mx-auto px-4 py-8">
      <div
        class="bg-white shadow-lg rounded-xl overflow-hidden max-w-4xl mx-auto"
      >
        <div class="bg-gradient-to-r from-green-600 to-green-500 p-6">
          <h1
            class="text-3xl font-bold text-white text-center flex items-center justify-center"
          >
            <i class="fas fa-trophy mr-4"></i>
            Leaderboard Scrapify
            <i class="fas fa-trophy ml-4"></i>
          </h1>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 p-4 rounded-lg text-center">
              <h3 class="font-bold text-green-700">Total Participants</h3>
              <p class="text-2xl text-green-900"><?php echo $totalParticipants; ?></p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center">
              <h3 class="font-bold text-green-700">Total Waste Collected</h3>
              <p class="text-2xl text-green-900"><?php echo number_format($totalWaste, 2); ?> kg</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center">
              <h3 class="font-bold text-green-700">Top Contributors</h3>
              <p class="text-2xl text-green-900"><?php echo count($leaderboard); ?></p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-green-600 text-white">
                  <th class="p-3 text-left">Rank</th>
                  <th class="p-3 text-left">Id</th>
                  <th class="p-3 text-left">Waste Collected</th>
                  <th class="p-3 text-left">Points</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($leaderboard as $index => $user): ?>
    <tr class="player-card hover:bg-green-50 transition-colors">
        <td class="p-3">
            <?php if ($index == 0): ?>
                <span class="rank-badge text-2xl font-bold text-yellow-500">🥇</span>
            <?php elseif ($index == 1): ?>
                <span class="rank-badge text-2xl font-bold text-gray-400">🥈</span>
            <?php elseif ($index == 2): ?>
                <span class="rank-badge text-2xl font-bold text-yellow-700">🥉</span>
            <?php else: ?>
                <span class="text-xl font-bold"><?php echo $index + 1; ?></span>
            <?php endif; ?>
        </td>
        <td class="p-3"><?php echo htmlspecialchars($user['user_name']); ?></td>
        <td class="p-3"><?php echo number_format($user['total_waste'], 2); ?></td>
        <td class="p-3 font-bold text-green-700"><?php echo $user['total_points']; ?></td>
    </tr>
<?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Additional interactive features can be added here
      document.querySelectorAll(".player-card").forEach((card) => {
        card.addEventListener("click", () => {
          // Example: Show more details about the player
          alert("Detailed player stats will be implemented soon!");
        });
      });
    </script>
  </body>
</html>
