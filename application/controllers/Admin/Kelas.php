<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Kelas extends CI_Controller
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
        $this->load->model(['Kelas/Kelas_model', 'Kelas/Server_Kelas_model', 'Auth/Users_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Kelas');
        // output
        $data['title'] = 'Mangement Kelas ';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/kelas/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Kelas');
        $this->breadcrumbs->push('Form Data', 'Admin/Kelas/add');
        // output
        $data['title'] = 'Form Kelas';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_kelas = null;
        $obj->tingkat = null;
        $obj->nama = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $data['tingkat'] = $this->db->get('tingkat')->result();
        $data['guru'] = $this->db->get('guru')->result();
        $this->template->admin('admin/kelas/form_data', $data);
    }
    public function process()
    {
        if (isset($_POST['add'])) {
            $tingkat =  htmlspecialchars($this->input->post('tingkat', true));
            $nama =  htmlspecialchars($this->input->post('nama', true));
            $cek = $this->db->get_where('kelas', ['tingkat' => $tingkat, 'nama' => $nama]);
            if ($cek->num_rows() > 0) {
                sweetAlert2('error', 'Failed', 'Kelas sudah ada', null, 'Admin/Kelas');
                exit();
            }
        }

        $this->form_validation->set_rules('tingkat', 'Tingkat', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama Kelas', 'required|trim');
        if (isset($_POST['add'])) {
            $this->form_validation->set_rules('guru_id[]', 'Guru', 'required|trim');
        }
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');

        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data = [
                    'tingkat' => htmlspecialchars($this->input->post('tingkat', true)),
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                ];
                $insert = $this->Kelas_model->insert($data);
                if ($insert > 0) {
                    $guru = $this->input->post('guru_id', true);
                    foreach ($guru as $result) {
                        $data = [
                            'guru_id' => $result,
                            'kelas_id' => $insert
                        ];
                        $count[] = $this->db->insert('kelas_detail', $data);
                    }
                    if ($insert > 0 || count($count) > 0) {
                        sweetAlert2('success', 'Successfully', 'Berhasil tambah data kelas', null, 'Admin/Kelas');
                    }
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data kelas', null, 'Admin/Kelas');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_Kelas', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_Kelas', true);
                $guru = $this->input->post('guru_id', true);
                $data = [
                    'tingkat' => htmlspecialchars($this->input->post('tingkat', true)),
                    'nama' => htmlspecialchars($this->input->post('nama', true)),
                ];
                $update = $this->Kelas_model->update($data, $id);
                $last_id = $id;
                if ($guru != null) {
                    $this->db->delete('kelas_detail', ['kelas_id' => $last_id]);
                    if ($guru != null) {
                        foreach ($guru as $result) {
                            $data = [
                                'guru_id' => $result,
                                'kelas_id' => $last_id
                            ];
                            $count[] = $this->db->insert('kelas_detail', $data);
                        }
                    }
                    if ($update > 0 || count($count) > 0) {
                        sweetAlert2('success', 'Successfully', 'Berhasil update data kelas', null, 'Admin/Kelas');
                    }
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data kelas', null, 'Admin/Kelas');
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
            $this->breadcrumbs->push('Kelas', 'Admin/Kelas');
            $this->breadcrumbs->push('Form Kelas', 'Admin/Kelas/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Kelas';
            $this->template->breadcrumbs($data);
            $get = $this->Kelas_model->get($id)->row();

            $data = [
                'row' => $get,
                "page" => 'edit',
                'tingkat' => $this->db->get('tingkat')->result(),
                'guru' =>  $this->db->get('guru')->result()
            ];
            $this->template->admin('admin/kelas/form_data', $data);
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
            $id_Kelas = htmlspecialchars($this->input->post('id', true));
            $row = $this->Kelas_model->get($id_Kelas)->row();
            $Kelas = $this->Kelas_model->delete($id_Kelas);
            if ($Kelas > 0) {
                $data = [
                    'id' => $id_Kelas,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_Kelas,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllKelas()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_Kelas = $this->input->post('data_item');
            foreach ($id_Kelas as $index => $result) {
                $row = $this->Kelas_model->get($result)->row();
                $Kelas = $this->Kelas_model->delete($result);
            }
            if ($Kelas > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_Kelas) . ' data',
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
    private function sessionKelas($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_Kelas()
    {
        $list = $this->Server_Kelas_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_Kelas" value="' . $field->id_kelas . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = $field->tingkat;
            $row[] = $field->nama;
            $row[] = val_guru_kelas($field->id_kelas);
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/Kelas/edit/' . $field->id_kelas) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = ' <a href="' . base_url('Admin/Kelas/delete/' . $field->id_kelas) . '" class="btn btn-danger delete_Kelas shadow-sm" data-id
                ="' . $field->id_kelas
                    . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            if ($this->create) {
                $this->create = '<a target="_blank" href="' . base_url('Admin/KelasGuru/index/' . $field->id_kelas) . '" class="btn btn-primary shadow-sm" title="Guru Kelas"><i class="fas fa-chalkboard-teacher"></i></a>';
            }
            $row[] = '
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
            ' . $this->create .  $this->update . $this->delete . '           
            </div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Kelas_model->count_all(),
            "recordsFiltered" => $this->Server_Kelas_model->count_filtered(),
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
            $Kelas = $this->Kelas_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->Kelas_model->get($result)->row();
            }
            $Kelas = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tingkat')
            ->setCellValue('B1', 'Nama');

        $kolom = 2;
        $nomor = 1;
        foreach ($Kelas as $result) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $result->tingkat)
                ->setCellValue('B' . $kolom, $result->nama);

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
        header('Content-Disposition: attachment;filename="Data Kelas.xlsx"');
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
                    $tingkat = $sheetData[$i]['0'];
                    $nama = $sheetData[$i]['1'];
                    $cek = $this->db->get_where('kelas', ['tingkat' => $tingkat, 'nama' => $nama]);
                    if ($cek->num_rows() > 0) {
                        sweetAlert2('error', 'Failed', 'Kelas ' . $tingkat . " | " . $nama . ' sudah ada', null, 'Admin/Kelas');
                        exit();
                    }

                    $data =  [
                        'tingkat' => $tingkat,
                        'nama' => $nama,
                    ];
                    $insert =  $this->Kelas_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Kelas', null, 'Admin/Kelas');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data Kelas', null, 'Admin/Kelas');
            }
        }
    }
}
