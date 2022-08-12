<?php
class Ttq_model extends CI_Model
{
    var $roles = '';
    var $users = '';
    var $getusers = '';

    public function __construct()
    {
        parent::__construct();
        $this->roles .= $this->session->userdata('roles_id');
        $this->users .= $this->session->userdata('users_id');
        $this->getusers = $this->db->get_where('siswa', ['users_id' => $this->users])->row();
    }
    public function get($id = null, $id_kategori_ttq = null)
    {
        $this->db->select('*');
        $this->db->from('ttq');
        if ($id != null) {
            $this->db->where('id_ttq', $this->db->escape_str($id));
        }
        if ($id_kategori_ttq != null) {
            $this->db->where('kategori_ttq_id', $id_kategori_ttq);
        }
        return $this->db->get();
    }
    public function getLaporanPerBulan($tahun = null, $guru = null, $kategori_ttq = null)
    {
        $this->db->select('*,MONTH(ttq.tanggal) bulanan');
        $this->db->from('ttq');
        $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
        $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        if ($tahun != null) {
            $this->db->where('YEAR(ttq.tanggal)',  $this->db->escape_str($tahun));
        }
        if ($this->roles == 2 || $guru != null) {
            $this->db->join('kelas_detail', 'kelas.id_kelas = kelas_detail.kelas_id');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            if ($guru != null) {
                $this->db->where('guru.id_guru', $guru);
            } else {
                $this->db->where('guru.users_id', $this->users);
            }
        }
        if ($kategori_ttq != null) {
            $this->db->where('kategori_ttq.id_kategori_ttq',  $this->db->escape_str($kategori_ttq));
        }
        $this->db->group_by('ttq.id_ttq');
        $this->db->order_by('YEAR(ttq.tanggal)', 'DESC');
        $this->db->order_by('(ttq.tanggal)', 'ASC');
        return $this->db->get();
    }

    public function getLaporanPerMinggu($tahun = null, $guru = null, $kategori_ttq = null)
    {
        $this->db->select('*,WEEK(ttq.tanggal) mingguan');
        $this->db->from('ttq');
        $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
        $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        if ($tahun != null) {
            $this->db->where('YEAR(ttq.tanggal)',  $this->db->escape_str($tahun));
        }
        if ($this->roles == 2 || $guru != null) {
            $this->db->join('kelas_detail', 'kelas.id_kelas = kelas_detail.kelas_id');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            if ($guru != null) {
                $this->db->where('guru.id_guru', $guru);
            } else {
                $this->db->where('guru.users_id', $this->users);
            }
        }
        if ($kategori_ttq != null) {
            $this->db->where('kategori_ttq.id_kategori_ttq',  $this->db->escape_str($kategori_ttq));
        }
        $this->db->group_by('ttq.id_ttq');
        $this->db->order_by('YEAR(ttq.tanggal)', 'DESC');
        $this->db->order_by('ttq.tanggal', 'ASC');

        return $this->db->get();
    }
    public function getLaporanPerHari($tahun = null, $guru = null, $kategori_ttq = null)
    {
        $this->db->select('*');
        $this->db->from('ttq');
        $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
        $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        if ($tahun != null) {
            $this->db->where('YEAR(ttq.tanggal)',  $this->db->escape_str($tahun));
        }
        if ($this->roles == 2 || $guru != null) {
            $this->db->join('kelas_detail', 'kelas.id_kelas = kelas_detail.kelas_id');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            if ($guru != null) {
                $this->db->where('guru.id_guru', $guru);
            } else {
                $this->db->where('guru.users_id', $this->users);
            }
        }
        if ($kategori_ttq != null) {
            $this->db->where('kategori_ttq.id_kategori_ttq',  $this->db->escape_str($kategori_ttq));
        }
        $this->db->group_by('ttq.id_ttq');
        $this->db->order_by('YEAR(ttq.tanggal)', 'DESC');
        $this->db->order_by('(ttq.tanggal)', 'ASC');
        return $this->db->get();
    }

