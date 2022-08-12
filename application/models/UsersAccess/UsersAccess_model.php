<?php
class UsersAccess_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('users_access_management');
        if ($id != null) {
            $this->db->where('id_users_access_management', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_users_access_management', $this->db->escape_str($id));
        $this->db->update('users_access_management', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('users_access_management', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_users_access_management', $id);
        $this->db->delete('users_access_management');
        return $this->db->affected_rows();
    }
    public function getJoinAccess($roles)
    {
        $this->db->select('*');
        $this->db->from('users_access_management');
        $this->db->join('menu_management', 'menu_management.id_menu_management = users_access_management.menu_management_id');
        if ($roles != null) {
            $this->db->where('users_access_management.users_roles_id', $roles);
        }
        $this->db->order_by('menu_management.urut', 'asc');
        return $this->db->get();
    }
}
