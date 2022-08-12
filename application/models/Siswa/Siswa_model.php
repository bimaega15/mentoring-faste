<?php
class Siswa_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        if ($id != null) {
            $this->db->where('id_siswa', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_siswa', $this->db->escape_str($id));
        $this->db->update('siswa', $data);
        return $this->db->affected_rows();
    }
    public function updateUsers($data, $id)
    {
        $this->db->where('users_id', $this->db->escape_str($id));
        $this->db->update('siswa', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('siswa', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_siswa', $id);
        $this->db->delete('siswa');
        return $this->db->affected_rows();
    }
    public function joinAllUsers($id = null)
    {
        $this->db->select('*,siswa.nama nama_siswa');
        $this->db->from('siswa');
        $this->db->join('users', 'users.id_users = siswa.users_id');
        $this->db->join('users_roles', 'users_roles.users_id = users.id_users');
        $this->db->join('roles', 'users_roles.roles_id = roles.id_roles');
        if ($id != null) {
            $this->db->where('siswa.id_siswa', $id);
        }
        $this->db->order_by('siswa.id_siswa', 'desc');
        return $this->db->get();
    }
    public function getProfile($id_users = null)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        if ($id_users != null) {
            $this->db->where('users_id', $id_users);
        }
        $this->db->order_by('id_siswa', 'desc');
        return $this->db->get();
    }
}
