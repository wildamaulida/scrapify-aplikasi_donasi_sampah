<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Sebagai</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
    <style>
      :root {
        --primary-color: #2cb249;
        --secondary-color: #238a3b;
        --bg-gradient: linear-gradient(135deg, #e0f3e8 0%, #ffffff 100%);
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI",
          Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue",
          sans-serif;
        background: var(--bg-gradient);
        color: #333;
        line-height: 1.6;
      }

      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }

      .header {
        text-align: center;
        margin-bottom: 3rem;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s forwards;
      }

      .header h1 {
        color: var(--primary-color);
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 800;
      }

      .header p {
        color: #666;
        max-width: 600px;
        margin: 0 auto;
        font-size: 1.1rem;
      }

      .login-options {
        display: flex;
        gap: 2rem;
        justify-content: center;
        width: 100%;
      }

      .login-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        width: 300px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        opacity: 0;
        transform: translateY(20px);
      }

      .login-card:nth-child(1) {
        animation: fadeInUp 0.8s 0.2s forwards;
      }

      .login-card:nth-child(2) {
        animation: fadeInUp 0.8s 0.4s forwards;
      }

      .login-card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      }

      .login-card .icon {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
      }

      .login-card:hover .icon {
        transform: rotate(360deg);
      }

      .login-card h2 {
        color: var(--secondary-color);
        margin-bottom: 1rem;
        font-size: 1.4rem;
      }

      .login-card .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          135deg,
          rgba(44, 178, 73, 0.1) 0%,
          rgba(35, 138, 59, 0.1) 100%
        );
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
      }

      .login-card:hover .overlay {
        opacity: 1;
      }

      @keyframes fadeInUp {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @media screen and (max-width: 768px) {
        .login-options {
          flex-direction: column;
          align-items: center;
        }

        .login-card {
          width: 100%;
          max-width: 400px;
        }
      }

      .login-card a {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-decoration: none;
        z-index: 10;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Pilih Jenis Pengguna</h1>
        <p>
          Selamat datang! Silakan pilih apakah Anda ingin masuk sebagai Admin
          atau Member untuk mengakses sistem kami.
        </p>
      </div>
      <div class="login-options">
        <div class="login-card">
          <div class="overlay"></div>
          <i class="fas fa-user-shield icon"></i>
          <h2>Admin Login</h2>
          <p>Masuk dengan akun administrator</p>
          <a href="../html/admin-login.php"></a>
        </div>
        <div class="login-card">
          <div class="overlay"></div>
          <i class="fas fa-users icon"></i>
          <h2>Member Login</h2>
          <p>Masuk dengan akun member</p>
          <a href="../html/member-login.php"></a>
        </div>
      </div>
    </div>
  </body>
</html>
