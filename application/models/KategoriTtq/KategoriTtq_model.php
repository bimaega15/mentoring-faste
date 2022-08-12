<?php
class KategoriTtq_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('kategori_ttq');
        if ($id != null) {
            $this->db->where('id_kategori_ttq', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_kategori_ttq', $this->db->escape_str($id));
        $this->db->update('kategori_ttq', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('kategori_ttq', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_kategori_ttq', $id);
        $this->db->delete('kategori_ttq');
        return $this->db->affected_rows();
    }
}
