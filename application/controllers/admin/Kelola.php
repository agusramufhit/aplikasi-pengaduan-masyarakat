<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelola extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->cekAdmin();
    }

    public function index()
    {
        $kelola = $this->M_all->tampilKelola()->result_array();
        $data = [
            "kelola"               => $kelola,
            "title"                => 'Data masyarakat',
            "breadcumb"            => 'kelola',
            "content"              => 'kelola/index'
        ];
        $this->load->view('template/view', $data, FALSE);
    }

    public function hapus($id)
    {
        $where = [
            'id'    => $id
        ];
        $hapus = $this->M_all->hapus('tb_pengaduan', $where);
        if($hapus){
            $this->session->set_flashdata('pesan', 'data berhasil dihapus');
            redirect('admin/kelola','refresh');
        }else{
            $this->session->set_flashdata('pesan', 'data gagal dihapus');
            redirect('admin/kelola','refresh');
        }
    }

    public function balas($id)
    {
        $this->form_validation->set_rules('balasan', 'Balasan', 'required', ["required" => "%s tidak boleh kosong"]);
        
        if ($this->form_validation->run() == True) {
            $data = [
                'id_pengaduan'      => $id,
                'tgl_tanggapan'     => date('Y-m-d'),
                'tanggapan'         => htmlspecialchars($this->input->post('balasan')),
                'id_petugas'        => $this->session->userdata('admin')['session_id']
            ];
            $tambah = $this->M_all->simpan('tb_tanggapan ', $data);
            if($tambah){
                $where = [
                    'id'    => $id
                ];
                $data = [
                    'status'    => 'ditanggapi'
                ];
                $ubah = $this->M_all->ubah('tb_pengaduan', $data, $where);
                $this->session->set_flashdata('pesan', 'data berhasil diubah');
                redirect('admin/kelola','refresh');
            }else{
                $this->session->set_flashdata('pesan', 'data gagal diubah');
                redirect('admin/kelola','refresh');
            }
        } else {
            $id_pengaduan = $id;
            $where = $id_pengaduan;
            $data_pengaduan = $this->M_all->tampilPengaduanSatu($where)->row();
            $data = [
                "pengaduan"            => $data_pengaduan,
                "title"                => 'Data masyarakat',
                "breadcumb"            => 'kelola',
                "content"              => 'kelola/balas'
            ];
            $this->load->view('template/view', $data, FALSE);
        }
    }

    public function selesai($id)
    {
        $where = [
            'id'    => $id
        ];
        $data = [
            'status'    => 'selesai'
        ];
        $ubah = $this->M_all->ubah('tb_pengaduan ', $data, $where);
        if($ubah){
            $this->session->set_flashdata('pesan', 'data berhasil diselsaikan');
            redirect('admin/kelola','refresh');
        }else{
            $this->session->set_flashdata('pesan', 'data gagal diselesaikan');
            redirect('admin/kelola','refresh');
        }
    }


}

/* End of file Kelola.php */
