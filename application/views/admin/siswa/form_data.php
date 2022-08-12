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
                    <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Mahasiswa</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <form method="post" action="<?= base_url('Admin/Siswa/process') ?>" enctype="multipart/form-data">
                        <?= form_hidden('id_Siswa', $row->id_siswa) ?>
                        <?php if ($page == 'add') : ?>
                            <div class="form-group">
                                <label for="nomor_induk">NIM <span class="required">*</span>
                                </label>
                                <input type="text" id="nomor_induk" class="form-control <?= form_error('nomor_induk') != null ? 'border border-danger' : '' ?>" name="nomor_induk" value="<?= $row->nomor_induk == null ? set_value('nomor_induk') : $row->nomor_induk ?>" placeholder="Masukkan nomor induk mahasiswa">
                                <?= form_error('nomor_induk') ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="password">Password <span class="required">*</span>
                                    </label>
                                    <input type="password" id="password" name="password" class="form-control <?= form_error('password') != null ? 'border border-danger' : '' ?>" name="password" placeholder="Enter your password">
                                    <i class="fas fa-eye password_css"></i>

                                    <?= form_error('password') ?>
                                </div>
                                <div class="col-lg-6">
                                    <label for="confirm_password">Confirm Password <span class="required">*</span>
                                    </label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control <?= form_error('confirm_password') != null ? 'border border-danger' : '' ?>" name="password" placeholder="Enter your confirm password">
                                    <i class="fas fa-eye password_css2"></i>

                                    <?= form_error('confirm_password') ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan <span class="required">*</span>
                            </label>
                            <select name="keterangan" id="keterangan" class="form-control <?= form_error('keterangan') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih keterangan</option>
                                <option value="Lengkap" <?= $row->keterangan == 'Lengkap' ? 'selected' : '' ?> <?= set_value('keterangan') == 'Lengkap' ? 'selected' : '' ?>>Lengkap</option>
                                <option value="Tidak lengkap" <?= $row->keterangan == 'Tidak lengkap' ? 'selected' : '' ?> <?= set_value('keterangan') == 'Tidak lengkap' ? 'selected' : '' ?>>Tidak lengkap</option>
                            </select>
                            <?= form_error('keterangan') ?>
                        </div>
                        <div class="form_lengkap d-none">
                            <div class="form-group">
                                <label for="nama">Nama Mahasiswa<span class="required">*</span></label>
                                <input type="text" id="nama" class="form-control <?= form_error('nama') != null ? 'border border-danger' : '' ?>" name="nama" value="<?= $row->nama == null ? set_value('nama') : $row->nama ?>" placeholder="Masukkan nama mahasiswa">
                                <?= form_error('nama') ?>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis kelamin <span class="required">*</span></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="L" <?= $row->jenis_kelamin == 'L' ? 'checked' : '' ?> <?= set_value('jenis_kelamin') == 'L' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="laki_laki">Laki laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P" <?= $row->jenis_kelamin == 'P' ? 'checked' : '' ?> <?= set_value('jenis_kelamin') == 'P' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                                <?= form_error('jenis_kelamin') ?>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="tempat_lahir">Tempat lahir <span class="required">*</span></label>
                                        <input type="text" id="tempat_lahir" class="form-control <?= form_error('tempat_lahir') != null ? 'border border-danger' : '' ?>" name="tempat_lahir" value="<?= $row->tempat_lahir == null ? set_value('tempat_lahir') : $row->tempat_lahir ?>" placeholder="Masukkan tempat lahir">
                                        <?= form_error('tempat_lahir') ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="tanggal_lahir">Tanggal lahir <span class="required">*</span></label>
                                        <input type="text" id="tanggal_lahir" class="form-control datepicker <?= form_error('tanggal_lahir') != null ? 'border border-danger' : '' ?>" name="tanggal_lahir" value="<?= $row->tanggal_lahir == null ? set_value('tanggal_lahir') : $row->tanggal_lahir ?>" placeholder="Enter tanggal lahir">
                                        <?= form_error('tanggal_lahir') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat <span class="required">*</span></label>
                                <textarea name="alamat" id="alamat" class="form-control <?= form_error('alamat') != null ? 'border border-danger' : '' ?>" rows="5">
                                    <?= $row->alamat == null ? set_value('alamat') : $row->alamat ?>
                                    </textarea>
                                <?= form_error('alamat') ?>
                            </div>
                            <div class="form-group">
                                <label for="no_telephone">No telephone <span class="required">*</span></label>
                                <input type="text" id="no_telephone" class="form-control <?= form_error('no_telephone') != null ? 'border border-danger' : '' ?>" name="no_telephone" value="<?= $row->no_telephone == null ? set_value('no_telephone') : $row->no_telephone ?>" placeholder="Masukkan No. Telephone">
                                <?= form_error('no_telephone') ?>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="gambar">Foto</label>
                                    <div class="custom-file">
                                        <input type="file" name="gambar" class="custom-file-input" id="gambar_upload">
                                        <label class="custom-file-label" for="gambar_upload"><?= $row->gambar != null ? $row->gambar : 'Silahkan masukan foto'; ?></label>
                                        <input type="hidden" name="old_gambar" value="<?= $row->gambar; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <?php $tmp = base_url('vendor/img/Siswa/') . $row->gambar; ?>
                                    <img id="picture" alt="Gambar Anda" class="img-thumbnail w-100 <?= $row->gambar != null ? "" : "d-none"; ?>" src="<?php echo $tmp ?>">
                                </div>
                            </div>

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