    public function update($data, $id)
    {
        $this->db->where('id_ttq', $this->db->escape_str($id));
        $this->db->update('ttq', $data);
        return $this->db->affected_rows();
    }
    public function insert($data)
    {
        $this->db->insert('ttq', $data);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where('id_ttq', $id);
        $this->db->delete('ttq');
        return $this->db->affected_rows();
    }
    public function getAllJoin($kelas_siswa = null)
    {
        $this->db->select('*,kelas.nama nama_kelas,tahun_akademik.nama nama_tahun');
        $this->db->from('kelas_siswa');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
        if ($kelas_siswa != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $kelas_siswa);
        }
        if ($this->roles == '2') {
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('guru.users_id', $this->users);
        }
        return $this->db->get();
    }
    public function getAllTTQ()
    {
        $this->db->select('ttq.keterangan keterangan_ttq,ttq.tanggal,ttq.waktu,ttq.siswa_id,siswa.nama,siswa.nomor_induk,ttq.id_ttq,ttq.kategori_ttq_id, CONCAT(siswa.nomor_induk, " | ", siswa.nama) nama_siswa_kelas,CONCAT(kelas.tingkat, " | ", kelas.nama," | ",tahun_akademik.nama) kelas_siswa,kelas.id_kelas,kategori_ttq.nama nama_kategori_ttq');
        $this->db->from('ttq');
        $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
        $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
        $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');


        if ($this->roles == 2) {
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('guru.users_id', $this->users);
        }
        if ($this->roles == 3) {
            $this->db->where('siswa.id_siswa', $this->getusers->id_siswa);
        }

        $this->db->group_by('ttq.id_ttq');
        return $this->db->get();
    }
    public function joinKelasGuru($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas_siswa');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->where('kelas_siswa.id_kelas_siswa', $id);
        return $this->db->get();
    }
    public function joinSiswaKelas($id_siswa, $id_ttq)
    {
        $this->db->select('*');
        $this->db->from('ttq');
        $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
        $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
        if ($id_siswa != null) {
            $this->db->where('ttq.siswa_id', $id_siswa);
        }
        if ($id_ttq != null) {
            $this->db->where('ttq.id_ttq', $id_ttq);
        }
        $this->db->group_by('ttq.id_ttq');
        return $this->db->get();
    }
    public function getKelasSiswa($id_siswa)
    {
        $this->db->select('CONCAT(kelas.tingkat," | ",kelas.nama," | ",tahun_akademik.nama) kelas_siswa');
        $this->db->from('siswa_kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
        $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
        if ($id_siswa != null) {
            $this->db->where('siswa_kelas.siswa_id', $id_siswa);
        }
        return $this->db->get();
    }
    public function getKelasTtq($id_kelas = null)
    {
        $this->db->select('kelas.tingkat,kelas.nama,tahun_akademik.nama nama_tahun,kelas.id_kelas');
        $this->db->from('kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.kelas_id = kelas.id_kelas');
        $this->db->join('tahun_akademik', 'kelas_siswa.tahun_akademik_id = tahun_akademik.id_tahun_akademik');
        if ($id_kelas != null) {
            $this->db->where('kelas.id_kelas', $id_kelas);
        }
        return $this->db->get();
    }
    public function getKelasSiswaTtq($id_kelas = null)
    {
        $this->db->select('kelas.tingkat,kelas.nama,tahun_akademik.nama nama_tahun,kelas.id_kelas,siswa_kelas.siswa_id');
        $this->db->from('kelas');
        $this->db->join('kelas_siswa', 'kelas_siswa.kelas_id = kelas.id_kelas');
        $this->db->join('siswa_kelas', 'siswa_kelas.kelas_siswa_id = kelas_siswa.id_kelas_siswa');
        $this->db->join('tahun_akademik', 'kelas_siswa.tahun_akademik_id = tahun_akademik.id_tahun_akademik');
        if ($id_kelas != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $id_kelas);
        }
        return $this->db->get();
    }
    public function getFromServerTtq($id_ttq = null, $id_kelas = null, $id_kategori_ttq = null, $guru = null, $tahun = null, $start_date = null, $end_date = null)
    {

        if ($this->roles == 1) {
            $this->db->distinct('ttq.id_ttq');
            $this->db->select('CONCAT(ttq.tanggal, "  ", ttq.waktu) as tanggal_waktu,ttq.keterangan keterangan_ttq,ttq.tanggal,ttq.waktu,ttq.siswa_id,siswa.nama,siswa.nomor_induk,ttq.id_ttq,ttq.kategori_ttq_id, CONCAT(siswa.nomor_induk, " | ", siswa.nama) nama_siswa_kelas,CONCAT(kelas.tingkat, " | ", kelas.nama," | ",tahun_akademik.nama," | ",guru.nama) kelas_siswa,kelas.id_kelas,kategori_ttq.nama nama_kategori_ttq,kelas_siswa.id_kelas_siswa,ttq.status_bacaan,ttq.surah');
            $this->db->from('ttq');
            $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
            $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
            $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
            $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
            $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
            $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas', 'left');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id', 'left');
        }

        if ($this->roles == 2) {
            $this->db->distinct('ttq.id_ttq');
            $this->db->select('ttq.keterangan keterangan_ttq,ttq.tanggal,ttq.waktu,ttq.siswa_id,siswa.nama,siswa.nomor_induk,ttq.id_ttq,ttq.kategori_ttq_id, CONCAT(siswa.nomor_induk, " | ", siswa.nama) nama_siswa_kelas,CONCAT(kelas.tingkat, " | ", kelas.nama," | ",tahun_akademik.nama," | ",guru.nama) kelas_siswa,kelas.id_kelas,kategori_ttq.nama nama_kategori_ttq,kelas_siswa.id_kelas_siswa,ttq.status_bacaan,ttq.surah');
            $this->db->from('ttq');
            $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
            $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
            $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
            $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
            $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
            $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('guru.users_id', $this->users);
        }

        if ($this->roles == 3) {
            $this->db->distinct('ttq.id_ttq');
            $this->db->select('ttq.keterangan keterangan_ttq,ttq.tanggal,ttq.waktu, ttq.siswa_id,siswa.nama,siswa.nomor_induk,ttq.id_ttq,ttq.kategori_ttq_id, CONCAT(siswa.nomor_induk, " | ", siswa.nama) nama_siswa_kelas,CONCAT(kelas.tingkat, " | ", kelas.nama," | ",tahun_akademik.nama," | ",guru.nama) kelas_siswa,kelas.id_kelas,kategori_ttq.nama nama_kategori_ttq,kelas_siswa.id_kelas_siswa, ttq.status_bacaan,ttq.surah');
            $this->db->from('ttq');
            $this->db->join('siswa', 'siswa.id_siswa = ttq.siswa_id');
            $this->db->join('kategori_ttq', 'kategori_ttq.id_kategori_ttq = ttq.kategori_ttq_id');
            $this->db->join('siswa_kelas', 'siswa_kelas.siswa_id = siswa.id_siswa');
            $this->db->join('kelas_siswa', 'kelas_siswa.id_kelas_siswa = siswa_kelas.kelas_siswa_id');
            $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun_akademik = kelas_siswa.tahun_akademik_id');
            $this->db->join('kelas', 'kelas.id_kelas = kelas_siswa.kelas_id');
            $this->db->join('kelas_detail', 'kelas_detail.kelas_id = kelas.id_kelas');
            $this->db->join('guru', 'guru.id_guru = kelas_detail.guru_id');
            $this->db->where('siswa.id_siswa', $this->getusers->id_siswa);
        }

        if ($id_ttq != null) {
            $this->db->where('ttq.id_ttq', $id_ttq);
        }

        if ($id_kategori_ttq != null) {
            $this->db->where('ttq.kategori_ttq_id', $id_kategori_ttq);
        }
        if ($id_kelas != null) {
            $this->db->where('kelas_siswa.id_kelas_siswa', $id_kelas);
        }
        if ($guru != null) {
            $this->db->where('guru.id_guru', $guru);
        }

        if ($tahun != null) {
            $this->db->where('YEAR(ttq.tanggal)',  $tahun);
        }
        if ($start_date != null && $end_date != null) {
            $this->db->where('ttq.tanggal >= ',  $start_date);
            $this->db->where('ttq.tanggal <= ',  $end_date);
        }
        $this->db->group_by('ttq.id_ttq');

        return $this->db->get();
    }
    public function checkedRolesYaumiyah($id_guru)
    {
        $this->db->select('*');
        $this->db->from('roles_yaumiyah');
        $this->db->join('roles_yaumiyah_detail', 'roles_yaumiyah_detail.roles_yaumiyah_id = roles_yaumiyah.id_roles_yaumiyah');
        $this->db->where('roles_yaumiyah.users_id', $id_guru);
        $this->db->order_by('roles_yaumiyah.id_roles_yaumiyah', 'DESC');
        return $this->db->get();
    }
}
