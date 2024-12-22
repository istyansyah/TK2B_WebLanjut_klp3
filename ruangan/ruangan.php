<?php
// Database connection using PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';

switch ($aksi) {
    case 'list':
        ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Ruangan</h1>
        </div>

        <div class="row">
            <div class="col-2">
                <a href="index.php?p=ruangan&aksi=input" class="btn btn-primary mb-3">Input Data Ruangan</a>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Kode ruangan</th>
                    <th>nama ruangan</th>
                    <th>Gedung</th>
                    <th>Lantai</th>
                    <th>Jenis Ruangan</th>
                    <th>Kapasitas</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>

                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM ruangan");
                    $no = 1;
                    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($data['kode_ruangan']) ?></td>
                            <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                            <td><?= htmlspecialchars($data['gedung']) ?></td>
                            <td><?= htmlspecialchars($data['lantai']) ?></td>
                            <td><?= htmlspecialchars($data['jenis_ruangan']) ?></td>
                            <td><?= htmlspecialchars($data['kapasitas']) ?></td>
                            <td><?= htmlspecialchars($data['keterangan']) ?></td>
                            <td>
                                <a href="index.php?p=ruangan&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="proses_ruangan.php?proses=delete&id=<?= $data['id'] ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Yakin akan menghapus data?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php
                        $no++;
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </table>
        </div>
        <?php
        break;

    case 'input':
        ?>
        <div class="row">
            <div class="col-6">
                <h2>Registrasi Ruangan</h2>
                <div class="col-6">
                    <p>Klik <a href="index.php?p=ruangan"><Button class="btn btn-primary mb-3">Disini</Button></a> Untuk Melihat Data</p>
                </div>
                <table>
                    <form action="proses_ruangan.php?proses=insert" method="POST">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Kode Ruangan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="kode" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Ruangan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Gedung</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="gedung" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Lantai</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="lantai" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-form-label col-sm-2 pt-0">Jenis Ruangan</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenisruang" value="Praktikum" required>
                                    <label class="form-check-label">Praktikum</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenisruang" value="Teori" required>
                                    <label class="form-check-label">Teori</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Kapasitas</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="kap" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea name="ket" cols="50" rows="4"></textarea>
                            </div>
                        </div>
                        <button type="submit" name="proses" class="btn btn-danger">Proses</button>
                        <button type="reset" class="btn btn-primary">Reset</button>
                    </form>
                </table>
            </div>
        </div>
        <?php
        break;

    case 'edit':
        try {
            $stmt = $pdo->prepare("SELECT * FROM ruangan WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $data_ruang = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$data_ruang) {
                throw new Exception("Data ruangan tidak ditemukan");
            }
            ?>
            <div class="row">
                <div class="col-6">
                    <h2>Edit Data Ruangan</h2>
                    <div class="col-2">
                        <a href="index.php?p=ruangan" class="btn btn-primary mb-3">Data Ruangan</a>
                    </div>
                    <table>
                        <form action="proses_ruangan.php?proses=update" method="POST">
                            <input type="hidden" name="id" value="<?= $data_ruang['id'] ?>">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kode Ruangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kode" 
                                           value="<?= htmlspecialchars($data_ruang['kode_ruangan']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama Ruangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama" 
                                           value="<?= htmlspecialchars($data_ruang['nama_ruangan']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Gedung</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="gedung" 
                                           value="<?= htmlspecialchars($data_ruang['gedung']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Lantai</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lantai" 
                                           value="<?= htmlspecialchars($data_ruang['lantai']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-2 pt-0">Jenis Ruangan</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenisruang" 
                                               value="Praktikum" <?= ($data_ruang['jenis_ruangan'] == 'Praktikum') ? 'checked' : '' ?> required>
                                        <label class="form-check-label">Praktikum</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenisruang" 
                                               value="Teori" <?= ($data_ruang['jenis_ruangan'] == 'Teori') ? 'checked' : '' ?> required>
                                        <label class="form-check-label">Teori</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kapasitas</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="kapasitas" 
                                           value="<?= htmlspecialchars($data_ruang['kapasitas']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea name="ket" cols="50" rows="4"><?= htmlspecialchars($data_ruang['keterangan']) ?></textarea>
                                </div>
                            </div>
                            <button type="submit" name="proses" class="btn btn-danger">Proses</button>
                            <button type="reset" class="btn btn-primary">Reset</button>
                        </form>
                    </table>
                </div>
            </div>
            <?php
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        break;
}
?>