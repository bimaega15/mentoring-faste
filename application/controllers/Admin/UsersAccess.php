<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class UsersAccess extends CI_Controller
{
    private $update = null;
    private $delete = null;
    private $create = null;
    private $read = null;
    public function __construct()
    {
        parent::__construct();
        check_not_login();
        $my_roles = $this->session->userdata('roles_id');
        if ($my_roles != 1) {
            show_404();
        }
        $roles = check_roles_crud('Admin/UsersAccess', $my_roles);
        if ($roles->update == 'ijin') {
            $this->update = true;
        }
        if ($roles->delete == 'ijin') {
            $this->delete = true;
        }
        if ($roles->create == 'ijin') {
            $this->create = true;
        }
        if ($roles->read == 'ijin') {
            $this->read = true;
        }
        $this->load->model(['UsersAccess/UsersAccess_model', 'UsersAccess/Server_UsersAccess_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Users Access', 'Admin/UsersAccess');
        // output
        $data['title'] = 'Mangement Users Access';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        // add breadcrumbs
        $this->template->admin('admin/usersaccess/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Users Access', 'Admin/UsersAccess');
        $this->breadcrumbs->push('Form Data', 'Admin/UsersAccess/add');
        // output
        $data['title'] = 'Form Users Access';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_users_access_management = null;
        $obj->users_roles_id = null;
        $obj->menu_management_id = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $data['users_roles'] = $this->db->get('roles')->result();
        $data['menu_management'] = $this->db->get('menu_management')->result();
        $this->template->admin('admin/usersaccess/form_data', $data);
    }
    public function process()
    {
        $id = $this->input->post('id_UsersAccess', true);
        if ($id == null) {
            $users_roles_id = $this->input->post('users_roles_id');
            $menu = $this->input->post('menu_management_id');
            foreach ($users_roles_id as $result) {
                $cek = $this->db->get_where('users_access_management', ['users_roles_id' => $result, 'menu_management_id' => $menu]);
                if ($cek->num_rows() > 0) {
                    sweetAlert2('error', 'Failed', 'Users Management sudah di input', null, 'Admin/UsersAccess');
                    exit();
                }
            }
        }
        $this->form_validation->set_rules('users_roles_id[]', 'Users Roles', 'required|trim|numeric');
        $this->form_validation->set_rules('menu_management_id', 'Menu Management', 'required|trim|numeric');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data_users_roles_id = $this->input->post('users_roles_id', true);
                foreach ($data_users_roles_id as $result) {
                    if ($result == 1) {
                        $data = [
                            'menu_management_id' => htmlspecialchars($this->input->post('menu_management_id', true)),
                            'users_roles_id' => htmlspecialchars($result),
                            'create' => htmlspecialchars($this->input->post('create_admin', true)),
                            'read' => htmlspecialchars($this->input->post('read_admin', true)),
                            'update' => htmlspecialchars($this->input->post('update_admin', true)),
                            'delete' => htmlspecialchars($this->input->post('delete_admin', true)),
                        ];
                        $insertUsers = $this->UsersAccess_model->insert($data);
                    }
                    if ($result == 2) {
                        $data = [
                            'menu_management_id' => htmlspecialchars($this->input->post('menu_management_id', true)),
                            'users_roles_id' => htmlspecialchars($result),
                            'create' => htmlspecialchars($this->input->post('create_guru', true)),
                            'read' => htmlspecialchars($this->input->post('read_guru', true)),
                            'update' => htmlspecialchars($this->input->post('update_guru', true)),
                            'delete' => htmlspecialchars($this->input->post('delete_guru', true)),
                        ];
                        $insertUsers = $this->UsersAccess_model->insert($data);
                    }
                    if ($result == 3) {
                        $data = [
                            'menu_management_id' => htmlspecialchars($this->input->post('menu_management_id', true)),
                            'users_roles_id' => htmlspecialchars($result),
                            'create' => htmlspecialchars($this->input->post('create_siswa', true)),
                            'read' => htmlspecialchars($this->input->post('read_siswa', true)),
                            'update' => htmlspecialchars($this->input->post('update_siswa', true)),
                            'delete' => htmlspecialchars($this->input->post('delete_siswa', true)),
                        ];
                        $insertUsers = $this->UsersAccess_model->insert($data);
                    }
                }
                if ($insertUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Users Access', null, 'Admin/UsersAccess');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Users Access', null, 'Admin/UsersAccess');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_UsersAccess', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_UsersAccess', true);
                $data_users_roles_id = $this->input->post('users_roles_id', true);
                foreach ($data_users_roles_id as $result) {
                    if ($result == 1) {
                        $data = [
                            'menu_management_id' => htmlspecialchars($this->input->post('menu_management_id', true)),
                            'users_roles_id' => htmlspecialchars($result),
                            'create' => htmlspecialchars($this->input->post('create_admin', true)),
                            'read' => htmlspecialchars($this->input->post('read_admin', true)),
                            'update' => htmlspecialchars($this->input->post('update_admin', true)),
                            'delete' => htmlspecialchars($this->input->post('delete_admin', true)),
                        ];
                        $updateUsers = $this->UsersAccess_model->update($data, $id);
                    }
                    if ($result == 2) {
                        $data = [
                            'menu_management_id' => htmlspecialchars($this->input->post('menu_management_id', true)),
                            'users_roles_id' => htmlspecialchars($result),
                            'create' => htmlspecialchars($this->input->post('create_guru', true)),
                            'read' => htmlspecialchars($this->input->post('read_guru', true)),
                            'update' => htmlspecialchars($this->input->post('update_guru', true)),
                            'delete' => htmlspecialchars($this->input->post('delete_guru', true)),
                        ];
                        $updateUsers = $this->UsersAccess_model->update($data, $id);
                    }
                    if ($result == 3) {
                        $data = [
                            'menu_management_id' => htmlspecialchars($this->input->post('menu_management_id', true)),
                            'users_roles_id' => htmlspecialchars($result),
                            'create' => htmlspecialchars($this->input->post('create_siswa', true)),
                            'read' => htmlspecialchars($this->input->post('read_siswa', true)),
                            'update' => htmlspecialchars($this->input->post('update_siswa', true)),
                            'delete' => htmlspecialchars($this->input->post('delete_siswa', true)),
                        ];
                        $updateUsers = $this->UsersAccess_model->update($data, $id);
                    }
                }
                if ($updateUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Users Access', null, 'Admin/UsersAccess');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data Users Access', null, 'Admin/UsersAccess');
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
            $this->breadcrumbs->push('Users Access', 'Admin/UsersAccess');
            $this->breadcrumbs->push('Form Users Access', 'admin/UsersAccess/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form UsersAccess';
            $this->template->breadcrumbs($data);
            $get = $this->UsersAccess_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
                'users_roles' => $this->db->get('roles')->result(),
                'menu_management' => $this->db->get('menu_management')->result()
            ];
            $this->template->admin('admin/usersaccess/form_data', $data);
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
            $id_UsersAccess = htmlspecialchars($this->input->post('id', true));
            $UsersAccess = $this->UsersAccess_model->delete($id_UsersAccess);
            if ($UsersAccess > 0) {
                $data = [

                    'id' => $id_UsersAccess,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_UsersAccess,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllUsersAccess()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_UsersAccess = $this->input->post('data_item');
            foreach ($id_UsersAccess as $index => $result) {
                $UsersAccess = $this->UsersAccess_model->delete($result);
            }
            if ($UsersAccess > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_UsersAccess) . ' data',
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
    private function sessionUsersAccess($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_UsersAccess()
    {
        $list = $this->Server_UsersAccess_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_UsersAccess" value="' . $field->id_users_access_management . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = ($field->nama_roles);
            $row[] = ($field->link_menu);
            $access = $field->create == "ijin" ? "<span class='badge badge-info'>Create: Ijin</span>" : "<span class='badge badge-info'>Create: Tidak Ijin</span>";
            $access .= $field->read == "ijin" ? "<span class='badge badge-info'>Read: Ijin</span>" : "<span class='badge badge-info'>Read: Tidak Ijin</span>";
            $access .= $field->update == "ijin" ? "<span class='badge badge-info'>Update: Ijin</span>" : "<span class='badge badge-info'>Update: Tidak Ijin</span>";
            $access .= $field->delete == "ijin" ? "<span class='badge badge-info'>Delete: Ijin</span>" : "<span class='badge badge-info'>Delete: Tidak Ijin</span>";
            $row[] = $access;
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/UsersAccess/edit/' . $field->id_users_access_management) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '  <a href="' . base_url('Admin/UsersAccess/delete/' . $field->id_users_access_management) . '" class="btn btn-danger delete_UsersAccess shadow-sm" data-id
                ="' . $field->id_users_access_management . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group" role="group" aria-label="Basic example">  
            ' . $this->update . $this->delete . '        
            </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_UsersAccess_model->count_all(),
            "recordsFiltered" => $this->Server_UsersAccess_model->count_filtered(),
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
            $UsersAccess_management = $this->UsersAccess_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->UsersAccess_model->get($result)->row();
            }
            $UsersAccess_management = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Users Roles')
            ->setCellValue('B1', 'Menu Management')
            ->setCellValue('C1', 'Create')
            ->setCellValue('D1', 'Read')
            ->setCellValue('E1', 'Update')
            ->setCellValue('F1', 'Delete');

        $kolom = 2;
        $nomor = 1;
        foreach ($UsersAccess_management as $UsersAccess) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, check_roles($UsersAccess->users_roles_id))
                ->setCellValue('B' . $kolom, check_menu_management($UsersAccess->menu_management_id))
                ->setCellValue('C' . $kolom, $UsersAccess->create)
                ->setCellValue('D' . $kolom, $UsersAccess->read)
                ->setCellValue('E' . $kolom, $UsersAccess->update)
                ->setCellValue('F' . $kolom, $UsersAccess->delete);

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
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray_title);


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
        $spreadsheet->getActiveSheet()->getStyle('A2:F' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A2:F' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:F' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Users Access.xlsx"');
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
                    $users_roles_id_cek = check_roles_convert($sheetData[$i]['0']);
                    $menu_cek = check_menu_management_convert($sheetData[$i]['1']);
                    $cek = $this->db->get_where('users_access_management', ['users_roles_id' => $users_roles_id_cek, 'menu_management_id' => $menu_cek]);

                    if ($cek->num_rows() > 0) {
                        sweetAlert2('error', 'Failed', 'Users Management sudah di input', null, 'Admin/UsersAccess');
                        exit();
                    }



                    $count[] = $i;
                    $users_roles_id = $sheetData[$i]['0'];
                    $menu_management_id = $sheetData[$i]['1'];
                    $create = $sheetData[$i]['2'];
                    $read = $sheetData[$i]['3'];
                    $update = $sheetData[$i]['4'];
                    $delete = $sheetData[$i]['5'];

                    $data =  [
                        'users_roles_id' => check_roles_convert($users_roles_id),
                        'menu_management_id' => check_menu_management_convert($menu_management_id),
                        'create' => $create,
                        'read' => $read,
                        'update' => $update,
                        'delete' => $delete,
                    ];
                    $insert =  $this->UsersAccess_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data UsersAccess', null, 'Admin/UsersAccess');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data UsersAccess', null, 'Admin/UsersAccess');
            }
        }
    }
}
