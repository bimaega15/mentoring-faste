<style>
    section#wrapper ul.breadcrumb {
        float: right;
        background: none !important;
        border: none !important;
        outline: none;
        box-shadow: none !important;
    }

    section#wrapper ul.breadcrumb li a {
        color: #3f4441;
        background-color: none !important;
    }

    section#wrapper ul.breadcrumb li.active {
        color: #5e6f64;
    }
</style>
<br><br><br><br>
<section id="wrapper" class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="poppins float-left pt-3"><?= $title; ?></div>
            <?= $breadcrumb; ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h3><i>Laporan Amalan Yaumiyah</i></h3>
            </div>

            <?= form_open(base_url('Siswa/RiwayatYaumiyah/laporan'), ['method' => 'get']) ?>

            <div class="row mb-3">
                <div class="col-lg-3 col-sm-3">
                    <select name="bulan" class="form-control" id="">
                        <option value="">Silahkan pilih bulan</option>
                        <?php
                        $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                        $jlh_bln = count($bulan);
                        for ($c = 0; $c < $jlh_bln; $c += 1) {
                            echo "<option value='" . (intval($c) + intval(1)) . "'> $bulan[$c] </option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <?php
                    $now = date('Y');
                    echo "<select name='tahun' class='form-control'>";
                    for ($a = date('Y'); $a >= 2010; $a--) {
                        echo "<option value='$a'>$a</option>";
                    }
                    echo "</select>";
                    ?>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <button type="submit" name="submit" class="btn btn-primary"> <i class="fas fa-search"></i> Search</button>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <a href="<?= base_url('Siswa/RiwayatYaumiyah/laporan?bulan=' . $bulan_amalan . '&tahun=' . $tahun . '&export=yes') ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
                </div>
            </div>

            <?= form_close() ?>

            <div class="row" style="min-height: 400px;">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped text-center table-bordered" id="RiwayatTtq">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Kegiatan</th>
                                    <th colspan="<?= $hari; ?>">Laporan Bulan <?= $bulan_tahun; ?></th>
                                </tr>
                                <tr>
                                    <?php for ($i = 1; $i <= $hari; $i++) : ?>
                                        <th>
                                            <?= $i; ?>
                                        </th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($sub_kategori as $result) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $result->nama; ?></td>
                                        <?php for ($i = 1; $i <= $hari; $i++) : ?>
                                            <td>
                                                <?= check_laporan_yaumiyah($i, $result->id_sub_kategori, $bulan_amalan, '', $kelas_siswa_id) ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>