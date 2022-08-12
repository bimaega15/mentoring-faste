<?php
class SiswaKelas_model extends CI_Model
{
    var $users_id  = '';
    public function __construct()
    {
        parent::__construct();
        $this->users_id = $this->session->userdata('users_id');
    }
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('siswa_kelas');
        if ($id != null) {
            $this->db->where('id_siswa_kelas', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_siswa_kelas', $this->db->escape_str($id));
        $this->db->update('siswa_kelas', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('siswa_kelas', $data);
        return $this->db->insert_id();
    }
    public function delete($id)
    {
        $this->db->where('id_siswa_kelas', $id);
        $this->db->delete('siswa_kelas');
        return $this->db->affected_rows();
    }
    public function getMyKelas($id = null)
    {
        $this->db->select('*, concat(kelas.tingkat, " | ", kelas.nama) kelas_ngajar');
        $this->db->from('siswa_kelas');
        $this->db->join('siswa', 'siswa.id_siswa = siswa_kelas.siswa_id');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
        $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
        if ($id != null) {
            $this->db->where('siswa_kelas.id_siswa_kelas', $this->db->escape_str($id));
        }
        $this->db->where('siswa.users_id', $this->db->escape_str($this->users_id));
        $this->db->group_by('guru_id');
        return $this->db->get();
    }
}
