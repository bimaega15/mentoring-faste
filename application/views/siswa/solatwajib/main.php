
<section id="wrapper" class="container mt-5">

    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="<?= base_url('Siswa/SolatWajib/detailSolatWajib/' . $id_kategori) ?>" class="btn btn-primary shadow-sm"><i class="fas fa-eye"></i> Lihat Detail</a>
        </div>
        <div class="col-lg-12">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr class="text-center">
                        <th colspan="5">Solat Wajib - <?= tanggal_indo($row->tanggal); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Subuh</td>
                        <td>Dzuhur</td>
                        <td>Ashar</td>
                        <td>Maghrib</td>
                        <td>Isya</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="subuh" type="checkbox" id="subuh" value="y" <?= $row->subuh == 'y' ? 'checked' : ''; ?>>
                                <label class="form-check-label waktu_subuh" for="subuh" style="font-size:12px;"><?= $row->submit_subuh; ?></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="dzuhur" type="checkbox" id="dzuhur" value="y" <?= $row->dzuhur == 'y' ? 'checked' : ''; ?>>
                                <label class="form-check-label waktu_dzuhur" for="dzuhur" style="font-size:12px;"><?= $row->submit_dzuhur; ?></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="ashar" type="checkbox" id="ashar" value="y" <?= $row->ashar == 'y' ? 'checked' : ''; ?>>
                                <label class="form-check-label waktu_ashar" for="ashar" style="font-size:12px;"><?= $row->submit_ashar; ?></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="maghrib" type="checkbox" id="maghrib" value="y" <?= $row->maghrib == 'y' ? 'checked' : ''; ?>>
                                <label class="form-check-label waktu_maghrib" for="maghrib" style="font-size:12px;"><?= $row->submit_maghrib; ?></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="isya" type="checkbox" id="isya" value="y" <?= $row->isya == 'y' ? 'checked' : ''; ?>>
                                <label class="form-check-label waktu_isya" for="isya" style="font-size:12px;"><?= $row->submit_isya; ?></label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $(document).on('click', '#subuh', function() {
            if ($(this).is(':checked')) {
                var val = $(this).val();
                $.ajax({
                    url: "<?= base_url('Siswa/SolatWajib/insertSolatWajib') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        val: val,
                        judul: 'subuh',
                        id_kategori: "<?= $id_kategori ?>"
                    },
                    success: function(data) {
                        console.log(data);
                        $('.waktu_subuh').html(data.waktu);
                        Swal.fire({
                            icon: data.status,
                            title: '<strong>' + data.title + '</strong>',
                            html: '<i>' + data.msg + '</i>',
                        });
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            }

        })
        $(document).on('click', '#dzuhur', function() {
            if ($(this).is(':checked')) {
                var val = $(this).val();
                $.ajax({
                    url: "<?= base_url('Siswa/SolatWajib/insertSolatWajib') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        val: val,
                        judul: 'dzuhur',
                        id_kategori: "<?= $id_kategori ?>"
                    },
                    success: function(data) {
                        console.log(data);
                        $('.waktu_dzuhur').html(data.waktu);
                        Swal.fire({
                            icon: data.status,
                            title: '<strong>' + data.title + '</strong>',
                            html: '<i>' + data.msg + '</i>',
                        });
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            }
        })
        $(document).on('click', '#ashar', function() {
            if ($(this).is(':checked')) {
                var val = $(this).val();

                $.ajax({
                    url: "<?= base_url('Siswa/SolatWajib/insertSolatWajib') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        val: val,
                        judul: 'ashar',
                        id_kategori: "<?= $id_kategori ?>"
                    },
                    success: function(data) {
                        console.log(data);
                        $('.waktu_ashar').html(data.waktu);
                        Swal.fire({
                            icon: data.status,
                            title: '<strong>' + data.title + '</strong>',
                            html: '<i>' + data.msg + '</i>',
                        });
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            }
        })
        $(document).on('click', '#maghrib', function() {
            if ($(this).is(':checked')) {
                var val = $(this).val();
                $.ajax({
                    url: "<?= base_url('Siswa/SolatWajib/insertSolatWajib') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        val: val,
                        judul: 'maghrib',
                        id_kategori: "<?= $id_kategori ?>"
                    },
                    success: function(data) {
                        console.log(data);
                        $('.waktu_maghrib').html(data.waktu);
                        Swal.fire({
                            icon: data.status,
                            title: '<strong>' + data.title + '</strong>',
                            html: '<i>' + data.msg + '</i>',
                        });
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            }
        })
        $(document).on('click', '#isya', function() {
            if ($(this).is(':checked')) {
                var val = $(this).val();
                $.ajax({
                    url: "<?= base_url('Siswa/SolatWajib/insertSolatWajib') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        val: val,
                        judul: 'isya',
                        id_kategori: "<?= $id_kategori ?>"
                    },
                    success: function(data) {
                        console.log(data);
                        $('.waktu_isya').html(data.waktu);

                        Swal.fire({
                            icon: data.status,
                            title: '<strong>' + data.title + '</strong>',
                            html: '<i>' + data.msg + '</i>',
                        });
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            }
        })

    })

</script>