<?php 
require("../../config/fungsi.php");
$data = $_SESSION['data_petugas'];

auth_petugas();
if(isset($_GET['logout'])) {
  logout();
}
if(isset($_GET['tanggapi'])){
  $idp = $_GET['tanggapi'];
  $outdatatanggapan = mysqli_query($koneksi, "SELECT * FROM pengaduan where id_pengaduan='$idp'");
  $datap = mysqli_fetch_array($outdatatanggapan);
    if(empty($datap)){
      echo "<center><h1 style=\"color:white;\">Data Tidak ditemukan !</h1></center>";
    }
}else{
  header("Location:index.php");
}
//Tanggapi
if(isset($_POST['tanggapi'])){
  tanggapi($datap['id_pengaduan'], htmlspecialchars($_POST['tanggapan']), $datap['nik'], $data['nama_petugas']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Tanggapi</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-9 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg">
                <div class="p-5">
                  <div class="text-center">
                    <h2 class="h4 text-gray-900 mb-4">Pengaduan ditulis pada Tanggal <span class="text-info"><?php echo $datap['tgl_pengaduan'];?></span> [ID:<?php echo $datap['id_pengaduan'];?>]</h2>
                  </div>
                  <form method="POST" action="">
                    <div class="form-group">
                      <div align="center">
                        <img src="../../file_upload/<?php echo $datap['foto'];?>" style="width: 150px;height: auto;">
                      </div>
                    </div>
                    <div class="form-group">
                      <label>NIK Pelapor</label>
                      <input value="<?php echo $datap['nik'];?>" required="required" type="text" class="form-control form-control-user" id="exampleInputEmail" readonly="readonly" aria-describedby="emailHelp" placeholder="Nama Lengkap">
                    </div>
                    <div class="form-group">
                      <label>Isi Laporan</label>
                      <textarea required="required" type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" readonly="readonly" placeholder="Isi Pengaduan"><?php echo $datap['isi_laporan'];?></textarea>
                    </div><hr/>
                    <div class="form-group">
                      <small>Nama Petugas : <?php echo $data['nama_petugas']; ?></small>
                      <textarea required="required" type="text" name="tanggapan" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp"  placeholder="Masukan Tanggapan"></textarea>
                    </div>
<?php
  if(isset($_GET['berhasil'])){
    if($_GET['berhasil']=="tanggapi"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-primary">Pengaduan <?php echo $datap['id_pengaduan'];?> berhasil ditanggapi !</b></center>
                      </div>
                    </div>
<?php
    }
  }
?>
<?php
  if(isset($_GET['berhasil'])){
    if($_GET['berhasil']=="duplikat"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-danger">Pengaduan <?php echo $datap['id_pengaduan'];?> Sudah Pernah ditanggapi !</b></center>
                      </div>
                    </div>
<?php
    }
  }
?>
                    <input type="submit" onclick="return confirm('Proses Tanggapan ?');" name="tanggapi" value="Tanggapi dan Proses" class="btn btn-primary btn-user btn-block">
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="index.php">Kembali</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>