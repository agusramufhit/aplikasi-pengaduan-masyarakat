<?php 
require("../../config/fungsi.php");
$data = $_SESSION['data_user'];

auth_user();
if(isset($_GET['logout'])) {
  logout();
}
if(isset($_POST['konfirmasi'])){
  ubah_profil_user($data['nik'], $_POST['nama_user'], $_POST['telp'], $_POST['konfirmasi_pass']);
}
if(isset($_POST['ubah_pass'])){
  ubah_pass_user($data['nik'], $_POST['pass1'], $_POST['pass2'], $_POST['pass_now']);
}
$profil = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE nik='$data[nik]' "));
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Profil</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

   <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
        <i class="fa fa-id-card" aria-hidden="true"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Pengaduan <sup>Masyarakat</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php if(!isset($_GET[page])){echo"active";}?>">
        <a class="nav-link" href="profil.php">
          <i class="fas fa-fw fa-user-alt"></i>
          <span>Edit Profil</span></a>
      </li>
      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php if(isset($_GET[page])){echo"active";}?>">
        <a class="nav-link" href="profil.php?page=password">
          <i class="fas fa-fw fa-user-alt"></i>
          <span>Ubah Password</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

        <!-- Topbar Navbar -->
<?php include("header/topbar.php");?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

<?php if(isset($_GET['page'])){ ?>
                  <!-- Outer Row -->
    <div class="row justify-content-center animated--grow-in">

      <div class="col-xl col-lg col-md">

        <div class="card o-hidden border-0 shadow-lg">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Ubah Password</h1>
                  </div>
                  <form method="POST" action="">
                    <div class="form-group">
                      <small>Password Baru</small>
                      <input required="required" type="password" name="pass1" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Masukan Password">
                    </div>
                    <div class="form-group">
                      <small>Konfirmasi Password Baru</small>
                      <input required="required" type="password" name="pass2" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Konfirmasi Password">
                    </div>
<?php
if(isset($_GET['berhasil'])){
  if($_GET['berhasil']=="passoke"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-primary">Password berhasil diubah !</b></center>
                      </div>
                    </div>
<?php
  }
}
?>
<?php
if(isset($_GET['berhasil'])){
  if($_GET['berhasil']=="konf"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-danger">Password Anda tidak sama !</b></center>
                      </div>
                    </div>
<?php
  }
}
?>
<?php
if(isset($_GET['berhasil'])){
  if($_GET['berhasil']=="keynotvalid"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-danger">Konfirmasi Password tidak valid !</b></center>
                      </div>
                    </div>
<?php
  }
}
?>
                    <!-- Konfirmasi Ubah-->
                    <div class="modal fade" id="ubahpw" tabindex="-1" role="dialog" aria-labelledby="konf" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="konf">Konfirmasi</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                          <div class="modal-body">Masukan Password saat ini untuk melanjutkan
                            <div class="form-group">
                              <input required="required" type="password" name="pass_now" class="form-control form-control-user" id="exampleInputPassword" placeholder="Masukan Password">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                            <input type="submit" name="ubah_pass" value="Konfirmasi" class=" btn btn-primary">
                          </div>
                        </div>
                      </div>
                    </div>
                    <a class="btn btn-primary btn-user btn-block" href="#" data-toggle="modal" data-target="#ubahpw">Ubah Password</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
<?php }else{ ?>
              <!-- Outer Row -->
    <div class="row justify-content-center animated--grow-in">

      <div class="col-xl col-lg col-md">

        <div class="card o-hidden border-0 shadow-lg">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Edit Profil</h1>
                  </div>
                  <form method="POST" action="">
                    <div class="form-group">
                      <small>Nama Anda</small>
                      <input required="required" type="text" name="nama_user" class="form-control form-control-user" id="exampleInputEmail" value="<?php echo $profil['nama'];?>" aria-describedby="emailHelp" placeholder="Nama Sesuai KTP">
                    </div>
                    <div class="form-group">
                      <small>Nomor Telepon</small>
                      <input required="required" type="number" name="telp" class="form-control form-control-user" id="exampleInputPassword" value="<?php echo $profil['telp'];?>" placeholder="Nomor Telepon">
                    </div>
<?php
if(isset($_GET['berhasil'])){
  if($_GET['berhasil']=="keynotvalid"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-danger">Konfirmasi Password tidak valid !</b></center>
                      </div>
                    </div>
<?php
  }
}
?>
<?php
if(isset($_GET['berhasil'])){
  if($_GET['berhasil']=="proses_ubah"){
?>
                    <div class="form-group">
                      <div class="small">
                        <center><b align="center" class="text-primary">Profil berhasil diubah !</b></center>
                      </div>
                    </div>
<?php
  }
}
?>  
                    <!-- Konfirmasi Ubah-->
                    <div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog" aria-labelledby="konf" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="konf">Konfirmasi</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                          <div class="modal-body">Masukan Password anda untuk melanjutkan
                            <div class="form-group">
                              <input required="required" type="password" name="konfirmasi_pass" class="form-control form-control-user" id="exampleInputPassword" placeholder="Masukan Password">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                            <input type="submit" name="konfirmasi" value="Konfirmasi" class=" btn btn-primary">
                          </div>
                        </div>
                      </div>
                    </div>
                    <a class="btn btn-primary btn-user btn-block" href="#" data-toggle="modal" data-target="#konfirmasi">Simpan Perubahan</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
<?php } ?>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
          <span>Copyright &copy;Kantor kecamatan Tanjungpinang timur 2021</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="?logout">Logout</a>
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

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
