<?php
class SubKategori_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('sub_kategori');
        if ($id != null) {
            $this->db->where('id_sub_kategori', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_sub_kategori', $this->db->escape_str($id));
        $this->db->update('sub_kategori', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('sub_kategori', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_sub_kategori', $id);
        $this->db->delete('sub_kategori');
        return $this->db->affected_rows();
    }
}
