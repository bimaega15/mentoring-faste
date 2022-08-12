<div class="right_col" role="main" style="min-height: 3823px;">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><?= $title; ?></h3>
            </div>
        </div>
        <div class="row" style="display: block;">
            <div class="col-lg-12">
                <?= $breadcrumb; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Form Setoran Hafalan AlQur'an (TTQ)</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <?php if (validation_errors()) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><?= validation_errors(); ?></strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <form method="post" action="<?= base_url('Admin/Ttq/process/' . $id_kelas . '?kategori=' . $id_kategori_ttq) ?>">
                            <?= form_hidden('id_Ttq', $row->id_ttq) ?>
                            <div class="form-group">
                                <label for="Surah">Surah<span class="required">*</span>
                                </label>
                                <input name="surah" class="form-control <?= form_error('surah') != null ? 'border border-danger' : '' ?>" type="text" id="surah" placeholder="Enter your surah" value="<?= $row->surah == null ? set_value('surah') : $row->surah; ?>">

                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal<span class="required">*</span>
                                </label>
                                <input name="tanggal" class="form-control datepicker <?= form_error('tanggal') != null ? 'border border-danger' : '' ?>" type="text" id="tanggal" placeholder="Enter your tanggal" value="<?= $row->tanggal == null ? set_value('tanggal') : $row->tanggal; ?>">

                            </div>
                            <div class="form-group">
                                <label for="waktu">Waktu<span class="required">*</span>
                                </label>
                                <input name="waktu" class="form-control timepicker <?= form_error('waktu') != null ? 'border border-danger' : '' ?>" type="text" id="waktu" placeholder="Enter your waktu" value="<?= $row->waktu == null ? set_value('waktu') : $row->waktu; ?>">

                            </div>

                            <div class="form-group">
                                <label for="">Mahasiswa<span class="required">*</span></label>
                                <?php if ($page == 'add') : ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tableSiswa">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="checkboxAllSiswa">
                                                            <label class="custom-control-label" for="checkboxAllSiswa"></label>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        Mahasiswa
                                                    </th>
                                                    <th>
                                                        Status Bacaan
                                                    </th>
                                                    <th>
                                                        Keterangan
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($get_siswa as $result) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="siswa[]" class="custom-control-input checkboxitemsiswa" id="checkboxitemsiswa<?= $no ?>" value="<?= $result->id_siswa ?>" data-no="<?= $no ?>">
                                                                <label class="custom-control-label" for="checkboxitemsiswa<?= $no ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span>
                                                                NIM : <?= $result->nomor_induk ?> <br>
                                                                Nama : <?= $result->nama ?> <br>
                                                                J.K : <?= $result->jenis_kelamin == "L" ? "Pria" : "Wanita" ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="table-responsive">
                                                                <input type="hidden" name="status_bacaan_hidden[]" value="<?= $no; ?>">
                                                                <table class="w-100">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_bacaan<?= $no ?>" id="statusbacaanlancar<?= $no ?>" value="lancar">
                                                                                <label class="form-check-label" for="statusbacaanlancar<?= $no ?>"> Lancar</label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_bacaan<?= $no ?>" id="statusbacaanbelumlancar<?= $no ?>" value="belum lancar">
                                                                                <label class="form-check-label" for="statusbacaanbelumlancar<?= $no ?>"> Belum Lancar</label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <textarea name="keterangan[]" id="" placeholder="Keterangan..." class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <?php $no++ ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <select name="siswa" class="form-control  <?= form_error('siswa') != null ? 'border border-danger' : '' ?>" id="">
                                        <option value="">-- Mahasiswa --</option>
                                        <?php foreach ($siswa as $key => $value) { ?>
                                            <option value="<?= $value->id_siswa ?>" <?= $value->id_siswa == $row->siswa_id ? "selected" : "" ?>><?= $value->nomor_induk ?> | <?= $value->nama ?></option>
                                        <?php } ?>
                                    </select>
                                <?php endif; ?>

                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="d-flex justify-content-center w-100">
                                    <button class="btn btn-danger shadow-sm" type="reset"><i class="fas fa-window-close"></i> Reset</button>
                                    <button name="<?= $page ?>" type="submit" class="btn btn-success shadow-sm"><i class="fas fa-save"></i> Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });
        $('select[name="siswa"]').select2({
            theme: 'bootstrap4',
        });
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm',
            scrollbar: true
        });
        var page = "<?= $page ?>";
        if (page == 'add') {
            $(document).on('change', 'select[name="kelas_siswa"]', function() {
                var val = $(this).val();
                $.ajax({
                    url: "<?= base_url('Admin/Ttq/getSiswaKelas') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: val
                    },
                    success: function(data) {
                        if (data != '') {
                            $('#load_siswa').html(data.output);
                            $('#load_guruKelas').html(data.kelas_detail);
                        } else {
                            $('#load_siswa').html('');
                            $('#load_guruKelas').html('');
                        }
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            })
        } else {
            var val = "<?= $id_kelas ?>";
            var id_siswa = "<?= $row->siswa_id ?>";
            load_kelas_siswa(val, id_siswa);
        }

        function load_kelas_siswa(val = '', id_siswa = '') {
            $.ajax({
                url: "<?= base_url('Admin/Ttq/getSiswaKelas') ?>",
                method: 'post',
                dataType: 'json',
                data: {
                    id: val,
                },
                success: function(data) {
                    if (data != '') {
                        $('#load_siswa').html(data.output);
                        $('#load_guruKelas').html(data.kelas_detail);
                        $('select[name="siswa"]').val(id_siswa).trigger('change');
                    } else {
                        var output = '<option value="">Siswa kosong</option>';
                        $('#load_siswa').html('');
                        $('#load_guruKelas').html('');
                    }
                },
                error: function(x, t, m) {
                    console.log(x.responseText);
                }
            })
        }
        $(document).on('click', '#checkboxAllSiswa', function() {
            if ($(this).is(':checked')) {
                $('.checkboxitemsiswa').prop('checked', true);
                if ($('.checkboxitemsiswa').is(':checked')) {
                    var arr = [];
                    $('.checkboxitemsiswa:checked').each(function(i, v) {
                        arr.push($(this).val());
                    });
                    var i = 1;
                    for (i; i <= arr.length; i++) {
                        $('input[name="status_bacaan' + i + '"]').attr('required', true);
                    }
                } else {
                    var arr = [];
                    $('.checkboxitemsiswa').each(function(i, v) {
                        arr.push($(this).val());
                    });
                    var i = 1;
                    for (i; i <= arr.length; i++) {
                        $('input[name="status_bacaan' + i + '"]').attr('required', false);
                    }
                }
            } else {
                $('.checkboxitemsiswa').prop('checked', false);
                var arr = [];
                $('.checkboxitemsiswa').each(function(i, v) {
                    arr.push($(this).val());
                });
                var i = 1;
                for (i; i <= arr.length; i++) {
                    $('input[name="status_bacaan' + i + '"]').attr('required', false);
                }
            }
        })
        $(document).on('click', '.checkboxitemsiswa', function() {
            var checked = $('.checkboxitemsiswa:checked').length;
            var checkbox = $('.checkboxitemsiswa').length;
            if (checked == checkbox) {
                $('#checkboxAllSiswa').prop('checked', true);
            } else {
                $('#checkboxAllSiswa').prop('checked', false);
            }
            if ($(this).is(':checked')) {
                var i = $(this).data('no');
                $('input[name="status_bacaan' + i + '"]').attr('required', true);
            } else {
                var i = $(this).data('no');
                $('input[name="status_bacaan' + i + '"]').attr('required', false);

            }
        })
        var pane = $('#keterangan');
        pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
            .replace(/(<[^\/][^>]*>)\s*/g, '$1')
            .replace(/\s*(<\/[^>]+>)/g, '$1'));
        var pane = $('#status_bacaan');
        pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
            .replace(/(<[^\/][^>]*>)\s*/g, '$1')
            .replace(/\s*(<\/[^>]+>)/g, '$1'));



    })
</script>