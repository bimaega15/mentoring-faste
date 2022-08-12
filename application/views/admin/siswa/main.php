<style>
    .modal-body.Siswa {
        height: 500px;
        overflow-y: auto;
    }
</style>
<div class="right_col" role="main" style="min-height: 676px;">
    <div class="">
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

        <div class="row" style="display: block;">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <?php if ($create) : ?>
                                <a href="<?= base_url('Admin/Siswa/add') ?>" class="btn btn-primary btn-rounded"><i class="fas fa-plus"></i> Tambah Data</a>
                            <?php endif; ?>
                            <?php if ($delete) : ?>
                                <button type="button" id="delete_all_Siswa" class="btn btn-danger btn-rounded">
                                    <div class="fas fa-trash-alt"></div> Hapus
                                </button>
                            <?php endif; ?>

                            <?php if ($create) : ?>
                                <button type="button" class="btn btn-secondary btn-rounded" data-toggle="modal" data-target="#modalSiswa"><i class="fas fa-file-import"></i> Import</button>
                                <a href="<?= base_url('Admin/Siswa/export') ?>" class="btn btn-secondary btn-rounded btn_export"><i class="fas fa-file-export"></i> Export</a>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="x_content table-responsive">
                        <table class="table table-bordered" id="table_Siswa">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checked_all_Siswa">
                                            <label class="custom-control-label" for="checked_all_Siswa"></label>
                                        </div>
                                    </th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Alamat</th>
                                    <th>No. Telephone</th>
                                    <th>Jenis kelamin</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalSiswa" tabindex="-1" role="dialog" aria-labelledby="modalSiswaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary font-weight-bold">
                <h5 class="modal-title text-white" id="modalSiswaLabel"><i class="fas fa-file-excel"></i> Import Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart(base_url('Admin/Siswa/importData')) ?>
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
<div class="modal fade" id="modalSiswaDetail" tabindex="-1" role="dialog" aria-labelledby="modalSiswaDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSiswaDetailLabel"><i class="fas fa-pencil-alt"></i> &nbsp;Detail Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body Siswa">
                <div id="ouput_detail_Siswa"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-check"></i> Ok</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $(document).on('click', '#delete_all_Siswa', function() {
            var arr = [];
            $('.check_item_Siswa:checked').each(function() {
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
                            url: "<?= base_url('Admin/Siswa/deleteAllSiswa') ?>",
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
        $(document).on('click', '#checked_all_Siswa', function() {
            if ($(this).is(':checked')) {
                $('.check_item_Siswa').prop('checked', true);
            } else {
                $('.check_item_Siswa').prop('checked', false);
            }
        })
        $(document).on('click', '.check_item_Siswa', function() {
            if ($('.check_item_Siswa:checked').length == $('.check_item_Siswa').length) {
                $('#checked_all_Siswa').prop('checked', true);
            } else {
                $('#checked_all_Siswa').prop('checked', false);
            }
        })
        $('#table_Siswa').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Admin/Siswa/server_Siswa') ?>",
                "type": "GET",
            },
            "columnDefs": [{
                "targets": [0, 5, 6, 7],
                "orderable": false,
            }, ],
        });
        $(document).on('click', ".delete_Siswa", function(e) {
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
                        url: "<?= base_url('Admin/Siswa/delete') ?>",
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
                            $('.delete_Siswa[data-id="' + data.id + '"]').closest('tr').load('<?= current_url() ?> .delete_Siswa[data-id="' + data.id + '"]');
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
            $('.check_item_Siswa:checked').each(function() {
                arr.push($(this).val());
            });
            if (arr != '') {
                $(this).attr('href', '<?= base_url('Admin/Siswa/export?checked=') ?>' + arr);
            }
        })
        $(document).on('click', '.detailSiswa', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: "<?= base_url('Admin/Siswa/detail') ?>",
                method: 'post',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $('#ouput_detail_Siswa').html('');
                    $('#ouput_detail_Siswa').html(data);
                },
                error: function(x, t, m) {
                    console.log(x.responseText);
                }
            })
        })
    })
</script>