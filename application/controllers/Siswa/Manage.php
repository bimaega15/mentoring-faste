<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage extends CI_Controller
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
        $this->load->model('Siswa/Siswa_model');
    }
    public function index()
    {
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Siswa/Home');
        $this->breadcrumbs->push('Manage', 'Siswa/Manage');
        // output
        $data['title'] = 'Manage Password';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        $data['title'] = 'Manage';
        $this->template->siswa('siswa/manage/main', $data);
    }

    public function updatePassword()
    {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
            $this->form_validation->set_message('required', '{field} wajib diisi');
            $this->form_validation->set_message('matches', '{field} tidak sama dengan {param}');
            $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
            $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small><br>');
            if ($this->form_validation->run() == false) {
                return $this->index();
            } else {
                $my_id = get_my_id()['row'];
                $password = htmlspecialchars($this->input->post('password', true));
                $data = [
                    'password' => md5($password),
                ];
                $this->db->update('users', $data, ['id_users' => $my_id->users_id]);
                $update = $this->db->affected_rows();
                if ($update > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil Mengupdate password', '', 'Siswa/Manage');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal Mengupdate password', '', 'Siswa/Manage');
                }
            }
        } else {
            show_404();
        }
    }
}
