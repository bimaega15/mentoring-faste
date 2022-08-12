<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class SiswaKelas extends CI_Controller
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
        $roles = check_roles_crud('Admin/SiswaKelas', $my_roles);
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
        $this->load->model(['SiswaKelas/SiswaKelas_model', 'SiswaKelas/Server_SiswaKelas_model', 'Auth/Users_model', 'KelasSiswa/KelasSiswa_model', 'Siswa/Server_Siswa_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Siswa Kelas', 'Admin/SiswaKelas');
        // output
        $data['title'] = 'Mangement Siswa Kelas';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/siswakelas/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Siswa Kelas', 'Admin/SiswaKelas');
        $this->breadcrumbs->push('Form Data', 'Admin/SiswaKelas/add');
        // output
        $data['title'] = 'Form Siswa Kelas';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_siswa_kelas = null;
        $obj->siswa_id = null;
        $obj->kelas_siswa_id = null;
        $data['row'] = $obj;
        $data['kelas_siswa'] = $this->KelasSiswa_model->joinAllKelas()->result();
        $data['page'] = 'add';
        $data["siswa"] = $this->db->get('siswa')->result();
        $this->template->admin('admin/siswakelas/form_data', $data);
    }
    public function process()
    {

        $this->form_validation->set_rules('kelas_siswa_id', 'Kelas Siswa', 'required|trim|numeric');
        if (isset($_POST['add'])) {
            $this->form_validation->set_rules('siswa_id[]', 'Siswa', 'required|trim|numeric');
        }
        if (isset($_POST['edit'])) {
            $this->form_validation->set_rules('siswa_id', 'Siswa', 'required|trim|numeric');
        }
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-white">', '</small><br>');

        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $siswa_id = $this->input->post('siswa_id', true);

                foreach ($siswa_id as $result) {
                    $data = [
                        'siswa_id' => $result,
                        'kelas_siswa_id' => $this->input->post('kelas_siswa_id', true)
                    ];
                    $insert[] = $this->SiswaKelas_model->insert($data);
                }

                if (count($insert) > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Siswa Kelas', null, 'Admin/SiswaKelas');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Siswa Kelas', null, 'Admin/SiswaKelas');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_SiswaKelas', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_SiswaKelas', true);
                $data = [
                    'siswa_id' => $this->input->post('siswa_id', true),
                    'kelas_siswa_id' => $this->input->post('kelas_siswa_id', true)
                ];
            }
            $update = $this->SiswaKelas_model->update($data, $id);
            if ($update > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil update data Siswa Kelas', null, 'Admin/SiswaKelas');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal update data Siswa Kelas', null, 'Admin/SiswaKelas');
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
            $this->breadcrumbs->push('Siswa Kelas', 'Admin/SiswaKelas');
            $this->breadcrumbs->push('Form Siswa Kelas', 'Admin/SiswaKelas/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Siswa Kelas';
            $this->template->breadcrumbs($data);
            $get = $this->SiswaKelas_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
                "siswa" => $this->db->get('siswa')->result(),
                'kelas_siswa' => $this->KelasSiswa_model->joinAllKelas()->result()
            ];
            $this->template->admin('admin/siswakelas/form_data', $data);
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
            $id_SiswaKelas = htmlspecialchars($this->input->post('id', true));
            $SiswaKelas = $this->SiswaKelas_model->delete($id_SiswaKelas);
            if ($SiswaKelas > 0) {
                $data = [
                    'id' => $id_SiswaKelas,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_SiswaKelas,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllSiswaKelas()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_SiswaKelas = $this->input->post('data_item');
            foreach ($id_SiswaKelas as $index => $result) {
                $SiswaKelas = $this->SiswaKelas_model->delete($result);
            }
            if ($SiswaKelas > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_SiswaKelas) . ' data',
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
    private function sessionSiswaKelas($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_SiswaKelas()
    {
        $list = $this->Server_SiswaKelas_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_SiswaKelas" value="' . $field->id_siswa_kelas . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = ($field->kelas);
            $row[] = val_guru_kelas($field->kelas_id);
            $row[] = ($field->siswa_kelas);
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/SiswaKelas/edit/' . $field->id_siswa_kelas) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/SiswaKelas/delete/' . $field->id_siswa_kelas) . '" class="btn btn-danger delete_SiswaKelas shadow-sm" data-id
                ="' . $field->id_siswa_kelas
                    . '"> <i class="fas fa-trash-alt"></i></a>';
            }

            $row[] = '
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
            '  . $this->update . $this->delete . '
            </div>';
            $data[] = $row;
        }


        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_SiswaKelas_model->count_all(),
            "recordsFiltered" => $this->Server_SiswaKelas_model->count_filtered(),
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
            $SiswaKelas = $this->SiswaKelas_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->SiswaKelas_model->get($result)->row();
            }
            $SiswaKelas = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Siswa')
            ->setCellValue('B1', 'Kelas Siswa');

        $kolom = 2;
        $nomor = 1;
        foreach ($SiswaKelas as $result) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, check_siswa_lengkap($result->siswa_id))
                ->setCellValue('B' . $kolom, checkKelasSiswaExport($result->kelas_siswa_id)->kelas_siswa);

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
        header('Content-Disposition: attachment;filename="Data SiswaKelas.xlsx"');
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
                    $siswa_id = $sheetData[$i]['0'];
                    $kelas_siswa_id = $sheetData[$i]['1'];

                    $data =  [
                        'siswa_id' => check_siswa_lengkap_convert($siswa_id),
                        'kelas_siswa_id' => check_kelasSiswaConvert($kelas_siswa_id),
                    ];

                    $insert =  $this->SiswaKelas_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Siswa Kelas', null, 'Admin/SiswaKelas');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data SiswaKelas', null, 'Admin/SiswaKelas');
            }
        }
    }
    public function getKelasGuru()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $val = htmlspecialchars($this->input->post('val', true));
            $get = $this->db->get_where('kelas_detail', ['kelas_id' => $val]);
            if ($get->num_rows() > 0) {
                $get = $get->result();
                $output = "<div class='alert alert-info'><strong>Berikut list guru kelas</strong> <ul>";
                foreach ($get as $result) {
                    $output .= '<li> ' . check_guru_lengkap($result->guru_id)->nama . " | " . check_ttq(check_guru_lengkap($result->guru_id)->status_ttq) .
                        '</li>';
                }
                $output .= '</ul></div>';
                echo json_encode($output);
            }
        }
    }
    public function server_Siswa()
    {
        $list = $this->Server_Siswa_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input name="checkbox_siswa[]" id="checkbox_item' . $no . '" type="checkbox" class="check_item_Siswa" value="' . $field->id_siswa . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->nomor_induk;
            $row[] = $field->nama;
            if ($field->jenis_kelamin != null) {
                $row[] = $field->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
            } else {
                $row[] = $field->jenis_kelamin;
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Siswa_model->count_all(),
            "recordsFiltered" => $this->Server_Siswa_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}
