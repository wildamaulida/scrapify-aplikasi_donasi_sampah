<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM edukasi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
        echo json_encode($article);
    } else {
        echo json_encode(null); // Jika tidak ada artikel ditemukan
    }
} else {
    echo json_encode(null); // Jika ID tidak diset
}
?>