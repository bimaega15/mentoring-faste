<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        timeZone();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles != 3) {
            show_404();
        }
    }
    public function index()
    {
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Siswa/Home');
        // output
        $data['title'] = 'Dashboard';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        $data['title'] = 'Dashboard';

        $this->template->siswa('siswa/home/main', $data);
    }
}
