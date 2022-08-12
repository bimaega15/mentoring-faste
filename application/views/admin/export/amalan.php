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
        <h1>Sholat Wajib</h1>
        <?php if ($keterangan == 'bulanan') : ?>
            <?php foreach ($banding as $result_bulanan) : ?>
                <h2>Bulan : <?= bulan_laporan($result_bulanan) ?></h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th colspan="2">Subuh</th>
                            <th colspan="2">Dzuhur</th>
                            <th colspan="2">Ashar</th>
                            <th colspan="2">Maghrib</th>
                            <th colspan="2">Isya</th>
                            <th rowspan="2">Siswa</th>
                        </tr>
                        <tr>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = 1;
                        foreach ($laporan as $result) :  ?>
                            <tr>
                                <td class="str"><?= $no++; ?></td>
                                <td class="str"><?= tanggal_indo($result->tanggal); ?></td>
                                <td class="str"><?= $result->subuh == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_subuh; ?></td>
                                <td class="str"><?= $result->dzuhur == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_dzuhur; ?></td>
                                <td class="str"><?= $result->ashar == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_ashar; ?></td>
                                <td class="str"><?= $result->maghrib == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_maghrib; ?></td>
                                <td class="str"><?= $result->isya == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_isya; ?></td>
                                <td class="str"><?= check_siswa_lengkap($result->siswa_id); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($keterangan == 'harian') : ?>
            <table border="1">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th colspan="2">Subuh</th>
                        <th colspan="2">Dzuhur</th>
                        <th colspan="2">Ashar</th>
                        <th colspan="2">Maghrib</th>
                        <th colspan="2">Isya</th>
                        <th rowspan="2">Siswa</th>
                    </tr>
                    <tr>
                        <th>Melaksanakan</th>
                        <th>Waktu</th>
                        <th>Melaksanakan</th>
                        <th>Waktu</th>
                        <th>Melaksanakan</th>
                        <th>Waktu</th>
                        <th>Melaksanakan</th>
                        <th>Waktu</th>
                        <th>Melaksanakan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $no = 1;
                    foreach ($laporan as $result) :  ?>
                        <tr>
                            <td class="str"><?= $no++; ?></td>
                            <td class="str"><?= tanggal_indo($result->tanggal); ?></td>
                            <td class="str"><?= $result->subuh == "y" ? "1" : "0"; ?></td>
                            <td class="str"><?= $result->submit_subuh; ?></td>
                            <td class="str"><?= $result->dzuhur == "y" ? "1" : "0"; ?></td>
                            <td class="str"><?= $result->submit_dzuhur; ?></td>
                            <td class="str"><?= $result->ashar == "y" ? "1" : "0"; ?></td>
                            <td class="str"><?= $result->submit_ashar; ?></td>
                            <td class="str"><?= $result->maghrib == "y" ? "1" : "0"; ?></td>
                            <td class="str"><?= $result->submit_maghrib; ?></td>
                            <td class="str"><?= $result->isya == "y" ? "1" : "0"; ?></td>
                            <td class="str"><?= $result->submit_isya; ?></td>
                            <td class="str"><?= check_siswa_lengkap($result->siswa_id); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($keterangan == 'mingguan') : ?>
            <?php foreach ($banding as $result_mingguan) : ?>
                <h4>Minggu ke : <?= $result_mingguan ?></h4>
                <table border="1">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th colspan="2">Subuh</th>
                            <th colspan="2">Dzuhur</th>
                            <th colspan="2">Ashar</th>
                            <th colspan="2">Maghrib</th>
                            <th colspan="2">Isya</th>
                            <th rowspan="2">Siswa</th>
                        </tr>
                        <tr>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                            <th>Melaksanakan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = 1;
                        foreach ($laporan as $result) :  ?>
                            <tr>
                                <td class="str"><?= $no++; ?></td>
                                <td class="str"><?= tanggal_indo($result->tanggal); ?></td>
                                <td class="str"><?= $result->subuh == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_subuh; ?></td>
                                <td class="str"><?= $result->dzuhur == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_dzuhur; ?></td>
                                <td class="str"><?= $result->ashar == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_ashar; ?></td>
                                <td class="str"><?= $result->maghrib == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_maghrib; ?></td>
                                <td class="str"><?= $result->isya == "y" ? "1" : "0"; ?></td>
                                <td class="str"><?= $result->submit_isya; ?></td>
                                <td class="str"><?= check_siswa_lengkap($result->siswa_id); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <h1>Sholat Sunah</h1>
        <?php if ($keterangan_sunah == 'bulanan') : ?>
            <?php foreach ($banding_sunah as $result_bulanan) : ?>
                <h4>Bulan : <?= bulan_laporan($result_bulanan) ?></h4>
                <table border="1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Siswa</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = 1;
                        foreach ($laporan_sunah as $result) :  ?>
                            <tr>
                                <td class="str"><?= $no++; ?></td>
                                <td class="str"><?= tanggal_indo($result->tanggal); ?></td>
                                <td class="str"><?= $result->waktu; ?></td>
                                <td class="str"><?= $result->nama; ?></td>
                                <td class="str"><?= $result->keterangan; ?></td>
                                <td class="str"><?= check_siswa_lengkap($result->siswa_id); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($keterangan_sunah == 'harian') : ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Judul</th>
                        <th>Keterangan</th>
                        <th>Siswa</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $no = 1;
                    foreach ($laporan_sunah as $result) :  ?>
                        <tr>
                            <td class="str"><?= $no++; ?></td>
                            <td class="str"><?= tanggal_indo($result->tanggal); ?></td>
                            <td class="str"><?= $result->waktu; ?></td>
                            <td class="str"><?= $result->nama; ?></td>
                            <td class="str"><?= $result->keterangan; ?></td>
                            <td class="str"><?= check_siswa_lengkap($result->siswa_id); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($keterangan_sunah == 'mingguan') : ?>
            <?php foreach ($banding_sunah as $result_mingguan) : ?>
                <h4>Minggu ke : <?= $result_mingguan ?></h4>
                <table border="1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Siswa</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = 1;
                        foreach ($laporan_sunah as $result) :  ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= tanggal_indo($result->tanggal); ?></td>
                                <td><?= $result->waktu; ?></td>
                                <td><?= $result->nama; ?></td>
                                <td><?= $result->keterangan; ?></td>
                                <td><?= check_siswa_lengkap($result->siswa_id); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>