<?php 
include 'koneksi.php';

if ($_GET['proses'] == 'insert') {
    if (isset($_POST['Proses'])) {
        $nama_level = $_POST['nama_level'];
        $keterangan = $_POST['keterangan'];

        try {
            $stmt = $pdo->prepare("INSERT INTO level (nama_level, keterangan) VALUES (:nama_level, :keterangan)");
            $stmt->bindParam(':nama_level', $nama_level);
            $stmt->bindParam(':keterangan', $keterangan);

            if ($stmt->execute()) {
                echo "<script>window.location='index.php?p=level'</script>";
            } else {
                echo "Data gagal disimpan";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if ($_GET['proses'] == 'update') {
    if (isset($_POST['Proses'])) {
        $id = $_GET['id'];
        $nama_level = $_POST['nama_level'];
        $keterangan = $_POST['keterangan'];

        try {
            $stmt = $pdo->prepare("UPDATE level SET nama_level = :nama_level, keterangan = :keterangan WHERE id = :id");
            $stmt->bindParam(':nama_level', $nama_level);
            $stmt->bindParam(':keterangan', $keterangan);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo "<script>window.location='index.php?p=level'</script>";
            } else {
                echo "Gagal memperbarui data!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if ($_GET['proses'] == 'delete') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM level WHERE id = :id");
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                header("Location:index.php?p=level");
            } else {
                echo "Gagal menghapus data!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } 
}
?>