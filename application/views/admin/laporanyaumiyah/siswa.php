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
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tableSiswa">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Induk</th>
                                    <th>Nama Siswa</th>
                                    <th>No. Telephone</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Gambar</th>
                                    <th>Lihat Laporan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <?= form_open(base_url('Admin/LaporanYaumiyah/getLaporan'), ['method' => 'get']) ?>
            <div class="row mt-3" style="display: block;">

                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4" style="margin-top: 26px;">
                    <label for=""></label>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search"></i> Lihat</button>
                </div>



            </div>
            <?= form_close(); ?> -->
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalYaumiyah" tabindex="-1" role="dialog" aria-labelledby="modalYaumiyahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary font-weight-bold">
                <h5 class="modal-title text-white" id="modalYaumiyahLabel"><i class="fas fa-file-excel"></i> Import Yaumiyah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart(base_url('Admin/Yaumiyah/importData')) ?>
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
        $(document).on('click', '#delete_all_Yaumiyah', function() {
            var arr = [];
            $('.check_item_Yaumiyah:checked').each(function() {
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
                            url: "<?= base_url('Admin/Yaumiyah/deleteAllYaumiyah') ?>",
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
        $(document).on('click', '#checked_all_Yaumiyah', function() {
            if ($(this).is(':checked')) {
                $('.check_item_Yaumiyah').prop('checked', true);
            } else {
                $('.check_item_Yaumiyah').prop('checked', false);
            }
        })
        $(document).on('click', '.check_item_Yaumiyah', function() {
            if ($('.check_item_Yaumiyah:checked').length == $('.check_item_Yaumiyah').length) {
                $('#checked_all_Yaumiyah').prop('checked', true);
            } else {
                $('#checked_all_Yaumiyah').prop('checked', false);
            }
        })
        $('#tableSiswa').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Admin/LaporanYaumiyah/serverSiswaKelas') ?>",
                "type": "GET",
                data: {
                    id_kelas: "<?= $id_kelas ?>"
                },
            },
            "columnDefs": [{
                "targets": [],
                "orderable": false,
            }, ],
        });
        $(document).on('click', ".delete_Yaumiyah", function(e) {
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
                        url: "<?= base_url('Admin/Yaumiyah/delete') ?>",
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
                            $('.delete_Yaumiyah[data-id="' + data.id + '"]').closest('tr').load('<?= current_url() ?> .delete_Yaumiyah[data-id="' + data.id + '"]');
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
            $('.check_item_Yaumiyah:checked').each(function() {
                arr.push($(this).val());
            });
            if (arr != '') {
                $(this).attr('href', '<?= base_url('Admin/Yaumiyah/export?checked=') ?>' + arr);
            }
        })
    })
</script>