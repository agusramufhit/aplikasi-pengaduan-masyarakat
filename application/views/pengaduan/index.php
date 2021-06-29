<!-- php notifikasi -->
<?php
    if($this->session->flashdata('pesan'))
    {
        echo '<div class="alert alert-success">';
        echo $this->session->flashdata('pesan');
        echo '</div>';
    }
?>
<h1 class="mt-4"><?= $title ?></h1>
<ol class="breadcrumb mb-4">
	<li class="breadcrumb-item active"><?= $breadcumb ?></li>
</ol>
<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="example" class="table table-striped table-bordered" style="width:100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Isi Laporan</th>
						<th>Foto</th>
						<th>Status</th>
						<th>aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 1; foreach($pengaduan as $list) { ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $list['tgl'] ?></td>
						<td><?= $list['isi_laporan'] ?></td>
						<td>
							<a href="./assets/gambar/<?= $list['foto'] ?>" data-toggle="lightbox"
								data-gallery="gallery" class="col-md-4">
								<img src="./assets/gambar/<?= $list['foto'] ?>"
									class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
									alt="">
							</a>
						</td>
						<td class="label label-primary"><?= $list['status'] ?></td>
						<td class="">
							<a href="<?= base_url('pengaduan/detail/') . $list['id'] ?>"
								class="btn btn-primary">Detail</a>
							<a href="<?= base_url('pengaduan/edit/') . $list['id'] ?>" class="btn btn-warning">Edit</a>
							<a href="<?= base_url('pengaduan/hapus/') . $list['id'] ?>" class="btn btn-danger">Hapus</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
