<?php  // Ensure session_start is called at the beginning of the file
$isLoggedIn = isset($_SESSION['pengguna_id']);
include "koneksi.php";

if (!isset($_SESSION['pengguna_id'])) {
    // Jika user belum login, beri efek di tampilan
    $isLoggedIn = false;
} else {
    $isLoggedIn = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SCRAPIFY - Responsive Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        html {
            scroll-behavior: smooth;
        }<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SCRAPIFY - Responsive Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        html {
            scroll-behavior: smooth;
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 0;
            background-color: white;
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: #002c1b;
            transform: translateY(-2px);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: #002c1b;
        }

        .nav-link.active::after {
            width: 100%;
            background-color: #002c1b;
        }

        .logout-btn {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .logout-btn:hover {
            transform: scale(1.05);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .logo img {
            max-width: 150%;
            max-height: 150%;
        }

        .navbar {
            background-color: #089c6c;
            height: 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 0.5rem;
            max-width: 250%;
            max-height: 250%;
        }

        .navbar-nav {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin-left: 1.5rem;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 1.5rem 0;
        }

        .nav-link i {
            margin-right: 0.5rem;
            transform: rotate(360deg) scale(1.2);
        }

        .navbar-toggler {
            display: none;
            border: none;
            background-color: transparent;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 767px) {
            .navbar-nav {
                display: none;
            }

            .navbar-toggler {
                display: block;
            }

            .navbar-nav.show {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 80px;
                left: 0;
                width: 100%;
                background-color: #089c6c;
                padding: 1rem;
            }

            .nav-item {
                margin-left: 0;
                margin-bottom: 1rem;
            }

            .nav-link {
                padding: 0.5rem 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="navbar">
        <a href="#" class="navbar-brand">
            <img src="../images/logo2.png" alt="SCRAPIFY Logo" />
            SCRAPIFY
        </a>
        <button class="navbar-toggler" id="navbar-toggler">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="../html/member-index.php" class="nav-link">
                    <i class="fas fa-home"></i> HOME
                </a>
            </li>
            <li class="nav-item">
                <a href="../html/member-manajemen_donasi.php" class="nav-link">
                    <i class="fas fa-hand-holding-heart"></i> DONASI
                </a>
            </li>
            <li class="nav-item">
                <a href="../html/member-leaderboard.php" class="nav-link">
                    <i class="fas fa-trophy"></i> LEADERBOARD
                </a>
            </li>
            <li class="nav-item">
                <a href="../html/member-reward.php" class="nav-link">
                    <i class="fas fa-gift"></i> REWARD
                </a>
            </li>
            <li class="nav-item">
                <a href="../html/member-edukasi.php" class="nav-link">
                    <i class="fas fa-book"></i> EDUKASI
                </a>
            </li>
            <li class="nav-item">
                <a href="../html/member-berita.php" class="nav-link">
                    <i class="fas fa-newspaper"></i> BERITA
                </a>
            </li>
        </ul>
        <button id="logout-btn"
            class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg flex items-center logout-btn" onclick="handleLogout()">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>

        </div>
    </nav>

    <script>
        const navbarToggler = document.getElementById("navbar-toggler");
        const navbarNav = document.querySelector(".navbar-nav");
        const navLinks = document.querySelectorAll(".nav-link");

        // Handle logout
        function handleLogout() {
            const confirmLogout = confirm('Apakah Anda yakin ingin keluar?');
            if (confirmLogout) {
                window.location.href = '../html/index.php';
            }
        } //GANTI PAKE SESSION YA

        // Toggle navbar on small screens
        navbarToggler.addEventListener("click", () => {
            navbarNav.classList.toggle("show");
        });

        // Highlight the active nav-link
        const currentPath = window.location.pathname;
        navLinks.forEach(link => {
            const href = link.getAttribute("href");
            if (currentPath.endsWith(href.split('/').pop())) {
                link.classList.add("active");
            }
        });

    </script>
</body>

</html>