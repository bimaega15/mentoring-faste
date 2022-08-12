<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class LaporanYaumiyah extends CI_Controller
{
    private $update = null;
    private $delete = null;
    private $create = null;
    private $read = null;
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles == 3) {
            show_404();
        }
        $roles = check_roles_crud('Admin/LaporanYaumiyah', $my_roles);
        if ($roles->update == 'ijin') {
            $this->update = true;
        }
        if ($roles->delete == 'ijin') {
            $this->delete = true;
        }
        if ($roles->create == 'ijin') {
            $this->create = true;
        }
        if ($roles->read == 'ijin') {
            $this->read = true;
        }
        $this->load->model(['Amalan/Amalan_model', 'Amalan/Server_Amalan_model', 'Kelas/Server_Kelas_model', 'Siswa/ServerSiswaKelas_Model', 'KelasSiswa/KelasSiswa_model', 'Amalan/Server_KelasSiswa', 'RolesYaumiyah/RolesYaumiyah_model']);
    }
    public function index($id_kelas_siswa = null)
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas Siswa', 'Admin/LaporanYaumiyah');
        if ($id_kelas_siswa != null) {
            $this->breadcrumbs->push('LaporanYaumiyah', 'Admin/LaporanYaumiyah/index/' . $id_kelas_siswa);
        }
        // output
        $data['title'] = 'Mangement LaporanYaumiyah';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $data['id_kelas_siswa'] = $id_kelas_siswa;
        if ($id_kelas_siswa != null) {
            $this->template->admin('admin/laporanyaumiyah/main_table', $data);
        } else {
            $this->template->admin('admin/laporanyaumiyah/kelas_siswa', $data);
        }
    }

    public function server_KelasYaumiyah()
    {
        $list = $this->Server_Kelas_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->tingkat;
            $row[] = $field->nama;
            $row[] = val_tahun_ajaran($field->id_kelas)->row()->nama;
            $row[] = val_guru_kelas($field->id_kelas);
            if ($this->read) {
                $row[] = "<a href='" . base_url('Admin/LaporanYaumiyah/KelasSiswa/' . $field->id_kelas)  . "' class='btn btn-sm btn-primary' title='Kelas Siswa'><i class='fas fa-eye'></i> Kelas Siswa</a>";
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Kelas_model->count_all(),
            "recordsFiltered" => $this->Server_Kelas_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function serverSiswaKelas()
    {
        $id_kelas = htmlspecialchars($this->input->get('id_kelas', true));
        $list = $this->ServerSiswaKelas_Model->get_datatables($id_kelas);
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nomor_induk;
            $row[] = $field->nama_siswa;
            $row[] = $field->no_telephone;
            $row[] = $field->jenis_kelamin == "L" ? "Laki laki" : "Perempuan";
            $row[] = "<img class='img-thumbnail w-50' src='" . base_url('vendor/img/siswa/' . $field->gambar) . "'></img>";
            if ($this->read) {
                $row[] = "<a href='" . base_url('Admin/LaporanYaumiyah/siswa/' . $field->id_siswa)  . "' class='btn btn-sm btn-primary' title='Siswa'><i class='fas fa-eye'></i> Laporan</a>";
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->ServerSiswaKelas_Model->count_all(),
            "recordsFiltered" => $this->ServerSiswaKelas_Model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function KelasSiswa($id_kelas)
    {
        check_kelas_guru($id_kelas);
        if ($this->read != true) {
            show_404();
        }
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('LaporanYaumiyah', 'Admin/LaporanYaumiyah');
        $this->breadcrumbs->push('Siswa', 'Admin/LaporanYaumiyah/KelasSiswa/' . $id_kelas);
        // output
        $data['title'] = 'Mangement Siswa';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['id_kelas'] = $id_kelas;
        $this->template->breadcrumbs($data);
        $this->template->admin('admin/laporanyaumiyah/kelas_siswa', $data);
    }
    public function siswa($id_siswa)
    {
        if ($this->read != true) {
            show_404();
        }

        if ($this->read != true) {
            show_404();
        }
        $id_kelas_siswa = $this->input->get('kelas_siswa', true);
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/LaporanYaumiyah');
        $this->breadcrumbs->push('LaporanYaumiyah', 'Admin/LaporanYaumiyah/index/' . $id_kelas_siswa);
        $this->breadcrumbs->push('Siswa', 'Admin/LaporanYaumiyah/Siswa/' . $id_siswa . '?kelas=' . $id_kelas_siswa);
        // output
        $data['title'] = 'Mangement Siswa';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['id_siswa'] = $id_siswa;
        $data['id_kelas_siswa'] = $id_kelas_siswa;
        $this->template->breadcrumbs($data);

        $tahun = htmlspecialchars($this->input->get('tahun', true));
        $bulan = htmlspecialchars($this->input->get('bulan', true));
        $export = htmlspecialchars($this->input->get('export', true));
        if ($tahun != null || $bulan != null) {
            $tahun = htmlspecialchars($this->input->get('tahun', true));
            $bulan = htmlspecialchars($this->input->get('bulan', true));
            $data['bulan_tahun'] =  $bulan . " - " . $tahun;
            $kalender = CAL_GREGORIAN;
            $hari = cal_days_in_month($kalender, $bulan, $tahun);
            $data['hari'] = $hari;
            $data['bulan_amalan'] = $bulan;
        } else {
            $data['bulan_tahun'] = bulan(date('m')) . " - " . date('Y');
            $kalender = CAL_GREGORIAN;
            $bulan = date('m');
            $tahun = date('Y');
            $hari = cal_days_in_month($kalender, $bulan, $tahun);
            $data['hari'] = $hari;
            $data['bulan_amalan'] = $bulan;
        }
        $data['id_siswa'] = $id_siswa;
        $data['kelas_siswa'] = $this->input->get('kelas_siswa');
        $data['sub_kategori'] = $this->RolesYaumiyah_model->getSubYaumiyah($id_siswa, $id_kelas_siswa)->result();

        if ($export != null && $export == 'yes') {
            $this->load->view('admin/export/amalan_yaumiyah', $data);
        } else {
            $this->template->admin('admin/laporanyaumiyah/main', $data);
        }
    }
    public function serverSiswaAmalan()
    {
        $id_kelas_siswa = $this->input->get('id_kelas_siswa', true);
        $list = $this->ServerSiswaKelas_Model->get_datatables($id_kelas_siswa);
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nomor_induk;
            $row[] = $field->nama_siswa;
            $row[] = ($field->jenis_kelamin == "L" ? "Laki-laki" : "Perempuan");
            $row[] = ($field->no_telephone);
            if ($this->read) {
                $row[] = "
                <div class='text-center'>
                <a href='" . base_url('Admin/LaporanYaumiyah/siswa/' . $field->id_siswa . "?kelas_siswa=" . $id_kelas_siswa)  . "' class='btn btn-sm btn-primary' title='Kelas Siswa'><i class='fas fa-eye'></i> Amalan Siswa</a>
                </div>
                ";
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->ServerSiswaKelas_Model->count_all(),
            "recordsFiltered" => $this->ServerSiswaKelas_Model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function serveKelasSiswa()
    {
        $list = $this->Server_KelasSiswa->get_datatables();

        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($field->kelas_siswa);
            $row[] = ($field->nama_tahun);

            $row[] = '
            <a href="' . base_url('Admin/LaporanYaumiyah/index/' . $field->id_kelas_siswa) . '" class="btn btn-primary" title="Catatan TTQ"><i class="fas fa-book-open"></i> YAUMIYAH</a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_KelasSiswa->count_all(),
            "recordsFiltered" => $this->Server_KelasSiswa->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}
