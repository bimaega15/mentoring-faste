<style>
    .password_css {
        position: absolute;
        top: 40px;
        right: 20px;
        cursor: pointer;
    }

    .password_css2 {
        position: absolute;
        top: 40px;
        right: 20px;
        cursor: pointer;
    }
</style>
<div class="right_col" role="main" style="min-height: 3823px;">

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
                    <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Kelompok / Kelas PA Mahasiswa</small></h2>
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
                    <form method="post" action="<?= base_url('Admin/SiswaKelas/process') ?>">
                        <?= form_hidden('id_SiswaKelas', $row->id_siswa_kelas) ?>
                        <div class="form-group">
                            <label for="kelas_siswa_id">Kelompok / Kelas PA Mahasiswa<span class="required">*</span>
                            </label>
                            <select name="kelas_siswa_id" id="kelas_siswa_id" class="form-control <?= form_error('kelas_siswa_id') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih Kelompok / Kelas PA Mahasiswa</option>
                                <?php foreach ($kelas_siswa as $result) : ?>
                                    <option value="<?= $result->id_kelas_siswa; ?>" <?= $row->kelas_siswa_id == $result->id_kelas_siswa ? 'selected' : '' ?> <?= set_value('kelas_siswa_id') == $result->id_kelas_siswa ? 'selected' : '' ?>><?= $result->kelas_siswa; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="load_guru"></div>
                        <?php if ($page == 'add') : ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong>Info!</strong> Silahkan pilih mahasiswa dropdown dibawah ini.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if ($page == 'add') : ?>
                            <div class="form-group">
                                <label for="">Mahasiswa</label>
                                <select name="siswa_id[]" multiple="multiple" id="siswa_id" class="form-control js-example-basic-multiple <?= form_error('siswa_id') != null ? 'border border-danger' : '' ?>">
                                    <option value="">Silahkan pilih mahasiswa</option>
                                    <?php foreach ($siswa as $result) : ?>
                                        <option value="<?= $result->id_siswa; ?>" <?= $row->siswa_id == $result->id_siswa ? 'selected' : '' ?> <?= set_value('siswa_id') == $result->id_siswa ? 'selected' : '' ?>><?= $result->nomor_induk; ?> | <?= $result->nama; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else : ?>
                            <div class="form-group">
                                <label for="">Mahasiswa</label>
                                <select name="siswa_id" id="siswa_id" class="form-control <?= form_error('siswa_id') != null ? 'border border-danger' : '' ?>">
                                    <option value="">Silahkan pilih mahasiswa</option>
                                    <?php foreach ($siswa as $result) : ?>
                                        <option value="<?= $result->id_siswa; ?>" <?= $row->siswa_id == $result->id_siswa ? 'selected' : '' ?> <?= set_value('siswa_id') == $result->id_siswa ? 'selected' : '' ?>><?= $result->nomor_induk; ?> | <?= $result->nama; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        <?php endif; ?>
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
<script>
    $(document).ready(function() {
        $('select[name="kelas_siswa_id"]').select2({
            theme: 'bootstrap4',
        });
        $('#siswa_id').select2({
            theme: 'bootstrap4',
        });

        $('#tableSiswa').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Admin/SiswaKelas/server_Siswa') ?>",
                "type": "GET",
            },
            "columnDefs": [{
                "targets": [0, 3],
                "orderable": false,
            }, ],
        });

        $(document).on('click', '#checboxAllSiswa', function() {
            if ($(this).is(':checked')) {
                $('.check_item_Siswa').prop('checked', true);
            } else {
                $('.check_item_Siswa').prop('checked', false);
            }
        })
        $(document).on('click', '.check_item_Siswa', function() {
            var item = $('.check_item_Siswa').length;
            var checked = $('.check_item_Siswa:checked').length;
            if (item == checked) {
                $('#checboxAllSiswa').prop('checked', true);
            } else {
                $('#checboxAllSiswa').prop('checked', false);
            }
        })
    })
</script>