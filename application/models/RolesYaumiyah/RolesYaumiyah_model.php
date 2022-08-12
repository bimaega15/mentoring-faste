<?php
class RolesYaumiyah_model extends CI_Model
{
    var $roles = '';
    var $users = '';
    var $getusers = '';
    public function __construct()
    {
        parent::__construct();
        $this->roles .= $this->session->userdata('roles_id');
        $this->users .= $this->session->userdata('users_id');
        $this->getusers = $this->db->get_where('siswa', ['users_id' => $this->users])->row();
    }
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('kelas');
        if ($id != null) {
            $this->db->where('id_kelas', $this->db->escape_str($id));
        }
        return $this->db->get();
    }
    public function getYaumiyah($id_siswa = null, $id_kelas_siswa = null)
    {
        $this->db->select('*');
        $this->db->from('siswa_kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('roles_yaumiyah', 'roles_yaumiyah.kelas_id = kelas_siswa.id_kelas_siswa');
        $this->db->join('roles_yaumiyah_detail', 'roles_yaumiyah_detail.roles_yaumiyah_id = roles_yaumiyah.id_roles_yaumiyah');
        $this->db->join('kategori', 'kategori.id_kategori = roles_yaumiyah_detail.kategori_id');
        if ($id_siswa != null) {
            $this->db->where('siswa_kelas.siswa_id', $this->db->escape_str($id_siswa));
        }
        if ($id_kelas_siswa != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $this->db->escape_str($id_kelas_siswa));
        }
        $this->db->group_by('kelas_siswa.kelas_id');
        $this->db->group_by('roles_yaumiyah_detail.id_roles_yaumiyah_detail');
        return $this->db->get();
    }
    public function getSubYaumiyah($id_siswa = null, $id_kelas_siswa = null)
    {
        $this->db->select('*');
        $this->db->from('siswa_kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('roles_yaumiyah', 'roles_yaumiyah.kelas_id = kelas_siswa.id_kelas_siswa');
        $this->db->join('roles_yaumiyah_detail', 'roles_yaumiyah_detail.roles_yaumiyah_id = roles_yaumiyah.id_roles_yaumiyah');
        $this->db->join('kategori', 'kategori.id_kategori = roles_yaumiyah_detail.kategori_id');
        $this->db->join('sub_kategori', 'sub_kategori.kategori_id = kategori.id_kategori');
        if ($id_siswa != null) {
            $this->db->where('siswa_kelas.siswa_id', $this->db->escape_str($id_siswa));
        }
        if ($id_kelas_siswa != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $this->db->escape_str($id_kelas_siswa));
        }
        $this->db->group_by('kelas_siswa.kelas_id');
        $this->db->group_by('roles_yaumiyah_detail.id_roles_yaumiyah_detail');
        $this->db->group_by('sub_kategori.id_sub_kategori');
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_kelas', $this->db->escape_str($id));
        $this->db->update('kelas', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('kelas', $data);
        return $this->db->insert_id();
    }
    public function delete($id)
    {
        $this->db->where('id_kelas', $id);
        $this->db->delete('kelas');
        return $this->db->affected_rows();
    }
    public function joinKelasAll($id_kelas)
    {
        $this->db->select('*');
        $this->db->from('kelas_detail');
        $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
        if ($id_kelas != null) {
            $this->db->where('kelas_detail.kelas_id', $this->db->escape_str($id_kelas));
        }
        return $this->db->get();
    }
    public function cekKelas($id_kelas = null)
    {
        $this->db->select('kelas.tingkat,kelas.nama,tahun_akademik.nama nama_tahun,kelas.id_kelas,concat(kelas.tingkat," | ",kelas.nama) kelas_siswa,kelas_siswa.id_kelas_siswa');
        $this->db->from('kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.kelas_id = kelas.id_kelas');
        $this->db->join('tahun_akademik', 'kelas_siswa.tahun_akademik_id = tahun_akademik.id_tahun_akademik');

        if ($this->roles == 2) {
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('guru.users_id', $this->users);
        }
        if ($id_kelas != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $id_kelas);
        }
        $this->db->group_by('kelas_siswa.id_kelas_siswa');
        return $this->db->get();
    }
    public function kelasSiswaYaumiyah($id_kelas_siswa)
    {
        $this->db->select('siswa.nama nama_siswa,siswa.nomor_induk,siswa.no_telephone,siswa.jenis_kelamin,siswa.gambar,siswa.id_siswa');
        $this->db->from('siswa');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        if ($id_kelas_siswa != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $id_kelas_siswa);
        }
        return $this->db->get();
    }
}
