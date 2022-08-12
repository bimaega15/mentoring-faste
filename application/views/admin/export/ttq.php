<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN " . strtoupper($keterangan) . " .xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN <?= strtoupper($keterangan) ?></title>
    <style>
        table {
            border-collapse: collapse;
            overflow: auto;
            width: 100%;
        }

        .str {
            mso-number-format: \@;
        }

        thead>tr {
            background-color: #0070C0;
            color: #f1f1f1;
        }

        thead>tr>th {
            background-color: #0070C0;
            color: #fff;
            padding: 10px;
            border-color: #fff;
        }

        th,
        td {
            padding: 2px;
            text-align: center !important;
        }

        th {
            color: #222;
        }

        body {
            font-family: Calibri;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }
    </style>
</head>

<body>
    <?php if (isset($keterangan)) : ?>
        <?php if ($keterangan == 'bulanan') : ?>

            <div style="text-align: center;">
                <h1>Laporan Bulanan</h1>
                <hr>
            </div>
            <?php if ($banding != null) : ?>
                <?php foreach ($banding as $result_bulanan) : ?>
                    <h4>Bulan : <?= bulan_laporan($result_bulanan) ?></h4>
                    <table width="100%;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Keterangan</th>
                                <th>Jumlah halaman</th>
                                <th>Siswa</th>
                                <th>Kelas Siswa</th>
                                <th>Guru Kelas</th>
                                <th>Kategori Ttq</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($laporan as $result) :  ?>
                                <?php if ($result->bulanan == $result_bulanan) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= tanggal_indo($result->tanggal); ?></td>
                                        <td><?= $result->waktu; ?></td>
                                        <td><?= $result->keterangan; ?></td>
                                        <td><?= $result->jumlah_halaman; ?></td>
                                        <td><?= check_siswa_lengkap($result->siswa_id); ?></td>
                                        <td><?= check_kelasSiswa($result->kelas_siswa_id); ?></td>
                                        <td><?= val_guru_kelas($result->kelas_id); ?></td>
                                        <td><?= check_kategori_ttq_id($result->kategori_ttq_id); ?></td>

                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($keterangan == 'harian') : ?>

            <div style="text-align: center;">
                <h1>Laporan Harian</h1>
                <hr>
            </div>
            <table width="100%;" border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                        <th>Jumlah halaman</th>
                        <th>Siswa</th>
                        <th>Kelas Siswa</th>
                        <th>Guru Kelas</th>
                        <th>Kategori Ttq</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($laporan as $result) :  ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= tanggal_indo($result->tanggal); ?></td>
                            <td><?= $result->waktu; ?></td>
                            <td><?= $result->keterangan; ?></td>
                            <td><?= $result->jumlah_halaman; ?></td>
                            <td><?= check_siswa_lengkap($result->siswa_id); ?></td>
                            <td><?= check_kelasSiswa($result->kelas_siswa_id); ?></td>
                            <td><?= val_guru_kelas($result->kelas_id); ?></td>
                            <td><?= check_kategori_ttq_id($result->kategori_ttq_id); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($keterangan == 'mingguan') : ?>

            <div style="text-align: center;">
                <h1>Laporan Mingguan</h1>
                <hr>
            </div>
            <?php if ($banding != null) : ?>
                <?php foreach ($banding as $result_mingguan) : ?>
                    <h4>Minggu ke : <?= $result_mingguan ?></h4>
                    <table width="100%;" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Keterangan</th>
                                <th>Jumlah halaman</th>
                                <th>Siswa</th>
                                <th>Kelas Siswa</th>
                                <th>Guru Kelas</th>
                                <th>Kategori Ttq</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($laporan as $result) :  ?>
                                <?php if ($result->mingguan == $result_mingguan) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= tanggal_indo($result->tanggal); ?></td>
                                        <td><?= $result->waktu; ?></td>
                                        <td><?= $result->keterangan; ?></td>
                                        <td><?= $result->jumlah_halaman; ?></td>
                                        <td><?= check_siswa_lengkap($result->siswa_id); ?></td>
                                        <td><?= check_kelasSiswa($result->kelas_siswa_id); ?></td>
                                        <td><?= val_guru_kelas($result->kelas_id); ?></td>
                                        <td><?= check_kategori_ttq_id($result->kategori_ttq_id); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>
</body>

</html>