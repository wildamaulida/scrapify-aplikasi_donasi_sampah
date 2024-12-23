<?php
include "koneksi.php";

// Tangkap data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $nomor_telepon = htmlspecialchars($_POST['nomor_telepon']);
    $tanggal_lahir = $_POST['tanggal_lahir'];

    // Validasi input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email tidak valid!");
    }

    // Masukkan ke database
    $sql = "INSERT INTO pengguna (nama, email, password, kontak, tanggal_lahir) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nama_lengkap, $email, $password, $nomor_telepon, $tanggal_lahir);

    if ($stmt->execute()) {
        echo "<script>alert('Akun berhasil dibuat!'); window.location = 'member-login.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
