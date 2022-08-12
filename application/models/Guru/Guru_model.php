<?php
class Guru_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('guru');
        if ($id != null) {
            $this->db->where('id_guru', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_guru', $this->db->escape_str($id));
        $this->db->update('guru', $data);
        return $this->db->affected_rows();
    }
    public function updateUsers($data, $id)
    {
        $this->db->where('users_id', $this->db->escape_str($id));
        $this->db->update('guru', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('guru', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_guru', $id);
        $this->db->delete('guru');
        return $this->db->affected_rows();
    }
    public function joinAllUsers($id = null)
    {
        $this->db->select('*,guru.nama nama_guru');
        $this->db->from('guru');
        $this->db->join('users', 'users.id_users = guru.users_id');
        $this->db->join('users_roles', 'users_roles.users_id = users.id_users');
        $this->db->join('roles', 'users_roles.roles_id = roles.id_roles');
        if ($id != null) {
            $this->db->where('guru.id_guru', $id);
        }
        $this->db->order_by('guru.id_guru', 'desc');
        return $this->db->get();
    }
    public function getProfile($id_users = null)
    {
        $this->db->select('*');
        $this->db->from('guru');
        if ($id_users != null) {
            $this->db->where('users_id', $id_users);
        }
        $this->db->order_by('id_guru', 'desc');
        return $this->db->get();
    }
}
