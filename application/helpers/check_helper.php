<?php
function timeZone()
{
    $ci = &get_instance();
    date_default_timezone_set('Asia/Jakarta');
}

function check_not_login()
{
    $ci = &get_instance();
    if (!$ci->session->has_userdata('logged')) {
        redirect(base_url('Login'));
    }
}
function check_already_login()
{
    $ci = &get_instance();
    if ($ci->session->has_userdata('logged')) {
        if ($ci->session->userdata('roles_id') == 3) {
            redirect(base_url('Siswa/Home'));
        } else {
            redirect(base_url('Admin/Home'));
        }
    }
}

function tanggal_indo($data)
{
    $ci = &get_instance();
    $tanggal = explode('-', $data);
    $tahun = $tanggal[0];
    $bulan = $tanggal[1];
    $tanggal = $tanggal[2];
    $fix = $tanggal . ' ' . bulan($bulan) . ' ' . $tahun;
    return $fix;
}
function tanggal_indo_convert($data)
{
    $ci = &get_instance();
    $tanggal = explode(' ', $data);
    $tahun = $tanggal[2];
    $bulan = $tanggal[1];
    $tanggal = $tanggal[0];
    $fix = $tahun . '-' . bulan_convert($bulan) . '-' . $tanggal;
    return $fix;
}
function bulan($data)
{
    $bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    return $bulan[$data];
}
function bulan_laporan($data)
{
    $bulan = [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    return $bulan[$data];
}
function bulan_convert($data)
{
    $bulan = [
        'Januari' => '01',
        'Februari' => '02',
        'Maret' => '03',
        'April' => '04',
        'Mei' => '05',
        'Juni' => '06',
        'Juli' => '07',
        'Agustus' => '08',
        'September' => '09',
        'Oktober' => '10',
        'November' => '11',
        'Desember' => '12'
    ];
    return $bulan[$data];
}
function check_agama_id($id_agama)
{
    $ci = &get_instance();
    $row = $ci->db->get_where('agama', ['id_agama' => $id_agama])->row();
    return $row->nama;
}

function check_kategori_id($id_kategori)
{
    $ci = &get_instance();
    $ci->load->model('Kategori/Kategori_model');
    $getKategori = $ci->Kategori_model->get($id_kategori)->row();
    return $getKategori->nama;
}
function check_kategori_ttq_id($id_kategori)
{
    $ci = &get_instance();
    $ci->load->model('KategoriTtq/KategoriTtq_model');
    $getKategori = $ci->KategoriTtq_model->get($id_kategori)->row();
    return $getKategori->nama;
}
function check_namakategori_ttq_id($nama)
{
    $ci = &get_instance();
    $ci->load->model('KategoriTtq/KategoriTtq_model');
    $getKategori = $ci->db->get_where('kategori_ttq', ['nama' => $nama])->row();
    return $getKategori->id_kategori_ttq;
}
function check_namakategori_id($nama_kategori)
{
    $ci = &get_instance();
    $getKategori = $ci->db->get_where('kategori', ['nama' => $nama_kategori])->row();
    return $getKategori->id_kategori;
}

function bulan_romawi($data)
{
    $bulan = [
        '01' => 'I',
        '02' => 'II',
        '03' => 'III',
        '04' => 'IV',
        '05' => 'V',
        '06' => 'VI',
        '07' => 'VII',
        '08' => 'VIII',
        '09' => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII'
    ];
    return $bulan[$data];
}
function wordlimit($text, $id = '', $controller = '', $limit = '', $keterangan = '')
{
    if (strlen($text) > $limit)
        $word = mb_substr($text, 0, $limit) . "<br> <a href='" . base_url('Web/' . $controller . '/selengkapnya/') . $id . "' class='btn btn-bordred-purple waves-effect btn-rounded  width-md waves-light btn_modal_content' data-controller='" . $controller . "' data-id='" . $id . "' data-toggle='modal' data-target='#modalContent' data-keterangan='" . $keterangan . "'>Selengkapnya</a>";
    else
        $word = $text;
    return $word;
}
function wordTextSlider($text, $limit)
{
    if (strlen($text) > $limit)
        $word = mb_substr($text, 0, $limit) . " ... ";
    else
        $word = $text;
    return $word;
}
function tanggalWaktu_indo($tanggalwaktu)
{
    $tanggalwaktu = explode('-', $tanggalwaktu);
    $endTanggalwaktu = explode(' ', $tanggalwaktu[2]);
    return $endTanggalwaktu[0] . ' ' . bulan($tanggalwaktu[1]) . ' ' . $tanggalwaktu[0] . ' ' . $endTanggalwaktu[1];
}
function check_tanggal_kegiatan($tanggal_waktu)
{
    $tanggalwaktu = explode('-', $tanggal_waktu);
    $endTanggalwaktu = explode(' ', $tanggalwaktu[2]);
    return $endTanggalwaktu[0];
}
function check_bulan_kegiatan($tanggal_waktu)
{
    $tanggalwaktu = explode('-', $tanggal_waktu);
    $endTanggalwaktu = explode(' ', $tanggalwaktu[2]);
    return bulan($tanggalwaktu[1]);
}
function check_tahun_kegiatan($tanggal_waktu)
{
    $tanggalwaktu = explode('-', $tanggal_waktu);
    $endTanggalwaktu = explode(' ', $tanggalwaktu[2]);
    return $tanggalwaktu[0];
}
function check_jam_kegiatan($tanggal_waktu)
{
    $tanggalwaktu = explode('-', $tanggal_waktu);
    $endTanggalwaktu = explode(' ', $tanggalwaktu[2]);

    return $endTanggalwaktu[1];
}

function tanggal($tanggal_waktu)
{
    $tanggal = explode('-', $tanggal_waktu);
    $tanggal2 = explode(' ', $tanggal[2]);

    return $tanggal[0] . '-' . $tanggal[1] . '-' . $tanggal2[0];
}
function sweetAlert2($status = null, $title = null, $msg = null, $footer = null, $url = null)
{
    echo '
    <head>
        <script src="' . base_url('assets_js/js/jquery-3.4.1.js') . '"></script>
        <script src="' . base_url('assets_js/js/sweetalert2.js') . '"></script>
  </head>';

    $ci = &get_instance();

    if ($status != null) {
        if ($footer == null) {
            if ($url != null) {
                echo '
                <script type="text/javascript">
                    $(document).ready(function(){
                        Swal.fire({
                            icon: "' . $status . '",
                            title: "' . $title . '",
                            html: "' . $msg . '",
                            footer: "' . $footer . '",
                        }).then(function() {
                            window.location = "' . base_url($url) . '"
                        })
                    });
        
                </script>
                ';
            } else {
                echo '
                <script type="text/javascript">
                    $(document).ready(function(){
                        Swal.fire({
                            icon: "' . $status . '",
                            title: "' . $title . '",
                            html: "' . $msg . '",
                            footer: "' . $footer . '",
                        })
                    });
        
                </script>
                ';
            }
        } else {
            if ($url != null) {
                echo '
                <script type="text/javascript">
                    $(document).ready(function(){
                        Swal.fire({
                            icon: "' . $status . '",
                            title: "' . $title . '",
                            html: "' . $msg . '",
                            footer: "' . $footer . '"
                        }).then(function() {
                            window.location = "' . base_url($url) . '"
                        })
                    });
        
                </script>
                ';
            } else {
                echo '
                <script type="text/javascript">
                    $(document).ready(function(){
                        Swal.fire({
                            icon: "' . $status . '",
                            title: "' . $title . '",
                            html: "' . $msg . '",
                            footer: "' . $footer . '"
                        })
                    });
                </script>
                ';
            }
        }
    }
}
function check_jk($jk)
{
    if ($jk == 'L') {
        return 'Laki-laki';
    } else {
        return 'Perempuan';
    }
}
function check_jk_convert($jk)
{
    if ($jk == 'Laki-laki') {
        return 'L';
    } else {
        return 'P';
    }
}
function check_roles($id)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('roles', ['id_roles' => $id])->row();
    return $get->nama;
}
function check_roles_convert($nama)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('roles', ['nama' => $nama])->row();
    return $get->id_roles;
}
function check_kelas($id)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('kelas', ['id_kelas' => $id])->row();
    return $get->tingkat . " | " . $get->nama;
}
function check_kelas_siswa($id)
{
    $ci = &get_instance();
    $ci->load->model('Kelas/Kelas_model');
    $get = $ci->Kelas_model->get($id)->row();
    return $get->tingkat . " | " . $get->nama;
}
function check_kelasSiswa($id)
{
    $ci = &get_instance();
    $ci->load->model('KelasSiswa/KelasSiswa_model');
    $get = $ci->KelasSiswa_model->joinAllKelas($id)->row();
    return $get->kelas_siswa;
}
function check_kelasSiswaConvert($nama)
{
    $ci = &get_instance();
    $explode = explode('|', $nama);

    $tingkat = trim($explode[0]);
    $nama = trim($explode[1]);
    $get = $ci->db->get_where('kelas', ['tingkat' => $tingkat, 'nama' => $nama])->row();
    $tahun = check_tahun_akademik_convert(trim($explode[2]));
    $get = $ci->db->get_where('kelas_siswa', ['kelas_id' => $get->id_kelas, 'tahun_akademik_id' => $tahun])->row();
    return $get->id_kelas_siswa;
}
function check_kelas_convert($kelas)
{
    $ci = &get_instance();
    $explode = explode('|', $kelas);
    $tingkat = trim($explode[0]);
    $nama = trim($explode[1]);
    $get = $ci->db->get_where('kelas', ['tingkat' => $tingkat, 'nama' => $nama])->row();
    return $get->id_kelas;
}
function check_tahun_akademik($id)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('tahun_akademik', ['id_tahun_akademik' => $id])->row();
    return $get->nama;
}
function check_tahun_akademik_convert($nama)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('tahun_akademik', ['nama' => $nama])->row();
    return $get->id_tahun_akademik;
}
function check_guru($id)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('guru', ['id_guru' => $id])->row();
    return $get->nama;
}
function check_guru_lengkap($id)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('guru', ['id_guru' => $id])->row();
    return $get;
}
function check_guru_convert($nama)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('guru', ['nama' => $nama])->row();
    return $get->id_guru;
}
function check_menu_management($id)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('menu_management', ['id_menu_management' => $id])->row();
    return $get->icon . " | " . $get->nama . " | " . $get->link;
}
function check_menu_management_convert($nama)
{
    $ci = &get_instance();
    $explode = explode("|", $nama);
    $icon = trim($explode[0]);
    $nama = trim($explode[1]);
    $link = trim($explode[2]);
    $get = $ci->db->get_where('menu_management', ['icon' => $icon, 'nama' => $nama, 'link' => $link])->row();
    return $get->id_menu_management;
}
function access_menu()
{
    $ci = &get_instance();
    $ci->load->model(['UsersAccess/UsersAccess_model']);
    $roles_id = $ci->session->userdata('roles_id');
    $users_management = $ci->UsersAccess_model->getJoinAccess($roles_id)->result();
    return $users_management;
}
function check_users_profile()
{
    $ci = &get_instance();
    $ci->load->model(['Auth/Users_model', 'Guru/Guru_model', 'Admin/Admin_model', 'Siswa/Siswa_model']);
    $roles_id = $ci->session->userdata('roles_id');
    $users_id = $ci->session->userdata('users_id');
    if ($roles_id == 1) {
        $profile = $ci->Admin_model->getProfile($users_id)->row();
    }
    if ($roles_id == 2) {
        $profile = $ci->Guru_model->getProfile($users_id)->row();
    }
    if ($roles_id == 3) {
        $profile = $ci->Siswa_model->getProfile($users_id)->row();
    }
    return $profile;
}
function waktu_format($waktu)
{
    $waktu = explode(":", $waktu);
    return $waktu[0] . ":" . $waktu[1];
}
function check_siswa($id_siswa)
{
    $ci = &get_instance();
    $getSiswa = $ci->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
    return $getSiswa->nama;
}
function check_siswa_lengkap($id_siswa)
{
    $ci = &get_instance();
    $getSiswa = $ci->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
    return $getSiswa->nomor_induk . " | " . $getSiswa->nama;
}
function check_siswa_lengkap_convert($id_siswa)
{
    $ci = &get_instance();
    $data = explode('|', $id_siswa);
    $getSiswa = $ci->db->get_where('siswa', ['nomor_induk' => trim($data[0]), 'nama' => trim($data[1])])->row();
    return $getSiswa->id_siswa;
}
function get_my_id()
{
    $ci = &get_instance();
    $roles = $ci->session->userdata('roles_id');
    $users = $ci->session->userdata('users_id');
    if ($roles == 1) {
        $getUsers = $ci->db->get_where('admin', ['users_id' => $users]);
    }
    if ($roles == 2) {
        $getUsers = $ci->db->get_where('guru', ['users_id' => $users]);
    }
    if ($roles == 3) {
        $getUsers = $ci->db->get_where('siswa', ['users_id' => $users]);
    }
    $data = [
        'roles' => $roles,
        'row' => $getUsers->row()
    ];
    return $data;
}
function check_roles_crud($link, $roles)
{
    $ci = &get_instance();
    foreach (access_menu() as $result) {
        if ($result->link == $link && $result->users_roles_id == $roles) {
            $data = $result;
        }
    }
    return $data;
}
function check_siswa_convert($nama)
{
    $ci = &get_instance();
    $nama = $ci->db->get_where('siswa', ['nama' => $nama])->row();
    return $nama->id_siswa;
}
function checkedYaumiyah($id_sub_kategori, $id_siswa = null, $id_kelas_siswa = null)
{
    $ci = &get_instance();
    if ($id_siswa != null) {
        $siswa = $ci->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
    } else {
        $users = $ci->session->userdata('users_id');
        $siswa = $ci->db->get_where('siswa', ['users_id' => $users])->row();
    }

    if ($id_kelas_siswa == null) {
        $siswaKelas = $ci->db->get_where('siswa_kelas', ['siswa_id' => $siswa->id_siswa])->result();
        $id_kelas_siswa = array_column($siswaKelas, 'kelas_siswa_id');
        $id_kelas_siswa = max($id_kelas_siswa);
    }

    $get = $ci->db->get_where('amalan', ['sub_kategori_id' => $id_sub_kategori, 'siswa_id' => $siswa->id_siswa, 'tanggal' => date('Y-m-d'), 'kelas_siswa_id' => $id_kelas_siswa]);
    if ($get->num_rows() > 0) {
        return 'checked';
    } else {
        return false;
    }
}
function check_amalan_id($id_sub_kategori)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('amalan', ['sub_kategori_id' => $id_sub_kategori, 'tanggal' => date('Y-m-d')]);
    if ($get->num_rows() > 0) {
        $get = $get->row();
        return $get->id_amalan;
    } else {
        return false;
    }
}
function check_join_all_kelas_siswa($id_siswa_kelas = '')
{

    $ci = &get_instance();
    $ci->load->model('SiswaKelas/SiswaKelas_model');
    $get = $ci->SiswaKelas_model->getMyKelas($id_siswa_kelas)->result();
    return $get;
}

