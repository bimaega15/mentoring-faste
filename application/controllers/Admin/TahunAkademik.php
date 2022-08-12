<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class TahunAkademik extends CI_Controller
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
        $roles = check_roles_crud('Admin/TahunAkademik', $my_roles);
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
        $this->load->model(['TahunAkademik/TahunAkademik_model', 'TahunAkademik/Server_TahunAkademik_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Tahun Akademik', 'Admin/TahunAkademik');
        // output
        $data['title'] = 'Mangement Tahun Akademik';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        // add breadcrumbs
        $this->template->admin('admin/tahunakademik/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Tahun Akademik', 'Admin/TahunAkademik');
        $this->breadcrumbs->push('Form Data', 'Admin/TahunAkademik/add');
        // output
        $data['title'] = 'Form Tahun Akademik';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_tahun_akademik = null;
        $obj->nama = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $this->template->admin('admin/tahunakademik/form_data', $data);
    }
    public function process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                ];
                $insert = $this->TahunAkademik_model->insert($data);
                if ($insert > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Tahun Akademik', null, 'Admin/TahunAkademik');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Tahun Akademik', null, 'Admin/TahunAkademik');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_TahunAkademik', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_TahunAkademik', true);
                $data = [
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                ];
                $update = $this->TahunAkademik_model->update($data, $id);
                if ($update > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Tahun Akademik', null, 'Admin/TahunAkademik');
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data Tahun Akademik', null, 'Admin/TahunAkademik');
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
            $this->breadcrumbs->push('Tahun Akademik', 'Admin/TahunAkademik');
            $this->breadcrumbs->push('Form TahunAkademik', 'admin/TahunAkademik/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Tahun Akademik';
            $this->template->breadcrumbs($data);
            $get = $this->TahunAkademik_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
            ];
            $this->template->admin('admin/tahunakademik/form_data', $data);
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
            $id_TahunAkademik = htmlspecialchars($this->input->post('id', true));
            $TahunAkademik = $this->TahunAkademik_model->delete($id_TahunAkademik);
            if ($TahunAkademik > 0) {
                $data = [

                    'id' => $id_TahunAkademik,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_TahunAkademik,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllTahunAkademik()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_TahunAkademik = $this->input->post('data_item');
            foreach ($id_TahunAkademik as $index => $result) {
                $TahunAkademik = $this->TahunAkademik_model->delete($result);
            }
            if ($TahunAkademik > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_TahunAkademik) . ' data',
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
    private function sessionTahunAkademik($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_TahunAkademik()
    {
        $list = $this->Server_TahunAkademik_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_TahunAkademik" value="' . $field->id_tahun_akademik . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->nama;
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/TahunAkademik/edit/' . $field->id_tahun_akademik) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/TahunAkademik/delete/' . $field->id_tahun_akademik) . '" class="btn btn-danger delete_TahunAkademik shadow-sm" data-id
                ="' . $field->id_tahun_akademik
                    . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group" role="group" aria-label="Basic example">
            ' . $this->update . $this->delete . '
            </div>
                ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_TahunAkademik_model->count_all(),
            "recordsFiltered" => $this->Server_TahunAkademik_model->count_filtered(),
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
            $TahunAkademik_management = $this->TahunAkademik_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->TahunAkademik_model->get($result)->row();
            }
            $TahunAkademik_management = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tahun Akademik');

        $kolom = 2;
        $nomor = 1;
        foreach ($TahunAkademik_management as $TahunAkademik) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $TahunAkademik->nama);

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
        $spreadsheet->getActiveSheet()->getStyle('A2:A' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A2:A' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:A' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Tahun Akademik.xlsx"');
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
                    $insert =  $this->TahunAkademik_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Tahun Akademik', null, 'Admin/TahunAkademik');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data Tahun Akademik', null, 'Admin/TahunAkademik');
            }
        }
    }
}
