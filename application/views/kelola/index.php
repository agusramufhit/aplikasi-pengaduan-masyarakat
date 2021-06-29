<!-- php notifikasi -->
<?php
    if($this->session->flashdata('pesan'))
    {
        echo '<div class="alert alert-success">';
        echo $this->session->flashdata('pesan');
        echo '</div>';
    }
?>
<div class="card mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table id="example" class="table table-striped table-bordered" style="width:100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Nik</th>
						<th>Nama</th>
						<th>Isi Lapporan</th>
						<th>Foto</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 1; foreach($kelola as $pp) { ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $pp['tgl'] ?></td>
						<td><?= $pp['nik'] ?></td>
						<td><?= $pp['nama'] ?></td>
						<td><?= $pp['isi_laporan'] ?></td>
						<td>
							<a href="<?= base_url('assets/gambar/') . $pp['foto'] ?>" data-toggle="lightbox"
								data-gallery="gallery" class="col-md-4">
								<img class="img-fluid" src="<?= base_url('assets/gambar/') . $pp['foto'] ?>"
									alt="Card image cap">
							</a>
						</td>
						<td class="text-capitalize"><?= $pp['status'] ?></td>
						<td>
							<?php if($pp['status'] == 'proses') { ?>
							<a href="<?= base_url('admin/kelola/balas/')?><?= $pp['id'] ?>"
								class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Balas </a>
							<?php } else if($pp['status'] == 'ditanggapi') { ?>
							<a href="<?= base_url('admin/kelola/selesai/')?><?= $pp['id'] ?>"
								class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Selesai </a>
							<?php } ?>
							<a href="<?= base_url('admin/kelola/hapus/')?><?= $pp['id'] ?>"
								class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus </a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
