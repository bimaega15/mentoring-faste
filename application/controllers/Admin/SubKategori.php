<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class SubKategori extends CI_Controller
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
        $roles = check_roles_crud('Admin/SubKategori', $my_roles);
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
        $this->load->model(['SubKategori/SubKategori_model', 'SubKategori/Server_SubKategori_model', 'Kategori/Kategori_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('SubKategori', 'Admin/SubKategori');
        // output
        $data['title'] = 'Mangement SubKategori';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/subkategori/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('SubKategori', 'Admin/SubKategori');
        $this->breadcrumbs->push('Form Data', 'Admin/SubKategori/add');
        // output
        $data['title'] = 'Form SubKategori';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_sub_kategori = null;
        $obj->kategori_id = null;
        $obj->nama = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $data['kategori'] = $this->db->get('kategori')->result();
        $this->template->admin('admin/subkategori/form_data', $data);
    }
    public function process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|trim');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'kategori_id' => htmlspecialchars($this->input->post('kategori_id', true)),
                ];
                $insertUsers = $this->SubKategori_model->insert($data);
                if ($insertUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data SubKategori', null, 'Admin/SubKategori');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data SubKategori', null, 'Admin/SubKategori');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_SubKategori', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_SubKategori', true);
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                    'kategori_id' => htmlspecialchars($this->input->post('kategori_id', true)),
                ];
                $updateUsers = $this->SubKategori_model->update($data, $id);
                if ($updateUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data SubKategori', null, 'Admin/SubKategori');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data SubKategori', null, 'Admin/SubKategori');
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
            $this->breadcrumbs->push('SubKategori', 'Admin/SubKategori');
            $this->breadcrumbs->push('Form SubKategori', 'admin/SubKategori/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form SubKategori';
            $this->template->breadcrumbs($data);
            $get = $this->SubKategori_model->get($id)->row();

            $data = [
                'row' => $get,
                "page" => 'edit',
                'kategori' => $this->db->get('kategori')->result()
            ];
            $this->template->admin('admin/subkategori/form_data', $data);
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
            $id_SubKategori = htmlspecialchars($this->input->post('id', true));
            $SubKategori = $this->SubKategori_model->delete($id_SubKategori);
            if ($SubKategori > 0) {
                $data = [

                    'id' => $id_SubKategori,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_SubKategori,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllSubKategori()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_SubKategori = $this->input->post('data_item');
            foreach ($id_SubKategori as $index => $result) {
                $SubKategori = $this->SubKategori_model->delete($result);
            }
            if ($SubKategori > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_SubKategori) . ' data',
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
    private function sessionSubKategori($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_SubKategori()
    {
        $list = $this->Server_SubKategori_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_SubKategori" value="' . $field->id_sub_kategori . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->nama;
            $row[] = check_kategori_id($field->kategori_id);
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/SubKategori/edit/' . $field->id_sub_kategori) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/SubKategori/delete/' . $field->id_sub_kategori) . '" class="btn btn-danger delete_SubKategori shadow-sm" data-id
                ="' . $field->id_sub_kategori . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
            ' . $this->update . $this->delete . '            
            </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_SubKategori_model->count_all(),
            "recordsFiltered" => $this->Server_SubKategori_model->count_filtered(),
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
            $SubKategori_management = $this->SubKategori_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->SubKategori_model->get($result)->row();
            }
            $SubKategori_management = $row;
        }

        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nama')
            ->setCellValue('B1', 'Kategori');

        $kolom = 2;
        $nomor = 1;
        foreach ($SubKategori_management as $SubKategori) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $SubKategori->nama)
                ->setCellValue('B' . $kolom, check_kategori_id($SubKategori->kategori_id));

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
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray_title);


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
        $spreadsheet->getActiveSheet()->getStyle('A1:B' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A1:B' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:B' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Sub Kategori.xlsx"');
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
                    $kategori_id = $sheetData[$i]['1'];


                    $data =  [
                        'nama' => $nama,
                        'kategori_id' => check_namakategori_id($kategori_id),
                    ];

                    $insert =  $this->SubKategori_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data SubKategori', null, 'Admin/SubKategori');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data SubKategori', null, 'Admin/SubKategori');
            }
        }
    }
}
