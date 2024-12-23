-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Des 2024 pada 08.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scrapify`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(3) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `berita_id` int(3) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `penulis` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`berita_id`, `judul`, `isi`, `gambar`, `tanggal`, `penulis`) VALUES
(13, 'Workshop Daur Ulang: Mengubah Sampah Menjadi Berkah', 'Pada tanggal 2 November 2024, diadakan workshop daur ulang yang diselenggarakan di OCY (Olahraga dan Cinta Yogyakarta), di mana para peserta tidak hanya memiliki kesempatan untuk belajar dari para ahli mengenai cara-cara inovatif dalam mengolah sampah plastik menjadi produk-produk berguna, seperti kerajinan tangan yang menarik dan fungsional, tetapi juga terlibat langsung dalam proses pembuatannya. Kegiatan ini, yang diikuti oleh berbagai kalangan masyarakat, mulai dari pelajar, mahasiswa, hingga ibu-ibu rumah tangga, bertujuan untuk mengedukasi mereka tentang pentingnya pengurangan sampah dan memberikan inspirasi untuk menerapkan prinsip daur ulang dalam kehidupan sehari-hari, sehingga bisa menciptakan lingkungan yang lebih bersih dan berkelanjutan bagi generasi mendatang. Selain itu, peserta juga diberikan pengetahuan praktis mengenai cara memilah dan mengelola sampah yang tepat, serta dampak positif dari pengurangan penggunaan plastik terhadap kesehatan lingkungan secara keseluruhan.', 'berita2.jpg', '2024-11-05', 'Tim Redaksi Lingkungan Yogyakarta'),
(15, 'Program “Satu Bima Satu Sampah” Resmi Diluncurkan', 'Pemerintah Kota Yogyakarta meluncurkan program inovatif bernama \\\"Satu Bima Satu Sampah,\\\" yang dirancang untuk mengajak setiap warga, tanpa terkecuali, untuk berperan aktif dalam mengelola sampah di lingkungan sekitar mereka, dengan harapan dapat menciptakan kesadaran kolektif tentang pentingnya kebersihan. Program ini melibatkan partisipasi pemuda dan anak-anak dalam berbagai kegiatan, seperti pengumpulan dan pemilahan sampah yang dilakukan secara rutin, serta memberikan pelatihan yang komprehensif mengenai pentingnya menjaga kebersihan lingkungan dan dampak positifnya terhadap kesehatan masyarakat. Melalui program ini, diharapkan warga tidak hanya memahami bagaimana cara mengelola sampah dengan baik, tetapi juga terinspirasi untuk menjadikan praktik menjaga kebersihan sebagai bagian dari gaya hidup sehari-hari mereka. Selain itu, program ini juga berfungsi sebagai wadah bagi komunitas untuk saling berbagi pengetahuan dan pengalaman, sehingga tercipta lingkungan yang lebih bersih, sehat, dan berkelanjutan bagi seluruh masyarakat Yogyakarta.', 'berita3-2.jpg', '2024-11-20', 'Tim Redaksi Komunitas Yogyakarta'),
(18, 'Peran Islam dalam Menangani Krisis Lingkungan: Perspektif Al-Qur’an dan Hadis', 'wdefrgtf', 'berita1.jpg', '2024-12-12', 'Wilda');

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi_sampah`
--

