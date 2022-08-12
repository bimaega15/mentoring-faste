<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth/Users_model');
    }
    public function index()
    {
        check_already_login();
        $cookie = get_cookie('cookie_login');
        if ($cookie <> '') {
            $rowCookie = $this->Users_model->getCookie($cookie)->row();
            $this->sessionLoginAuth($rowCookie);
        } else {
            $this->template->login('auth/login');
        }
    }
    public function process()
    {
        check_already_login();

        $this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric_spaces|required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim|alpha_numeric_spaces');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('min_length', '{field} diisi minimal sebanyak {param} karakter');
        $this->form_validation->set_message('trim', 'tidak boleh ada spasi di depan dan di akhir text pada {field}');
        $this->form_validation->set_message('alpha_numeric_spaces', 'Karakter {field} tidak didukung');
        $this->form_validation->set_error_delimiters('<small class="text-danger float-left" style="margin-top:-20px; margin-bottom:5px;">', '</small>');
        if ($this->form_validation->run() == false) {
            return $this->index();
        } else {
            $username = htmlspecialchars($this->input->post('username', true));
            $password = htmlspecialchars($this->input->post('password', true));
            $remember_me = htmlspecialchars($this->input->post('remember_me', true));
            $row = $this->Users_model->getUsersGroup(null, $username)->row();

            if ($row != null) {
                if ($row->username == $username) {
                    if (md5($password) == $row->password) {
                        if ($remember_me != null) {
                            $key = hash('sha256', $row->username);
                            set_cookie('cookie_login', $key, 60 * 60 * 24);
                            // simpan ke database
                            $updateKey = [
                                'cookie' => $key,
                            ];
                            $update = $this->Users_model->update($updateKey, $row->id_users);
                        }
                        $this->sessionLoginAuth($row);
                    } else {
                        sweetAlert2('error', 'Failed!', 'Password anda salah', null, 'Login');
                    }
                } else {
                    sweetAlert2('error', 'Failed!', 'Username anda salah', null, 'Login');
                }
            } else {
                sweetAlert2('error', 'Failed', 'Username tidak ditemukan', null, 'Login');
            }
        }
    }
    public function forgot()
    {
        check_already_login();
        $this->template->login('auth/forgot');
    }

    private function sessionLoginAuth($row)
    {
        if ($row->roles_id == '1') {
            $get = $this->db->get_where('admin', ['users_id' => $row->users_id])->row();
            if ($get->keterangan == 'Tidak lengkap') {
                $sess = array(
                    'logged' => TRUE,
                    'users_id' => $row->id_users,
                    'roles_id' => $row->id_roles,
                );
                $this->session->set_userdata($sess);
                redirect('Account/Profile');
            }
        }
        if ($row->roles_id == '2') {
            $get = $this->db->get_where('guru', ['users_id' => $row->users_id])->row();
            if ($get->keterangan == 'Tidak lengkap') {
                $sess = array(
                    'logged' => TRUE,
                    'users_id' => $row->id_users,
                    'roles_id' => $row->id_roles,
                );
                $this->session->set_userdata($sess);
                redirect('Account/Profile');
            }
        }
        if ($row->roles_id == '3') {
            $get = $this->db->get_where('siswa', ['users_id' => $row->users_id])->row();
            if ($get->keterangan == 'Tidak lengkap') {
                $sess = array(
                    'logged' => TRUE,
                    'users_id' => $row->id_users,
                    'roles_id' => $row->id_roles,
                );
                $this->session->set_userdata($sess);
                redirect('Account/Profile');
            }
        }
        if ($row->status != 'active') {
            sweetAlert2('error', 'Failed', 'Account anda belum di aktivasi silahkan hubungi admin', 'No. Wa Admin: 082387288927', 'Login');
        } else {
            $sess = array(
                'logged' => TRUE,
                'users_id' => $row->id_users,
                'roles_id' => $row->id_roles,
            );
            $this->session->set_userdata($sess);
            $cookie = get_cookie('cookie_login');
            if ($cookie != null) {
                if ($this->session->userdata('roles_id') == 3) {
                    redirect('Siswa/Home');
                } else {
                    redirect('Admin/Home');
                }
            } else {
                if ($this->session->userdata('roles_id') == 3) {
                    sweetAlert2('success', 'Successfully', 'Berhasil login', null, 'Siswa/Home');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil login', null, 'Admin/Home');
                }
            }
        }
        return redirect(base_url('Login'));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        delete_cookie('cookie_login');
        redirect('Login');
    }

    public function process_forgot()
    {
        check_already_login();

        $this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric_spaces|required');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('trim', 'tidak boleh ada spasi di depan dan di akhir text pada {field}');
        $this->form_validation->set_message('alpha_numeric_spaces', 'Karakter {field} tidak didukung');
        $this->form_validation->set_error_delimiters('<small class="text-danger float-left" style="margin-top:-20px; margin-bottom:5px;">', '</small>');
        if ($this->form_validation->run() == false) {
            return $this->forgot();
        } else {
            $username = htmlspecialchars($this->input->post('username', true));
            $data = [
                'forgot' => 1
            ];
            $update = $this->db->update('users', $data, ['username' => $username]);
            $update = $this->db->affected_rows();
            if ($update > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil mengajukan reset password', null, 'Login/forgot');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal mengajukan reset password', null, 'Login/forgot');
            }
        }
    }
}
