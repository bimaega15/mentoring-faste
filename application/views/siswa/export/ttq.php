<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN TTQ SAYA.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN TTQ SAYA</title>
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
                <th>No</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Jumlah halaman</th>
                <th>Prodi</th>
                <th>Dosen PA</th>
                <th>Keterangan</th>
                <th>Kategori Ttq</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($row as $result) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= tanggal_indo($result->tanggal); ?></td>
                    <td><?= ($result->waktu); ?></td>
                    <td><?= ($result->jumlah_halaman); ?></td>
                    <td><?= $result->kelas_siswa; ?></td>
                    <td><?= val_guru_kelas($result->id_kelas); ?></td>
                    <td><?= $result->keterangan_ttq; ?></td>
                    <td><?= check_kategori_ttq_id($result->kategori_ttq_id); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>