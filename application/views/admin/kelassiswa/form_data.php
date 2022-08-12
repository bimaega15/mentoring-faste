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
                    <form method="post" action="<?= base_url('Admin/KelasSiswa/process') ?>">
                        <?= form_hidden('id_KelasSiswa', $row->id_kelas_siswa) ?>
                        <div class="form-group">
                            <label for="kelas_id">Kelompok / Kelas PA Mahasiswa<span class="required">*</span>
                            </label>
                            <select name="kelas_id" id="kelas_id" class="form-control <?= form_error('kelas_id') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih kelompok / kelas PA</option>
                                <?php foreach ($kelas_siswa as $result) : ?>
                                    <option value="<?= $result->id_kelas; ?>" <?= $row->kelas_id == $result->id_kelas ? 'selected' : '' ?> <?= set_value('kelas_id') == $result->id_kelas ? 'selected' : '' ?>><?= $result->tingkat; ?> | <?= $result->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('kelas_id') ?>
                        </div>
                        <div id="load_guru"></div>
                        <div class="form-group">
                            <label for="tahun_akademik_id">Tahun akademik<span class="required">*</span>
                            </label>
                            <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-control <?= form_error('tahun_akademik_id') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih tahun akademik</option>
                                <?php foreach ($tahun_akademik as $result) : ?>
                                    <option value="<?= $result->id_tahun_akademik; ?>" <?= $row->tahun_akademik_id == $result->id_tahun_akademik ? 'selected' : '' ?> <?= set_value('tahun_akademik_id') == $result->id_tahun_akademik ? 'selected' : '' ?>><?= $result->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('tahun_akademik_id') ?>
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
<script>
    $(document).ready(function() {
        $(document).on('change', '#kelas_id', function() {
            var val = $(this).val();
            $.ajax({
                url: "<?= base_url('Admin/KelasSiswa/getKelasGuru') ?>",
                method: "post",
                dataType: 'json',
                data: {
                    val: val
                },
                success: function(data) {
                    $('#load_guru').html(data);
                },
                error: function(x, t, m) {
                    console.log(x.responseText);
                }
            })
        })
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