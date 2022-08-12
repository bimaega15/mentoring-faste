<?php
class Server_KelasGuru_model extends CI_Model
{
    var $table = 'kelas_detail'; //nama tabel dari database
    var $id_kelas = '';
    var $column_order = array(
        null, 'kelas_id', 'guru_id'
    ); //field yang ada di table user
    var $column_search = array('kelas_id', 'guru_id'); //field yang diizin untuk pencarian 
    var $order = array('id_kelas_detail' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id_kelas)
    {
        $this->db->from($this->table);
        if ($id_kelas != null) {
            $this->db->where('kelas_id', $id_kelas);
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

    function get_datatables($id_kelas)
    {
        $this->id_kelas .= $id_kelas;
        $this->_get_datatables_query($this->id_kelas);
        if ($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query($this->id_kelas);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query($this->id_kelas);
        return $this->db->count_all_results();
    }
}
