<?php
session_start();
include 'admin/config.php';

$target_dir = "uploads/";

if ($_GET['proses'] == 'insert') {
    try {
        // Cek apakah ada file yang diupload
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
            $nama_file = rand() . '-' . basename($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . $nama_file;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validasi file
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check === false || $_FILES["fileToUpload"]["size"] > 500000 || !in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                throw new Exception("File tidak valid.");
            }

            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                throw new Exception("Gagal mengunggah file.");
            }
        }

        $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $query = "INSERT INTO user (username, email, password, level, nama_lengkap, notelp, photo) 
                  VALUES (:username, :email, :password, :level, :nama_lengkap, :notelp, :photo)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':password' => $password_hashed,
            ':level' => $_POST['level_id'],
            ':nama_lengkap' => $_POST['nama_lengkap'],
            ':notelp' => $_POST['notelp'],
            ':photo' => isset($nama_file) ? $nama_file : null
        ]);

        echo "<script>window.location='index.php?p=users'</script>";
    } catch (Exception $e) {
        echo "Gagal menyimpan data: " . $e->getMessage();
    }
}

if ($_GET['proses'] == 'update') {
    try {
        $id = $_POST['id'];

        // Persiapkan query update
        $query = "UPDATE user SET username = :username, email = :email, level = :level, nama_lengkap = :nama_lengkap, notelp = :notelp";

        $params = [
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':level' => $_POST['level_id'],
            ':nama_lengkap' => $_POST['nama_lengkap'],
            ':notelp' => $_POST['notelp'],
            ':id' => $id
        ];

        if (!empty($_POST['password'])) {
            $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $query .= ", password = :password";
            $params[':password'] = $password_hashed;
        }

        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
            $nama_file = rand() . '-' . basename($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . $nama_file;

            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                throw new Exception("Gagal mengunggah file.");
            }

            // Hapus file lama jika ada
            $stmt = $db->prepare("SELECT photo FROM user WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data['photo'] && file_exists($target_dir . $data['photo'])) {
                unlink($target_dir . $data['photo']);
            }

            $query .= ", photo = :photo";
            $params[':photo'] = $nama_file;
        }

        $query .= " WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        echo "<script>window.location='index.php?p=users'</script>";
    } catch (Exception $e) {
        echo "Gagal memperbarui data: " . $e->getMessage();
    }
}

if ($_GET['proses'] == 'delete') {
    try {
        $id = $_GET['id'];

        // Hapus file lama jika ada
        $stmt = $db->prepare("SELECT photo FROM user WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data['photo'] && file_exists($target_dir . $data['photo'])) {
            unlink($target_dir . $data['photo']);
        }

        // Hapus data
        $stmt = $db->prepare("DELETE FROM user WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "<script>window.location='index.php?p=users'</script>";
    } catch (Exception $e) {
        echo "Gagal menghapus data: " . $e->getMessage();
    }
}
?>