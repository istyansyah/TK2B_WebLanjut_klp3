<?php
// proses_mahasiswa.php
require_once 'koneksi.php';

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

switch ($proses) {
    case 'insert':
        try {
            // Construct date from form inputs
            $tgl_lahir = $_POST['thn'] . '-' . 
                         str_pad($_POST['bln'], 2, '0', STR_PAD_LEFT) . '-' . 
                         str_pad($_POST['tgl'], 2, '0', STR_PAD_LEFT);
            
            // Convert hobbies array to comma-separated string
            $hobi = isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '';

            $stmt = $db->prepare("INSERT INTO mahasiswa 
                (nama, nim, tgl_lahir, jekel, email, hobi, nohp, alamat) 
                VALUES (:nama, :nim, :tgl_lahir, :jekel, :email, :hobi, :nohp, :alamat)");
            
            $result = $stmt->execute([
                ':nama' => $_POST['nama'],
                ':nim' => $_POST['nim'],
                ':tgl_lahir' => $tgl_lahir,
                ':jekel' => $_POST['jekel'],
                ':email' => $_POST['email'],
                ':hobi' => $hobi,
                ':nohp' => $_POST['nohp'],
                ':alamat' => $_POST['alamat']
            ]);

            if ($result) {
                header("Location: index.php?p=mhs&aksi=list");
                exit();
            } else {
                echo "Gagal menyimpan data";
            }
        } catch(PDOException $e) {
            die("Insert failed: " . $e->getMessage());
        }
        break;

    case 'update':
        try {
            // Construct date from form inputs
            $tgl_lahir = $_POST['thn'] . '-' . 
                         str_pad($_POST['bln'], 2, '0', STR_PAD_LEFT) . '-' . 
                         str_pad($_POST['tgl'], 2, '0', STR_PAD_LEFT);
            
            // Convert hobbies array to comma-separated string
            $hobi = isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '';

            $stmt = $db->prepare("UPDATE mahasiswa 
                SET nama = :nama, 
                    tgl_lahir = :tgl_lahir, 
                    jekel = :jekel, 
                    email = :email, 
                    hobi = :hobi, 
                    nohp = :nohp, 
                    alamat = :alamat 
                WHERE nim = :nim");
            
            $result = $stmt->execute([
                ':nama' => $_POST['nama'],
                ':nim' => $_POST['nim'],
                ':tgl_lahir' => $tgl_lahir,
                ':jekel' => $_POST['jekel'],
                ':email' => $_POST['email'],
                ':hobi' => $hobi,
                ':nohp' => $_POST['nohp'],
                ':alamat' => $_POST['alamat']
            ]);

            if ($result) {
                header("Location: index.php?p=mhs&aksi=list");
                exit();
            } else {
                echo "Gagal memperbarui data";
            }
        } catch(PDOException $e) {
            die("Update failed: " . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            $stmt = $db->prepare("DELETE FROM mahasiswa WHERE nim = :nim");
            $result = $stmt->execute(['nim' => $_GET['nim']]);

            if ($result) {
                header("Location: index.php?p=mhs&aksi=list");
                exit();
            } else {
                echo "Gagal menghapus data";
            }
        } catch(PDOException $e) {
            die("Delete failed: " . $e->getMessage());
        }
        break;

    default:
        header("Location: index.php?p=mhs&aksi=list");
        exit();
}
?>