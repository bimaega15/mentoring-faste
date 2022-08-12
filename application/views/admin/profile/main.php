<style>
    .change_password {
        transition: all 0.5s;
        color: #000;
        border: 1px solid #127681;
    }

    .change_password:hover {
        background-color: #127681;
        color: #fff !important;
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        opacity: 0;
        transition: all 0.5s;
        background-color: #127681;
    }

    #crop-avatar {
        position: relative;
        cursor: pointer;
    }

    #crop-avatar:hover .overlay {
        opacity: 1;
    }

    .icon {
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .password_css_admin {
        position: absolute;
        top: 40px;
        right: 10px;
        cursor: pointer;
    }

    .password_css_guru {
        position: absolute;
        top: 40px;
        right: 10px;
        cursor: pointer;
    }
</style>
<?php $profile = get_my_id()['row'];
$roles = get_my_id()['roles']; ?>
<div class="right_col" role="main" style="min-height: 1243px;">
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fas fa-pencil-alt"></i> My Profile</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php if (validation_errors()) { ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= validation_errors() ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php  } ?>


                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Nomor Induk</th>
                                    <th>Nama</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>No Telephone</th>
                                    <th>Jenis Kelamin</th>
                                    <th width="150px;">Gambar</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $profile->nomor_induk ?></td>
                                    <td><?= $profile->nama ?></td>
                                    <td><?= $profile->tempat_lahir ?></td>
                                    <td><?= tanggal_indo($profile->tanggal_lahir) ?></td>
                                    <td><?= ($profile->alamat) ?></td>
                                    <td><?= ($profile->no_telephone) ?></td>
                                    <td><?= ($profile->jenis_kelamin == "L" ? "Laki-laki" : "Perempuan") ?></td>
                                    <?php if ($roles == 1) : ?>
                                        <td><img src="<?= base_url('vendor/img/admin/' . $profile->gambar) ?>" alt="Gambar Admin" class="img-thumbnail"></td>
                                    <?php elseif ($roles == 2) : ?>
                                        <td><img src="<?= base_url('vendor/img/admin/' . $profile->gambar) ?>" alt="Gambar Admin" class="img-thumbnail"></td>
                                    <?php endif; ?>
                                    <?php if ($roles == 1) : ?>
                                        <td>
                                            <a href="#" id="get_admin" data-toggle="modal" data-target="#modalAdmin" data-id_admin="<?= $profile->id_admin; ?>"><i class="fas fa-pencil-alt text-primary"></i></a>
                                        </td>
                                    <?php elseif ($roles == 2) : ?>
                                        <td>
                                            <a href="#" id="get_guru" data-toggle="modal" data-target="#modalGuru" data-id_guru="<?= $profile->id_guru; ?>"><i class="fas fa-pencil-alt text-primary"></i></a>
                                        </td>
                                    <?php endif; ?>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="modalUploadImage" tabindex="-1" role="dialog" aria-labelledby="modalUploadImageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalUploadImageLabel"><i class="far fa-image"></i> Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Admin/Profile/changeGambar') ?>" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar_upload" name="gambar">
                            <label class="custom-file-label" for="gambar_upload">Choose file</label>
                        </div>
                        <img id="picture" alt="Gambar Anda" class="img-thumbnail w-100 d-none">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-window-close    "></i> Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog" aria-labelledby="modalChangePasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalChangePasswordLabel"><i class="fas fa-lock"></i> Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Admin/Profile/changePassword') ?>" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <label for="">Password</label>
                        </div>
                        <div class="col-lg-7">
                            <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password">
                            <i class="fas fa-eye password_css password_1"></i>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-5">
                            <label for="">Confirm Password</label>
                        </div>
                        <div class="col-lg-7">
                            <input id="confirm_password" type="password" class="form-control " name="confirm_password" placeholder="Enter your confirm password">
                            <i class="fas fa-eye password_css password_2"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-window-close"></i> Close</button>
                    <button name="change" type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save    "></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div> -->


<!-- Modal -->
<div class="modal fade" id="modalAdmin" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel" aria-hidden="true" style="z-index:999999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="modalAdminLabel"><i class="fas fa-pencil-alt"></i> Edit Biodata</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart(base_url('Admin/Profile/Process'), ['id' => 'form_detail_Admin']) ?>
            <?= form_hidden('id_admin', '') ?>
            <div class="modal-body" style="height: 500px; overflow: auto;">
                <div class="form-group">
                    <label for="">Nomor Induk</label>
                    <input type="text" class="form-control nomor_induk" readonly>
                </div>
                <div class="form-group" style="position: relative;">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control password_admin" placeholder="Password...">
                    <i class="fas fa-eye password_css_admin"></i>
                </div>
                <div class="form-group">
                    <label for="">Nama</label>
                    <input type="text" class="form-control nama" name="nama">
                </div>
                <div class="form-group">
                    <label for="">Tempat lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control tempat_lahir">
                </div>
                <div class="form-group">
                    <label for="">Tanggal lahir</label>
                    <input type="text" name="tanggal_lahir" class="form-control datepicker tanggal_lahir">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <textarea name="alamat" class="form-control alamat" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="">No. Telephone</label>
                    <input type="number" name="telephone" class="form-control telephone">
                </div>
                <div class="form-group">
                    <label for="">Jenis kelamin</label><br>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="admin_pria" name="jenis_kelamin" class="custom-control-input checked-l" value="L">
                        <label class="custom-control-label" for="admin_pria">Laki laki</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="admin_wanita" name="jenis_kelamin" class="custom-control-input checked-p" value="P">
                        <label class="custom-control-label" for="admin_wanita">Perempuan</label>
                    </div>
                </div>
                <!-- <div class="form-group">
                        <label for="">Gambar</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="gambar" id="upload_gambar">
                            <label class="custom-file-label" for="upload_gambar">Choose file</label>
                        </div>
                        <div id="load_gambar"></div>
                    </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Close</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>

<div class="modal fade" id="modalGuru" tabindex="-1" role="dialog" aria-labelledby="modalGuruLabel" aria-hidden="true" style="z-index:999999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="modalGuruLabel"><i class="fas fa-pencil-alt"></i> Edit Biodata</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart(base_url('Admin/Profile/Process'), ['id' => 'form_detail_guru']) ?>
            <?= form_hidden('id_guru', '') ?>
            <div class="modal-body" style="height: 500px; overflow: auto;">
                <div class="form-group">
                    <label for="">Nomor Induk</label>
                    <input type="text" class="form-control nomor_induk" readonly>
                </div>
                <div class="form-group" style="position: relative;">
                    <label for="">Password</label>
                    <input name="password" type="password" class="form-control password_guru" placeholder="Password...">
                    <i class="fas fa-eye password_css_guru"></i>
                </div>
                <div class="form-group">
                    <label for="">Nama</label>
                    <input type="text" name="nama" class="form-control nama">
                </div>
                <div class="form-group">
                    <label for="">Tempat lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control tempat_lahir">
                </div>
                <div class="form-group">
                    <label for="">Tanggal lahir</label>
                    <input type="text" name="tanggal_lahir" class="form-control datepicker tanggal_lahir">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <textarea name="alamat" class="form-control alamat" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="">No. Telephone</label>
                    <input type="number" name="telephone" class="form-control telephone">
                </div>
                <div class="form-group">
                    <label for="">Jenis kelamin</label><br>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="guru_pria" name="jenis_kelamin" class="custom-control-input checked-l" value="L">
                        <label class="custom-control-label" for="guru_pria">Laki laki</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="guru_wanita" name="jenis_kelamin" class="custom-control-input checked-p" value="P">
                        <label class="custom-control-label" for="guru_wanita">Perempuan</label>
                    </div>
                </div>
                <!-- <div class="form-group">
                        <label for="">Gambar</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="gambar" id="upload_gambar">
                            <label class="custom-file-label" for="upload_gambar">Choose file</label>
                        </div>
                        <div id="load_gambar"></div>
                    </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Close</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
            <?= form_close() ?>

        </div>
    </div>
</div>


<!-- Admin -->
<script>
    $(document).ready(function() {
        $(document).on('click', '.password_css_admin', function() {
            var type = $('.password_admin').attr('type');
            if (type == 'password') {
                $('.password_admin').attr('type', 'text');
                $('.password_css_admin').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $('.password_admin').attr('type', 'password');
                $('.password_css_admin').removeClass('fa-eye-slash').addClass('fa-eye');

            }
        })
    })
    $(document).on('click', '#get_admin', function() {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        var val = $(this).data('id_admin');
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $.ajax({
            url: "<?= base_url('Admin/Profile/getData') ?>",
            method: "post",
            dataType: 'json',
            data: {
                id_admin: val
            },
            success: function(data) {
                if (data != null) {
                    $('#form_detail_admin').trigger("reset");
                    $('.nomor_induk').val(data.row.nomor_induk);
                    $('.nama').val(data.row.nama);
                    $('.tempat_lahir').val(data.row.tempat_lahir);
                    $('.tanggal_lahir').val(data.tanggal);
                    $('.alamat').val(data.row.alamat);
                    $('.telephone').val(data.row.no_telephone);
                    if (data.row.jenis_kelamin == 'L') {
                        $('.checked-l').attr('checked', true);
                    }
                    if (data.row.jenis_kelamin == 'P') {
                        $('.checked-p').attr('checked', true);
                    }
                    $('.load_gambar').html(data.gambar);
                    $('input[name="id_admin"]').val(data.row.id_admin);
                }
            },
            error: function(x, t, m) {
                console.log(x.responseText);
            }
        })
    })
</script>


<!-- Guru -->
<script>
    $(document).ready(function() {
        $(document).on('click', '.password_css_guru', function() {
            var type = $('.password_guru').attr('type');
            if (type == 'password') {
                $('.password_guru').attr('type', 'text');
                $('.password_css_guru').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $('.password_guru').attr('type', 'password');
                $('.password_css_guru').removeClass('fa-eye-slash').addClass('fa-eye');

            }
        })
    })
    $(document).on('click', '#get_guru', function() {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        var val = $(this).data('id_guru');
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $.ajax({
            url: "<?= base_url('Admin/Profile/getDataGuru') ?>",
            method: "post",
            dataType: 'json',
            data: {
                id_guru: val
            },
            success: function(data) {
                if (data != null) {
                    $('#form_detail_guru').trigger("reset");
                    $('.nomor_induk').val(data.row.nomor_induk);
                    $('.nama').val(data.row.nama);
                    $('.tempat_lahir').val(data.row.tempat_lahir);
                    $('.tanggal_lahir').val(data.tanggal);
                    $('.alamat').val(data.row.alamat);
                    $('.telephone').val(data.row.no_telephone);
                    if (data.row.jenis_kelamin == 'L') {
                        $('.checked-l').attr('checked', true);
                    }
                    if (data.row.jenis_kelamin == 'P') {
                        $('.checked-p').attr('checked', true);
                    }
                    $('.load_gambar').html(data.gambar);
                    $('input[name="id_guru"]').val(data.row.id_guru);
                }
            },
            error: function(x, t, m) {
                console.log(x.responseText);
            }
        })
    })
</script>