<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
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
        $this->breadcrumbs->push('Profile', 'Siswa/Profile');
        // output
        $data['title'] = 'Manage Profile';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        $data['title'] = 'Profile';
        $this->template->siswa('siswa/profile/main', $data);
    }
    public function getData()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id = htmlspecialchars($this->input->post('id_siswa', true));
            $get = $this->db->get_where('siswa', ['id_siswa' => $id]);
            if ($get->num_rows() > 0) {
                $profile = get_my_id()['row'];
                $gambar = '
                <img class="img-thumbnail" src="' . base_url('vendor/img/siswa/' . $profile->gambar) . '"></img>
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
    public function process()
    {
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|numeric');

        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small><br>');
        if ($this->form_validation->run() == false) {
            return $this->index();
        } else {
            $id_siswa =  htmlspecialchars($this->input->post('id_siswa', true));
            $id_users = $this->db->get_where('siswa', ['id_siswa' => $id_siswa])->row();
            $alamat = htmlspecialchars($this->input->post('alamat', true));
            $telephone = htmlspecialchars($this->input->post('telephone', true));
            $tempat_lahir = htmlspecialchars($this->input->post('tempat_lahir', true));
            $tanggal_lahir = htmlspecialchars($this->input->post('tanggal_lahir', true));
            $jenis_kelamin = htmlspecialchars($this->input->post('jenis_kelamin', true));
            $nama = htmlspecialchars($this->input->post('nama', true));
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
            $update = $this->Siswa_model->update($data, $id_siswa);
            if ($update > 0 || $updateUsers > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil Mengupdate Biodata', '', 'Siswa/Profile');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal Mengupdate Biodata', '', 'Siswa/Profile');
            }
        }
    }

    private function uploadGambar($jenis_kelamin = '')
    {
        $gambar = $_FILES['gambar']['name'];
        if ($gambar != null) {
            $config['upload_path'] = './vendor/img/siswa';
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
                $config['source_image'] = './vendor/img/siswa/' . $gambar['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['quality'] = '50%';
                $config['width'] = 600;
                $config['height'] = 600;
                $config['new_image'] = './vendor/img/siswa/' . $gambar['file_name'];
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
        $select_image = $this->Siswa_model->get($id)->row();
        if ($select_image != null) {
            if ($select_image->gambar != null) {
                if ($select_image->gambar != 'default_female.png' && $select_image->gambar != 'default_male.jpg') {
                    $filename = explode('.', $select_image->gambar)[0];
                    return array_map('unlink', glob(FCPATH . "vendor/img/siswa/" . $filename . '.*'));
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
