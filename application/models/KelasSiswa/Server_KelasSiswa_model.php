<?php
class Server_KelasSiswa_model extends CI_Model
{
    var $table = 'kelas_siswa'; //nama tabel dari database
    var $column_order = array(
        null, 'kelas_siswa', null, 'tahun_akademik.nama'
    ); //field yang ada di table user
    var $column_search = array('CONCAT(kelas.tingkat," | ",kelas.nama)', 'tahun_akademik.nama'); //field yang diizin untuk pencarian 
    var $order = array('id_kelas_siswa' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->select('CONCAT(kelas.tingkat," | ",kelas.nama) kelas_siswa,kelas.id_kelas,tahun_akademik.nama nama_akademik,kelas_siswa.id_kelas_siswa');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }
}
