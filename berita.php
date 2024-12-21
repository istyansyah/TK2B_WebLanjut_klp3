<?php
// Koneksi PDO
include 'koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';
switch ($aksi) {

    case 'list':
?>

        <div class="container">
            <div class="row">
                <div class="col-2">
                    <a href="index.php?p=berita&aksi=input" class="btn btn-primary mb-3"> Input berita </a>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Date Created</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    // Mengambil data berita
                    $sql = "SELECT berita.*, kategori.nama_kategori FROM berita JOIN kategori ON berita.kategori_id = kategori.id";
                    $stmt = $pdo->query($sql);
                    $no = 1;
                    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>

                        <tr>
                            <td><?php echo $no ?></td>
                            <td><?= htmlspecialchars($data['judul']) ?></td>
                            <td><?= htmlspecialchars($data['nama_kategori']) ?></td>
                            <td><?= htmlspecialchars($data['data_created']) ?></td>
                            <td>
                                <a href="index.php?p=berita&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success">Edit</a>
                                <a href="proses_berita.php?proses=delete&id=<?= $data['id'] ?>&img=<?= $data['file_upload'] ?>" onclick="return confirm('Apa anda yakin menghapus data?')" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>

                </table>

            </div>
        </div>

    <?php
        break;

    case 'input':
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Input Berita</title>
    </head>
    <body>
    <div class="container">
            <form action="proses_berita.php?proses=insert" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-3">
                            <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" name="judul" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kategori_id" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select name="kategori_id" id="kategori_id" class="form-select">
                                    <option value="">--Pilih Kategori--</option>
                                    <?php
                                    // Mengambil data kategori
                                    $sql = "SELECT * FROM kategori";
                                    $stmt = $pdo->query($sql);
                                    while ($data_kategori = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $data_kategori['id'] . "'>" . htmlspecialchars($data_kategori['nama_kategori']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="file-upload" class="col-sm-2 col-form-label">File Upload</label>
                            <div class="col-sm-10">
                                <input type="file" name="fileToUpload" class="form-control" id="file-upload">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="isi_berita" class="col-sm-2 col-form-label">Isi Berita</label>
                            <div class="col-sm-10">
                                <textarea name="isi_berita" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                        <button type="submit" name="Proses" value="Proses" class="btn btn-primary">Proses</button>
                    </div>
                </div>
        </div>
    </body>
    </html>

    <?php
        break;

    case 'edit':
        // Mengambil ID berita dari URL
        $id = $_GET['id'];

        // Mengambil data berita berdasarkan ID
        $sql = "SELECT * FROM berita WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data_berita = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
        <div class="container">
            <form action="proses_berita.php?proses=update" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-3">
                            <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="id" value="<?php echo $data_berita['id']; ?>">
                                <input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($data_berita['judul']); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kategori_id" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select name="kategori_id" class="form-select">
                                    <option value="">--Pilih Kategori--</option>
                                    <?php
                                    $sql = "SELECT * FROM kategori";
                                    $stmt = $pdo->query($sql);
                                    while ($data_kategori = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($data_kategori['id'] == $data_berita['kategori_id']) ? 'selected' : '';
                                        echo "<option value='" . $data_kategori['id'] . "' " . $selected . ">" . htmlspecialchars($data_kategori['nama_kategori']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="file-upload" class="col-sm-2 col-form-label">File Upload</label>
                            <div class="col-sm-10">
                                <input type="file" name="fileToUpload" class="form-control" id="file-upload">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="isi_berita" class="col-sm-2 col-form-label">Isi Berita</label>
                            <div class="col-sm-10">
                                <textarea name="isi_berita" class="form-control" rows="10"><?php echo htmlspecialchars($data_berita['isi_berita']); ?></textarea>
                            </div>
                        </div>
                        <button type="submit" name="Proses" value="Proses" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
<?php
        break;
}
?>