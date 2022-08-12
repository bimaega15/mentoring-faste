<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        $this->load->model(['Admin/Admin_model', 'Admin/Server_Admin_model', 'Auth/Users_model', 'Guru/Guru_model', 'Siswa/Siswa_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Profile', 'Admin/Profile');
        // output
        $data['title'] = 'Mangement Profile';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        $roles = $this->session->userdata('roles_id');
        if ($roles == 1) {
            $src = 'vendor/img/admin/';
        } else if ($roles == 2) {
            $src = 'vendor/img/guru/';
        } else if ($roles == 3) {
            $src = 'vendor/img/siswa/';
        }
        $data['src'] = $src;
        $data['roles'] = $roles;
        $data['profile'] = check_users_profile();
        $data['process'] = 'process';
        // add breadcrumbs
        $this->template->admin('admin/profile/main', $data);
    }
    public function process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|numeric');

        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-white">', '</small><br>');
        if ($this->form_validation->run() == false) {
            return $this->index();
        } else {
            $roles = get_my_id()['roles'];
            if ($roles == 1) {
                $id_admin =  htmlspecialchars($this->input->post('id_admin', true));
                $id_users = $this->db->get_where('admin', ['id_admin' => $id_admin])->row();
            } else {
                $id_guru =  htmlspecialchars($this->input->post('id_guru', true));
                $id_users = $this->db->get_where('guru', ['id_guru' => $id_guru])->row();
            }

            $nama = htmlspecialchars($this->input->post('nama', true));
            $alamat = htmlspecialchars($this->input->post('alamat', true));
            $telephone = htmlspecialchars($this->input->post('telephone', true));
            $tempat_lahir = htmlspecialchars($this->input->post('tempat_lahir', true));
            $tanggal_lahir = htmlspecialchars($this->input->post('tanggal_lahir', true));
            $jenis_kelamin = htmlspecialchars($this->input->post('jenis_kelamin', true));
            if ($jenis_kelamin == 'L') {
                $gambar = 'default_male.jpg';
            } else {
                $gambar = 'default_female.png';
            }
            $updateUsers = htmlspecialchars($this->input->post('password', true));
            if ($updateUsers != null) {
                $data = [
                    'password' => md5($updateUsers),
                ];
                $updateUsers = $this->db->update('users', $data, ['id_users' => $id_users->users_id]);
                $updateUsers = $this->db->affected_rows();
            }

            $data = [
                'nama' => $nama,
                'alamat' => $alamat,
                'no_telephone' => $telephone,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'jenis_kelamin' => $jenis_kelamin,
                'gambar' => $gambar
            ];

            if ($roles == 1) {
                $update = $this->Admin_model->update($data, $id_admin);
            } else {
                $update = $this->Guru_model->update($data, $id_guru);
            }

            if ($update > 0 || $updateUsers > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil Mengupdate Biodata', '', 'Admin/Profile');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal Mengupdate Biodata', '', 'Admin/Profile');
            }
        }
    }

    public function changePassword()
    {
        if (isset($_POST['change'])) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');
            $this->form_validation->set_message('required', '{field} wajib diisi');
            $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
            $this->form_validation->set_message('matches', '{field} tidak sama dengan {param}');
            $this->form_validation->set_error_delimiters('<small class="text-white">', '</small>');
            $this->form_validation->error_array();
            if ($this->form_validation->run() == false) {
                return $this->index();
            } else {
                $roles = $this->session->userdata('roles_id');
                $users = $this->session->userdata('users_id');

                $data = [
                    'password' => md5(htmlspecialchars($this->input->post('password', true))),
                ];
                $update = $this->Users_model->update($data, $users);
                if ($update > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update password', null, 'Admin/Profile');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update password', null, 'Admin/Profile');
                }
            }
        } else {
            show_404();
        }
    }
    private function uploadGambar($jenis_kelamin = '')
    {
        $roles = $this->session->userdata('roles_id');
        if ($roles == 1) {
            $src = 'admin';
        }
        if ($roles == 2) {
            $src = 'guru';
        }
        if ($roles == 1) {
            $src = 'siswa';
        }
        $gambar = $_FILES['gambar']['name'];
        if ($gambar != null) {
            $config['upload_path'] = './vendor/img/' . $src;
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['overwrite'] = true;
            $new_name = rand(1000, 100000) . time() . $_FILES["gambar"]['name'];
            $config['file_name'] = $new_name;
            // $config['max_size'] = 1024;
            // $config['max_width'] = 1024;
            // $config['max_height'] = 768;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('gambar')) {
                echo $this->upload->display_errors();
            } else {
                $gambar = $this->upload->data();
                //Compress Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = './vendor/img/' . $src . '/' . $gambar['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['quality'] = '50%';
                $config['width'] = 600;
                $config['height'] = 600;
                $config['new_image'] = './vendor/img/' . $src . '/' . $gambar['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                return $gambar['file_name'];
            }
        } else {
            if ($jenis_kelamin == 'L') {
                return 'default_male.jpg';
            } else {
                return 'default_female.png';
            }
        }
    }
    private function removeImage($id)
    {
        $roles = $this->session->userdata('roles_id');
        $users = $this->session->userdata('users_id');
        if ($roles == 1) {
            $src = 'admin';
            $select_image = $this->db->get('admin', ['users_id' => $id])->row();
        }
        if ($roles == 2) {
            $src = 'guru';
            $select_image = $this->db->get('guru', ['users_id' => $id])->row();
        }
        if ($roles == 3) {
            $src = 'siswa';
            $select_image = $this->db->get('siswa', ['users_id' => $id])->row();
        }
        if ($select_image != null) {
            if ($select_image->gambar != null) {
                if ($select_image->gambar != 'default_female.png' && $select_image->gambar != 'default_male.jpg') {
                    $filename = explode('.', $select_image->gambar)[0];
                    return array_map('unlink', glob(FCPATH . "vendor/img/" . $src . '/' . $filename . '.*'));
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    public function changeGambar()
    {
        $roles = $this->session->userdata('roles_id');

        if ($roles == 1) {
            $users = $this->session->userdata('users_id');
            $get = $this->db->get_where('admin', ['users_id' => $users])->row();
            $this->removeImage($users);
            $data = [
                'gambar' => $this->uploadGambar($get->jenis_kelamin),
            ];
            $update = $this->db->update('admin', $data, ['users_id' => $users]);
            $update = $this->db->affected_rows();
        }
        if ($roles == 2) {
            $users = $this->session->userdata('users_id');
            $get = $this->db->get_where('guru', ['users_id' => $users])->row();
            $this->removeImage($users);
            $data = [
                'gambar' => $this->uploadGambar($get->jenis_kelamin),
            ];
            $update = $this->db->update('guru', $data, ['users_id' => $users]);
            $update = $this->db->affected_rows();
        }
        if ($roles == 3) {
            $users = $this->session->userdata('users_id');
            $get = $this->db->get_where('siswa', ['users_id' => $users])->row();
            $this->removeImage($users);
            $data = [
                'gambar' => $this->uploadGambar($get->jenis_kelamin),
            ];
            $update = $this->db->update('siswa', $data, ['users_id' => $users]);
            $update = $this->db->affected_rows();
        }
        if ($update > 0) {
            sweetAlert2('success', 'Successfully', 'Berhasil ganti foto profil', null, 'Admin/Profile');
        } else {
            sweetAlert2('error', 'Failed', 'Gagal ganti foto profil', null, 'Admin/Profile');
        }
    }

    public function getData()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id = htmlspecialchars($this->input->post('id_admin', true));
            $get = $this->db->get_where('admin', ['id_admin' => $id]);
            if ($get->num_rows() > 0) {
                $profile = get_my_id()['row'];
                $gambar = '
                <img class="img-thumbnail" src="' . base_url('vendor/img/admin/' . $profile->gambar) . '"></img>
                ';
                $data = [
                    'row' => $profile,
                    'gambar' => $gambar,
                    'tanggal' => ($profile->tanggal_lahir)
                ];
                echo json_encode($data);
            }
        }
    }

    public function getDataGuru()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id = htmlspecialchars($this->input->post('id_guru', true));
            $get = $this->db->get_where('guru', ['id_guru' => $id]);
            if ($get->num_rows() > 0) {
                $profile = get_my_id()['row'];

                $gambar = '
                <img class="img-thumbnail" src="' . base_url('vendor/img/guru/' . $profile->gambar) . '"></img>
                ';
                $data = [
                    'row' => $profile,
                    'gambar' => $gambar,
                    'tanggal' => ($profile->tanggal_lahir)
                ];
                echo json_encode($data);
            }
        }
    }
}
