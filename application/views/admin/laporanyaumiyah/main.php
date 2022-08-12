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
            <?= form_open(base_url('Admin/LaporanYaumiyah/siswa/' . $id_siswa), ['method' => 'get']) ?>
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
                    <a href="<?= base_url('Admin/LaporanYaumiyah/siswa/' . $id_siswa . '?export=yes') ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
                </div>
            </div>
            <?= form_close(); ?>

            <div class="row">
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
                                                <?= check_laporan_yaumiyah($i, $result->id_sub_kategori, $bulan_amalan, $id_siswa, $kelas_siswa) ?>
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
    })
</script>