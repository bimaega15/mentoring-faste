<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Xlsximport;

class LaporanTTQ extends CI_Controller
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
        $roles = check_roles_crud('Admin/LaporanTTQ', $my_roles);
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
        $this->load->model(['Ttq/Ttq_model', 'Ttq/Server_Ttq_model', 'Ttq/Server_Ttq_kelasSiswa']);
    }
    public function index($kelas_siswa = null)
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Kelas Siswa', 'Admin/LaporanTTQ');
        if ($kelas_siswa != null) {
            $this->breadcrumbs->push('Laporan TTQ', 'Admin/LaporanTTQ/index/' . $kelas_siswa);
        }
        // output
        $data['title'] = 'Mangement Laporan TTQ';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $data['create'] = $this->create;
        $data['delete'] = $this->delete;
        $data['id_kelas'] = $kelas_siswa;
        $data['kategori_ttq'] = $this->db->get('kategori_ttq')->result();
        $data['guru'] = $this->db->get('guru')->result();
        $data['kategori_ttq'] = $this->db->get('kategori_ttq')->result();
        if ($kelas_siswa == null) {
            $this->template->admin('admin/laporanttq/kelas_siswa', $data);
        } else {
            $this->template->admin('admin/laporanttq/main', $data);
        }
    }
    public function detail($id_kelas, $id_ttq = null)
    { // add breadcrumbs
        $this->breadcrumbs->push('Home', 'Admin/Home');
        $this->breadcrumbs->push('Laporan TTQ', 'Admin/LaporanTTQ');
        $this->breadcrumbs->push('Detail Laporan TTQ', 'Admin/LaporanTTQ/index/' . $id_kelas . '/' . $id_ttq);
        // output
        $data['title'] = 'Mangement Laporan TTQ';
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->breadcrumbs($data);

        // add breadcrumbs
        $get = $this->Ttq_model->get($id_ttq)->row();
        $data['row'] = $get;
        $data['id_kelas'] = $id_kelas;
        $this->template->admin('admin/laporanttq/detail', $data);
    }

    public function getLaporan()
    {
        if (!$this->input->get()) {
            show_404();
        } else {

            $kategori = htmlspecialchars($this->input->get('laporan_kategori'));
            if ($kategori == 'per_hari') {
                $tahun = htmlspecialchars($this->input->get('tahun', true));
                $guru = htmlspecialchars($this->input->get('guru', true));
                $kategori_ttq = htmlspecialchars($this->input->get('kategori_ttq', true));
                $getHarian = $this->Ttq_model->getLaporanPerHari($tahun, $guru, $kategori_ttq)->result();
                $data['laporan'] = $getHarian;
                $data['guru_laporan'] = $guru;
                $data['kategori_ttq_laporan'] = $kategori_ttq;
                $data['keterangan'] = 'harian';
                $data['banding'] = null;
                $data['kategori'] = $kategori;
                $data['tahun'] = $tahun;
                if (isset($_GET['export'])) {
                    $this->load->view('admin/export/ttq', $data);
                } else {
                    return $this->index($data);
                }
            } else if ($kategori == 'per_minggu') {
                $tahun = htmlspecialchars($this->input->get('tahun', true));
                $guru = htmlspecialchars($this->input->get('guru', true));
                $kategori_ttq = htmlspecialchars($this->input->get('kategori_ttq', true));
                $getMingguan = $this->Ttq_model->getLaporanPerMinggu($tahun, $guru, $kategori_ttq)->result();
                $data_mingguan = null;
                if ($getMingguan != null) {
                    foreach ($getMingguan as $result) {
                        $data_mingguan[$result->mingguan] = $result->mingguan;
                    }
                    $data_mingguan = array_unique($data_mingguan);
                }
                $data['laporan'] = $getMingguan;
                $data['guru_laporan'] = $guru;
                $data['kategori_ttq_laporan'] = $kategori_ttq;
                $data['keterangan'] = 'mingguan';
                $data['banding'] = $data_mingguan;
                $data['kategori'] = $kategori;
                $data['tahun'] = $tahun;
                if (isset($_GET['export'])) {
                    $this->load->view('admin/export/ttq', $data);
                } else {
                    return $this->index($data);
                }
            } else if ($kategori == 'per_bulan') {
                $tahun = htmlspecialchars($this->input->get('tahun', true));
                $guru = htmlspecialchars($this->input->get('guru', true));
                $kategori_ttq = htmlspecialchars($this->input->get('kategori_ttq', true));
                $getBulanan = $this->Ttq_model->getLaporanPerBulan($tahun, $guru, $kategori_ttq)->result();
                $data_bulanan = null;
                if ($getBulanan != null) {
                    foreach ($getBulanan as $result) {
                        $data_bulanan[$result->bulanan] = $result->bulanan;
                    }
                    $data_bulanan = array_unique($data_bulanan);
                }
                $data['laporan'] = $getBulanan;
                $data['guru_laporan'] = $guru;
                $data['kategori_ttq_laporan'] = $kategori_ttq;
                $data['keterangan'] = 'bulanan';
                $data['banding'] = $data_bulanan;
                $data['kategori'] = $kategori;
                $data['tahun'] = $tahun;
                if (isset($_GET['export'])) {
                    $this->load->view('admin/export/ttq', $data);
                } else {
                    return $this->index($data);
                }
            } else {
                $tahun = htmlspecialchars($this->input->get('tahun', true));
                $guru = htmlspecialchars($this->input->get('guru', true));
                $kategori_ttq = htmlspecialchars($this->input->get('kategori_ttq', true));

                $getHarian = $this->Ttq_model->getLaporanPerHari($tahun, $guru, $kategori_ttq)->result();

                $data['laporan'] = $getHarian;
                $data['guru_laporan'] = $guru;
                $data['kategori_ttq_laporan'] = $kategori_ttq;
                $data['keterangan'] = '';
                $data['banding'] = null;
                $data['kategori'] = $kategori;
                $data['tahun'] = $tahun;
                if (isset($_GET['export'])) {
                    $this->load->view('admin/export/ttq', $data);
                } else {
                    return $this->index($data);
                }
            }
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
                <a href="' . base_url('Admin/LaporanTTQ/index/' . $field->id_kelas_siswa) . '" class="btn btn-primary" title="Catatan TTQ"><i class="fas fa-book-open"></i> Laporan</a>
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
    public function server_Ttq()
    {
        $id_kelas = ($this->input->get('id_kelas', true));
        $tahun = ($this->input->get('tahun', true));
        $guru = ($this->input->get('guru', true));
        $kategori_ttq = ($this->input->get('kategori_ttq', true));
        $start_date = ($this->input->get('start_date', true));
        $end_date = ($this->input->get('end_date', true));


        $list = $this->Server_Ttq_model->get_datatables($kategori_ttq, $id_kelas, $tahun, $guru, $start_date, $end_date);

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
            $row[] = ($field->tanggal) . " " . waktu_format($field->waktu);
            $row[] =  $field->surah;
            $row[] =  $field->status_bacaan;
            $row[] = $field->nama_siswa_kelas;
            $row[] = ($field->kelas_siswa);
            $row[] = ($field->nama_kategori_ttq);

            $row[] =  '<a href="' . base_url('Admin/LaporanTTQ/detail/' . $id_kelas . '/' . $field->id_ttq) . '" class="btn btn-info shadow-sm"> <i class="fas fa-eye"></i></a>';
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
        $get = $this->input->get('checked');
        $kategori_ttq = $this->input->get('kategori_ttq');
        $tahun = null;
        $guru = null;
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');


        if ($get == null) {
            $Ttq_management = $this->Ttq_model->getFromServerTtq(null, $id_kelas, $kategori_ttq, $guru, $tahun, $start_date, $end_date)->result();
        } else {
            $id = explode(',', $get);

            foreach ($id as $result) {
                $row[] = $this->Ttq_model->getFromServerTtq($result, $id_kelas, $kategori_ttq, $guru, $tahun, $start_date, $end_date)->row();
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
    public function pdf($id_kelas)
    {

        $get = $this->input->get('checked');
        $kategori_ttq = $this->input->get('kategori_ttq');
        $tahun = null;
        $guru = null;
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        if ($get == null) {
            $Ttq_management = $this->Ttq_model->getFromServerTtq(null, $id_kelas, $kategori_ttq, $guru, $tahun, $start_date, $end_date)->result();
    
        } else {
            $id = explode(',', $get);

            foreach ($id as $result) {
                $row[] = $this->Ttq_model->getFromServerTtq($result, $id_kelas, $kategori_ttq, $guru, $tahun, $start_date, $end_date)->row();
            }
            $Ttq_management = $row;
        }
        $data = array(
            "kategori" => check_kategori_ttq_id($kategori_ttq),
            "result" => $Ttq_management,
        );

        $this->load->library('pdf');

        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "laporan-ttq.pdf";
        $this->pdf->load_view('admin/laporanpdf/ttq', $data);
    }
}
