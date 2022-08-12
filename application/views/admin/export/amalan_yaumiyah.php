<?php
$siswa = check_siswa_lengkap($id_siswa);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN " . $siswa . " " . strtoupper($bulan_tahun) . " .xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN <?= strtoupper($bulan_tahun) ?></title>
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
    <table border="1">
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
                            <?= check_laporan_yaumiyah($i, $result->id_sub_kategori, $bulan_amalan, $id_siswa, $kelas_siswa) ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tbody>
        </tbody>
    </table>
</body>

</html>