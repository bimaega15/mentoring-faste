<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class RolesYaumiyah extends CI_Controller
{
    private $update = null;
    public function __construct()
    {
        parent::__construct();

        check_not_login();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles != 2) {
            show_404();
        }
        $roles = check_roles_crud('Admin/RolesYaumiyah', $my_roles);
        if ($roles->update == 'ijin') {
            $this->update = true;
        }
        $this->load->model(['RolesYaumiyah/RolesYaumiyah_model', 'KelasSiswa/KelasSiswa_model']);
    }
    public function index($id_kelas = '')
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/RolesYaumiyah');
        // output
        $data['title'] = 'Management RolesYaumiyah';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['update'] = $this->update;
        $data['id_kelas'] = $id_kelas;

        $data['kelas'] = $this->RolesYaumiyah_model->cekKelas()->result();
        $this->template->admin('admin/rolesyaumiyah/main_table', $data);
    }
    public function kategoriYaumiyah($id_kelas)
    {
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/RolesYaumiyah');
        $this->breadcrumbs->push('Kategori Roles Yaumiyah', 'Admin/RolesYaumiyah/kategoriYaumiyah/' . $id_kelas);
        // output
        $data['title'] = 'Management Roles Yaumiyah';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['update'] = $this->update;
        $data['id_kelas'] = $id_kelas;
        $data['kategori'] = $this->db->get('kategori')->result();
        $this->template->admin('admin/rolesyaumiyah/main_kategori_yaumiyah', $data);
    }
    public function inputYaumiyah($id_siswa, $id_kelas_siswa)
    {
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/RolesYaumiyah');
        $this->breadcrumbs->push('Input Yaumiyah', 'Admin/RolesYaumiyah/inputYaumiyah/' . $id_siswa);
        // output
        $data['title'] = 'Management Roles Yaumiyah';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        $getKelasSiswa = $this->KelasSiswa_model->get_kelas_siswa($id_kelas_siswa, $id_siswa)->row();
        $id_kelas_siswa = $getKelasSiswa->id_kelas_siswa;

        // add breadcrumbs
        $data['update'] = $this->update;
        $data['id_kelas_siswa'] = $id_kelas_siswa;
        $data['id_siswa'] = $id_siswa;
        $data['id_kelas_siswa'] = $id_kelas_siswa;
        $data['kategori'] = $this->RolesYaumiyah_model->getYaumiyah($id_siswa, $id_kelas_siswa)->result();

        $data['sub_kategori'] = $this->db->get('sub_kategori')->result();
        $this->template->admin('admin/rolesyaumiyah/main_yaumiyah', $data);
    }
    public function siswaYaumiyah($id_kelas_siswa)
    {
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/RolesYaumiyah');
        $this->breadcrumbs->push('Siswa', 'Admin/RolesYaumiyah/siswaYaumiyah/' . $id_kelas_siswa);
        // output
        $data['title'] = 'Management Siswa';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['update'] = $this->update;
        $data['id_kelas_siswa'] = $id_kelas_siswa;
        $data['siswa_yaumiyah'] = $this->RolesYaumiyah_model->kelasSiswaYaumiyah($id_kelas_siswa)->result();
        $this->template->admin('admin/rolesyaumiyah/main_siswa_yaumiyah', $data);
    }
    public function process()
    {
        $id_kelas = $this->input->post('id_kelas', true);
        $kategori = $this->input->post('kategori', true);

        $users = check_users_profile();

        $check = $this->db->get_where('roles_yaumiyah', [
            'users_id' => $users->id_guru,
            'kelas_id' => $id_kelas,
        ]);
        $checkRow = $check->num_rows();

        if (!($checkRow > 0)) {
            $data = [
                'users_id' => $users->id_guru,
                'kelas_id' => $id_kelas,
            ];
            $insert = $this->db->insert('roles_yaumiyah', $data);
            $last_id = $this->db->insert_id();
            for ($i = 0; $i < count($kategori); $i++) {
                $data_detail = [
                    'roles_yaumiyah_id' => $last_id,
                    'kategori_id' => $kategori[$i],
                ];
                $this->db->insert('roles_yaumiyah_detail', $data_detail);
            }
        } else {
            $row = $check->row();
            $last_id = $row->id_roles_yaumiyah;
            $this->db->delete('roles_yaumiyah_detail', ['roles_yaumiyah_id' => $last_id]);
            for ($i = 0; $i < count($kategori); $i++) {
                $data_detail = [
                    'roles_yaumiyah_id' => $last_id,
                    'kategori_id' => $kategori[$i],
                ];
                $this->db->insert('roles_yaumiyah_detail', $data_detail);
            }
        }


        sweetAlert2('success', 'Successfully', 'Berhasil menambahkan kategori ttq', '', 'Admin/RolesYaumiyah/kategoriYaumiyah/' . $id_kelas);
    }
    public function processSubmitYaumiyah()
    {
        $id_kelas_siswa = $this->input->post('id_kelas_siswa', true);
        $this->form_validation->set_rules('sub_kategori[]', 'Nama', 'trim|numeric');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        if ($this->form_validation->run() == false) {
            return $this->index($id_kelas_siswa);
        } else {
            $users = $this->input->post('id_siswa', true);
            $siswa = $this->db->get_where('siswa', ['id_siswa' => $users])->row();
            $sub_kategori = $this->input->post('sub_kategori', true);
            $tanggal = date('Y-m-d');

            $getKelasSiswa = $this->KelasSiswa_model->get_kelas_siswa($id_kelas_siswa, $users)->row();
            $id_kelas_siswa = $getKelasSiswa->id_kelas_siswa;

            $cek = $this->db->get_where('amalan', ['tanggal' => $tanggal]);
            if ($cek->num_rows() == 0 || $cek->num_rows() == null) {
                foreach ($sub_kategori as $result) {
                    $data = [
                        'tanggal' => date('Y-m-d'),
                        'waktu' => date('H:i:s'),
                        'sub_kategori_id' => $result,
                        'status' => '1',
                        'siswa_id' => $siswa->id_siswa,
                        'kelas_siswa_id' => $id_kelas_siswa
                    ];
                    $this->db->insert('amalan', $data);
                    $insert[] = $this->db->affected_rows();
                }
                if (count($insert) > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil menambahkan amalan yaumiyah', '', 'Admin/RolesYaumiyah/inputYaumiyah/' . $users . '/' . $id_kelas_siswa);
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal menambahkan amalan yaumiyah', '', 'Admin/RolesYaumiyah/inputYaumiyah/' . $users . '/' . $id_kelas_siswa);
                }
            } else {
                $users = $this->input->post('id_siswa', true);
                $siswa = $this->db->get_where('siswa', ['id_siswa' => $users])->row();
                $this->db->delete('amalan', ['tanggal' => date('Y-m-d'), 'siswa_id' => $siswa->id_siswa]);
                foreach ($sub_kategori as $index => $result) {
                    $data = [
                        'tanggal' => date('Y-m-d'),
                        'waktu' => date('H:i:s'),
                        'sub_kategori_id' => $result,
                        'status' => '1',
                        'siswa_id' => $siswa->id_siswa,
                        'kelas_siswa_id' => $id_kelas_siswa
                    ];
                    $this->db->insert('amalan', $data);
                    $update[] = $this->db->affected_rows();
                }
                if (count($update) > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil mengupdate amalan yaumiyah', '', 'Admin/RolesYaumiyah/inputYaumiyah/' . $users . '/' . $id_kelas_siswa);
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal mengupdate amalan yaumiyah', '', 'Admin/RolesYaumiyah/inputYaumiyah/' . $users . '/' . $id_kelas_siswa);
                }
            }
        }
    }
}
