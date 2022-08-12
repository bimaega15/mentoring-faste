<?php
class Amalan_model extends CI_Model
{
    var $roles = '';
    var $users = '';
    public function __construct()
    {
        parent::__construct();
        $this->roles .= $this->session->userdata('roles_id');
        $this->users .= $this->session->userdata('users_id');
    }
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('amalan');
        if ($id != null) {
            $this->db->where('id_amalan', $this->db->escape_str($id));
        }
        return $this->db->get();
    }
    public function getLaporanPerBulan($tahun = null, $bulan = null, $id_siswa = null)
    {
        $this->db->select('*,MONTH(amalan.tanggal) bulanan');
        $this->db->from('amalan');
        $this->db->join('siswa', 'siswa.id_siswa = amalan.siswa_id');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('guru', 'guru.id_guru = kelas.guru_id');
        if ($tahun != null) {
            $this->db->where('YEAR(amalan.tanggal)',  $this->db->escape_str($tahun));
        }
        if ($bulan != null) {
            $this->db->where('MONTH(amalan.tanggal)',  $this->db->escape_str($bulan));
        }
        if ($this->roles == 2) {
            $this->db->where('guru.users_id', $this->users);
        }
        if ($id_siswa != null) {
            $this->db->where('siswa.id_siswa', $id_siswa);
        }
        $this->db->order_by('YEAR(amalan.tanggal)', 'DESC');
        $this->db->order_by('(amalan.tanggal)', 'ASC');
        return $this->db->get();
    }

    public function getLaporanPerBulanFix($tahun = null, $bulan = null, $sub_kategori = null, $siswa = null, $id_kelas_siswa = null)
    {
        $this->db->select('MONTH(amalan.tanggal) bulanan,tanggal');
        $this->db->from('amalan');
        if ($tahun != null) {
            $this->db->where('YEAR(amalan.tanggal)',  $this->db->escape_str($tahun));
        }
        if ($bulan != null) {
            $this->db->where('MONTH(amalan.tanggal)',  $this->db->escape_str($bulan));
        }
        if ($sub_kategori != null) {
            $this->db->where('sub_kategori_id',  $this->db->escape_str($sub_kategori));
        }
        if ($siswa != null) {
            $this->db->where('siswa_id',  $this->db->escape_str($siswa));
        }
        if ($id_kelas_siswa) {
            $this->db->where('kelas_siswa_id', $this->db->escape_str($id_kelas_siswa));
        }
        $this->db->order_by('YEAR(amalan.tanggal)', 'DESC');
        $this->db->order_by('(amalan.tanggal)', 'ASC');
        return $this->db->get();
    }

    public function getLaporanPerMinggu($tahun = null)
    {
        $this->db->select('*,WEEK(amalan.tanggal) mingguan');
        $this->db->from('amalan');
        if ($tahun != null) {
            $this->db->where('YEAR(amalan.tanggal)',  $this->db->escape_str($tahun));
        }
        $this->db->join('siswa', 'siswa.id_siswa = amalan.siswa_id');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('guru', 'guru.id_guru = kelas.guru_id');
        if ($this->roles == 2) {
            $this->db->where('guru.users_id', $this->users);
        }
        $this->db->order_by('YEAR(amalan.tanggal)', 'DESC');
        $this->db->order_by('amalan.tanggal', 'ASC');

        return $this->db->get();
    }

    public function getLaporanPerHari($tahun = null)
    {
        $this->db->select('*');
        $this->db->from('amalan');
        if ($tahun != null) {
            $this->db->where('YEAR(amalan.tanggal)',  $this->db->escape_str($tahun));
            $this->db->join('siswa', 'siswa.id_siswa = amalan.siswa_id');
            $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa.kelas_siswa_id');
            $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
            $this->db->join('guru', 'guru.id_guru = kelas.guru_id');
            if ($this->roles == 2) {
                $this->db->where('guru.users_id', $this->users);
            }
        }
        $this->db->order_by('YEAR(amalan.tanggal)', 'DESC');
        $this->db->order_by('(amalan.tanggal)', 'ASC');
        return $this->db->get();
    }



    public function update($data, $id)
    {
        $this->db->where('id_amalan', $this->db->escape_str($id));
        $this->db->update('amalan', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('amalan', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_amalan', $id);
        $this->db->delete('amalan');
        return $this->db->affected_rows();
    }
    public function getAllJoin()
    {
        $this->db->select('*,kelas.nama nama_kelas');
        $this->db->from('kelas_siswa');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('guru', 'guru.id_guru = kelas.guru_id');
        if ($this->roles == 2) {
            $this->db->where('guru.users_id', $this->users);
        }
        return $this->db->get();
    }
}
