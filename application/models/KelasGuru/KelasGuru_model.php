<?php
class KelasGuru_model extends CI_Model
{
    public function get($id = null, $kelas_id = null)
    {
        $this->db->select('*');
        $this->db->from('kelas_detail');
        if ($id != null) {
            $this->db->where('id_kelas_detail', $this->db->escape_str($id));
        }
        if ($kelas_id != null) {
            $this->db->where('kelas_id', $this->db->escape_str($kelas_id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_kelas_detail', $this->db->escape_str($id));
        $this->db->update('kelas_detail', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('kelas_detail', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_kelas_detail', $id);
        $this->db->delete('kelas_detail');
        return $this->db->affected_rows();
    }
    public function joinAllUsers($id = null)
    {
        $this->db->select('*,kelas_detail.nama nama_kelas_detail');
        $this->db->from('kelas_detail');
        $this->db->join('users', 'users.id_users = kelas_detail.users_id');
        $this->db->join('users_roles', 'users_roles.users_id = users.id_users');
        $this->db->join('roles', 'users_roles.roles_id = roles.id_roles');
        if ($id != null) {
            $this->db->where('kelas_detail.id_kelas_detail', $id);
        }
        $this->db->order_by('kelas_detail.id_kelas_detail', 'desc');
        return $this->db->get();
    }
    public function getProfile($id_users = null)
    {
        $this->db->select('*');
        $this->db->from('kelas_detail');
        if ($id_users != null) {
            $this->db->where('users_id', $id_users);
        }
        $this->db->order_by('id_kelas_detail', 'desc');
        return $this->db->get();
    }
}
