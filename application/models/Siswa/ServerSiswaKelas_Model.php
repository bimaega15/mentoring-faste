<?php
class ServerSiswaKelas_Model extends CI_Model
{
    var $table = 'siswa'; //nama tabel dari database
    var $column_order = array(
        null, 'siswa.nomor_induk', 'nama_siswa', 'siswa.no_telephone', 'siswa.jenis_kelamin', null
    ); //field yang ada di table user
    var $column_search = array('siswa.nomor_induk', 'siswa.nama', 'siswa.no_telephone', 'siswa.jenis_kelamin'); //field yang diizin untuk pencarian 
    var $id_kelas_siswa = '';

    var $order = array('siswa.id_siswa' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id_kelas_siswa)
    {
        $this->db->select('siswa.nama nama_siswa,siswa.nomor_induk,siswa.no_telephone,siswa.jenis_kelamin,siswa.gambar,siswa.id_siswa,kelas_siswa.id_kelas_siswa');
        $this->db->from($this->table);
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        if ($id_kelas_siswa != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $id_kelas_siswa);
        }
        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_GET['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_GET['search']['value']);
                } else {
                    $this->db->or_like($item, $_GET['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_GET['order'])) {
            $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($id_kelas_siswa = null)
    {
        $this->id_kelas_siswa .= $id_kelas_siswa;
        $this->_get_datatables_query($this->id_kelas_siswa);
        if ($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query($this->id_kelas_siswa);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query($this->id_kelas_siswa);
        return $this->db->count_all_results();
    }
}
