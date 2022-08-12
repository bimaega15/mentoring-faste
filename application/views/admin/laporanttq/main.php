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
                <div class="col-md-3">
                    <a href="#" class="btn_reset btn btn-warning mt-4"><i class="fas fa-redo-alt"></i> Reset</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <input type="text" id="start_date" name="start_date" class="form-control datepicker" placeholder="Start Date...">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="end_date" name="end_date" class="form-control datepicker" placeholder="End Date...">
                                </div>
                                <div class="col-lg-4">
                                    <a href="" class="btn btn-primary btn-search"><i class="fas fa-search    "></i></a>
                                </div>
                            </div>
                            <div class="btn-group" role="group" aria-label="Basic example">

                                <a href="<?= base_url('Admin/LaporanTTQ/export/' . $id_kelas) ?>" class="btn btn-secondary btn-rounded btn_export"><i class="fas fa-file-export"></i> Export</a>

                                <a href="<?= base_url('Admin/LaporanTTQ/pdf/') ?>" class="btn btn-danger btn-rounded btn-laporan-ttq"><i class="fas fa-file-pdf"></i> PDF</a>


                            </div>
                        </div>
                        <div class="x_content table-responsive">
                            <table class="table table-bordered" id="table_Ttq">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="checked_all_Ttq">
                                                <label class="custom-control-label" for="checked_all_Ttq"></label>
                                            </div>
                                        </th>
                                        <th>Tanggal</th>
                                        <th>Surah</th>
                                        <th>Status Bacaan</th>
                                        <th>Mahasiswa</th>
                                        <th width="20%;">Kelas/Kelompok PA</th>
                                        <th>Kategori</th>
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
        $(document).on('click', '.btn-search', function(e) {
            e.preventDefault();
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            if (start != '' && end != '') {
                var tahun = $('select[name="tahun"]').children('option:selected').val();
                var guru = $('select[name="guru"]').children('option:selected').val();
                var kategori_ttq = $('select[name="kategori_ttq"]').children('option:selected').val();

                $('#table_Ttq').DataTable().destroy();
                load_table_side(tahun, guru, kategori_ttq, start, end);
            }
        })
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });
        $('select[name="guru"]').select2({
            theme: 'bootstrap4',
        });
        $('select[name="kategori_ttq"]').select2({
            theme: 'bootstrap4',
        });

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

        $(document).on('click', '.btn_export', function(e) {
            var arr = [];
            $('.check_item_Ttq:checked').each(function() {
                arr.push($(this).val());
            });
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var tahun = $('select[name="tahun"]').find('option:selected').val();
            var guru = $('select[name="guru"]').find('option:selected').val();
            var kategori_ttq = $('select[name="kategori_ttq"]').find('option:selected').val();
            var url = "<?= base_url('Admin/LaporanTTQ/export/' . $id_kelas . '?checked=') ?>" + arr + '&tahun=' + tahun + '&guru=' + guru + '&kategori_ttq=' + kategori_ttq + '&start_date=' + start + '&end_date=' + end;
            $(this).attr('href', url);
        })
        $(document).on('click', '.btn-laporan-ttq', function(e) {
            var arr = [];
            $('.check_item_Ttq:checked').each(function() {
                arr.push($(this).val());
            });
            var id = $(this).data('id');
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var tahun = $('select[name="tahun"]').find('option:selected').val();
            var guru = $('select[name="guru"]').find('option:selected').val();
            var kategori_ttq = $('select[name="kategori_ttq"]').find('option:selected').val();
            if (kategori_ttq == '') {
                alert('Harap select kategori ttq dahulu');
                e.preventDefault();
            }
            var url = "<?= base_url('Admin/LaporanTTQ/pdf/' . $id_kelas . '?checked=') ?>" + arr + '&tahun=' + tahun + '&guru=' + guru + '&kategori_ttq=' + kategori_ttq + '&start_date=' + start + '&end_date=' + end;
            $(this).attr('href', url);
        })
        $(document).on('change', 'select[name="tahun"]', function() {
            var tahun = $(this).val();
            var guru = $('select[name="guru"]').children('option:selected').val();
            var kategori_ttq = $('select[name="kategori_ttq"]').children('option:selected').val();
            $('#table_Ttq').DataTable().destroy();
            load_table_side(tahun, guru, kategori_ttq);
        })
        $(document).on('change', 'select[name="guru"]', function() {
            var guru = $(this).val();
            var tahun = $('select[name="tahun"]').children('option:selected').val();
            var kategori_ttq = $('select[name="kategori_ttq"]').children('option:selected').val();
            $('#table_Ttq').DataTable().destroy();
            load_table_side(tahun, guru, kategori_ttq);
        })
        $(document).on('change', 'select[name="kategori_ttq"]', function() {
            var kategori_ttq = $(this).val();
            var tahun = $('select[name="tahun"]').children('option:selected').val();
            var guru = $('select[name="guru"]').children('option:selected').val();
            $('#table_Ttq').DataTable().destroy();
            load_table_side(tahun, guru, kategori_ttq);
        })
        $(document).on('click', '.btn_reset', function() {
            var kategori_ttq = $('select[name="kategori_ttq"]').val('');
            var tahun = $('select[name="tahun"]').val('');
            var guru = $('select[name="guru"]').val('');
            $('#table_Ttq').DataTable().destroy();
            load_table_side(null, null, null);
        })

        function load_table_side(tahun = null, guru = null, kategori = null, start_date = null, end_date = null) {
            $('#table_Ttq').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo base_url('Admin/LaporanTTQ/server_Ttq') ?>",
                    "type": "GET",
                    data: {
                        id_kelas: "<?= $id_kelas ?>",
                        tahun: tahun,
                        guru: guru,
                        kategori_ttq: kategori,
                        start_date: start_date,
                        end_date: end_date,
                    },
                },
                "columnDefs": [{
                    "targets": [0, 4, 5, 6, 7],
                    "orderable": false,
                }, ],
            });
        }
        load_table_side();
    })
</script>