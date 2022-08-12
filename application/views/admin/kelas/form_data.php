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
                    <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Kelompok PA / Kelas</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <form method="post" action="<?= base_url('Admin/Kelas/process') ?>">
                        <?= form_hidden('id_Kelas', $row->id_kelas) ?>
                        <div class="form-group">
                            <label for="tingkat">Prodi<span class="required">*</span>
                            </label>
                            <select name="tingkat" id="tingkat" class="form-control <?= form_error('tingkat') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih prodi</option>
                                <?php foreach ($tingkat as $result) : ?>
                                    <option value="<?= $result->nama; ?>" <?= $row->tingkat == $result->nama ? 'selected' : '' ?> <?= set_value('tingkat') == $result->nama ? 'selected' : '' ?>><?= $result->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('tingkat') ?>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Kelompok PA / Kelas<span class="required">*</span></label>
                            <input type="text" id="nama" class="form-control <?= form_error('nama') != null ? 'border border-danger' : '' ?>" name="nama" value="<?= $row->nama == null ? set_value('nama') : $row->nama ?>" placeholder="Silahkan tulis nama kelompok / kelas">
                            <?= form_error('nama') ?>
                        </div>
                        <div class="form-group">
                            <label for="guru_id">Dosen PA <span class="required">*</span></label>
                            <select name="guru_id[]" multiple="multiple" id="guru_id" class="form-control js-example-basic-multiple <?= form_error('guru_id') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih dosen</option>
                                <?php foreach ($guru as $result) : ?>
                                    <option value="<?= $result->id_guru; ?>"><?= $result->nama; ?> | <?= $result->status_ttq == "y" ? "Guru TTQ" : "Wali Kelas"; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('guru_id') ?>
                        </div>
                        <?php if ($page == 'edit') : ?>
                            <div class="alert alert-info">
                                <strong>Nama Dosen PA Sebelumnya</strong>
                                <br>
                                <ul>
                                    <?php $detail = $this->db->get_where('kelas_detail', ['kelas_id' => $row->id_kelas])->result(); ?>
                                    <?php foreach ($detail as $result) : ?>
                                        <li><?= check_guru_lengkap($result->guru_id)->nama ?> | <?= check_guru_lengkap($result->guru_id)->status_ttq == "y" ? "Guru TTQ" : "Wali Kelas"; ?></li>
                                    <?php endforeach; ?>
                                </ul>
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
        $('#guru_id').select2({
            theme: 'bootstrap4',
        });

        var keterangan = $('#keterangan').find("option:selected").val();
        if (keterangan != '') {
            if (keterangan == 'Tidak lengkap') {
                $('.form_lengkap').addClass('d-none').removeClass('d-block');
            } else {
                $('.form_lengkap').removeClass('d-none').addClass('d-block');
            }

        }
        $('.password_css').on('click', function() {
            const type = $('input[name="password"]').attr('type');
            if (type == 'password') {
                $('input[name="password"]').attr('type', 'text');
                $('.password_css').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $('input[name="password"]').attr('type', 'password');
                $('.password_css').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        })
        $('.password_css2').on('click', function() {
            const type = $('input[name="confirm_password"]').attr('type');
            if (type == 'password') {
                $('input[name="confirm_password"]').attr('type', 'text');
                $('.password_css2').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $('input[name="confirm_password"]').attr('type', 'password');
                $('.password_css2').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        })
        $(document).on('change', '#keterangan', function() {
            var value = $(this).val();
            if (value == 'Lengkap') {
                $('.form_lengkap').hide().fadeIn(1500);
                $('.form_lengkap').removeClass('d-none').addClass('d-block');
            } else {
                $('.form_lengkap').fadeOut(1500);
                $('.form_lengkap').addClass('d-none').removeClass('d-block');
            }
        })
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#picture').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#gambar_upload").change(function() {
            readURL(this);
            $('#picture').removeClass('d-none');
        });
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        var pane = $('#alamat');
        pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
            .replace(/(<[^\/][^>]*>)\s*/g, '$1')
            .replace(/\s*(<\/[^>]+>)/g, '$1'));


    })
</script>