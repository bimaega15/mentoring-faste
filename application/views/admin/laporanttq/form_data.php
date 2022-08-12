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
                        <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Users Access</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form method="post" action="<?= base_url('Admin/Ttq/process') ?>">
                            <?= form_hidden('id_Ttq', $row->id_ttq) ?>
                            <div class="form-group">
                                <label for="tanggal">Tanggal<span class="required">*</span>
                                </label>
                                <input name="tanggal" class="form-control datepicker <?= form_error('tanggal') != null ? 'border border-danger' : '' ?>" type="text" id="tanggal" placeholder="Enter your tanggal" value="<?= $row->tanggal == null ? set_value('tanggal') : $row->tanggal; ?>">
                                <?= form_error('tanggal	') ?>
                            </div>
                            <div class="form-group">
                                <label for="waktu">Waktu<span class="required">*</span>
                                </label>
                                <input name="waktu" class="form-control timepicker <?= form_error('waktu') != null ? 'border border-danger' : '' ?>" type="text" id="waktu" placeholder="Enter your waktu" value="<?= $row->waktu == null ? set_value('waktu') : $row->waktu; ?>">
                                <?= form_error('waktu') ?>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_halaman">Jumlah halaman<span class="required">*</span>
                                </label>
                                <input name="jumlah_halaman" class="form-control <?= form_error('jumlah_halaman') != null ? 'border border-danger' : '' ?>" type="text" id="jumlah_halaman" placeholder="Enter your halaman" value="<?= $row->jumlah_halaman == null ? set_value('jumlah_halaman') : $row->jumlah_halaman; ?>">
                                <?= form_error('jumlah_halaman') ?>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan<span class="required">*</span>
                                </label>
                                <textarea placeholder="Enter your keterangan" name="keterangan" class="form-control <?= form_error('keterangan') != null ? 'border border-danger' : '' ?>" id="keterangan">
                                <?= $row->keterangan == null ? set_value('keterangan') : $row->keterangan; ?>
                                </textarea>
                                <?= form_error('keterangan') ?>
                            </div>
                            <div class="form-group">
                                <label for="">Kelas Mahasiswa<span class="required">*</span></label>
                                <select name="kelas_siswa" class="form-control  <?= form_error('kelas_siswa') != null ? 'border border-danger' : '' ?>" id="">
                                    <option value="">Silahkan pilih kelas mahasiswa</option>
                                    <?php if ($page == 'add') : ?>
                                        <?php foreach ($kelas_siswa as $result) : ?>
                                            <option value="<?= $result->id_kelas_siswa ?>"><?= $result->tingkat ?> <?= $result->nama_kelas ?> | <?= $result->nama; ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <?php foreach ($kelas_siswa as $result) : ?>
                                            <option value="<?= $result->id_kelas_siswa ?>" <?= $kelas_siswa_id->kelas_siswa_id == $result->id_kelas_siswa ? "selected" : "" ?>><?= $result->tingkat ?> <?= $result->nama_kelas ?> | <?= $result->nama; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif ?>
                                </select>
                                <?= form_error('kelas_siswa') ?>
                            </div>
                            <div class="form-group">
                                <label for="">Mahasiswa<span class="required">*</span></label>
                                <select name="siswa" class="form-control  <?= form_error('siswa') != null ? 'border border-danger' : '' ?>" id="load_siswa">
                                <option value="">Silahkan pilih mahasiswa</option>
                                </select>
                                <?= form_error('siswa') ?>
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
            format: 'yyyy-mm-dd'
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
                            var output = '<option value="">Silahkan pilih siswa</option>';
                            $(data).each(function(i, v) {
                                console.log(v);
                                output += `
                            <option value="` + v.id_siswa + `">` + v.nama + " | " + v.nomor_induk + `</option>
                            `
                            })
                            $('#load_siswa').html(output);
                        } else {
                            var output = '<option value="">Siswa kosong</option>';
                            $('#load_siswa').html(output);
                        }
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            })
        } else {
            var val = $('select[name="kelas_siswa"').find(':selected').val();
            var id_siswa = "<?= $row->siswa_id ?>";
            load_kelas_siswa(val, id_siswa);
        }

        function load_kelas_siswa(val = '', id_siswa = '') {
            $.ajax({
                url: "<?= base_url('Admin/Ttq/getSiswaKelas') ?>",
                method: 'post',
                dataType: 'json',
                data: {
                    id: val
                },
                success: function(data) {
                    if (data != '') {
                        var output = '<option value="">Silahkan pilih siswa</option>';
                        $(data).each(function(i, v) {
                            console.log(v);
                            output += `
                            <option value="` + v.id_siswa + `">` + v.nama + " | " + v.nomor_induk + `</option>
                            `
                        })
                        $('#load_siswa').html(output);
                        $('select[name="siswa"]').val(id_siswa).trigger('change');
                    } else {
                        var output = '<option value="">Siswa kosong</option>';
                        $('#load_siswa').html(output);
                    }
                },
                error: function(x, t, m) {
                    console.log(x.responseText);
                }
            })
        }
        var pane = $('#keterangan');
        pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
            .replace(/(<[^\/][^>]*>)\s*/g, '$1')
            .replace(/\s*(<\/[^>]+>)/g, '$1'));

    })
</script>