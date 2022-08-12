<div class="right_col" role="main" style="min-height: 676px;">
    <div class="page-title">
        <div class="title_left">
            <h3>Tables <small><?= $title; ?></small></h3>
        </div>
    </div>

    <div class="row" style="display: block;">
        <div class="col-lg-12">
            <?= $breadcrumb; ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="card">
        <div class="card-body">
            <?= form_open(base_url('Admin/LaporanTTQ/getLaporan'), ['method' => 'get']) ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-md-3">
                        <label for=""></label>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search"></i> Lihat</button>
                    </div>
                </div>
            </div>
            <div class="row mt-3" style="display: block;">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="laporan_kategori">Laporan Kategori</label>
                        <select name="laporan_kategori" class="form-control">
                            <option value="">Silahkan pilih kategori</option>
                            <option value="per_hari">PER HARI</option>
                            <option value="per_minggu">PER MINGGU</option>
                            <option value="per_bulan">PER BULAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" class="form-control">
                            <option value="">Silahkan pilih tahun</option>
                            <?php
                            $thn_skr = date('Y');
                            for ($x = $thn_skr; $x >= 2010; $x--) {
                            ?>
                                <option value="<?php echo $x ?>"><?php echo $x ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php $roles = get_my_id()['roles']; ?>
                <?php if ($roles == 1) : ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="guru">Dosen</label>
                            <select name="guru" class="form-control">
                                <option value="">Silahkan pilih dosen</option>
                                <?php foreach ($guru as $result) : ?>
                                    <option value="<?= $result->id_guru ?>"><?= $result->nomor_induk; ?> | <?= $result->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="kategori_ttq">Kategori Setoran Hafalan</label>
                        <select name="kategori_ttq" class="form-control">
                            <option value="">Silahkan pilih kategori ttq</option>
                            <?php
                            foreach ($kategori_ttq as $result) { ?>
                                <option value="<?= $result->id_kategori_ttq ?>"><?= $result->nama; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="display:block;">
                <div class="col-md-12">
                    <div class="text-center">
                    <hr>
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="text-right">
                                    <a href="getLaporan?laporan_kategori=per_hari&tahun=&export=y&guru=&kategori_ttq=" class="btn btn-success border-0 shadow-sm"><i class="fas fa-file-excel"></i> Export</a>
                                </div>
                            </div>
                        </div>
                        <h1>Laporan Setoran Hafalan AlQur'an / TTQ</h1>
                        <hr>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-5 tableLaporanTtq">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah halaman</th>
                                    <th>Mahasiswa</th>
                                    <th>Kelas Mahasiswa</th>
                                    <th>Dosen PA</th>
                                    <th>Kategori TTQ</th>
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
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalTtq" tabindex="-1" role="dialog" aria-labelledby="modalTtqLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary font-weight-bold">
                <h5 class="modal-title text-white" id="modalTtqLabel"><i class="fas fa-file-excel"></i> Import Ttq</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart(base_url('Admin/Ttq/importData')) ?>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" name="file" class="custom-file-input" id="file-excel" accept=".xls, .xlsx">
                        <label class="custom-file-label" for="file-excel">Masukan file</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Batal</button>
                <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // $('select[name="tahun"]').select2({
        //     theme: 'bootstrap4',
        // });
        $(document).on('change', 'select[name="laporan_kategori"]', function() {
            var val = $(this).val();
            if (val == 'per_bulan') {
                $('.bulan').hide();
            }
            if (val == 'per_hari') {
                $('.bulan').hide();
            }
        })
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $(document).on('click', '#delete_all_Ttq', function() {
            var arr = [];
            $('.check_item_Ttq:checked').each(function() {
                arr.push($(this).val());
            });
            if (arr != '') {
                Swal.fire({
                    title: "Apakah anda yakin ingin menghapus beberapa item ini?",
                    text: "Silahkan pilih salah satu tombol",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, saya yakin',
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "<?= base_url('Admin/Ttq/deleteAllTtq') ?>",
                            method: 'post',
                            dataType: 'json',
                            data: {
                                data_item: arr
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    title: '<strong>' + data.title + '</strong>',
                                    html: '<i>' + data.msg + '</i>',
                                }).then(function() {
                                    location.reload(true);
                                });
                            },
                            error: function(x, t, m) {
                                console.log(x.responseText);
                            }
                        })
                    }
                })

            } else {
                Swal.fire({
                    title: '<strong>Data kosong</strong>',
                    icon: 'info',
                    html: '<i>Silahkan terlebih dahulu select item pada table</i>',
                    showCloseButton: true,
                    focusConfirm: false,
                })
            }
        })
        $(document).on('click', '#checked_all_Ttq', function() {
            if ($(this).is(':checked')) {
                $('.check_item_Ttq').prop('checked', true);
            } else {
                $('.check_item_Ttq').prop('checked', false);
            }
        })
        $(document).on('click', '.check_item_Ttq', function() {
            if ($('.check_item_Ttq:checked').length == $('.check_item_Ttq').length) {
                $('#checked_all_Ttq').prop('checked', true);
            } else {
                $('#checked_all_Ttq').prop('checked', false);
            }
        })
        $('#table_Ttq').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Admin/Ttq/server_Ttq') ?>",
                "type": "GET",
            },
            "columnDefs": [{
                "targets": [0, 6],
                "orderable": false,
            }, ],
        });
        $(document).on('click', ".delete_Ttq", function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "Apakah anda yakin ingin menghapus ini?",
                text: "Silahkan pilih salah satu tombol",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, saya yakin',
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "<?= base_url('Admin/Ttq/delete') ?>",
                        method: 'post',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                title: '<strong>' + data.title + '</strong>',
                                html: '<i>' + data.msg + '</i>',
                            });
                            $('.delete_Ttq[data-id="' + data.id + '"]').closest('tr').load('<?= current_url() ?> .delete_Ttq[data-id="' + data.id + '"]');
                        },
                        error: function(x, t, m) {
                            console.log(x.responseText);
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn_export', function(e) {
            var arr = [];
            $('.check_item_Ttq:checked').each(function() {
                arr.push($(this).val());
            });
            if (arr != '') {
                $(this).attr('href', '<?= base_url('Admin/Ttq/export?checked=') ?>' + arr);
            }
        })
        // $(document).on('change', 'select[name="laporan_kategori"]', function() {
        //     var val = $(this).val();
        //     var val_tahun = $('select[name="tahun"]').children("option:selected").val();
        //     var val_guru = $('select[name="guru"]').children("option:selected").val();
        //     var val_kategoriTtq = $('select[name="kategori_ttq"]').children("option:selected").val();
        //     load_table_laporanTtq(val, val_tahun, val_guru, val_kategoriTtq);
        // })

        function load_table_laporanTtq(laporanKategori = null, tahun = null, guru = null, kategoriTtq = null) {
            // $('.tableLaporanTtq').DataTable({
            //     "processing": true,
            //     "serverSide": true,
            //     "order": [],
            //     "ajax": {
            //         "url": "<?= base_url('Admin/LaporanTTQ/serverLaporanTtq') ?>",
            //         "type": "GET",
            //         data: {
            //             laporan_kategori: laporanKategori,
            //             tahun: tahun,
            //             guru: guru,
            //             kategori_ttq: kategoriTtq,
            //         },
            //         success: function(data) {
            //             console.log(data);
            //         },
            //         error: function(data) {
            //             console.log(data);
            //         }
            //     },
            //     "columnDefs": [{
            //         "targets": [],
            //         "orderable": false,
            //     }, ],
            // });
            $('.tableLaporanTtq').DataTable();
        }
        load_table_laporanTtq();
    })
</script>