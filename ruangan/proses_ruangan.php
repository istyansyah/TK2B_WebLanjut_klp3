<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$proses = $_GET['proses'];

switch($proses) {
    case 'insert':
        try {
            $stmt = $pdo->prepare("INSERT INTO ruangan (kode_ruangan, nama_ruangan, gedung, lantai, 
                                                      jenis_ruangan, kapasitas, keterangan) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $_POST['kode'],
                $_POST['nama'],
                $_POST['gedung'],
                $_POST['lantai'],
                $_POST['jenisruang'],
                $_POST['kap'],
                $_POST['ket']
            ]);
            
            header("location: index.php?p=ruangan&status=success");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        break;

    case 'update':
        try {
            $stmt = $pdo->prepare("UPDATE ruangan 
                                  SET kode_ruangan = ?, 
                                      nama_ruangan = ?, 
                                      gedung = ?, 
                                      lantai = ?, 
                                      jenis_ruangan = ?, 
                                      kapasitas = ?, 
                                      keterangan = ? 
                                  WHERE id = ?");
            
            $stmt->execute([
                $_POST['kode'],
                $_POST['nama'],
                $_POST['gedung'],
                $_POST['lantai'],
                $_POST['jenisruang'],
                $_POST['kapasitas'],
                $_POST['ket'],
                $_POST['id']
            ]);
            
            header("location: index.php?p=ruangan&status=success");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        break;

    case 'delete':
        try {
            $stmt = $pdo->prepare("DELETE FROM ruangan WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            
            header("location: index.php?p=ruangan&status=success");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        break;
}
?>