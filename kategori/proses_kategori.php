<?php 
include 'koneksi.php';

// Proses Insert
if ($_GET['proses'] == 'insert') {
    if (isset($_POST['Proses'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori, keterangan) VALUES (:nama_kategori, :keterangan)");
            $stmt->bindParam(':nama_kategori', $_POST['nama_kategori']);
            $stmt->bindParam(':keterangan', $_POST['keterangan']);
            $stmt->execute();
            echo "<script>window.location='index.php?p=kategori'</script>";
        } catch (PDOException $e) {
            echo "Gagal menambahkan data kategori! Error: " . $e->getMessage();
        }
    }
}

// Proses Update
if ($_GET['proses'] == 'update') {
    if (isset($_POST['Proses'])) {
        try {
            $stmt = $pdo->prepare("UPDATE kategori SET nama_kategori = :nama_kategori, keterangan = :keterangan WHERE id = :id");
            $stmt->bindParam(':nama_kategori', $_POST['nama_kategori']);
            $stmt->bindParam(':keterangan', $_POST['keterangan']);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
            echo "<script>window.location='index.php?p=kategori'</script>";
        } catch (PDOException $e) {
            echo "Gagal memperbarui data kategori! Error: " . $e->getMessage();
        }
    }
}

// Proses Delete
if ($_GET['proses'] == 'delete') {
    try {
        $stmt = $pdo->prepare("DELETE FROM kategori WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        echo "<script>window.location='index.php?p=kategori'</script>";
    } catch (PDOException $e) {
        echo "Gagal menghapus data kategori! Error: " . $e->getMessage();
    }
}
?>