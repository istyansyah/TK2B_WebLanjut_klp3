<?php
include 'admin/koneksi.php'; // Menghubungkan ke database menggunakan PDO

if (isset($_GET['proses'])) {
    $proses = $_GET['proses'];

    try {
        switch ($proses) {
            case 'insert': // Proses tambah data dosen
                $stmt = $db->prepare("INSERT INTO dosenn (nip, nama_dosen, email, prodi_id, notelp, alamat) 
                                      VALUES (:nip, :nama_dosen, :email, :prodi_id, :notelp, :alamat)");
                $stmt->execute([
                    ':nip' => $_POST['nip'],
                    ':nama_dosen' => $_POST['nama_dosen'],
                    ':email' => $_POST['email'],
                    ':prodi_id' => $_POST['prodi_id'],
                    ':notelp' => $_POST['notelp'],
                    ':alamat' => $_POST['alamat']
                ]);
                header("Location: index.php?p=dosen");
                break;

            case 'update': // Proses update data dosen
                $stmt = $db->prepare("UPDATE dosenn SET nip = :nip, nama_dosen = :nama_dosen, email = :email, 
                                      prodi_id = :prodi_id, notelp = :notelp, alamat = :alamat WHERE id = :id");
                $stmt->execute([
                    ':nip' => $_POST['nip'],
                    ':nama_dosen' => $_POST['nama_dosen'],
                    ':email' => $_POST['email'],
                    ':prodi_id' => $_POST['prodi_id'],
                    ':notelp' => $_POST['notelp'],
                    ':alamat' => $_POST['alamat'],
                    ':id' => $_POST['id']
                ]);
                header("Location: index.php?p=dosen");
                break;

            case 'delete': // Proses hapus data dosen
                $stmt = $db->prepare("DELETE FROM dosenn WHERE id = :id");
                $stmt->execute([':id' => $_GET['id']]);
                header("Location: index.php?p=dosen");
                break;

            default:
                echo "Proses tidak dikenal!";
                break;
        }
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    echo "Proses tidak ditemukan!";
}
