<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Forgot extends CI_Controller
{
    private $create = null;
    private $update = null;
    private $delete = null;
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles != 1 && $my_roles != 2) {
            show_404();
        }
        $roles = check_roles_crud('Admin/Forgot', $my_roles);
        if ($roles->update == 'ijin') {
            $this->update = true;
        }
        if ($roles->delete == 'ijin') {
            $this->delete = true;
        }
        $this->load->model(['Auth/Users_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Forgot/Home');
        $this->breadcrumbs->push('Forgot', 'Forgot/Forgot');
        // output
        $data['title'] = 'Mangement Forgot';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['update'] = $this->update;
        $data['reset_pass'] = $this->db->get_where('users', ['forgot' => 1])->result();
        $this->template->admin('admin/forgot/main', $data);
    }
    public function resetPasswordAll()
    {
        if ($this->update != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id = $this->input->post('data_item', true);
            foreach ($id as $result) {
                $username = $this->db->get_where('users', ['id_users' => $result])->row()->username;
                $data = [
                    'password' => md5($username),
                    'forgot' => 0,
                ];
                $update = $this->db->update('users', $data, ['id_users' => $result]);
                $count_update[] = $this->db->affected_rows();
            }
            $count = count($count_update);
            if ($count > 0) {
                $data = [
                    'status' => 'success',
                    'title' => 'Successfully',
                    'msg' => 'Berhasil reset password ' . $count . ' Users'
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'title' => 'Failed',
                    'msg' => 'Gagal reset password ' . $count . ' Users'
                ];
            }
            echo json_encode($data);
        }
    }
    public function resetOne($id_users)
    {
        if ($this->update != true) {
            show_404();
        }
        $username = $this->db->get_where('users', ['id_users' => $id_users])->row()->username;
        $data = [
            'password' => md5($username),
            'forgot' => 0
        ];
        $update = $this->db->update('users', $data, ['id_users' => $id_users]);
        $count = $this->db->affected_rows();
        if ($count > 0) {
            sweetAlert2('success', 'Successfully', 'Berhasil reset password', '', 'Admin/Forgot');
        } else {
            sweetAlert2('error', 'Failed', 'Gagal reset password', '', 'Admin/Forgot');
        }
    }
}
