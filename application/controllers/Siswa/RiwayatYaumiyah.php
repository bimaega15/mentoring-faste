<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatYaumiyah extends CI_Controller
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
        $this->load->model(['Ttq/Ttq_model', 'Ttq/Server_Ttq_model', 'Amalan/Amalan_model', 'RolesYaumiyah/RolesYaumiyah_model']);
    }

    // Solat sunnah
    public function index()
    {
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Siswa/Home');
        $this->breadcrumbs->push('Amalan Yaumiyah', 'Siswa/RiwayatYaumiyah');
        // output
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['title'] = 'Amalan Yaumiyah';
        $profile = check_users_profile();
        $kelas_siswa = $this->db->get_where('siswa_kelas', ['siswa_id' => $profile->id_siswa])->result();
        $kelas_siswa = array_unique(array_column($kelas_siswa, 'kelas_siswa_id'));
        $kelas_siswa = max($kelas_siswa);

        $data['kategori'] = $this->RolesYaumiyah_model->getYaumiyah($profile->id_siswa, $kelas_siswa)->result();
        $data['sub_kategori'] = $this->db->get('sub_kategori')->result();
        $this->template->siswa('siswa/riwayatyaumiyah/main', $data);
    }
    public function process()
    {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('sub_kategori[]', 'Nama', 'trim|numeric');
            $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
            $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
            if ($this->form_validation->run() == false) {
                return $this->index();
            } else {
                $users = $this->session->userdata('users_id');
                $siswa = $this->db->get_where('siswa', ['users_id' => $users])->row();
                $siswa_kelas = $this->db->get_where('siswa_kelas', ['siswa_id' => $siswa->id_siswa])->result();

                $siswa_kelas = array_unique(array_column($siswa_kelas, 'kelas_siswa_id'));
                $siswa_kelas = max($siswa_kelas);


                $sub_kategori = $this->input->post('sub_kategori', true);
                $tanggal = date('Y-m-d');
                $cek = $this->db->get_where('amalan', ['tanggal' => $tanggal]);
                if ($cek->num_rows() == 0 || $cek->num_rows() == null) {
                    foreach ($sub_kategori as $result) {
                        $data = [
                            'tanggal' => date('Y-m-d'),
                            'waktu' => date('H:i:s'),
                            'sub_kategori_id' => $result,
                            'status' => '1',
                            'siswa_id' => $siswa->id_siswa,
                            'kelas_siswa_id' => $siswa_kelas
                        ];
                        $this->db->insert('amalan', $data);
                        $insert[] = $this->db->affected_rows();
                    }
                    if (count($insert) > 0) {
                        sweetAlert2('success', 'Successfully', 'Berhasil menambahkan amalan yaumiyah', '', 'Siswa/RiwayatYaumiyah');
                    } else {
                        sweetAlert2('error', 'Failed', 'Gagal menambahkan amalan yaumiyah', '', 'Siswa/RiwayatYaumiyah');
                    }
                } else {
                    $siswa = $this->session->userdata('users_id');
                    $siswa = $this->db->get_where('siswa', ['users_id' => $siswa])->row();
                    $this->db->delete('amalan', ['tanggal' => date('Y-m-d'), 'siswa_id' => $siswa->id_siswa]);
                    foreach ($sub_kategori as $index => $result) {
                        $data = [
                            'tanggal' => date('Y-m-d'),
                            'waktu' => date('H:i:s'),
                            'sub_kategori_id' => $result,
                            'status' => '1',
                            'siswa_id' => $siswa->id_siswa,
                            'kelas_siswa_id' => $siswa_kelas
                        ];
                        $this->db->insert('amalan', $data);
                        $update[] = $this->db->affected_rows();
                    }
                    if (count($update) > 0) {
                        sweetAlert2('success', 'Successfully', 'Berhasil mengupdate amalan yaumiyah', '', 'Siswa/RiwayatYaumiyah');
                    } else {
                        sweetAlert2('error', 'Failed', 'Gagal mengupdate amalan yaumiyah', '', 'Siswa/RiwayatYaumiyah');
                    }
                }
            }
        } else {
            show_404();
        }
    }
    public function laporan()
    {
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Siswa/Home');
        $this->breadcrumbs->push('Amalan Yaumiyah', 'Siswa/RiwayatYaumiyah');
        $this->breadcrumbs->push('Laporan Amalan Yaumiyah', 'Siswa/RiwayatYaumiyah/laporan');
        // output
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['title'] = 'Laporan Amalan Yaumiyah';
        $tahun = htmlspecialchars($this->input->get('tahun', true));
        $bulan = htmlspecialchars($this->input->get('bulan', true));
        $export = htmlspecialchars($this->input->get('export', true));
        if ($tahun != null || $bulan != null) {
            $tahun = htmlspecialchars($this->input->get('tahun', true));
            $bulan = htmlspecialchars($this->input->get('bulan', true));
            $data['bulan_tahun'] = $bulan . " - " . $tahun;
            $kalender = CAL_GREGORIAN;
            $hari = cal_days_in_month($kalender, $bulan, $tahun);
            $data['hari'] = $hari;
            $data['bulan_amalan'] = $bulan;
            $data['tahun'] = $tahun;
        } else {
            $data['bulan_tahun'] = bulan(date('m')) . " - " . date('Y');
            $kalender = CAL_GREGORIAN;
            $bulan = date('m');
            $tahun = date('Y');
            $hari = cal_days_in_month($kalender, $bulan, $tahun);
            $data['hari'] = $hari;
            $data['bulan_amalan'] = $bulan;
            $data['tahun'] = $tahun;
        }
        $profile = check_users_profile();

        $siswa_kelas = $this->db->get_where('siswa_kelas', ['siswa_id' => $profile->id_siswa])->result();
        $siswa_kelas = array_unique(array_column($siswa_kelas, 'kelas_siswa_id'));
        $siswa_kelas = max($siswa_kelas);

        $data['sub_kategori'] = $this->RolesYaumiyah_model->getSubYaumiyah($profile->id_siswa, $siswa_kelas)->result();


        $data['kelas_siswa_id'] = $siswa_kelas;
        $data['profile'] = $profile;
        if ($export != null && $export == 'yes') {
            $this->load->view('siswa/export/amalan_yaumiyah', $data);
        } else {
            $this->template->siswa('siswa/riwayatyaumiyah/laporan', $data);
        }
    }
}
