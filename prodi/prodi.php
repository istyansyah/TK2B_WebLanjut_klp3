<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
    <i class="nav-icon fas fa-university"></i>
    Prodi
    </h1>
</div>

<?php 
$aksi=isset($_GET['aksi']) ? $_GET['aksi'] : 'list';
switch ($aksi) {
    case 'list':
?>
<div class="container">
        <div class="row">
            
            <div class ="col-2">
                <a href= "index.php?p=prodi&aksi=input" class="btn btn-primary mb-3"><i class="bi bi-file-plus"></i> Input Prodi Baru </a>
            </div>
             <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Nama Prodi</th>
                    <th>Jenjang Studi</th>
                    <th>Aksi</th>
                    
                </tr>
                <?php
                    include 'koneksi.php';
                    $ambil= mysqli_query($db, "SELECT * FROM prodi");
                    $no=1;
                    while($data=mysqli_fetch_array($ambil)){
                ?>

                <tr>
                    <td><?php echo $no ?></td>
                    <td><?= $data['nama_prodi']?></td>
                    <td><?= $data['jenjang_studi']?></td>
                    <td>
                        <a href="index.php?p=prodi&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success"><i class="bi bi-pen-fill"></i> Edit</a>
                        <a href="proses_prodi.php?proses=delete&id=<?= $data['id'] ?>" onclick="return confirm('Apa anda yakin menghapus data?')" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</a>
                    </td>

                </tr>
                <?php 
                    $no++;
                    } 
                ?>

             </table>
<?php
        break;
    
    case 'input' :
?>
<div class="container">
    
        <form action="proses_prodi.php?proses=insert" method="POST">
        <div class="row">
            <div class="col-6">
            <div class="col-20">
                <h3>Klik <a href="index.php?p=prodi" class="btn btn-primary mb-3">Disini</a>Untuk Melihat Data Prodi</h3>

                </div>
                <form>

                <!-- NAma Prodi -->
                    <div class="row mb-3">
                        <label for="nama_prodi" class="col-sm-2 col-form-label">Nama Prodi</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" required autofocus>
                        </div>
                    </div>

                    <!-- Jenjang Studi -->
                    <div class="row mb-3">
                        <label for="jenjang_studi" class="col-sm-2 col-form-label">Jenjang Studi</label>
                        <div class="col-sm-10">
                        <div class="row mb-3">
                        
                        <div class="col-sm-10">
                            <input type="radio" name="jenjang_studi" value="D-2" checked class="form-check-input">D-2 <br>
                            <input type="radio" name="jenjang_studi" value="D-3" class="form-check-input">D-3 <br>
                            <input type="radio" name="jenjang_studi" value="D-4" class="form-check-input">D-4 <br>
                            <input type="radio" name="jenjang_studi" value="S1" class="form-check-input">S1 <br>
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
        include 'koneksi.php';
    // Mengambil data prodi berdasarkan ID yang dikirimkan melalui URL
    $id = $_GET['id']; // Mengambil ID dari URL
    $ambil = mysqli_query($db, "SELECT * FROM prodi WHERE id = '$id'");
    $data_prodi = mysqli_fetch_array($ambil);
?>
<div class="container">
        
        <form action="proses_prodi.php?proses=update&id=<?php echo $data_prodi['id']?>" method="POST">

           <!-- Nama Prodi -->
           <div class="row mb-3">
                <label for="nama_prodi" class="col-sm-2 col-form-label">Nama Prodi</label>
                <div class="col-sm-10">
                    <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" value="<?= htmlspecialchars($data_prodi['nama_prodi']); ?>" required autofocus>
                </div>
            </div>

            <!-- Jenjang Studi -->
            <div class="row mb-3">
                <label for="jenjang_studi" class="col-sm-2 col-form-label">Jenjang Studi</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input type="radio" name="jenjang_studi" value="D-2" class="form-check-input" <?= ($data_prodi['jenjang_studi'] == 'D-2') ? 'checked' : ''; ?>>D-2 <br>
                        <input type="radio" name="jenjang_studi" value="D-3" class="form-check-input" <?= ($data_prodi['jenjang_studi'] == 'D-3') ? 'checked' : ''; ?>>D-3 <br>
                        <input type="radio" name="jenjang_studi" value="D-4" class="form-check-input" <?= ($data_prodi['jenjang_studi'] == 'D-4') ? 'checked' : ''; ?>>D-4 <br>
                        <input type="radio" name="jenjang_studi" value="S1" class="form-check-input" <?= ($data_prodi['jenjang_studi'] == 'S1') ? 'checked' : ''; ?>>S1 <br>
                    </div>
                </div>
            </div>

            <button type="submit" name="Proses" value="Proses" class="btn btn-primary">Proses</button>

        </form>
    </div>
<?php
    break;

}
?>