function checkKelasSiswaExport($kelas_siswa_id)
{
    $ci = &get_instance();
    $ci->load->model('KelasSiswa/KelasSiswa_model');
    $get = $ci->KelasSiswa_model->tingkatNamaTahun($kelas_siswa_id)->row();

    return $get;
}
function check_laporan_yaumiyah($tanggal, $sub_kategori_id, $bulan_amalan, $id_siswa = '', $id_kelas_siswa = '')
{
    $ci = &get_instance();
    $ci->load->model('Amalan/Amalan_model');
    $users = $ci->session->userdata('users_id');
    if ($id_siswa == null) {
        $siswa = $ci->db->get_where('siswa', ['users_id' => $users])->row();
        $get = $ci->Amalan_model->getLaporanPerBulanFix(null, $bulan_amalan, $sub_kategori_id, $siswa->id_siswa, $id_kelas_siswa)->result();
    } else {
        $get = $ci->Amalan_model->getLaporanPerBulanFix(null, $bulan_amalan, $sub_kategori_id, $id_siswa, $id_kelas_siswa)->result();
    }

    foreach ($get as $result) {
        $data[] = explode('-', $result->tanggal);
    }
    if (isset($data)) {
        foreach ($data as $result) {
            $slur_tanggal[] = intval($result[2]);
        }
        if (in_array($tanggal, $slur_tanggal)) {
            return '<span class="text-success">1</span>';
        } else {
            return "<span class='text-danger'>0</span>";
        }
    } else {
        return "<span class='text-danger'>0</span>";
    }
}
function val_guru_kelas($id_kelas)
{
    $ci = &get_instance();
    $get = $ci->db->get_where('kelas_detail', ['kelas_id' => $id_kelas])->result();
    $output = '<ul>';
    foreach ($get as $result) {
        $output .= '<li>' . check_guru_lengkap($result->guru_id)->nomor_induk . " | " . check_guru_lengkap($result->guru_id)->nama . ' | ' . check_ttq(check_guru_lengkap($result->guru_id)->status_ttq) . '</li>';
    }
    $output .= '</ul>';
    return $output;
}
function val_tahun_ajaran($id_kelas)
{
    $ci = &get_instance();
    $ci->db->select('tahun_akademik.nama');
    $ci->db->from('kelas');
    $ci->db->join('kelas_siswa', 'kelas_siswa.kelas_id = kelas.id_kelas');
    $ci->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
    $get = $ci->db->get();
    return $get;
}
function val_guru_kelas_ui($id_kelas)
{
    $ci = &get_instance();
    $ci->load->model('Kelas/Kelas_model');
    $get = $ci->Kelas_model->joinKelasAll($id_kelas)->result();
    return $get;
}
function check_ttq($status)
{
    if ($status == 'y') {
        return 'Guru TTQ';
    } else {
        return 'Wali Kelas';
    }
}
function check_sudah_lengkap()
{
    $ci = &get_instance();
    $roles = $ci->session->userdata('roles_id');
    $users = $ci->session->userdata('users_id');

    if ($roles == 1) {
        $get = $ci->db->get_where('admin', ['users_id' => $users])->row();
        if ($get->keterangan == 'Lengkap') {
            redirect(base_url('Login'));
        }
    }
    if ($roles == 2) {
        $get = $ci->db->get_where('guru', ['users_id' => $users])->row();
        if ($get->keterangan == 'Lengkap') {
            redirect(base_url('Login'));
        }
    }
    if ($roles == 3) {
        $get = $ci->db->get_where('siswa', ['users_id' => $users])->row();
        if ($get->keterangan == 'Lengkap') {
            redirect(base_url('Login'));
        }
    }
}
function check_kelas_guru($id_guru)
{
    $ci = &get_instance();
    $ci->load->model('Kelas/Kelas_model');
    $kelas = $ci->Kelas_model->cekKelas($id_guru);
    if ($kelas->num_rows() == 0) {
        show_404();
    }
}
function check_kelas_siswa_table($id_siswa)
{
    $ci = &get_instance();
    $ci->load->model('Ttq/Ttq_model');
    $get = $ci->Ttq_model->getKelasSiswa($id_siswa)->result();
    $output = '';
    foreach ($get as $result) {
        $output .= '
            <ul>
                <li>' . $result->kelas_siswa . '</li>
            </ul>
        ';
    }
    return $output;
}
function checkedRolesYaumiyah()
{
    $users = check_users_profile();
    $ci = &get_instance();
    $get = $ci->load->model('Ttq/Ttq_model');
    $row = $ci->Ttq_model->checkedRolesYaumiyah($users->id_guru)->result();
    return $row;
}
function checkUsers($id_users)
{
    $ci = &get_instance();
    $ci->load->model(['Admin/Admin_model', 'Guru/Guru_model', 'Siswa/Siswa_model']);
    $admin = $ci->Admin_model->getProfile($id_users);
    $guru = $ci->Guru_model->getProfile($id_users);
    $siswa = $ci->Siswa_model->getProfile($id_users);
    if ($admin->num_rows() > 0) {
        $rows = $admin->row();
        $output = '
        <small>
        Akses : Admin <br>
        Nomor Induk : ' . $rows->nomor_induk . ' <br>
        Nama : ' . $rows->nama . ' <br>
         J.K : ' . $rows->jenis_kelamin == "P" ? "Wanita" : "Pria" . ' <br>
        </small>
        ';
    }
    if ($guru->num_rows() > 0) {
        $rows = $guru->row();
        $output = '
        <small>
        Akses : Guru <br>
        Nomor Induk : ' . $rows->nomor_induk . ' <br>
        Nama : ' . $rows->nama . ' <br>
         J.K : ' . $rows->jenis_kelamin == "P" ? "Wanita" : "Pria" . ' <br>
        </small>
        ';
    }
    if ($siswa->num_rows() > 0) {
        $rows = $siswa->row();
        $output = '
        <small>
        Akses : Siswa <br>
        Nomor Induk : ' . $rows->nomor_induk . ' <br>
        Nama : ' . $rows->nama . ' <br>
        J.K : ' . $rows->jenis_kelamin == "P" ? "Wanita" : "Pria" . ' <br>
        </small>
        ';
    }
    return $output;
}
function removeArrayNull($array)
{
    $fix =  array_filter($array, 'strlen');
    $no = 0;
    $data = null;
    foreach ($fix as $value) {
        $data[$no] = $value;
        $no++;
    }
    return $data;
}
