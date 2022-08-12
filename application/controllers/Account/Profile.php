<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_sudah_lengkap();
        $this->load->model(['Guru/Guru_model', 'Admin/Admin_model', 'Siswa/Siswa_model']);
    }
    public function index()
    {
        $this->load->view('account/profile');
    }
    public function process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('no_telephone', 'Telephone', 'trim|required|numeric');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('trim', 'tidak boleh ada spasi di depan dan di akhir text pada {field}');
        $this->form_validation->set_message('numeric', 'Karakter {field} harus berupa angka');
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="text-align: left; font-size:12px; ">', '</p>');
        if ($this->form_validation->run() == false) {
            return $this->index();
        } else {
            $allowed = $this->session->userdata('roles_id');
            $pengguna_id = $this->session->userdata('users_id');
            $jenis_kelamin = htmlspecialchars($this->input->post('jenis_kelamin', true));
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'jenis_kelamin' => htmlspecialchars($this->input->post('jenis_kelamin', true)),
                'tempat_lahir' => htmlspecialchars($this->input->post('tempat_lahir', true)),
                'tanggal_lahir' => htmlspecialchars($this->input->post('tanggal_lahir', true)),
                'alamat' => htmlspecialchars($this->input->post('alamat', true)),
                'no_telephone' => htmlspecialchars($this->input->post('no_telephone', true)),
                'gambar' => $this->uploadGambar($jenis_kelamin),
                'keterangan' => 'Lengkap'
            ];
            switch ($allowed) {
                case 1:
                    $insertAccount = $this->Admin_model->updateUsers($data, $pengguna_id);
                    break;
                case 2:
                    $insertAccount = $this->Guru_model->updateUsers($data, $pengguna_id);
                    break;
                case 3:
                    $insertAccount = $this->Siswa_model->updateUsers($data, $pengguna_id);
                    break;
            }
            if ($insertAccount > 0) {
                if ($allowed == 3) {
                    sweetAlert2('success', 'Successfully', 'Berhasil validasi account', null, 'Siswa/Home');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil validasi account', null, 'Admin/Home');
                }
            } else {
                if ($allowed == 3) {
                    sweetAlert2('error', 'Failed', 'Gagal validasi account', null, 'Siswa/Home');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal validasi account', null, 'Admin/Home');
                }
            }
        }
    }
    private function uploadGambar($jenis_kelamin)
    {
        $allowed = $this->session->userdata('roles_id');
        if ($allowed == 1) {
            $folder = 'admin';
        } else if ($allowed == 2) {
            $folder = 'guru';
        } else if ($allowed == 3) {
            $folder = 'siswa';
        }
        $gambar = $_FILES['gambar']['name'];
        if ($gambar != null) {
            $config['upload_path'] = './vendor/img/' . $folder;
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
                $config['source_image'] = './vendor/img/' . $folder . '/' . $gambar['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['quality'] = '50%';
                $config['width'] = 600;
                $config['height'] = 400;
                $config['new_image'] = './vendor/img/' . $folder . '/' . $gambar['file_name'];
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
}
