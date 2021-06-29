<?php
include("koneksi.php");
session_start();
date_default_timezone_set('Asia/jakarta');
$token = md5(base64_encode(date('mdshs')));
$tgl = date('Y-m-d');

// Proses Pengerjaan Selanjutnya
// setcookie('lock', 'locked', time()+60, '/', '', 0);
// if(isset($_COOKIE['lock'])) {
// 	echo "TERBUKA";
// }else{
// 	echo "TERKUNCI";
// }

function login($usernames, $passs, $ingat)
{
	global $koneksi;
	global $token;
	$username = htmlspecialchars($usernames);
	$pass = htmlspecialchars($passs);
	$auth = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE username='$username' and password='$pass' ");
	$validation = mysqli_num_rows($auth);
	$data = mysqli_fetch_array($auth);
	if($validation > 0){
		if(isset($ingat)){
			setcookie('ingatkan', $username, time()+(60 * 60 * 24 * 1), '../view/index.php', '', 0);
		}
		$_SESSION['data_user'] = $data;
		header("Location:../view/user/");
	}else{
		$auth_admin = mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$username' and password='$pass' ");
		$valid = mysqli_num_rows($auth_admin);
		$dataa = mysqli_fetch_array($auth_admin);
		if($valid > 0){
			if(isset($ingat)){
				setcookie('ingatkan', $username, time()+(60 * 60 * 24 * 1), '../view/index.php', '', 0);
			}
			$_SESSION['data_petugas'] = $dataa;
			$_SESSION['token'] = $token;
			setcookie('token', $token, time() + (60 * 60 * 24), '/', '', 0);
			mysqli_query($koneksi, "UPDATE petugas set token='$token' where username='$username'");
			header("Location:../view/admin/");
		}else{
			header("Location:../view/index.php?validation=failed");
		}
	}
	return $auth;
}
function register($niks, $namas, $usrnms, $passs, $telps)
{
	global $koneksi;
	$nik = htmlspecialchars($niks);
	$nama =	htmlspecialchars($namas);
	$usrnm = htmlspecialchars($usrnms);
	$pass = htmlspecialchars($passs);
	$telp = htmlspecialchars($telps);
	// if(!intval($nik)){
	if(!preg_match('/[0-9]{1,17}$/', $nik)){
		header("Location:?berhasil=error_value");
	}else{
		$cekduplikatuser = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE username='$usrnm'"));
		$cekduplikatuserpro = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$usrnm'"));
		$cekduplikatnik = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE nik='$nik'"));
		if($cekduplikatuser > 0){
			header("Location:?berhasil=duplikat&username=$usrnm");
		}elseif($cekduplikatuserpro > 0){
			header("Location:?berhasil=duplikat&username=$usrnm");
		}elseif($cekduplikatnik > 0){
			header("Location:?berhasil=duplikatnik&nik=$nik");
		}else{
			$register = mysqli_query($koneksi, "INSERT INTO masyarakat (nik, nama, username, password, telp) VALUES ('$nik', '$nama', '$usrnm', '$pass', '$telp')") or die ("<h1>ILEGAL TEXT DETECTED !</h1><b>TERJADI KESALAHAN PADA SISTEM HARAP HUBUNGI ADMINISTRATOR</b>");
			header("Location:?berhasil=register");
			return $register;
		}

	}
}
function register_petugas($namas, $usrnms, $passs, $telps, $levels)
{
	global $koneksi;
	$nama =	htmlspecialchars($namas);
	$usrnm = htmlspecialchars($usrnms);
	$pass = htmlspecialchars($passs);
	$telp = htmlspecialchars($telps);
	$level = htmlspecialchars($levels);
	$cekduplikatuser = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE username='$usrnm'"));
	$cekduplikatuserpro = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$usrnm'"));
	if($cekduplikatuser > 0){
		header("Location:?berhasil=duplikat&username=$usrnm");
	}elseif($cekduplikatuserpro > 0){
		header("Location:?berhasil=duplikat&username=$usrnm");
	}else{
		$register = mysqli_query($koneksi, "INSERT INTO petugas (nama_petugas, username, password, telp, level) VALUES ('$nama', '$usrnm', '$pass', '$telp', '$level')") or die ("<h1>ILEGAL TEXT DETECTED !</h1><b>TERJADI KESALAHAN PADA SISTEM HARAP HUBUNGI ADMINISTRATOR</b>");
		header("Location:?berhasil=register");
	}
	return $register;
}
function ajukan($nik, $path, $filename, $isis)
{
	global $koneksi;
	global $tgl;
	$isi = htmlspecialchars($isis);
	$spam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nik='$nik' and tgl_pengaduan='$tgl'"));
	if($spam > 0){
		header("Location:?berhasil=maxsend");
	}else{
		$adukan = mysqli_query($koneksi, "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) VALUES ('$tgl', '$nik', '$isi', '$filename', '1')") or die ("<h1>ILEGAL TEXT DETECTED !</h1><b>TERJADI KESALAHAN PADA SISTEM HARAP HUBUNGI ADMINISTRATOR</b>");
		$isi_notifikasi = "Pengaduan anda Berhasil dikirim dan segera diproses oleh Petugas.";
		$send_notif = mysqli_query($koneksi, "INSERT INTO notifikasi (nik, notifikasi, tgl) VALUES ('$nik', '$isi_notifikasi', '$tgl')");
			header("Location:?berhasil=ajukan");
			
	}
	return $adukan;
}
function logout()
{
	session_start();
	session_unset();
	session_destroy();
	setcookie('token', '', 'time()', '/', '', 0);
	header("Location:../index.php");
}
function token_validation()
{	
	global $koneksi;
	$mytkn = $_SESSION['token'];
	$usernameoutput = $_SESSION['data_petugas'];
	$a = $usernameoutput['id_petugas'];
	$cektoken = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM petugas WHERE id_petugas='$a' and token='$mytkn'"));
	if($cektoken > 0){
		
	}else{
		header("Location:../index.php?validation=token_error");
	}
	return $cektoken;
}
function activate_petugas()
{
	global $koneksi;
	$status = $_SESSION['data_petugas'];
	$id = $status['id_petugas'];
	$cektoken = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM petugas WHERE id_petugas='$id' and level='nonaktif'"));
	if($cektoken > 0){
		header("Location:../index.php?validation=akun_nonaktif");
	}
	return $cektoken;
}
function auth_user()
{
	if(!isset($_SESSION['data_user'])){
		logout();
	}
}
function auth_petugas()
{
	token_validation();
	activate_petugas();
	
	if(!isset($_SESSION['data_petugas'])){
		logout();
	}
}
function auth_admin()
{
	token_validation();
	activate_petugas();

	if(!isset($_SESSION['data_petugas'])){
		logout();
	}else{
		$akunsuper = $_SESSION['data_petugas'];
		if($akunsuper['level']=="admin"){

		}else{
			header("Location:index.php");
		}
	}
}
function tanggapi($id_pengaduan, $tanggapan, $nik, $nama_petugas_tanggapan)
{
	global $koneksi;
	global $tgl;
	$data = $_SESSION['data_petugas'];
	$id_petugas = $data['id_petugas'];

echo $nik;
	$cekduplikat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tanggapan WHERE id_pengaduan='$id_pengaduan'"));
	if($cekduplikat > 0){
		header("Location:?tanggapi=$id_pengaduan&berhasil=duplikat");
	}else{
		$masukan_tanggapan = mysqli_query($koneksi, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, id_petugas) values ('$id_pengaduan', '$tgl', '$tanggapan', '$id_petugas')") or die ("<h1>ILEGAL TEXT DETECTED !</h1><b>TERJADI KESALAHAN PADA SISTEM HARAP HUBUNGI ADMINISTRATOR</b>");
		$update_status = mysqli_query($koneksi, "UPDATE pengaduan set status='proses' where id_pengaduan='$id_pengaduan'");
		$isi_notifikasi = "Pengaduan anda dengan ID: ".$id_pengaduan." telah ditanggapi oleh Petugas.";
		$send_notif = mysqli_query($koneksi, "INSERT INTO notifikasi (nik, notifikasi, tgl) VALUES ('$nik', '$isi_notifikasi', '$tgl')");
		header("Location:?tanggapi=$id_pengaduan&berhasil=tanggapi");
	}
}
function proses_selesai($id, $nik)
{
	global $koneksi;
	global $tgl;
	$proses = mysqli_query($koneksi, "UPDATE pengaduan set status='selesai' where id_pengaduan='$id'") or die ("Terjadi Kesalahan");
	$isi_notifikasi = "Pengaduan anda dengan ID: ".$id." telah Selesai diproses.";
	$send_notif = mysqli_query($koneksi, "INSERT INTO notifikasi (nik, notifikasi, tgl) VALUES ('$nik', '$isi_notifikasi', '$tgl')");
	header("Location:?berhasil=proses_oke");
	return $proses;
}
function hapus_pengaduan($id)
{
	global $koneksi;
	$hapus_tanggapan = mysqli_query($koneksi, "DELETE FROM tanggapan where id_pengaduan='$id' ") or die ("Terjadi Kesalahan");
	$hapus_pengaduan = mysqli_query($koneksi, "DELETE FROM pengaduan where id_pengaduan='$id' ") or die ("Terjadi Kesalahan");
	header("Location:?berhasil=proses_hapus");
	return $hapus_tanggapan;
	return $hapus_pengaduan;
}
function nonaktif($id)
{
	global $koneksi;
	$nonaktifkan_petugas = mysqli_query($koneksi, "UPDATE petugas set level='nonaktif' where id_petugas='$id'") or die ("Terjadi Kesalahan");
	header("Location:?berhasil=proses_hapus");
	return $nonaktifkan_petugas;
}
function aktifkan($id)
{
	global $koneksi;
	$nonaktifkan_petugas = mysqli_query($koneksi, "UPDATE petugas set level='petugas' where id_petugas='$id'") or die ("Terjadi Kesalahan");
	header("Location:?berhasil=proses_hapus");
	return $nonaktifkan_petugas;
}
function ubah_data_petugas($id_petugas, $nama, $username, $password, $telp, $level)
{
	global $koneksi;
	if(empty($password)){
		$update = mysqli_query($koneksi, "UPDATE petugas set nama_petugas='$nama', telp='$telp', level='$level' WHERE id_petugas='$id_petugas' ");
	}else{
		$update = mysqli_query($koneksi, "UPDATE petugas set nama_petugas='$nama', password='$password', telp='$telp', level='$level' WHERE id_petugas='$id_petugas' ");
	}
	if($update){
		header("Location:?berhasil=proses_ubah");	
	}
	return $update;
}
function update_data_view($id_petugas)
{	
	global $koneksi;
	$upd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM petugas WHERE id_petugas='$id_petugas' "));
	$_SESSION['data_petugas'] = $upd;
	return $upd;
}
function update_data_view_user($nik)
{	
	global $koneksi;
	$upd = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE nik='$nik' "));
	$_SESSION['data_user'] = $upd;
	return $upd;
}
function ubah_profil($id, $nama, $telp, $pass)
{
	global $koneksi;
	$cek = mysqli_query($koneksi, "SELECT * FROM petugas WHERE id_petugas='$id' and password='$pass' ");
	$auth = mysqli_num_rows($cek);
	if($auth > 0){
		mysqli_query($koneksi, "UPDATE petugas set nama_petugas='$nama', telp='$telp' WHERE id_petugas='$id'");
		update_data_view($id);
		header("Location:?berhasil=proses_ubah");
	}else{
		header("Location:?berhasil=keynotvalid");
	}

}
function ubah_profil_user($nik, $nama, $telp, $pass)
{
	global $koneksi;
	$cek = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE nik='$nik' and password='$pass' ");
	$auth = mysqli_num_rows($cek);
	if($auth > 0){
		mysqli_query($koneksi, "UPDATE masyarakat set nama='$nama', telp='$telp' WHERE nik='$nik'");
		update_data_view_user($nik);
		header("Location:?berhasil=proses_ubah");
	}else{
		header("Location:?berhasil=keynotvalid");
	}

}
function ubah_pass($id_petugas, $pw1, $pw2, $pass_now)
{
	global $koneksi;
	$cek = mysqli_query($koneksi, "SELECT * FROM petugas WHERE id_petugas='$id_petugas' and password='$pass_now' ");
	$auth = mysqli_num_rows($cek);
	if($auth > 0){
		if($pw1==$pw2){
			mysqli_query($koneksi, "UPDATE petugas set password='$pw2' WHERE id_petugas='$id_petugas'");
			header("Location:?page=pass&berhasil=passoke");
		}else{
			header("Location:?page=pass&berhasil=konf");
		}
	}else{
		header("Location:?page=pass&berhasil=keynotvalid");
	}
}
function ubah_pass_user($nik, $pw1, $pw2, $pass_now)
{
	global $koneksi;
	$cek = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE nik='$nik' and password='$pass_now' ");
	$auth = mysqli_num_rows($cek);
	if($auth > 0){
		if($pw1==$pw2){
			mysqli_query($koneksi, "UPDATE masyarakat set password='$pw2' WHERE nik='$nik'");
			header("Location:?page=pass&berhasil=passoke");
		}else{
			header("Location:?page=pass&berhasil=konf");	
		}
	}else{
		header("Location:?page=pass&berhasil=keynotvalid");
	}
}
?>