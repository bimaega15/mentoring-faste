<?php
class Admin_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('admin');
        if ($id != null) {
            $this->db->where('id_admin', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_admin', $this->db->escape_str($id));
        $this->db->update('admin', $data);
        return $this->db->affected_rows();
    }
    public function updateUsers($data, $id)
    {
        $this->db->where('users_id', $this->db->escape_str($id));
        $this->db->update('admin', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('admin', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_admin', $id);
        $this->db->delete('admin');
        return $this->db->affected_rows();
    }
    public function joinAllUsers($id = null)
    {
        $this->db->select('*,admin.nama nama_admin');
        $this->db->from('admin');
        $this->db->join('users', 'users.id_users = admin.users_id');
        $this->db->join('users_roles', 'users_roles.users_id = users.id_users');
        $this->db->join('roles', 'users_roles.roles_id = roles.id_roles');
        if ($id != null) {
            $this->db->where('admin.id_admin', $id);
        }
        $this->db->order_by('admin.id_admin', 'desc');
        return $this->db->get();
    }
    public function getProfile($id_users = null)
    {
        $this->db->select('*');
        $this->db->from('admin');
        if ($id_users != null) {
            $this->db->where('users_id', $id_users);
        }
        $this->db->order_by('id_admin', 'desc');
        return $this->db->get();
    }
}
