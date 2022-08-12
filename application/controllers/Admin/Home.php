<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles == 3) {
            show_404();
        }
        check_not_login();
    }
    public function index()
    {
        $this->breadcrumbs->push('Home', 'Admin/Home');
        // output
        $data['title'] = 'Dashboard';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['admin'] = $this->db->get('admin')->num_rows();
        $data['guru'] = $this->db->get('guru')->num_rows();
        $data['siswa'] = $this->db->get('siswa')->num_rows();
        $data['kelas'] = $this->db->get('kelas')->num_rows();
        $data['kelas_siswa'] = $this->db->get('kelas_siswa')->num_rows();
        $this->template->breadcrumbs($data);
        $this->template->admin('admin/home/main');
    }
}
