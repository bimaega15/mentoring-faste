<?php
class Server_SolatWajib extends CI_Model
{
    var $table = 'solat_wajib'; //nama tabel dari database
    var $column_order = array(
        null, 'tanggal', 'subuh', 'submit_subuh', 'dzuhur', 'submit_dzuhur', 'ashar', 'submit_ashar', 'maghrib', 'submit_maghrib', 'isya', 'submit_isya'
    ); //field yang ada di table user
    var $column_search = array('tanggal', 'subuh', 'submit_subuh', 'dzuhur', 'submit_dzuhur', 'ashar', 'submit_ashar', 'maghrib', 'submit_maghrib', 'isya', 'submit_isya'); //field yang diizin untuk pencarian 
    var $order = array('id_solat_wajib' => 'desc'); // default order 
    var $users = '';
    var $siswa = '';
    var $kategori = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->users = $this->session->userdata('users_id');
        $this->siswa = $this->db->get_where('siswa', ['users_id' => $this->users])->row();
    }

    private function _get_datatables_query($id_kategori = '')
    {

        $this->db->from($this->table);
        if ($id_kategori != null) {
            $this->db->where('kategori_id', $id_kategori);
        }
        $this->db->where('siswa_id', $this->siswa->id_siswa);
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

    function get_datatables($id_kategori = '')
    {
        $this->kategori .= $id_kategori;
        $this->_get_datatables_query($this->kategori);
        if ($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query($this->kategori);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query($this->kategori);
        return $this->db->count_all_results();
    }
}
