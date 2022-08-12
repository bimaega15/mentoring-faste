<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class Ttq extends CI_Controller
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
        $roles = check_roles_crud('Admin/Ttq', $my_roles);
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
        $this->load->model(['Ttq/Ttq_model', 'Ttq/Server_Ttq_model', 'Ttq/Server_Ttq_kelasSiswa', 'Ttq/Server_Ttq_KategoriTtq']);
    }
    public function index($id_kelas = '')
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Ttq');
        if ($id_kelas != null) {
            $this->breadcrumbs->push('Kategori TTQ', 'Admin/Ttq/index/' . $id_kelas);
        }
        // output
        $data['title'] = 'Mangement Ttq';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $data['id_kelas'] = $id_kelas;
        if ($id_kelas != null) {
            $data['id_kelas'] = $id_kelas;
            $data['kategori'] = $this->db->get('kategori_ttq')->result();
            $this->template->admin('admin/ttq/main_kategori', $data);
        } else {
            $data['kelas'] = $this->Ttq_model->getKelasTtq()->result();
            $this->template->admin('admin/ttq/main_table', $data);
        }
    }
    public function catatan($id_kelas)
    {
        $get = $this->input->get('kategori', true);
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Ttq');
        $this->breadcrumbs->push('Kategori TTQ', 'Admin/Ttq/index/' . $id_kelas);
        $this->breadcrumbs->push('Catatan TTQ', 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get);
        // output
        $data['title'] = 'Kategori Ttq';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $data['id_kelas'] = $id_kelas;
        $data['id_kategori_ttq'] = $get;


        $this->template->admin('admin/ttq/main', $data);
    }
    public function add($id_kelas)
    {
        $get_url = $this->input->get('kategori', true);
        if ($this->create != true) {
            show_404();
        }

        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas', 'Admin/Ttq');
        $this->breadcrumbs->push('Kategori TTQ', 'Admin/Ttq/index/' . $id_kelas);
        $this->breadcrumbs->push('Catatan TTQ', 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_url);
        $this->breadcrumbs->push('Form TTQ', 'Admin/Ttq/add/' . $id_kelas . '?kategori=' . $get_url);
        // output
        $data['title'] = 'Form Ttq';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);
        // add breadcrumbs
        $obj = new stdClass();
        $obj->surah = null;
        $obj->id_ttq = null;
        $obj->tanggal = null;
        $obj->waktu = null;
        $obj->keterangan = null;
        $obj->kelas_siswa_id = null;
        $obj->id_kelas = null;
        $obj->siswa_id = null;
        $obj->status_bacaan = null;
        $data['row'] = $obj;
        $data['page'] = 'add';
        $get_siswa = null;
        $table = $this->Ttq_model->getKelasSiswaTtq($id_kelas)->result();
        foreach ($table as $siswa) {
            $get_siswa[$siswa->siswa_id] = $this->db->get_where('siswa', ['id_siswa' => $siswa->siswa_id])->row();
        }
        $data['get_siswa'] = $get_siswa;
        $data['kelas_siswa'] = $this->Ttq_model->getAllJoin()->result();
        $data['id_kelas'] = $id_kelas;
        $data['id_kategori_ttq'] = $get_url;
        $this->template->admin('admin/ttq/form_data', $data);
    }
    public function process($id_kelas)
    {
        $this->form_validation->set_rules('surah', 'Surah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required|trim');
        if (isset($_POST['add'])) {
            $this->form_validation->set_rules('siswa[]', 'Siswa', 'required|trim|numeric');
        }
        if (isset($_POST['edit'])) {
            $this->form_validation->set_rules('siswa', 'Siswa', 'required|trim|numeric');
        }
        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka');
        $this->form_validation->set_error_delimiters('<small class="text-white">', '</small><br>');
        if (isset($_POST['add'])) {
            if ($this->form_validation->run() == false) {
                return $this->add($id_kelas);
            } else {
                $kategori_ttq = $this->input->get('kategori');
                $siswa = $this->input->post('siswa', true);
                if (count($siswa) == 0 || count($siswa) == null) {
                    sweetAlert2('error', 'Empty', 'Anda harus menginputkan siswa', '', 'Admin/Ttq/index/' . $id_kelas);
                    exit();
                }

                $status_bacaan_hidden = $this->input->post('status_bacaan_hidden', true);

                foreach ($status_bacaan_hidden as $index => $value) {
                    if (isset($_POST['status_bacaan' . $value])) {
                        $ArrayStatusBacaan[$value] = $_POST['status_bacaan' . $value];
                    }
                }
                $noArray = 0;
                foreach ($ArrayStatusBacaan as $index => $v) {
                    $fixArrayStatusBacaan[$noArray] = $v;
                    $noArray++;
                }
                $keterangan = removeArrayNull($this->input->post('keterangan', true));
                foreach ($siswa as $key => $value) {
                    $no = $key + 1;
                    $keterangan_siswa = isset($keterangan[$key]) ? $keterangan[$key] : null;
                    $status_bacaan_siswa =  isset($fixArrayStatusBacaan[$key]) ? $fixArrayStatusBacaan[$key] : null;
                    $data = [
                        'surah' => htmlspecialchars($this->input->post('surah', true)),
                        'tanggal' => htmlspecialchars($this->input->post('tanggal', true)),
                        'waktu' => htmlspecialchars($this->input->post('waktu', true)),
                        'keterangan' => $keterangan_siswa,
                        'siswa_id' => $value,
                        'kategori_ttq_id' => $kategori_ttq,
                        'status_bacaan' => $status_bacaan_siswa,
                    ];

                    $insertUsers[] = $this->Ttq_model->insert($data);
                }

                if (count($insertUsers) > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Ttq', null, 'Admin/Ttq/catatan/' . $id_kelas . "?kategori=" . $kategori_ttq);
                } else {
                    sweetAlert2('success', 'Successfully', 'Berhasil tambah data Ttq', null, 'Admin/Ttq/catatan/' . $id_kelas . "?kategori=" . $kategori_ttq);
                }
            }
        } else if (isset($_POST['edit'])) {
            if ($this->form_validation->run() == false) {
                $id = htmlspecialchars($this->input->post('id_Ttq', true));
                return $this->edit($id, $id_kelas);
            } else {
                $get_url = $this->input->get('kategori', true);
                $id = $this->input->post('id_Ttq', true);
                $data = [
                    'tanggal' => htmlspecialchars($this->input->post('tanggal', true)),
                    'waktu' => htmlspecialchars($this->input->post('waktu', true)),
                    'keterangan' => htmlspecialchars($this->input->post('keterangan', true)),
                    'status_bacaan' => htmlspecialchars($this->input->post('status_bacaan', true)),
                    'siswa_id' => htmlspecialchars($this->input->post('siswa', true)),
                    'kategori_ttq_id' => $get_url,
                ];
                $updateUsers = $this->Ttq_model->update($data, $id);
                if ($updateUsers > 0) {
                    sweetAlert2('success', 'Successfully', 'Berhasil update data Ttq', null, 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_url);
                } else {
                    sweetAlert2('error', 'Failed', 'Gagal update data Ttq', null, 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_url);
                }
            }
        } else {
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
            $get_kategori_url = $this->input->get('kategori');
            // add breadcrumbs
            $this->breadcrumbs->push('Home', 'Admin/Home');
            $this->breadcrumbs->push('Kelas', 'Admin/Ttq');
            $this->breadcrumbs->push('Kategori TTQ', 'Admin/Ttq/index/' . $id_kelas);
            $this->breadcrumbs->push('Catatan TTQ', 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_kategori_url);
            $this->breadcrumbs->push('Form Ttq', 'admin/ttq/edit/' . $id . '/' . $id_kelas);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Ttq';
            $this->template->breadcrumbs($data);
            $get = $this->Ttq_model->get($id)->row();
            $getKelasSiswa = $this->Ttq_model->joinSiswaKelas($get->siswa_id, $id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
                'kelas_siswa' => $this->Ttq_model->getAllJoin()->result(),
                'kelas_siswa_id' => $this->db->get_where('siswa', ['id_siswa' => $get->siswa_id])->row(),
                'id_kelas' => $id_kelas,
                'siswa' => $this->db->get('siswa')->result(),
                'get_kelas_siswa' => $getKelasSiswa,
                'id_kategori_ttq' => $get_kategori_url,
            ];
            $this->template->admin('admin/ttq/form_data', $data);
        }
    }
    public function detail($id, $id_kelas)
    {
        if ($this->update != true) {
            show_404();
        }
        if (!isset($id)) {
            show_404();
        } else {
            $get_kategori_url = $this->input->get('kategori');
            // add breadcrumbs
            $this->breadcrumbs->push('Home', 'Admin/Home');
            $this->breadcrumbs->push('Kelas', 'Admin/Ttq');
            $this->breadcrumbs->push('Kategori TTQ', 'Admin/Ttq/index/' . $id_kelas);
            $this->breadcrumbs->push('Catatan TTQ', 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_kategori_url);
            $this->breadcrumbs->push('Detail Ttq', 'admin/ttq/detail/' . $id . '/' . $id_kelas);
            // output
            $data['breadcrumb'] = $this->breadcrumbs->show();
            $data['title'] = 'Form Ttq';
            $this->template->breadcrumbs($data);
            $get = $this->Ttq_model->get($id)->row();
            $getKelasSiswa = $this->Ttq_model->joinSiswaKelas($get->siswa_id, $id)->row();
            $data = [
                'row' => $get,
                "page" => 'edit',
                'kelas_siswa' => $this->Ttq_model->getAllJoin()->result(),
                'kelas_siswa_id' => $this->db->get_where('siswa', ['id_siswa' => $get->siswa_id])->row(),
                'id_kelas' => $id_kelas,
                'siswa' => $this->db->get('siswa')->result(),
                'get_kelas_siswa' => $getKelasSiswa,
                'id_kategori_ttq' => $get_kategori_url,
            ];
            $this->template->admin('admin/ttq/form_data_detail', $data);
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
            $id_Ttq = htmlspecialchars($this->input->post('id', true));
            $Ttq = $this->Ttq_model->delete($id_Ttq);
            if ($Ttq > 0) {
                $data = [

                    'id' => $id_Ttq,
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus data',
                    'status' => 'success'
                ];
            } else {
                $data = [

                    'id' => $id_Ttq,
                    'title' => 'Failed',
                    'msg' => 'Gagal menghapus data',
                    'status' => 'error'
                ];
            }
            echo json_encode($data);
        }
    }
    public function deleteAllTtq()
    {
        if ($this->delete != true) {
            show_404();
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id_Ttq = $this->input->post('data_item');
            foreach ($id_Ttq as $index => $result) {
                $Ttq = $this->Ttq_model->delete($result);
            }
            if ($Ttq > 0) {
                $data = [
                    'title' => 'Successfully',
                    'msg' => 'Berhasil menghapus ' . count($id_Ttq) . ' data',
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
    private function sessionTtq($status, $title, $msg, $footer = null)
    {
        $this->session->set_flashdata('status', $status);
        $this->session->set_flashdata('title', $title);
        $this->session->set_flashdata('content', $msg);
        $this->session->set_flashdata('footer', $footer);
    }
    public function server_Ttq()
    {
        $id_kelas = htmlspecialchars($this->input->get('id_kelas', true));
        $id_kategori_ttq = htmlspecialchars($this->input->get('id_kategori_ttq', true));
        $list = $this->Server_Ttq_model->get_datatables($id_kategori_ttq, $id_kelas);
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = '<div class="checkbox checkbox-primary">
            <input id="checkbox_item' . $no . '" type="checkbox" class="check_item_Ttq" value="' . $field->id_ttq . '">
            <label for="checkbox_item' . $no . '">
            </label>
        </div>';
            $row[] = ($field->tanggal) . ' ' . waktu_format($field->waktu);
            $row[] =  $field->surah;
            $row[] =  $field->status_bacaan;
            $row[] = $field->nama_siswa_kelas;
            $row[] = ($field->kelas_siswa);
            $detail = '<a href="' . base_url('Admin/Ttq/detail/' . $field->id_ttq . '/' . $id_kelas . '?kategori=' . $id_kategori_ttq) . '" class="btn btn-info shadow-sm"> <i class="fas fa-eye"></i></a>';
            if ($this->update) {
                $this->update = '<a href="' . base_url('Admin/Ttq/edit/' . $field->id_ttq . '/' . $id_kelas . '?kategori=' . $id_kategori_ttq) . '" class="btn btn-success shadow-sm"> <i class="fas fa-pencil-alt"></i></a>';
            }
            if ($this->delete) {
                $this->delete = '<a href="' . base_url('Admin/Ttq/delete/' . $field->id_ttq) . '" class="btn btn-danger delete_Ttq shadow-sm" data-id
                ="' . $field->id_ttq . '"> <i class="fas fa-trash-alt"></i></a>';
            }
            $row[] = '
            <div class="btn-group btn-rounded text-center" role="group" aria-label="Basic example">
            ' . $detail . $this->update . $this->delete . '            
            </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Ttq_model->count_all(),
            "recordsFiltered" => $this->Server_Ttq_model->count_filtered(),
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
        $kategori_ttq = $this->input->get('kategori');
        if ($get == null) {
            $Ttq_management = $this->Ttq_model->getFromServerTtq(null, $id_kelas, $kategori_ttq)->result();
        } else {
            $id = explode(',', $get);

            foreach ($id as $result) {
                $row[] = $this->Ttq_model->getFromServerTtq($result, $id_kelas, $kategori_ttq)->row();
            }
            $Ttq_management = $row;
        }
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tanggal')
            ->setCellValue('B1', 'Waktu')
            ->setCellValue('C1', 'Status Bacaan')
            ->setCellValue('D1', 'Keterangan')
            ->setCellValue('E1', 'Siswa')
            ->setCellValue('F1', 'Kategori TTQ')
            ->setCellValue('G1', 'Surah / Ayat');

        $kolom = 2;
        $nomor = 1;
        foreach ($Ttq_management as $Ttq) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, tanggal_indo($Ttq->tanggal))
                ->setCellValue('B' . $kolom, $Ttq->waktu)
                ->setCellValue('C' . $kolom, $Ttq->status_bacaan)
                ->setCellValue('D' . $kolom, $Ttq->keterangan_ttq)
                ->setCellValue('E' . $kolom, check_siswa($Ttq->siswa_id))
                ->setCellValue('F' . $kolom, check_kategori_ttq_id($Ttq->kategori_ttq_id))
                ->setCellValue('G' . $kolom, $Ttq->surah);


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
        $spreadsheet->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray_title);


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
        $spreadsheet->getActiveSheet()->getStyle('A2:G' . $kolom)->applyFromArray($styleArrayColumn);
        $spreadsheet->getActiveSheet()->getStyle('A2:G' . $kolom)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:G' . $kolom)
            ->getAlignment()->setWrapText(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(35);



        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Ttq.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    public function importData($id_kelas)
    {
        if ($this->create != true) {
            show_404();
        }
        $get_url = $this->input->get('kategori');
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
                    $tanggal = tanggal_indo_convert($sheetData[$i]['0']);
                    $waktu = $sheetData[$i]['1'];
                    $status_bacaan = $sheetData[$i]['2'];
                    $keterangan = $sheetData[$i]['3'];
                    $siswa_id = check_siswa_convert($sheetData[$i]['4']);
                    $kategori_ttq = check_namakategori_ttq_id($sheetData[$i]['5']);
                    $surah = $sheetData[$i]['6'];




                    $data =  [
                        'tanggal' => $tanggal,
                        'waktu' => $waktu,
                        'keterangan' => $keterangan,
                        'siswa_id' => $siswa_id,
                        'kategori_ttq_id' => $kategori_ttq,
                        'status_bacaan' => $status_bacaan,
                        'surah' => $surah,
                    ];

                    $insert =  $this->Ttq_model->insert($data);
                }
            }
            $count = count($count);
            $notif = $count . ' Data Imported successfully';
            $notifSalah = 'Terjadi kesalahan insert data';
            if ($insert > 0) {
                sweetAlert2('success', 'Successfully', 'Berhasil ' . $count . ' import data Ttq', null, 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_url);
            } else {
                sweetAlert2('error', 'Failed', 'Gagal import data Ttq', null, 'Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $get_url);
            }
        }
    }
    public function getSiswaKelas()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $id = htmlspecialchars($this->input->post('id', true));
            $kelas = $this->Ttq_model->joinKelasGuru($id)->row();

            $kelas_detail = val_guru_kelas($kelas->id_kelas);

            $table = $this->Ttq_model->getKelasTtq($id)->result();
            foreach ($table as $siswa) {
                $get_siswa[$siswa->siswa_id] = $this->db->get_where('siswa', ['id_siswa' => $siswa->siswa_id])->row();
            }
            $no = 1;
            $output = '
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableSiswa">
                        <thead>
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkboxAllSiswa">
                                        <label class="custom-control-label" for="checkboxAllSiswa"></label>
                                    </div>
                                </th>
                                <th>
                                    Nomor Induk
                                </th>
                                <th>
                                    Nama Siswa
                                </th>
                                <th>
                                    Jenis Kelamin
                                </th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($get_siswa as $result) {
                $output .= '
                           <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="siswa[]" class="custom-control-input checkboxitemsiswa" id="checkboxitemsiswa' . $no . '" value="' . $result->id_siswa . '">
                                        <label class="custom-control-label" for="checkboxitemsiswa' . $no . '"></label>
                                    </div>
                                </td>
                                <td>
                                    ' . $result->nomor_induk . '
                                </td>
                                <td>
                                    ' . $result->nama . '
                                </td>
                                <td>
                                    ' . $result->jenis_kelamin . '
                                </td>
                            </tr>
                            ';
                $no++;
            }
            $output .= '</tbody>
                    </table>
                </div>
            ';

            $data = [
                'kelas_detail' => $kelas_detail,
                'output' => $output,
            ];
            echo json_encode($data);
        }
    }
    public function serveKelasSiswa()
    {
        $list = $this->Server_Ttq_kelasSiswa->get_datatables();


        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($field->kelas_siswa);
            $row[] = ($field->nama_tahun);

            $row[] = '
            <div class="text-center">
                <a href="' . base_url('Admin/Ttq/index/' . $field->id_kelas_siswa) . '" class="btn btn-primary text-center" title="Catatan TTQ"><i class="fas fa-book-open"></i> Kategori</a>
            </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Ttq_kelasSiswa->count_all(),
            "recordsFiltered" => $this->Server_Ttq_kelasSiswa->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function serverKategoriTtq()
    {
        $id_kelas = $this->input->get('id_kelas', true);
        $list = $this->Server_Ttq_KategoriTtq->get_datatables();

        $data = array();
        $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($field->nama);

            $row[] = '
            <div class="text-center">
                <a href="' . base_url('Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $field->id_kategori_ttq) . '" class="btn btn-primary text-center" title="Catatan TTQ"><i class="fas fa-book-open"></i> Setoran</a>
            </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_GET['draw'],
            "recordsTotal" => $this->Server_Ttq_KategoriTtq->count_all(),
            "recordsFiltered" => $this->Server_Ttq_KategoriTtq->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}
