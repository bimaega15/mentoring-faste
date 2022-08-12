<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('users');
        if ($id != null) {
            $this->db->where('id_users', $this->db->escape_str($id));
        }
        return $this->db->get();
    }
    public function insert($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }
    public function getCookie($cookie = null)
    {
        $this->db->select('*');
        $this->db->from('users');
        if ($cookie != null) {
            $this->db->where('cookie', $cookie);
        }
        return $this->db->get();
    }
    public function update($data, $id)
    {
        $this->db->where('id_users', $this->db->escape_str($id));
        $this->db->update('users', $this->db->escape_str($data));
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id_users', $id);
        $this->db->delete('users');
        return $this->db->affected_rows();
    }
    public function getUsername($username = null)
    {
        $this->db->select('id_users,password,username');
        $this->db->from('users');
        if ($username != null) {
            $this->db->where('username', $this->db->escape_str($username));
        }
        return $this->db->get();
    }
    public function getUsersGroup($id = null, $username = null)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_roles', 'users_roles.users_id = users.id_users');
        $this->db->join('roles', 'users_roles.roles_id = roles.id_roles');
        if ($id != null) {
            $this->db->where('users.id_users', $this->db->escape_str($id));
        }
        if ($username != null) {
            $this->db->where('users.username', $this->db->escape_str($username));
        }
        return $this->db->get();
    }
}
