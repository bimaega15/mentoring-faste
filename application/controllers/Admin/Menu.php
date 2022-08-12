<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Menu extends CI_Controller
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
        $roles = check_roles_crud('Admin/Menu', $my_roles);
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
        $this->load->model(['Menu/Menu_model', 'Menu/Server_Menu_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Menu', 'Admin/Menu');
        // output
        $data['title'] = 'Mangement Menu';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/menu/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Menu', 'Admin/Menu');
        $this->breadcrumbs->push('Form Data', 'Admin/Menu/add');
        // output
        $data['title'] = 'Form Menu';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_menu_management = null;
        $obj->icon = null;
        $obj->nama = null;
        $obj->link = null;
        $obj->urut = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $this->template->admin('admin/menu/form_data', $data);
    }
    public function process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('link', 'Link', 'required|trim');
        $this->form_validation->set_rules('urut', 'urut', 'required|trim');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data = [
                    'icon' => htmlspecialchars($this->input->post('icon', true)),
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'link' => htmlspecialchars($this->input->post('link', true)),
                    'urut' => htmlspecialchars($this->input->post('urut', true)),
                ];
                $insert = $this->Menu_model->insert($data);
                if ($insert > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data menu', null, 'Admin/Menu');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data menu', null, 'Admin/Menu');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_menu', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_menu', true);
                $data = [
                    'icon' => htmlspecialchars($this->input->post('icon', true)),
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'link' => htmlspecialchars($this->input->post('link', true)),
                    'urut' => htmlspecialchars($this->input->post('urut', true)),
                ];
                $update = $this->Menu_model->update($data, $id);
                if ($update > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data menu', null, 'Admin/Menu');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data menu', null, 'Admin/Menu');
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
            $this->breadcrumbs->push('Menu', 'Admin/Menu');
            $this->breadcrumbs->push('Form Menu', 'admin/menu/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Menu';
            $this->template->breadcrumbs($data);
            $get = $this->Menu_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
            ];
            $this->template->admin('admin/menu/form_data', $data);
        }
    }
    private function uploadGambar()
    {
        $gambar = $_FILES['gambar']['name'];
        if ($gambar != null) {
            $config['upload_path'] = './vendor/img/Menu';
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
                $config['source_image'] = './vendor/img/Menu/' . $gambar['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['quality'] = '50%';
                $config['width'] = 600;
                $config['height'] = 600;
                $config['new_image'] = './vendor/img/Menu/' . $gambar['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                return $gambar['file_name'];
            }
        } else {
            return 'default.png';
        }
    }

    private function removeImage($id)
    {
        $select_image = $this->Menu_model->get($id)->row();
        if ($select_image != null) {
            if ($select_image->gambar != 'default.png') {
                $filename = explode('.', $select_image->gambar)[0];
                return array_map('unlink', glob(FCPATH . "vendor/img/Menu/" . $filename . '.*'));
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
            $id_Menu = htmlspecialchars($this->input->post('id', true));
            $Menu = $this->Menu_model->delete($id_Menu);
            if ($Menu > 0) {
                $data = [

                    'id' => $id_Menu,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_Menu,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllMenu()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_Menu = $this->input->post('data_item');
            foreach ($id_Menu as $index => $result) {
                $Menu = $this->Menu_model->delete($result);
            }
            if ($Menu > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_Menu) . ' data',
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
    private function sessionMenu($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_Menu()
    {
        $list = $this->Server_Menu_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_Menu" value="' . $field->id_menu_management . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->icon;
            $row[] = $field->nama;
            $row[] = $field->link;
            $row[] = $field->urut;
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/Menu/edit/' . $field->id_menu_management) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/Menu/delete/' . $field->id_menu_management) . '" class="btn btn-danger delete_Menu shadow-sm" data-id
                ="' . $field->id_menu_management
                    . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group" role="group" aria-label="Basic example">   
            ' . $this->update . $this->delete . '         
            </div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Menu_model->count_all(),
            "recordsFiltered" => $this->Server_Menu_model->count_filtered(),
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
            $menu_management = $this->Menu_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->Menu_model->get($result)->row();
            }
            $menu_management = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Icon')
            ->setCellValue('B1', 'Nama')
            ->setCellValue('C1', 'Link')
            ->setCellValue('D1', 'Urut');

        $kolom = 2;
        $nomor = 1;
        foreach ($menu_management as $menu) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $menu->icon)
                ->setCellValue('B' . $kolom, $menu->nama)
                ->setCellValue('C' . $kolom, $menu->link)
                ->setCellValue('D' . $kolom, $menu->urut);

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
        $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray_title);


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
        $spreadsheet->getActiveSheet()->getStyle('A2:D' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A2:D' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:D' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Menu.xlsx"');
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
                    $icon = $sheetData[$i]['0'];
                    $nama = $sheetData[$i]['1'];
                    $link = $sheetData[$i]['2'];
                    $urut = $sheetData[$i]['3'];
                    $data =  [
                        'icon' => $icon,
                        'nama' => $nama,
                        'link' => $link,
                        'urut' => $urut,
                    ];
                    $insert =  $this->Menu_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data menu', null, 'Admin/Menu');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data menu', null, 'Admin/Menu');
            }
        }
    }
}