CREATE TABLE `donasi_sampah` (
  `donasi_id` int(3) NOT NULL,
  `tanggal_donasi` date DEFAULT NULL,
  `poin` int(10) DEFAULT NULL,
  `berat` int(3) DEFAULT NULL,
  `kode_resi` varchar(30) DEFAULT NULL,
  `status` enum('Disetujui','Belum Terverifikasi','Ditolak') DEFAULT NULL,
  `tanggal_penjemputan` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `gambar` varchar(225) DEFAULT NULL,
  `jenis_sampah` varchar(25) NOT NULL,
  `pengguna_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi_sampah`
--

INSERT INTO `donasi_sampah` (`donasi_id`, `tanggal_donasi`, `poin`, `berat`, `kode_resi`, `status`, `tanggal_penjemputan`, `alamat`, `gambar`, `jenis_sampah`, `pengguna_id`) VALUES
(22, '2024-12-12', 25000, 5, 'DONASI-374833', 'Disetujui', '2024-12-12', '34t5r', 'uploads/bgLoginSebagai.jpg', 'organik', 15),
(23, '2024-12-12', 25000, 5, 'DONASI-734781', 'Ditolak', '2024-12-12', '34t5r', 'uploads/bgLoginSebagai.jpg', 'organik', 15),
(24, '2024-12-12', 30000, 6, 'DONASI-302701', 'Disetujui', '2024-12-12', 'werdfgh', 'uploads/sampahPlastik.jpeg', 'organik', 15),
(28, '2024-12-11', 210000, 12, 'DONASI-956008', 'Belum Terverifikasi', '2024-12-11', '23eretghyt', 'uploads/event.jpg', 'organik', 16),
(76, '2024-12-14', 15000, 3, 'DONASI-314291', 'Belum Terverifikasi', '2024-12-20', 'sqwdefrtg', 'uploads/bgLoginSebagai.jpg', 'organik', 16),
(88, '2024-12-13', 10000, 2, 'DONASI-213945', 'Belum Terverifikasi', '2024-12-27', '232e3wefr', 'uploads/berita2.jpg', 'organik', 16),
(89, '2024-12-13', 10000, 2, 'DONASI-697624', 'Belum Terverifikasi', '2024-12-27', '232e3wefr', 'uploads/berita2.jpg', 'organik', 16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `edukasi`
--

CREATE TABLE `edukasi` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `penulis` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `edukasi`
--

INSERT INTO `edukasi` (`id`, `judul`, `isi`, `gambar`, `tanggal`, `penulis`) VALUES
(20, 'Mengolah Sampah Organik Menjadi Kompos', 'Sampah organik dapat diubah menjadi kompos yang bermanfaat, \r\n            membantu meningkatkan kesuburan tanah dan mendukung upaya penghijauan', '1734610243_kompos.jpg', '2024-12-19', 'Nayla'),
(22, 'Strategi Pemilahan Sampah yang Efektif', 'Memilah sampah sangat penting untuk meningkatkan daur ulang dan mengurangi limbah, \r\n            sehingga menciptakan lingkungan yang lebih bersih dan berkelanjutan.', '1734610519_pilahKompos.jpeg', '2024-12-19', 'Wilda'),
(23, 'Mencari Solusi untuk Sampah Plastik', 'Penggunaan plastik sekali pakai berkontribusi besar terhadap pencemaran lingkungan, \r\n            sehingga diperlukan solusi alternatif yang lebih ramah lingkungan untuk mengurangi dampaknya.', '1734610575_sampahPlastik.jpeg', '2024-12-19', 'Nia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `pengguna_id` int(3) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `total_poin` int(10) NOT NULL,
  `kontak` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`pengguna_id`, `nama`, `email`, `total_poin`, `kontak`, `password`, `tanggal_lahir`) VALUES
(15, 'wilda', 'wilda@gmail.com', 0, '08123456789', '$2y$10$eo0fB7OqcRoTe8WTZoKyk.3bzofdN/FS8rc7Mr6SxWExiwngyFTBK', '1990-01-01'),
(16, 'Jaka Arya', 'member@example.com', 0, '08123456789', '$2y$10$ut1UH7c3vHzuKcgKvKgK7ezLm5fVqNEQyl3dUzaAvLervXFNHlgme', '1990-01-01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reward`
--

CREATE TABLE `reward` (
  `id` int(3) NOT NULL,
  `tanggal` timestamp NULL DEFAULT current_timestamp(),
  `nomor_ewallet` varchar(30) DEFAULT NULL,
  `poin_convert` int(15) DEFAULT NULL,
  `rupiah` varchar(255) DEFAULT NULL,
  `status` enum('Disetujui','Belum terverifikasi','Ditolak','') DEFAULT NULL,
  `donasi_id` int(3) DEFAULT NULL,
  `pengguna_id` int(3) DEFAULT NULL,
  `wallet` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reward`
--

INSERT INTO `reward` (`id`, `tanggal`, `nomor_ewallet`, `poin_convert`, `rupiah`, `status`, `donasi_id`, `pengguna_id`, `wallet`) VALUES
(14, '2024-12-20 03:35:06', '089678816368', 50000, '50000', 'Disetujui', NULL, 16, 'GoPay'),
(15, '2024-12-20 03:36:02', '089678816368', 50000, '50000', 'Belum terverifikasi', NULL, 16, 'GoPay'),
(16, '2024-12-20 03:36:20', '089678816368', 50000, '50000', 'Belum terverifikasi', NULL, 16, 'GoPay'),
(17, '2024-12-20 03:36:46', '089678816368', 60000, '60000', 'Disetujui', NULL, 16, 'GoPay'),
(18, '2024-12-20 03:38:50', '089678816368', 60000, '60000', 'Ditolak', NULL, 16, 'GoPay'),
(19, '2024-12-20 03:39:32', '089678816368', 60000, '60000', 'Disetujui', NULL, 16, 'GoPay'),
(20, '2024-12-20 03:39:43', '089678816368', 50000, '50000', 'Ditolak', NULL, 16, 'GoPay');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`berita_id`);

--
-- Indeks untuk tabel `donasi_sampah`
--
ALTER TABLE `donasi_sampah`
  ADD PRIMARY KEY (`donasi_id`);

--
-- Indeks untuk tabel `edukasi`
--
ALTER TABLE `edukasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`pengguna_id`);

--
-- Indeks untuk tabel `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reward_donasi` (`donasi_id`),
  ADD KEY `fk_pengguna_reward` (`pengguna_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `berita_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `donasi_sampah`
--
ALTER TABLE `donasi_sampah`
  MODIFY `donasi_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `edukasi`
--
ALTER TABLE `edukasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `pengguna_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `reward`
--
ALTER TABLE `reward`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `reward`
--
ALTER TABLE `reward`
  ADD CONSTRAINT `fk_pengguna_reward` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`pengguna_id`),
  ADD CONSTRAINT `fk_reward_donasi` FOREIGN KEY (`donasi_id`) REFERENCES `donasi_sampah` (`donasi_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
