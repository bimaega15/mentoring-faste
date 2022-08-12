<?php
class Server_ttq_siswa extends CI_Model
{
    var $table = 'ttq'; //nama tabel dari database
    var $column_order = array(
        null, 'ttq.tanggal', 'ttq.waktu', 'ttq.jumlah_halaman', 'kelas_siswa', 'kelas.id_kelas', 'keterangan_ttq', 'nama_kategori_ttq'
    ); //field yang ada di table user
    var $column_search = array('ttq.tanggal', 'ttq.waktu', 'ttq.jumlah_halaman', 'CONCAT(kelas.tingkat, " | ", kelas.nama," | ",tahun_akademik.nama)', 'kelas.id_kelas', 'ttq.keterangan', 'kategori_ttq.nama'); //field yang diizin untuk pencarian 
    var $order = array('ttq.tanggal' => 'asc'); // default order 
    var $roles = '';
    var $users = '';
    var $id_kategori_ttq = '';
    var $id_kelas = '';
    var $tahun = '';
    var $guru = '';
    var $getusers = '';
    public function __construct()
    {
        parent::__construct();
        $this->roles .= $this->session->userdata('roles_id');
        $this->users .= $this->session->userdata('users_id');
        $this->getusers = $this->db->get_where('siswa', ['users_id' => $this->users])->row();
        $this->load->database();
    }

    private function _get_datatables_query($id_kategori_ttq, $id_kelas, $tahun, $guru)
    {
        $this->db->select('ttq.keterangan keterangan_ttq,ttq.tanggal,ttq.waktu,ttq.jumlah_halaman,ttq.siswa_id,siswa.nama,siswa.nomor_induk,ttq.id_ttq,ttq.kategori_ttq_id, CONCAT(siswa.nomor_induk, " | ", siswa.nama) nama_siswa_kelas,CONCAT(kelas.tingkat, " | ", kelas.nama," | ",tahun_akademik.nama) kelas_siswa,kelas.id_kelas,kategori_ttq.nama nama_kategori_ttq');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
        $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');


        if ($tahun != null) {
            $this->db->where('YEAR(ttq.tanggal)',  $tahun);
        }
        if ($id_kategori_ttq != null) {
            $this->db->where('ttq.kategori_ttq_id', $id_kategori_ttq);
        }
        if ($id_kelas != null) {
            $this->db->where('kelas.id_kelas', $id_kelas);
        }
        if ($guru != null) {
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('guru.id_guru', $guru);
        }

        if ($this->roles == 2) {
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('guru.users_id', $this->users);
        }
        if ($this->roles == 3) {
            $this->db->where('siswa.id_siswa', $this->getusers->id_siswa);
        }

        $this->db->group_by('ttq.id_ttq');
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

    function get_datatables($id_kategori_ttq = null, $id_kelas = null, $tahun = null, $guru = null)
    {
        $this->id_kategori_ttq .= $id_kategori_ttq;
        $this->id_kelas .= $id_kelas;
        $this->tahun .= $tahun;
        $this->guru .= $guru;
        $this->_get_datatables_query($this->id_kategori_ttq, $this->id_kelas, $this->tahun, $this->guru);
        if ($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query($this->id_kategori_ttq, $this->id_kelas, $this->tahun, $this->guru);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query($this->id_kategori_ttq, $this->id_kelas, $this->tahun, $this->guru);
        return $this->db->count_all_results();
    }
}
