<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dosen</h1>
</div>

<?php
include 'admin/koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';

switch ($aksi) {
    case 'list':
?>

<div class="row">
    <div class="col-2">
        <a href="index.php?p=dosen&aksi=input" class="btn btn-primary mb-3"><i class="bi bi-file-plus"></i> Tambah Dosen</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Dosen</th>
                <th>Email</th>
                <th>Prodi</th>
                <th>No Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->prepare("SELECT dosenn.*, prodi.nama_prodi FROM dosenn INNER JOIN prodi ON prodi.id = dosenn.prodi_id");
            $stmt->execute();
            $no = 1;
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $data['nip'] ?></td>
                    <td><?= $data['nama_dosen'] ?></td>
                    <td><?= $data['email'] ?></td>
                    <td><?= $data['nama_prodi'] ?></td>
                    <td><?= $data['notelp'] ?></td>
                    <td><?= $data['alamat'] ?></td>
                    <td>
                        <a href="index.php?p=dosen&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success"><i class="bi bi-pen-fill"></i> Edit</a>
                        <a href="proses_dosen.php?proses=delete&id=<?= $data['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin akan menghapus data?')"><i class="bi bi-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php
    break;

    case 'input':
?>

<div class="row">
    <div class="col-6">
        <h2>Tambah Data Dosen</h2>
        <a href="index.php?p=dosen" class="btn btn-primary mb-3">Kembali ke Data Dosen</a>
        <form action="proses_dosen.php?proses=insert" method="POST">
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="number" class="form-control" name="nip" required>
            </div>
            <div class="mb-3">
                <label for="nama_dosen" class="form-label">Nama Dosen</label>
                <input type="text" class="form-control" name="nama_dosen" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="prodi_id" class="form-label">Program Studi</label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">- Pilih Prodi -</option>
                    <?php
                    $prodi_stmt = $db->query("SELECT * FROM prodi");
                    while ($prodi = $prodi_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$prodi['id']}'>{$prodi['nama_prodi']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="notelp" class="form-label">Nomor Telepon</label>
                <input type="number" class="form-control" name="notelp" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </div>
</div>

<?php
    break;

    case 'edit':
        $stmt = $db->prepare("SELECT * FROM dosenn WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $data_dosen = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-6">
        <h2>Edit Data Dosen</h2>
        <a href="index.php?p=dosen" class="btn btn-primary mb-3">Kembali ke Data Dosen</a>
        <form action="proses_dosen.php?proses=update" method="POST">
            <input type="hidden" name="id" value="<?= $data_dosen['id'] ?>">
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="number" class="form-control" name="nip" value="<?= $data_dosen['nip'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_dosen" class="form-label">Nama Dosen</label>
                <input type="text" class="form-control" name="nama_dosen" value="<?= $data_dosen['nama_dosen'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= $data_dosen['email'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="prodi_id" class="form-label">Program Studi</label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">- Pilih Prodi -</option>
                    <?php
                    $prodi_stmt = $db->query("SELECT * FROM prodi");
                    while ($prodi = $prodi_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($prodi['id'] == $data_dosen['prodi_id']) ? 'selected' : '';
                        echo "<option value='{$prodi['id']}' {$selected}>{$prodi['nama_prodi']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="notelp" class="form-label">Nomor Telepon</label>
                <input type="number" class="form-control" name="notelp" value="<?= $data_dosen['notelp'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" rows="4" required><?= $data_dosen['alamat'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </div>
</div>

<?php
    break;
}
?>
