<?php
class kategori_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('kategori');
        if ($id != null) {
            $this->db->where('id_kategori', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_kategori', $this->db->escape_str($id));
        $this->db->update('kategori', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('kategori', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_kategori', $id);
        $this->db->delete('kategori');
        return $this->db->affected_rows();
    }
}
