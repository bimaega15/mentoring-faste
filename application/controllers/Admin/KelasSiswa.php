<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class KelasSiswa extends CI_Controller
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
        $roles = check_roles_crud('Admin/KelasSiswa', $my_roles);
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
        $this->load->model(['KelasSiswa/KelasSiswa_model', 'KelasSiswa/Server_KelasSiswa_model', 'Auth/Users_model', 'Kelas/Kelas_model']);
    }
    public function index()
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas Siswa', 'Admin/KelasSiswa');
        // output
        $data['title'] = 'Mangement Kelas Siswa';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $this->template->admin('admin/kelassiswa/main', $data);
    }
    public function add()
    {
        if ($this->create != true) {
            show_404();
        }
        // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas Siswa', 'Admin/KelasSiswa');
        $this->breadcrumbs->push('Form Data', 'Admin/KelasSiswa/add');
        // output
        $data['title'] = 'Form Kelas Siswa';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->id_kelas_siswa = null;
        $obj->kelas_id = null;
        $obj->tahun_akademik_id = null;
        $data['row'] = $obj;
        $data['kelas_siswa'] = $this->Kelas_model->get()->result();
        $data['tahun_akademik'] = $this->db->get('tahun_akademik')->result();
        $data['page'] = 'add';
        $this->template->admin('admin/kelassiswa/form_data', $data);
    }
    public function process()
    {
        $kelas_id = $this->input->post('kelas_id');
        $tahun_akademik_id = $this->input->post('tahun_akademik_id');

        $cek = $this->db->get_where('kelas_siswa', ['kelas_id' => $kelas_id, 'tahun_akademik_id' => $tahun_akademik_id]);
        if ($cek->num_rows() > 0) {
            sweetAlert2('error', 'Failed', 'Kelas Siswa sudah ada', null, 'Admin/KelasSiswa');
            exit();
        }

        $this->form_validation->set_rules('kelas_id', 'Kelas Siswa', 'required|trim|numeric');
        $this->form_validation->set_rules('tahun_akademik_id', 'Tahun Akademik', 'required|trim|numeric');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} hanya boleh berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');

        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add();
            } else {
                $data = [
                    'kelas_id' => htmlspecialchars($this->input->post('kelas_id', true)),
                    'tahun_akademik_id' => htmlspecialchars($this->input->post('tahun_akademik_id', true)),
                ];
                $insert = $this->KelasSiswa_model->insert($data);
                if ($insert > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Kelas Siswa', null, 'Admin/KelasSiswa');
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Kelas Siswa', null, 'Admin/KelasSiswa');
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_KelasSiswa', true));
                return $this->edit($id);
            } else {
                $id = $this->input->post('id_KelasSiswa', true);
                $data = [
                    'kelas_id' => htmlspecialchars($this->input->post('kelas_id', true)),
                    'tahun_akademik_id' => htmlspecialchars($this->input->post('tahun_akademik_id', true)),
                ];
            }
            $update = $this->KelasSiswa_model->update($data, $id);
            if ($update > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil update data Kelas Siswa', null, 'Admin/KelasSiswa');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal update data Kelas Siswa', null, 'Admin/KelasSiswa');
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
            $this->breadcrumbs->push('Kelas Siswa', 'Admin/KelasSiswa');
            $this->breadcrumbs->push('Form Kelas Siswa', 'Admin/KelasSiswa/edit/' . $id);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Kelas Siswa';
            $this->template->breadcrumbs($data);
            $get = $this->KelasSiswa_model->get($id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
                'kelas_siswa' => $this->Kelas_model->get()->result(),
                'tahun_akademik' => $this->db->get('tahun_akademik')->result(),
            ];
            $this->template->admin('admin/kelassiswa/form_data', $data);
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
            $id_KelasSiswa = htmlspecialchars($this->input->post('id', true));
            $row = $this->KelasSiswa_model->get($id_KelasSiswa)->row();
            $KelasSiswa = $this->KelasSiswa_model->delete($id_KelasSiswa);
            if ($KelasSiswa > 0) {
                $data = [
                    'id' => $id_KelasSiswa,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_KelasSiswa,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllKelasSiswa()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_KelasSiswa = $this->input->post('data_item');
            foreach ($id_KelasSiswa as $index => $result) {
                $row = $this->KelasSiswa_model->get($result)->row();
                $KelasSiswa = $this->KelasSiswa_model->delete($result);
            }
            if ($KelasSiswa > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_KelasSiswa) . ' data',
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
    private function sessionKelasSiswa($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_KelasSiswa()
    {
        $list = $this->Server_KelasSiswa_model->get_datatables();
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_KelasSiswa" value="' . $field->id_kelas_siswa . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = ($field->kelas_siswa);
            $row[] = val_guru_kelas($field->id_kelas);
            $row[] = ($field->nama_akademik);
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/KelasSiswa/edit/' . $field->id_kelas_siswa) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/KelasSiswa/delete/' . $field->id_kelas_siswa) . '" class="btn btn-danger delete_KelasSiswa shadow-sm" data-id
                ="' . $field->id_kelas_siswa
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
            "recordsTotal" => $this->Server_KelasSiswa_model->count_all(),
            "recordsFiltered" => $this->Server_KelasSiswa_model->count_filtered(),
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
            $KelasSiswa = $this->KelasSiswa_model->get()->result();
        } else {
            $id = explode(',', $get);
            foreach ($id as $result) {
                $row[] = $this->KelasSiswa_model->get($result)->row();
            }
            $KelasSiswa = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Kelas')
            ->setCellValue('B1', 'Tahun akademik');

        $kolom = 2;
        $nomor = 1;
        foreach ($KelasSiswa as $result) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, check_kelas_siswa($result->kelas_id))
                ->setCellValue('B' . $kolom, check_tahun_akademik($result->tahun_akademik_id));

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
        header('Content-Disposition: attachment;filename="Data KelasSiswa.xlsx"');
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
                    $kelas_id = $sheetData[$i]['0'];
                    $tahun_akademik_id = $sheetData[$i]['1'];

                    $cek = $this->db->get_where('kelas_siswa', ['kelas_id' => check_kelas_convert($kelas_id), 'tahun_akademik_id' => check_tahun_akademik_convert($tahun_akademik_id)]);
                    if ($cek->num_rows() > 0) {
                        sweetAlert2('error', 'Failed', 'Kelas Siswa ' . $kelas_id . " | " . $tahun_akademik_id . ' sudah ada', null, 'Admin/KelasSiswa');
                        exit();
                    }

                    $data =  [
                        'kelas_id' => check_kelas_convert($kelas_id),
                        'tahun_akademik_id' => check_tahun_akademik_convert($tahun_akademik_id),
                    ];
                    $insert =  $this->KelasSiswa_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Kelas Siswa', null, 'Admin/KelasSiswa');
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data KelasSiswa', null, 'Admin/KelasSiswa');
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
}
