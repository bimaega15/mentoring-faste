<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatTtq extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        timeZone();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles != 3) {
            show_404();
        }
        $this->load->model(['Ttq/Ttq_model', 'Ttq/Server_Ttq_model', 'Siswa/Server_ttq_siswa']);
    }

    // Solat sunnah
    public function index()
    {
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Siswa/Home');
        $this->breadcrumbs->push('TTQ', 'Siswa/RiwayatTtq');
        // output
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        $data['title'] = 'TTQ';
        $get = $this->input->get('export');
        if ($get != null && $get == 'yes') {
            $data['row'] = $this->Ttq_model->getAllTTQ()->result();
            $this->load->view('siswa/export/ttq', $data);
        } else {
            $this->template->siswa('siswa/riwayatttq/main', $data);
        }
    }
    public function serverRiwayatTtq()
    {
        $list = $this->Server_ttq_siswa->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($field->tanggal);
            $row[] = $field->waktu;
            $row[] = $field->jumlah_halaman;
            $row[] = '<small class="text-left">' . $field->kelas_siswa . '</small>';
            $row[] = '<small class="text-left">' . val_guru_kelas($field->id_kelas) . '</small>';
            $row[] = $field->keterangan_ttq;
            $row[] = check_kategori_ttq_id($field->kategori_ttq_id);
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_ttq_siswa->count_all(),
            "recordsFiltered" => $this->Server_ttq_siswa->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}