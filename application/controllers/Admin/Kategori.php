<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Kategori extends CI_Controller
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
        if ($my_roles == 3) {
            show_404();
        }
        $roles = check_roles_crud('Admin/Kategori', $my_roles);
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
        $this->load->model(['Kategori/Kategori_model', 'Kategori/Server_Kategori_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kategori', 'Admin/Kategori');
        // output
        $data['title'] = 'Mangement Kategori';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/kategori/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kategori', 'Admin/Kategori');
        $this->breadcrumbs->push('Form Data', 'Admin/Kategori/add');
        // output
        $data['title'] = 'Form Kategori';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_kategori = null;
        $obj->nama = null;
        $obj->icon = null;
        $obj->link = null;
        $obj->background = null;
        $data['row'] = $obj;
        $data['page'] = 'add';

        $this->template->admin('admin/kategori/form_data', $data);
    }
    public function process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                ];
                $insertUsers = $this->Kategori_model->insert($data);
                if ($insertUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Kategori', null, 'Admin/Kategori');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Kategori', null, 'Admin/Kategori');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_kategori', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_Kategori', true);
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                ];
                $updateUsers = $this->Kategori_model->update($data, $id);
                if ($updateUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Kategori', null, 'Admin/Kategori');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data Kategori', null, 'Admin/Kategori');
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
            $this->breadcrumbs->push('Kategori', 'Admin/Kategori');
            $this->breadcrumbs->push('Form Kategori', 'admin/Kategori/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Kategori';
            $this->template->breadcrumbs($data);
            $get = $this->Kategori_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
            ];
            $this->template->admin('admin/kategori/form_data', $data);
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
            $id_kategori = htmlspecialchars($this->input->post('id', true));
            $Kategori = $this->Kategori_model->delete($id_kategori);
            if ($Kategori > 0) {
                $data = [

                    'id' => $id_kategori,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_kategori,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllKategori()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_kategori = $this->input->post('data_item');
            foreach ($id_kategori as $index => $result) {
                $Kategori = $this->Kategori_model->delete($result);
            }
            if ($Kategori > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_kategori) . ' data',
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
    private function sessionKategori($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_Kategori()
    {
        $list = $this->Server_Kategori_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_Kategori" value="' . $field->id_kategori . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->nama;
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/Kategori/edit/' . $field->id_kategori) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/Kategori/delete/' . $field->id_kategori) . '" class="btn btn-danger delete_Kategori shadow-sm" data-id
                ="' . $field->id_kategori . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
            ' . $this->update . $this->delete . '            
            </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Kategori_model->count_all(),
            "recordsFiltered" => $this->Server_Kategori_model->count_filtered(),
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
            $Kategori_management = $this->Kategori_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->Kategori_model->get($result)->row();
            }
            $Kategori_management = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nama');

        $kolom = 2;
        $nomor = 1;
        foreach ($Kategori_management as $Kategori) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $Kategori->nama);

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
        $spreadsheet->getActiveSheet()->getStyle('A1:A1')->applyFromArray($styleArray_title);


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
        $spreadsheet->getActiveSheet()->getStyle('A1:A' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A1:A' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:A' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Kategori.xlsx"');
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
                    $nama = $sheetData[$i]['0'];


                    $data =  [
                        'nama' => $nama,
                    ];

                    $insert =  $this->Kategori_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Kategori', null, 'Admin/Kategori');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data Kategori', null, 'Admin/Kategori');
            }
        }
    }
}
