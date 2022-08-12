<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class KelasGuru extends CI_Controller
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
        $roles = check_roles_crud('Admin/Kelas', $my_roles);
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
        $this->load->model(['KelasGuru/KelasGuru_model', 'KelasGuru/Server_KelasGuru_model', 'Auth/Users_model']);
    }
    public function index($id_kelas)
    {
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Kelas');
        $this->breadcrumbs->push('Kelas Guru', 'Admin/KelasGuru');
        // output
        $data['title'] = 'Mangement Kelas Guru';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $data['id_kelas'] = $id_kelas;
        $this->template->admin('admin/kelasguru/main', $data);
    }
    public function add($id_kelas)
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Kelas');
        $this->breadcrumbs->push('KelasGuru', 'Admin/KelasGuru/index/' . $id_kelas);
        $this->breadcrumbs->push('Form Data', 'Admin/KelasGuru/add/' . $id_kelas);
        // output
        $data['title'] = 'Form KelasGuru';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_kelas_detail = null;
        $obj->kelas_id = null;
        $obj->guru_id = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $data['guru'] = $this->db->get('guru')->result();
        $data['id_kelas'] = $id_kelas;
        $this->template->admin('admin/kelasguru/form_data', $data);
    }
    public function process($id_kelas)
    {
        $this->form_validation->set_rules('guru_id', 'Guru', 'required|trim|numeric');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');

        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add($id_kelas);
            } else {
                $guru = htmlspecialchars($this->input->post('guru_id', true));
                $data = [
                    'guru_id' => $guru,
                    'kelas_id' => $id_kelas,
                ];
                $insert = $this->KelasGuru_model->insert($data);

                if ($insert > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Kelas Guru', null, 'Admin/KelasGuru/index/' . $id_kelas);
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Kelas Guru', null, 'Admin/KelasGuru/index/' . $id_kelas);
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_KelasGuru', true));
                return $this->edit($id, $id_kelas);
            } else {
                $id = $this->input->post('id_KelasGuru', true);
                $data = [
                    'guru_id' => htmlspecialchars($this->input->post('guru_id', true)),
                    'kelas_id' => $id_kelas,
                ];
                $update = $this->KelasGuru_model->update($data, $id);

                if ($update > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Kelas Guru', null, 'Admin/KelasGuru/index/' . $id_kelas);
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Kelas Guru', null, 'Admin/KelasGuru/index/' . $id_kelas);
                }
            }
        }
        else {
            show_404();
        }
    }
    public function edit($id, $id_kelas)
    {
        if ($this->update != true) {
            show_404();
        }
        if (!isset($id)) {
            show_404();
        } else {
            // add breadcrumbs
            $this->breadcrumbs->push('Home', 'Admin/Home');
            $this->breadcrumbs->push('Kelas', 'Admin/Kelas');
            $this->breadcrumbs->push('KelasGuru', 'Admin/KelasGuru/index/' . $id_kelas);
            $this->breadcrumbs->push('Form KelasGuru', 'Admin/KelasGuru/edit/' . $id . '/' . $id_kelas);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Kelas Guru';
            $this->template->breadcrumbs($data);
            $get = $this->KelasGuru_model->get($id)->row();

            $data = [
                'row' => $get,
                "page" => 'edit',
                'guru' =>  $this->db->get('guru')->result(),
                'id_kelas' => $id_kelas
            ];
            $this->template->admin('admin/kelasguru/form_data', $data);
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
            $id_KelasGuru = htmlspecialchars($this->input->post('id', true));
            $row = $this->KelasGuru_model->get($id_KelasGuru)->row();
            $KelasGuru = $this->KelasGuru_model->delete($id_KelasGuru);
            if ($KelasGuru > 0) {
                $data = [
                    'id' => $id_KelasGuru,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_KelasGuru,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllKelasGuru()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_KelasGuru = $this->input->post('data_item');
            foreach ($id_KelasGuru as $index => $result) {
                $row = $this->KelasGuru_model->get($result)->row();
                $KelasGuru = $this->KelasGuru_model->delete($result);
            }
            if ($KelasGuru > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_KelasGuru) . ' data',
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
    private function sessionKelasGuru($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_KelasGuru()
    {
        $id_kelas = htmlspecialchars($this->input->get('id_kelas', true));
        $list = $this->Server_KelasGuru_model->get_datatables($id_kelas);
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_KelasGuru" value="' . $field->id_kelas_detail . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = check_guru_lengkap($field->guru_id)->nama . " | " . check_ttq(check_guru_lengkap($field->guru_id)->status_ttq);
            $row[] = check_kelas($field->kelas_id);
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/KelasGuru/edit/' . $field->id_kelas_detail . '/' . $id_kelas) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = ' <a href="' . base_url('Admin/KelasGuru/delete/' . $field->id_kelas_detail) . '" class="btn btn-danger delete_KelasGuru shadow-sm" data-id
                ="' . $field->id_kelas_detail
                    . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
            ' . $this->update . $this->delete . '           
            </div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_KelasGuru_model->count_all(),
            "recordsFiltered" => $this->Server_KelasGuru_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function export($id_kelas)
    {
        if ($this->create != true) {
            show_404();
        }
        $get = $this->input->get('checked');
        if ($get == null) {
            $KelasGuru = $this->KelasGuru_model->get(null, $id_kelas)->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->KelasGuru_model->get($result, $id_kelas)->row();
            }
            $KelasGuru = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Guru')
            ->setCellValue('B1', 'Ngajar Kelas');

        $kolom = 2;
        $nomor = 1;
        foreach ($KelasGuru as $result) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, check_guru($result->guru_id))
                ->setCellValue('B' . $kolom, check_kelas($result->kelas_id));

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
        $spreadsheet->getActiveSheet()->getStyle('A2:B' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A2:B' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:B' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data KelasGuru.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    public function importData($id_kelas)
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
                    $guru_id = check_guru_convert($sheetData[$i]['0']);
                    $kelas_id = check_kelas_convert($sheetData[$i]['1']);

                    $data =  [
                        'guru_id' => $guru_id,
                        'kelas_id' => $kelas_id,
                    ];
                    $insert =  $this->KelasGuru_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data KelasGuru', null, 'Admin/KelasGuru/index/' . $id_kelas);
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data KelasGuru', null, 'Admin/KelasGuru/index/' . $id_kelas);
            }
        }
    }
}
