<?php
session_start();
include "koneksi.php"; // Pastikan koneksi ke database benar
$message = ""; // Pesan untuk error atau feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk memeriksa email
    $sql = "SELECT * FROM pengguna WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Jika login berhasil, simpan pengguna_id ke session
            $_SESSION['pengguna_id'] = $row['pengguna_id'];  // Simpan ID pengguna ke session
            $_SESSION['nama'] = $row['nama'];  // Simpan nama pengguna jika diperlukan

            // Tampilkan alert dan arahkan ke member-index.php
            echo "<script>
                    alert('Login berhasil! Selamat datang, " . htmlspecialchars($row['nama']) . ".');
                    window.location.href = 'member-index.php';
                  </script>";
            exit(); // Pastikan eksekusi berhenti setelah redirect
        } else {
            $message = "<div style='color: red;'>Password salah!</div>";
        }
    } else {
        $message = "<div style='color: red;'>Email tidak ditemukan!</div>";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Bank Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            background: linear-gradient(135deg, #e6f3e6 0%, #b0d4b0 100%);
        }

        .login-container {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
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
    <div x-data="loginForm()" class="w-full max-w-md bg-white rounded-xl login-container p-8 shadow-lg">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-green-600 mb-2">Bank Sampah</h1>
            <p class="text-gray-600">Masuk sebagai Member</p>
        </div>
        <?php if (!empty($message)) echo $message; ?>
        <form method="POST" action="">
            <div class="space-y-4">
                <!-- Email Input -->
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input name="email" type="email" x-model="email" placeholder="Email" required
                        class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300">
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input :type="showPassword ? 'text' : 'password'" x-model="password" name="password" placeholder="Password" required
                        class="input-with-icon w-full p-3 pl-10 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition duration-300">
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-600">
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>

        
            </div>

            <button type="submit"
                class="w-full mt-6 bg-green-500 hover:bg-green-600 text-white 
                font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105">
                Masuk
            </button>
        </form>

        <!-- Signup Link -->
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a href="member-signup.php" class="text-green-600 hover:underline">Daftar Sekarang</a>
            </p>
        </div>

        <script>
            function loginForm() {
                return {
                    email: 'member@example.com',
                    password: 'password123',
                    showPassword: false,
                    rememberMe: false,
                    submitLogin() {
                        // Placeholder for login logic
                        alert('Login berhasil!');
                        // Redirect or further processing would go here
                    }
                }
            }
        </script>
</body>

</html>