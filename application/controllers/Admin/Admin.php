<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Admin extends CI_Controller
{
    private $update = null;
    private $delete = null;
    private $read = null;
    private $create = null;
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles != 1) {
            show_404();
        }
        $roles = check_roles_crud('Admin/Admin', $my_roles);
        if ($roles->update == 'ijin') {
            $this->update = true;
        }
        if ($roles->delete == 'ijin') {
            $this->delete = true;
        }
        if ($roles->read == 'ijin') {
            $this->read = true;
        }
        if ($roles->create == 'ijin') {
            $this->create = true;
        }

        $this->load->model(['Admin/Admin_model', 'Admin/Server_Admin_model', 'Auth/Users_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Admin', 'Admin/Admin');
        // output
        $data['title'] = 'Mangement Admin';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/admin/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Admin', 'Admin/Admin');
        $this->breadcrumbs->push('Form Data', 'Admin/Admin/add');
        // output
        $data['title'] = 'Form Admin';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_admin = null;
        $obj->nomor_induk = null;
        $obj->nama = null;
        $obj->password = null;
        $obj->no_telephone = null;
        $obj->tempat_lahir = null;
        $obj->tanggal_lahir = null;
        $obj->keterangan = null;
        $obj->jenis_kelamin = null;
        $obj->alamat = null;
        $obj->gambar = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $this->template->admin('admin/admin/form_data', $data);
    }
    public function process()
    {
        $keterangan = $this->input->post('keterangan');
        if (isset($_POST['add'])) {
            $this->form_validation->set_rules('nomor_induk', 'Nomor Induk', 'required|trim|numeric|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|min_length[6]|matches[password]');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
            if ($keterangan == 'Lengkap') {
                $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
                $this->form_validation->set_rules('tempat_lahir', 'Tempat lahir', 'trim|required');
                $this->form_validation->set_rules('tanggal_lahir', 'Tanggal lahir', 'trim|required');
                $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
                $this->form_validation->set_rules('no_telephone', 'No telephone', 'trim|numeric|required');
                $this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'trim|alpha|required');
            }
            $this->form_validation->set_message('required', '{field} wajib diisi');
            $this->form_validation->set_message('alpha', '{field} hanya boleh berupa huruf');
            $this->form_validation->set_message('min_length', '{field} harus lebih dari 6 huruf');
            $this->form_validation->set_message('matches', '{field} tidak sama dengan {param}');
            $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
            $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        } else {
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|min_length[6]|matches[password]');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

            if ($keterangan == 'Lengkap') {
                $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
                $this->form_validation->set_rules('tempat_lahir', 'Tempat lahir', 'trim|required');
                $this->form_validation->set_rules('tanggal_lahir', 'Tanggal lahir', 'trim|required');
                $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
                $this->form_validation->set_rules('no_telephone', 'No telephone', 'trim|numeric|required');
                $this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'trim|alpha|required');
            }
            $this->form_validation->set_message('required', '{field} wajib diisi');
            $this->form_validation->set_message('alpha', '{field} hanya boleh berupa huruf');
            $this->form_validation->set_message('min_length', '{field} harus lebih dari 6 huruf');
            $this->form_validation->set_message('matches', '{field} tidak sama dengan {param}');
            $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
            $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        }

        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $keterangan = $this->input->post('keterangan');
                $jenis_kelamin = $this->input->post('jenis_kelamin');
                $data = [
                    'username' => htmlspecialchars(trim($this->input->post('nomor_induk', true))),
                    'password' => md5(htmlspecialchars($this->input->post('password', true))),
                    'status' => 'active',

                ];
                $insertUsers = $this->Users_model->insert($data);
                $data_users_roles = [
                    'users_id' => $insertUsers,
                    'roles_id' => 1
                ];
                $this->db->insert('users_roles', $data_users_roles);

                if ($keterangan != 'Lengkap') {
                    $data = [
                        'nomor_induk' => htmlspecialchars($this->input->post('nomor_induk', true)),
                        'users_id' => $insertUsers,
                        'keterangan' => htmlspecialchars($this->input->post('keterangan')),
                    ];
                } else {
                    $data = [
                        'nomor_induk' => htmlspecialchars($this->input->post('nomor_induk', true)),
                        'nama' => htmlspecialchars($this->input->post('nama', true)),
                        'tempat_lahir' => htmlspecialchars($this->input->post('tempat_lahir')),
                        'tanggal_lahir' => htmlspecialchars($this->input->post('tanggal_lahir')),
                        'alamat' => htmlspecialchars(trim($this->input->post('alamat'))),
                        'no_telephone' => htmlspecialchars($this->input->post('no_telephone')),
                        'jenis_kelamin' => htmlspecialchars($this->input->post('jenis_kelamin')),
                        'users_id' => $insertUsers,
                        'keterangan' => htmlspecialchars($this->input->post('keterangan')),
                        'gambar' => $this->uploadGambar($jenis_kelamin)
                    ];
                }
                $insert = $this->Admin_model->insert($data);
                if ($insert > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Admin', null, 'Admin/Admin');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Admin', null, 'Admin/Admin');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_Admin', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_Admin', true);
                $jenis_kelamin = $this->input->post('jenis_kelamin');
                $gambar = $_FILES['gambar']['name'];
                $old_gambar = $this->input->post('old_gambar');
                if ($gambar == null) {
                    $update_gambar = $old_gambar;
                } else {
                    $this->removeImage($id);
                    $update_gambar = $this->uploadGambar($jenis_kelamin);
                }
                $get = $this->Admin_model->get($id)->row();
                if ($get->jenis_kelamin != $jenis_kelamin) {
                    if ($jenis_kelamin == 'L') {
                        $update_gambar = 'default_male.jpg';
                    } else {
                        $update_gambar = 'default_female.png';
                    }
                }
                $password = $this->input->post('password');
                $updateUsers = null;
                if ($password != null) {
                    $data = [
                        'password' => md5(htmlspecialchars($this->input->post('password', true)))
                    ];
                    $updateUsers = $this->Users_model->update($data, $get->users_id);
                }
                $keterangan = $this->input->post('keterangan');
                if ($keterangan != 'Lengkap') {
                    $data = [
                        'nama' => null,
                        'tempat_lahir' => null,
                        'tanggal_lahir' => null,
                        'alamat' => null,
                        'no_telephone' => null,
                        'jenis_kelamin' => null,
                        'keterangan' => htmlspecialchars($this->input->post('keterangan')),
                        'gambar' => $update_gambar
                    ];
                } else {
                    $data = [
                        'nama' => htmlspecialchars($this->input->post('nama', true)),
                        'tempat_lahir' => htmlspecialchars($this->input->post('tempat_lahir')),
                        'tanggal_lahir' => htmlspecialchars($this->input->post('tanggal_lahir')),
                        'alamat' => htmlspecialchars(trim($this->input->post('alamat'))),
                        'no_telephone' => htmlspecialchars($this->input->post('no_telephone')),
                        'jenis_kelamin' => htmlspecialchars($this->input->post('jenis_kelamin')),
                        'keterangan' => htmlspecialchars($this->input->post('keterangan')),
                        'gambar' => $update_gambar

                    ];
                }
                $update = $this->Admin_model->update($data, $id);
                if ($update > 0 || $updateUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Admin', null, 'Admin/Admin');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data Admin', null, 'Admin/Admin');
                }
            }
        } else {
            show_404();
        }
    }
    public function edit($id)
    {
        if ($this->update != true) {
            show_404();
        }
        if (!isset($id)) {
            show_404();
        } else {
            // add breadcrumbs
            $this->breadcrumbs->push('Home', 'Admin/Home');
            $this->breadcrumbs->push('Admin', 'Admin/Admin');
            $this->breadcrumbs->push('Form Admin', 'Admin/Admin/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Admin';
            $this->template->breadcrumbs($data);
            $get = $this->Admin_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
            ];
            $this->template->admin('admin/admin/form_data', $data);
        }
    }
    private function uploadGambar($jenis_kelamin = '')
    {
        $gambar = $_FILES['gambar']['name'];
        if ($gambar != null) {
            $config['upload_path'] = './vendor/img/admin';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['overwrite'] = true;
            $new_name = rand(1000, 100000) . time() . $_FILES["gambar"]['name'];
            $config['file_name'] = $new_name;
            // $config['max_size'] = 1024;
            // $config['max_width'] = 1024;
            // $config['max_height'] = 768;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('gambar')) {
                echo $this->upload->display_errors();
            } else {
                $gambar = $this->upload->data();
                //Compress Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = './vendor/img/admin/' . $gambar['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['quality'] = '50%';
                $config['width'] = 600;
                $config['height'] = 600;
                $config['new_image'] = './vendor/img/admin/' . $gambar['file_name'];
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
        $select_image = $this->Admin_model->get($id)->row();
        if ($select_image != null) {
            if ($select_image->gambar != null) {
                if ($select_image->gambar != 'default_female.png' && $select_image->gambar != 'default_male.jpg') {
                    $filename = explode('.', $select_image->gambar)[0];
                    return array_map('unlink', glob(FCPATH . "vendor/img/admin/" . $filename . '.*'));
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    public function delete()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_Admin = htmlspecialchars($this->input->post('id', true));
            $this->removeImage($id_Admin);
            $row = $this->Admin_model->get($id_Admin)->row();
            $Admin = $this->Admin_model->delete($id_Admin);
            $this->db->delete('users', ['id_users' => $row->users_id]);
            $this->db->delete('users_roles', ['users_id' => $row->users_id]);
            if ($Admin > 0) {
                $data = [
                    'id' => $id_Admin,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_Admin,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllAdmin()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_Admin = $this->input->post('data_item');
            foreach ($id_Admin as $index => $result) {
                $this->removeImage($result);
                $row = $this->Admin_model->get($result)->row();
                $Admin = $this->Admin_model->delete($result);
                $this->db->delete('users', ['id_users' => $row->users_id]);
                $this->db->delete('users_roles', ['users_id' => $row->users_id]);
            }
            if ($Admin > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_Admin) . ' data',
                    'status' => 'success'
                ];
            } else {
                $data = [
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    private function sessionAdmin($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_Admin()
    {
        $list = $this->Server_Admin_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_Admin" value="' . $field->id_admin . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->nomor_induk;
            $row[] = $field->nama;
            $row[] = $field->alamat;
            $row[] = $field->no_telephone;
            if ($field->jenis_kelamin != null) {
                $row[] = $field->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
            } else {
                $row[] = $field->jenis_kelamin;
            }
            $row[] = $field->keterangan == 'Lengkap' ? '<span class="badge badge-success">Lengkap</span>' : '<span class="badge badge-danger">Tidak Lengkap</span>';
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/Admin/edit/' . $field->id_admin) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/Admin/delete/' . $field->id_admin) . '" class="btn btn-danger delete_Admin shadow-sm" data-id
                ="' . $field->id_admin
                    . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            if ($this->read) {
                $this->read = '<a href="' . base_url('Admin/Admin/detail/' . $field->id_admin) . '" class="btn btn-primary shadow-sm detailAdmin" data-id="' . $field->id_admin . '"  data-toggle="modal" data-target="#modalAdminDetail"> <i class="fas fa-eye"></i></a>';
            }
            $row[] = '
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
            ' . $this->read . $this->update . $this->delete . '            
            </div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Admin_model->count_all(),
            "recordsFiltered" => $this->Server_Admin_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function export()
    {
        if ($this->create != true) {
            show_404();
        }
        $get = $this->input->get('checked');
        if ($get == null) {
            $Admin = $this->Admin_model->joinAllUsers()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->Admin_model->joinAllUsers($result)->row();
            }
            $Admin = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nomor Induk')
            ->setCellValue('B1', 'Nama')
            ->setCellValue('C1', 'Tempat lahir')
            ->setCellValue('D1', 'Tanggal lahir')
            ->setCellValue('E1', 'Alamat')
            ->setCellValue('F1', 'No. Telephone')
            ->setCellValue('G1', 'Jenis kelamin')
            ->setCellValue('H1', 'Keterangan')
            ->setCellValue('I1', 'Username')
            ->setCellValue('J1', 'Password')
            ->setCellValue('K1', 'Status')
            ->setCellValue('L1', 'Roles')
            ->setCellValue('M1', 'Gambar');

        $kolom = 2;
        $nomor = 1;
        foreach ($Admin as $result) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $result->nomor_induk)
                ->setCellValue('B' . $kolom, $result->nama_admin)
                ->setCellValue('C' . $kolom, $result->tempat_lahir)
                ->setCellValue('D' . $kolom, tanggal_indo($result->tanggal_lahir))
                ->setCellValue('E' . $kolom, $result->alamat)
                ->setCellValue('F' . $kolom, $result->no_telephone)
                ->setCellValue('G' . $kolom, check_jk($result->jenis_kelamin))
                ->setCellValue('H' . $kolom, $result->keterangan)
                ->setCellValue('I' . $kolom, $result->username)
                ->setCellValue('J' . $kolom, $result->password)
                ->setCellValue('K' . $kolom, $result->status)
                ->setCellValue('L' . $kolom, check_roles($result->id_roles))
                ->setCellValue('M' . $kolom, $result->gambar);

            $kolom++;
            $nomor++;
        }

        $styleArray_title = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleArray_title);


        $styleArrayColumn = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $kolom = $kolom - 1;
        $spreadsheet->getActiveSheet()->getStyle('A2:M' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A2:M' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:M' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(30);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Admin.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    public function importData()
    {
        if ($this->create != true) {
            show_404();
        }
        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new Xlsximport;
            }

            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            for ($i = 1; $i < count($sheetData); $i++) {
                $cek = $sheetData[$i]['0'];
                if ($cek != null) {
                    $count[] = $i;
                    $nomor_induk = $sheetData[$i]['0'];
                    $nama = $sheetData[$i]['1'];
                    $tempat_lahir = $sheetData[$i]['2'];
                    $tanggal_lahir = tanggal_indo_convert($sheetData[$i]['3']);
                    $alamat = $sheetData[$i]['4'];
                    $no_telephone = $sheetData[$i]['5'];
                    $jenis_kelamin = check_jk_convert($sheetData[$i]['6']);
                    $keterangan = $sheetData[$i]['7'];
                    $username = $sheetData[$i]['8'];
                    $password = md5($sheetData[$i]['9']);
                    $status = $sheetData[$i]['10'];
                    $roles = check_roles_convert($sheetData[$i]['11']);
                    $gambar = $sheetData[$i]['12'];
                    $cekUsername = $this->db->get_where('users', ['username' => $username]);
                    if ($cekUsername->num_rows() > 0) {
                        sweetAlert2('error', 'Failed', 'Username ' . $username . ' sudah digunakan', null, 'Admin/Admin');
                        exit();
                    }
                    $data_users = [
                        'username' => $username,
                        'password' => $password,
                        'status' => $status,
                    ];
                    $insertUsers = $this->Users_model->insert($data_users);

                    $data_users_roles = [
                        'users_id' => $insertUsers,
                        'roles_id' => $roles,
                    ];
                    $this->db->insert('users_roles', $data_users_roles);

                    $data =  [
                        'nomor_induk' => $nomor_induk,
                        'nama' => $nama,
                        'tempat_lahir' => $tempat_lahir,
                        'tanggal_lahir' => $tanggal_lahir,
                        'alamat' => $alamat,
                        'no_telephone' => $no_telephone,
                        'jenis_kelamin' => $jenis_kelamin,
                        'keterangan' => $keterangan,
                        'users_id' => $insertUsers,
                        'gambar' => $gambar
                    ];
                    $insert =  $this->Admin_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Admin', null, 'Admin/Admin');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data Admin', null, 'Admin/Admin');
            }
        }
    }
    public function detail()
    {
        if ($this->read != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id = htmlspecialchars($this->input->post('id', true));
            $row = $this->Admin_model->get($id)->row();
            $row_users = $this->Users_model->get($row->users_id)->row();
            $row_roles = $this->db->get_where('users_roles', ['users_id' => $row_users->id_users])->row();
            $row_roles_account = $this->db->get_where('roles', ['id_roles' => $row_roles->roles_id])->row();
            $jenis_kelamin = $row->jenis_kelamin == "L" ? "Laki laki" : "Perempuan";
            if ($row->keterangan == 'Lengkap') {
                $output = '
                <h4>Biodata Admin</h4>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td>Nomor induk</td>
                            <td>:</td>
                            <td>' . $row->nomor_induk . '</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>' . $row->nama . '</td>
                        </tr>
                        <tr>
                            <td>Tempat lahir</td>
                            <td>:</td>
                            <td>' . $row->tempat_lahir . '</td>
                        </tr>
                        <tr>
                            <td>Tanggal lahir</td>
                            <td>:</td>
                            <td>' . tanggal_indo($row->tanggal_lahir) . '</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>' . $row->alamat . '</td>
                        </tr>
                        <tr>
                            <td>No. Telephone</td>
                            <td>:</td>
                            <td>' . $row->no_telephone . '</td>
                        </tr>
                     
                        <tr>
                            <td>Jenis kelamin</td>
                            <td>:</td>
                            <td>' . $jenis_kelamin . '</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>' . $row->keterangan . '</td>
                        </t>
                        <tr>
                            <td colspan="3" class="text-center"><img src="' . base_url('vendor/img/admin/' . $row->gambar) . '" class="img-fluid img-thumbnail w-50" alt="Gambar admin"></td>
                        </t>
                    </table>
                <h4>Biodata Account</h4>
                <hr>
                    <table class="table table-striped">
                        <tr>
                            <td>Username</td>
                            <td>:</td>
                            <td>' . $row_users->username . '</td>
                        </tr>         
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>' . $row_users->status . '</td>
                        </tr>             
                    </table>
                <h4>Roles</h4>
                <hr>
                    <table class="table table-striped">
                        <tr>
                            <td>Hak akses</td>
                            <td>:</td>
                            <td>' . $row_roles_account->nama . '</td>
                        </tr>          
                    </table>
                </div>
                ';
            } else {
                $output = '
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td colspan="3" class="font-weight-bold text-center">Account belum lengkap</td>
                        </tr>
                    </table>
                </div>
                ';
            }

            echo json_encode($output);
        }
    }
}
