<?php
class KelasSiswa_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('kelas_siswa');
        if ($id != null) {
            $this->db->where('id_kelas_siswa', $this->db->escape_str($id));
        }
        return $this->db->get();
    }
    public function joinAllKelas($id = null)
    {
        $this->db->select('kelas_siswa.id_kelas_siswa, CONCAT(kelas.tingkat," | ",kelas.nama," | ",tahun_akademik.nama) as kelas_siswa,kelas.id_kelas');
        $this->db->from('kelas_siswa');
        $this->db->join('kelas', 'kelas_siswa.kelas_id = kelas.id_kelas');
        $this->db->join('tahun_akademik', 'kelas_siswa.tahun_akademik_id = tahun_akademik.id_tahun_akademik');
        if ($id != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_kelas_siswa', $this->db->escape_str($id));
        $this->db->update('kelas_siswa', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('kelas_siswa', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_kelas_siswa', $id);
        $this->db->delete('kelas_siswa');
        return $this->db->affected_rows();
    }
    public function tingkatNamaTahun($kelas_siswa_id)
    {
        $this->db->select('*,kelas.nama nama_kelas, concat(kelas.tingkat, " | ", kelas.nama, " | ", tahun_akademik.nama) kelas_siswa');
        $this->db->from('kelas_siswa');
        $this->db->join('kelas', 'kelas_siswa.kelas_id = kelas.id_kelas', 'left');
        $this->db->join('tahun_akademik', 'kelas_siswa.tahun_akademik_id = tahun_akademik.id_tahun_akademik', 'left');
        if ($kelas_siswa_id != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $this->db->escape_str($kelas_siswa_id));
        }
        return $this->db->get();
    }
    public function get_kelas_siswa($id_kelas_siswa, $id_siswa)
    {
        $this->db->select('kelas_siswa.*');
        $this->db->from('kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.kelas_id = kelas.id_kelas', 'left');
        $this->db->join('siswa_kelas', 'siswa_kelas.kelas_siswa_id = kelas_siswa.id_kelas_siswa', 'left');
        if ($id_kelas_siswa != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $this->db->escape_str($id_kelas_siswa));
        }
        if ($id_siswa != null) {
            $this->db->where('siswa_kelas.siswa_id', $this->db->escape_str($id_siswa));
        }
        return $this->db->get();
    }
}
