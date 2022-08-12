<?php
class TahunAkademik_model extends CI_Model
{
    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('tahun_akademik');
        if ($id != null) {
            $this->db->where('id_tahun_akademik', $this->db->escape_str($id));
        }
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_tahun_akademik', $this->db->escape_str($id));
        $this->db->update('tahun_akademik', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('tahun_akademik', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_tahun_akademik', $id);
        $this->db->delete('tahun_akademik');
        return $this->db->affected_rows();
    }

}
