<?php
// Koneksi ke database
include "koneksi.php";

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $tanggal_lahir = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : '';
    $nomor_telepon = isset($_POST['nomor_telepon']) ? $_POST['nomor_telepon'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validasi data
    if (empty($name) || empty($email) || empty($tanggal_lahir) || empty($nomor_telepon) || empty($password)) {
        echo "<p>Semua field wajib diisi!</p>";
        exit;
    }

    // Hash password sebelum menyimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menyimpan data ke tabel
    $query = "INSERT INTO pengguna (nama, email, tanggal_lahir, kontak, password) 
              VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        // Bind parameter
        $stmt->bind_param("sssss", $name, $email, $tanggal_lahir, $nomor_telepon, $hashed_password);

        // Eksekusi query
        if ($stmt->execute()) {
          echo "<script>
          alert('Akun berhasil dibuat!');
          window.location.href = 'member-login.php'; // Arahkan ke halaman login
        </script>";
        } else {
            echo "<p>Terjadi kesalahan: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Terjadi kesalahan dalam persiapan query: " . $conn->error . "</p>";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buat Akun - Sistem Informasi Bank Sampah</title>
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
      defer
    ></script>
    <style>
      body {
        background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
      }
      .form-container {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
      }
      .form-container:hover {
        transform: translateY(-10px);
      }
      .input-group {
        position: relative;
      }
      .input-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #4a5568;
      }
      .input-with-icon {
        padding-left: 35px;
      }
    </style>
  </head>
  <body class="min-h-screen flex items-center justify-center px-4 py-8">
    <div
      x-data="signupForm()"
      class="w-full max-w-md bg-white rounded-xl form-container p-8 shadow-lg"
    >
      <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-green-600 mb-2">Bank Sampah</h1>
        <p class="text-gray-600">Buat Akun Baru</p>
      </div>

      <form
        action=""
        method="POST"
      >
        <div class="space-y-4">
          <!-- Name Input -->
          <div class="input-group">
            <i class="fas fa-user input-icon"></i>
            <input
              type="text"
              x-model="name"
              name="name"
              placeholder="Nama Lengkap"
              required
              class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300"
            />
          </div>

          <!-- Email Input -->
          <div class="input-group">
            <i class="fas fa-envelope input-icon"></i>
            <input
              type="email"
              x-model="email"
              name="email"
              placeholder="Email"
              required
              class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300"
            />
          </div>

          <!-- Birthdate Input -->
          <div class="input-group">
            <i class="fas fa-calendar input-icon"></i>
            <input
              type="date"
              name="tanggal_lahir"
              x-model="birthdate"
              required
              class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300"
            />
          </div>

          <!-- Phone Number Input -->
          <div class="input-group">
            <i class="fas fa-phone input-icon"></i>
            <input
              type="tel"
              x-model="phone"
              name="nomor_telepon"
              placeholder="Nomor Telepon"
              required
              class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300"
            />
          </div>
          <!-- Password Input -->
          <div class="input-group">
            <i class="fas fa-lock input-icon"></i>
            <input
              :type="showPassword ? 'text' : 'password'"
              x-model="password"
              name="password"
              placeholder="Password"
              required
              class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-600"
            >
              <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </button>
          </div>

          

          
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full mt-6 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105"
          onclick="window.location.href='member-index.php'"
        >
          Buat Akun
        </button>
      </form>

      <!-- Login Link -->
      <div class="text-center mt-4">
        <p class="text-sm text-gray-600">
          Sudah punya akun?
          <a href="../html/member-login.php" class="text-green-600 hover:underline">Masuk</a>
        </p>
      </div>
    </div>

    <script>
      function signupForm() {
        return {
          name: "Jaka Arya",
          email: "member@example.com",
          password: "password123",
          phone: "08123456789",
          birthdate: "1990-01-01",
          showPassword: false,
          submitForm() {
            // Placeholder for form submission logic
            alert("Akun member berhasil dibuat!");
          },
        };
      }
    </script>
  </body>
</html>
