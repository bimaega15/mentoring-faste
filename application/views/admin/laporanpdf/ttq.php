<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $kategori; ?></title>
    <style>
        .text-center {
            text-align: center;
        }

        table.table {
            width: 100%;
        }

        table,
        td,
        th {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1 class="text-center">
        Kegiatan <?= $kategori; ?>
    </h1>
    <table class="table">
        <thead>
            <tr>
                <th>
                    No.
                </th>
                <th>Tanggal</th>
                <th>Surah</th>
                <th>Status Bacaan</th>
                <th>Siswa</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($result as $row) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= ($row->tanggal) . " " . waktu_format($row->waktu)  ?></td>
                    <td><?= $row->surah  ?></td>
                    <td><?= $row->status_bacaan ?></td>
                    <td><?= $row->nama_siswa_kelas ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>