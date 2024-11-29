<?php
// koneksi.php
try {
    $db = new PDO('mysql:host=localhost;dbname=tekom2b_kasus', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// index.php (Main File)
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';
switch ($aksi) {
    case 'list':
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mahasiswa</h1>
</div>

<div class="row">
    <div class="col-2">
        <a href="index.php?p=mhs&aksi=input" class="btn btn-primary mb-3">Input Data Mahasiswa</a>
    </div>

    <table class="table table-bordered table-striped table-sm">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Email</th>
            <th>Hobi</th>
            <th>No Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>

        <?php
        try {
            $stmt = $db->query("SELECT * FROM mahasiswa");
            $no = 1;
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['nim'] ?></td>
                <td><?= $data['tgl_lahir'] ?></td>
                <td><?= $data['jekel'] ?></td>
                <td><?= $data['email'] ?></td>
                <td><?= $data['hobi'] ?></td>
                <td><?= $data['nohp'] ?></td>
                <td><?= $data['alamat'] ?></td>
                <td>
                    <a href="index.php?p=mhs&aksi=edit&nim=<?= $data['nim'] ?>" class="btn btn-success"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="proses_mahasiswa.php?proses=delete&nim=<?= $data['nim'] ?>" class="btn btn-danger" onclick="return confirm('Yakin akan menghapus data?')"><i class="bi bi-trash"></i> Hapus</a>
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
        <h2>Registrasi Mahasiswa</h2>
        <div class="col-6">
            <p>Klik <a href="index.php?p=mhs"><button class="btn btn-primary mb-3">Disini</button></a> Untuk Melihat Data</p>
        </div>
        <table>
            <form action="proses_mahasiswa.php?proses=insert" method="POST">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">NIM</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="nim">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-4">
                                <select name="thn" class="form-control">
                                    <option value="">--YY--</option>
                                    <?php
                                    for ($i = date('Y'); $i >= 1980; $i--)
                                        echo "<option value=" . $i . ">" . $i . "</option>"
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="bln" class="form-control">
                                    <option value="">-----MM-----</option>
                                    <?php
                                    $nama_bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach ($nama_bln as $key => $itembln) {
                                        echo "<option value=" . ($key + 1) . ">" . $itembln . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="tgl" class="form-control">
                                    <option value="">--DD--</option>
                                    <?php
                                    for ($i = 0; $i <= 30; $i++)
                                        echo "<option value=" . ($i + 1) . ">" . ($i + 1) . "</option>"
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-form-label col-sm-2 pt-0">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jekel" value="Laki-Laki">
                            <label class="form-check-label" for="gridRadios1">
                                Laki-Laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jekel" value="Perempuan">
                            <label class="form-check-label" for="gridRadios2">
                                Perempuan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Hobi</label>
                    <input type="checkbox" name="hobi[]" value="berenang"> &nbsp Berenang
                    <input type="checkbox" name="hobi[]" value="membaca"> &nbsp Membaca
                    <input type="checkbox" name="hobi[]" value="futsal"> &nbsp Futsal
                    <input type="checkbox" name="hobi[]" value="voli"> &nbsp Voli
                    <input type="checkbox" name="hobi[]" value="nonton"> &nbsp Nonton
                    <input type="checkbox" name="hobi[]" value="ngoding"> &nbsp Ngoding
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">No Hp</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="nohp">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea name="alamat" cols="50" rows="4"></textarea>
                    </div>
                </div>
                <button type="submit" name="proses" class="btn btn-danger">Proses</button> &nbsp
                <button type="reset" class="btn btn-primary">Reset</button>
            </form>
        </table>
    </div>
</div>
<?php
    break;

    case 'edit':
        try {
            $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
            $stmt->execute(['nim' => $_GET['nim']]);
            $data_mhs = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $tgl = explode("-", $data_mhs['tgl_lahir']);
            $hobies = explode(",", $data_mhs['hobi']);
        } catch(PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
?>
<div class="row">
    <div class="col-6">
        <h2>Edit Data Mahasiswa</h2>
        <div class="col-2">
            <a href="index.php?p=mhs" class="btn btn-primary mb-3">Data Mahasiswa</a>
        </div>
        <table>
            <form action="proses_mahasiswa.php?proses=update" method="POST">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama" value="<?= $data_mhs['nama'] ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">NIM</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="nim" value="<?= $data_mhs['nim'] ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-4">
                                <select name="thn" class="form-control">
                                    <option value="">--YY--</option>
                                    <?php
                                    for ($i = date('Y'); $i >= 1980; $i--)
                                        echo "<option value=" . $i . " " . ($tgl[0] == $i ? 'selected' : '') . ">" . $i . "</option>"
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="bln" class="form-control">
                                    <option value="">-----MM-----</option>
                                    <?php
                                    $nama_bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach ($nama_bln as $key => $itembln) {
                                        echo "<option value=" . ($key + 1) . " " . ($tgl[1] == ($key + 1) ? 'selected' : '') . ">" . $itembln . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="tgl" class="form-control">
                                    <option value="">--DD--</option>
                                    <?php
                                    for ($i = 0; $i <= 30; $i++)
                                        echo "<option value=" . ($i + 1) . " " . ($tgl[2] == ($i + 1) ? 'selected' : '') . ">" . ($i + 1) . "</option>"
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-form-label col-sm-2 pt-0">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jekel" value="Laki-Laki" <?= ($data_mhs['jekel'] == 'Laki-Laki') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="gridRadios1">
                                Laki-Laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jekel" value="Perempuan" <?= ($data_mhs['jekel'] == 'Perempuan') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="gridRadios2">
                                Perempuan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" value="<?= $data_mhs['email'] ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Hobi</label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="hobi[]" value="berenang" <?php if (in_array('berenang', $hobies)) echo 'checked' ?>> &nbsp Berenang
                        <input type="checkbox" name="hobi[]" value="membaca" <?php if (in_array('membaca', $hobies)) echo 'checked' ?>> &nbsp Membaca
                        <input type="checkbox" name="hobi[]" value="futsal" <?php if (in_array('futsal', $hobies)) echo 'checked' ?>> &nbsp Futsal
                        <input type="checkbox" name="hobi[]" value="voli" <?php if (in_array('voli', $hobies)) echo 'checked' ?>> &nbsp Voli
                        <input type="checkbox" name="hobi[]" value="nonton" <?php if (in_array('nonton', $hobies)) echo 'checked' ?>> &nbsp Nonton
                        <input type="checkbox" name="hobi[]" value="ngoding" <?php if (in_array('ngoding', $hobies)) echo 'checked' ?>> &nbsp Ngoding
                    </div>
                </div>
                <div class="row mb">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">No Hp</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="nohp" value="<?= $data_mhs['nohp'] ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea name="alamat" cols="50" rows="4"><?= $data_mhs['alamat'] ?></textarea>
                    </div>
                </div>
                <button type="submit" name="proses" class="btn btn-danger">Update</button> &nbsp
                <button type="reset" class="btn btn-primary">Reset</button>
            </form>
        </table>
    </div>
</div>
<?php
    break;
}
?>