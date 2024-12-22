<?php


$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';
switch ($aksi) {
    case 'list':
?>
        <div class="row">
            <div class="col-30">
                <a href="index.php?p=users&aksi=input" class="btn btn-primary mb-3"><i class="bi bi-person-add"></i> Tambah User</a>
            </div>
            <table class="table table-bordered col-30">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Nama Lengkap</th>
                    <th>No Telp</th>
                    <th>Photo</th>
                    <th>Aksi</th>
                </tr>
                <?php
                try {
                    $stmt = $db->prepare("SELECT user.*, level.nama_level FROM user LEFT JOIN level ON user.level = level.id");
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $no = 1;
                    foreach ($users as $data) {
                ?>
                        <tr>
                            <td><?php echo $no ?></td>
                            <td><?= htmlspecialchars($data['username']) ?></td>
                            <td><?= htmlspecialchars($data['email']) ?></td>
                            <td><?= htmlspecialchars($data['nama_level']) ?></td>
                            <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($data['notelp']) ?></td>
                            <td>
                                <?php if ($data['photo'] && file_exists("uploads/" . $data['photo'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Photo" style="max-width: 100px;">
                                <?php else: ?>
                                    <img src="assets/img/no-image.png" alt="No Photo" style="max-width: 100px;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?p=users&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success"><i class="bi bi-pen-fill"></i> Edit</a>
                                <a href="proses_users.php?proses=delete&id=<?= $data['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                <?php
                        $no++;
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </table>
        </div>
<?php
        break;

    case 'input':
?>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <form action="proses_users.php?proses=insert" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" id="username" class="form-control" required autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="level_id" class="col-sm-2 col-form-label">Level</label>
                            <div class="col-sm-10">
                                <select name="level_id" id="level_id" class="form-select" required>
                                    <option value="">--Pilih Level--</option>
                                    <?php
                                    try {
                                        $stmt = $db->prepare("SELECT * FROM level");
                                        $stmt->execute();
                                        $levels = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($levels as $level) {
                                            echo "<option value='" . htmlspecialchars($level['id']) . "'>" . htmlspecialchars($level['nama_level']) . "</option>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="notelp" class="col-sm-2 col-form-label">No. Telepon</label>
                            <div class="col-sm-10">
                                <input type="tel" name="notelp" id="notelp" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="photo" class="col-sm-2 col-form-label">Photo</label>
                            <div class="col-sm-10">
                                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                            </div>
                        </div>

                        <button type="submit" name="Proses" value="Proses" class="btn btn-primary">Proses</button>
                    </form>
                </div>
            </div>
        </div>
<?php
        break;

    case 'edit':
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
?>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <form action="proses_users.php?proses=update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">

                        <div class="row mb-3">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin meng
                    <div class="row mb-3">
                        <label for="level_id" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <select name="level_id" id="level_id" class="form-select" required>
                                <option value="">--Pilih Level--</option>
                                <?php
                                try {
                                    $stmt = $db->prepare("SELECT * FROM level");
                                    $stmt->execute();
                                    $levels = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($levels as $level) {
                                        $selected = $data['level'] == $level['id'] ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($level['id']) . "' $selected>" . htmlspecialchars($level['nama_level']) . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="notelp" class="col-sm-2 col-form-label">No. Telepon</label>
                        <div class="col-sm-10">
                            <input type="tel" name="notelp" id="notelp" class="form-control" value="<?= htmlspecialchars($data['notelp']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="photo" class="col-sm-2 col-form-label">Photo</label>
                        <div class="col-sm-10">
                            <?php if ($data['photo'] && file_exists("uploads/" . $data['photo'])): ?>
                                <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Photo" style="max-width: 100px; display: block; margin-bottom: 10px;">
                            <?php else: ?>
                                <img src="assets/img/no-image.png" alt="No Photo" style="max-width: 100px; display: block; margin-bottom: 10px;">
                            <?php endif; ?>
                            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                        </div>
                    </div>

                    <button type="submit" name="Proses" value="Update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <?php
        break;
}
?>