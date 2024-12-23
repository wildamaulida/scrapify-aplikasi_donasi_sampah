<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: rgb(31, 49, 42);
      --secondary-color: #2d6a4f;
      --accent-color: #40916c;
      --danger-color: #ff4444;
      --background-dark: rgb(255, 255, 255);
      --text-light: #ffffff;
      --text-gray: rgb(235, 255, 242);
      --hover-bg: #ffffff;
      --hover-text: #1b4332;
      --soft-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      --hover-transition: all 0.2s ease-in-out;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', 'Arial', sans-serif;
      line-height: 1.4;
      background-color: var(--background-dark);
      color: var(--text-light);
      font-size: 14px;
    }

    .sidebar {
      width: 220px;
      height: 100vh;
      background-color: var(--primary-color);
      box-shadow: var(--soft-shadow);
      display: flex;
      flex-direction: column;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 1000;
      overflow-y: auto;
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      position: sticky;
      top: 0;
      background-color: var(--primary-color);
      z-index: 10;
    }

    .sidebar-logo img {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      object-fit: cover;
      transition: var(--hover-transition);
      box-shadow: 0 0 10px rgba(183, 228, 199, 0.3);
    }

    .sidebar-logo img:hover {
      transform: scale(1.05) rotate(3deg);
    }

    .sidebar-menu {
      flex-grow: 1;
      overflow-y: auto;
      padding: 10px 0;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: var(--text-gray);
      text-decoration: none;
      padding: 10px 15px;
      margin: 6px 10px;
      border-radius: 8px;
      transition: var(--hover-transition);
      font-size: 13px;
      position: relative;
      overflow: hidden;
    }

    .sidebar a.active {
      background: linear-gradient(90deg, var(--hover-bg) 0%, rgba(255,255,255,0.9) 100%);
      color: var(--hover-text);
      transform: translateX(3px);
      box-shadow: 0 2px 10px rgba(255, 255, 255, 0.2);
      font-weight: 500;
    }

    .sidebar a.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background-color: var(--hover-text);
      border-radius: 0 1px 1px 0;
    }

    .sidebar a:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: var(--text-light);
      transform: translateX(3px);
    }

    .sidebar a.active:hover {
      background: linear-gradient(90deg, var(--hover-bg) 0%, rgba(255,255,255,0.9) 100%);
      color: var(--hover-text);
    }

    .sidebar a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
      font-size: 1em;
      transition: var(--hover-transition);
    }

    .sidebar a span {
      white-space: nowrap;
      opacity: 1;
      transition: var(--hover-transition);
    }

    .logout-section {
      margin-top: auto;
      padding: 15px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      position: sticky;
      bottom: 0;
      background-color: var(--primary-color);
      z-index: 10;
    }

    .logout-btn {
      width: 100%;
      background-color: var(--danger-color);
      color: var(--text-light);
      border: none;
      padding: 10px 15px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: pointer;
      transition: var(--hover-transition);
      font-size: 13px;
      font-weight: 500;
    }

    .logout-btn:hover {
      background-color: #ff1111;
      transform: scale(1.03);
      box-shadow: 0 2px 8px rgba(255, 68, 68, 0.3);
    }

    .mobile-header {
      display: none;
      background-color: var(--primary-color);
    }

    .hamburger-menu {
      cursor: pointer;
      z-index: 1200;
      padding: 8px;
    }

    .hamburger-menu span {
      display: block;
      width: 20px;
      height: 2px;
      background-color: var(--text-light);
      margin: 4px 0;
      transition: 0.3s;
      border-radius: 4px;
    }

    .hamburger-menu.active span:nth-child(1) {
      transform: rotate(-45deg) translate(-4px, 5px);
    }

    .hamburger-menu.active span:nth-child(2) {
      opacity: 0;
    }

    .hamburger-menu.active span:nth-child(3) {
      transform: rotate(45deg) translate(-4px, -5px);
    }

    @media screen and (max-width: 768px) {
      .sidebar {
        width: 0;
        transform: translateX(-100%);
      }

      .sidebar.active {
        width: 100%;
        max-width: 260px;
        transform: translateX(0);
      }

      .mobile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background-color: var(--primary-color);
        box-shadow: var(--soft-shadow);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1100;
      }

      .mobile-header-logo {
        height: 32px;
        border-radius: 6px;
      }
    }

    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: var(--primary-color);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--accent-color);
      border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--text-gray);
    }
  </style>
</head>
<body>
  <div class="mobile-header">
    <div class="hamburger-menu">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <img src="../images/logo2.png" alt="Logo Scrapify" class="mobile-header-logo">
  </div>

  <div class="sidebar">
    <div class="sidebar-logo">
      <img src="../images/logo2.png" alt="Logo Scrapify">
    </div>

    <div class="sidebar-menu">
      <a href="../html/admin-dashboard.php" class="menu-item">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard Admin</span>
      </a>
      <a href="../html/admin-member.php" class="menu-item">
        <i class="fas fa-users"></i> <span>List Member</span>
      </a>
      <a href="../html/admin-manajemen_donasi.php" class="menu-item">
        <i class="fas fa-trash-restore"></i> <span>Manajemen Donasi</span>
      </a>
      <a href="../html/admin-reward.php" class="menu-item">
        <i class="fas fa-donate"></i> <span>Konversi Poin</span>
      </a>
      <a href="../html/admin-edukasi.php" class="menu-item">
        <i class="fas fa-book"></i> <span>Edukasi</span>
      </a>
      <a href="../html/admin-berita.php" class="menu-item">
        <i class="fas fa-newspaper"></i> <span>Berita</span>
      </a>
    </div>

    <div class="logout-section">
      <button class="logout-btn" onclick="handleLogout()">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
      </button>
    </div>
  </div>

  <script>
    const hamburgerMenu = document.querySelector('.hamburger-menu');

    // Mobile menu toggle
    hamburgerMenu?.addEventListener('click', () => {
      hamburgerMenu.classList.toggle('active');
      document.querySelector('.sidebar').classList.toggle('active');
    });

    // Set active menu
    function setActiveMenu() {
      const currentPath = window.location.pathname;
      const menuItems = document.querySelectorAll('.sidebar .menu-item');

      menuItems.forEach(item => {
        const href = item.getAttribute('href');
        const menuPath = href.split('/').pop();
        const currentFile = currentPath.split('/').pop();

        if (menuPath === currentFile) {
          item.classList.add('active');
        }
      });
    }

    // Handle logout
    function handleLogout() {
      const confirmLogout = confirm('Apakah Anda yakin ingin keluar?');
      if (confirmLogout) {
        window.location.href = '../html/index.php';
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', setActiveMenu);

    // Handle menu item clicks on mobile
    const menuItems = document.querySelectorAll('.sidebar .menu-item');
    menuItems.forEach(item => {
      item.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
          hamburgerMenu?.classList.remove('active');
          document.querySelector('.sidebar').classList.remove('active');
        }
      });
    });
  </script>
</body>
</html>
