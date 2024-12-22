<?php
include 'koneksi.php'; // Pastikan koneksi menggunakan PDO

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';

switch ($aksi) {
    case 'list':
        ?>
        <div class="row">
            <div class="col-6">
                <a href="index.php?p=level&aksi=input" class="btn btn-primary mb-3"><i class="bi bi-file-plus"></i> Tambah Level</a>
            </div>

            <table class="table table-border">
                <tr>
                    <th>No</th>
                    <th>Nama Level</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $query = $pdo->query("SELECT * FROM level");
                $no = 1;
                while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td><?= htmlspecialchars($data['nama_level']) ?></td>
                        <td><?= htmlspecialchars($data['keterangan']) ?></td>
                        <td>
                            <a href="index.php?p=level&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success"><i class="bi bi-pen-fill"></i> Edit</a>
                            <a href="proses_level.php?proses=delete&id=<?= $data['id'] ?>" class="btn btn-warning" onclick="return confirm('Yakin akan menghapus data?')"><i class="bi bi-trash"></i> Hapus</a>
                        </td>
                    </tr>
                <?php
                    $no++;
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
                <div class="col-20">
                    <h3>Klik <a href="index.php?p=level" class="btn btn-primary mb-3">Disini</a> Untuk Melihat Data Level</h3>
                </div>
                <table>
                    <form action="proses_level.php?proses=insert" method="POST">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Level</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_level" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="keterangan" required>
                            </div>
                        </div>

                        <button type="submit" name="Proses" class="btn btn-danger">Proses</button> &nbsp;
                        <button type="reset" class="btn btn-primary"> Reset</button>
                    </form>
                </table>
            </div>
        </div>
        <?php
        break;

    case 'edit':
        $stmt = $pdo->prepare("SELECT * FROM level WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $data_level = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data_level) {
            echo "<script>alert('Data tidak ditemukan');window.location='index.php?p=level';</script>";
            exit;
        }
        ?>
        <div class="row">
            <div class="col-6">
                <h2>Edit Data Level</h2>
                <div class="col-2">
                    <a href="index.php?p=level" class="btn btn-primary mb-3">Data Level</a>
                </div>
                <table>
                    <form action="proses_level.php?proses=update" method="POST">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Level</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_level" value="<?= htmlspecialchars($data_level['nama_level']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="keterangan" value="<?= htmlspecialchars($data_level['keterangan']) ?>" required>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $data_level['id'] ?>">
                        
                        <button type="submit" name="Proses" class="btn btn-danger">Update</button> &nbsp;
                        <button type="reset" class="btn btn-primary">Reset</button>
                    </form>
                </table>
            </div>
        </div>
        <?php
}
?>