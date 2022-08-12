<?php
class Menu_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('menu_management');
        if ($id != null) {
            $this->db->where('id_menu_management', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_menu_management', $this->db->escape_str($id));
        $this->db->update('menu_management', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('menu_management', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_menu_management', $id);
        $this->db->delete('menu_management');
        return $this->db->affected_rows();
    }

}
