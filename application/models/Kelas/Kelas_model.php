<?php
class Kelas_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('kelas');
        if ($id != null) {
            $this->db->where('id_kelas', $this->db->escape_str($id));
        }
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
    public function cekKelas($id_kelas)
    {
        $roles = get_my_id()['roles'];
        $users = $this->session->userdata('users_id');
        $this->db->select('*');
        $this->db->from('kelas');
        $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
        $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
        if ($id_kelas != null) {
            $this->db->where('kelas.id_kelas', $this->db->escape_str($id_kelas));
        }
        if ($roles == 2) {
            $this->db->where('guru.users_id', $users);
        }
        return $this->db->get();
    }
}
