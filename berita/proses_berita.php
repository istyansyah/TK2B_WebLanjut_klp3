<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi menggunakan PDO

$target_dir = "uploads/";

// Mengecek proses berdasarkan nilai `$_GET['proses']`
if ($_GET['proses'] == 'insert') {
    // Menangani file yang diupload
    $nama_file = rand() . '-' . basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $nama_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi file
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["size"] < 1024 || $_FILES["fileToUpload"]["size"] > 100 * 1024 * 1024) {
        echo "Sorry, file size is either too small or too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Proses upload file dan insert data ke database
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            try {
                $stmt = $db->prepare("INSERT INTO berita (user_id, kategori_id, judul, file_upload, isi_berita) 
                                      VALUES (:user_id, :kategori_id, :judul, :file_upload, :isi_berita)");
                $stmt->execute([
                    ':user_id' => $_SESSION['user_id'],
                    ':kategori_id' => $_POST['kategori_id'],
                    ':judul' => $_POST['judul'],
                    ':file_upload' => $nama_file,
                    ':isi_berita' => $_POST['isi_berita']
                ]);
                echo "<script>window.location='index.php?p=berita'</script>";
            } catch (PDOException $e) {
                echo "Gagal menyimpan data: " . $e->getMessage();
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    } else {
        echo "Maaf, file Anda tidak dapat diunggah.";
    }
}

// Proses update data berita
if ($_GET['proses'] == 'update') {
    if (isset($_POST['Proses'])) {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $kategori_id = $_POST['kategori_id'];
        $isi_berita = $_POST['isi_berita'];

        try {
            if (!empty($_FILES['fileToUpload']['name'])) {
                $nama_file = rand() . '-' . basename($_FILES["fileToUpload"]["name"]);
                $target_file = $target_dir . $nama_file;

                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // Update data dengan file baru
                    $stmt = $db->prepare("UPDATE berita SET 
                        judul = :judul, 
                        kategori_id = :kategori_id, 
                        isi_berita = :isi_berita, 
                        file_upload = :file_upload 
                        WHERE id = :id");
                    $stmt->execute([
                        ':judul' => $judul,
                        ':kategori_id' => $kategori_id,
                        ':isi_berita' => $isi_berita,
                        ':file_upload' => $nama_file,
                        ':id' => $id
                    ]);
                } else {
                    echo "Maaf, terjadi kesalahan saat mengunggah file.";
                }
            } else {
                // Jika tidak ada file yang diupload, update data tanpa file
                $stmt = $db->prepare("UPDATE berita SET 
                    judul = :judul, 
                    kategori_id = :kategori_id, 
                    isi_berita = :isi_berita
                    WHERE id = :id");
                $stmt->execute([
                    ':judul' => $judul,
                    ':kategori_id' => $kategori_id,
                    ':isi_berita' => $isi_berita,
                    ':id' => $id
                ]);
            }

            // Redirect jika berhasil update
            echo "<script>window.location='index.php?p=berita'</script>";
        } catch (PDOException $e) {
            echo "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}

// Proses hapus berita dan file terkait
if ($_GET['proses'] == 'delete') {
    try {
        unlink($target_dir . $_GET['img']);
        $stmt = $db->prepare("DELETE FROM berita WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        header('location:index.php?p=berita');
    } catch (PDOException $e) {
        echo "Gagal menghapus data: " . $e->getMessage();
    }
}
?>