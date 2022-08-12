<?php
class Server_UsersAccess_model extends CI_Model
{
    var $table = 'users_access_management'; //nama tabel dari database
    var $column_order = array(
        null, 'roles.nama', 'menu_management.link', null, null
    ); //field yang ada di table user
    var $column_search = array('roles.nama', 'menu_management.link'); //field yang diizin untuk pencarian 
    var $order = array('users_access_management.id_users_access_management' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->select('roles.nama nama_roles,menu_management.link link_menu,users_access_management.create,users_access_management.read,users_access_management.update,users_access_management.delete,users_access_management.id_users_access_management');
        $this->db->from($this->table);
        $this->db->join('roles', 'roles.id_roles = users_access_management.users_roles_id');
        $this->db->join('menu_management', 'menu_management.id_menu_management = users_access_management.menu_management_id');

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
