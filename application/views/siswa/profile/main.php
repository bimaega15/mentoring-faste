<style>
    section#wrapper ul.breadcrumb {
        float: right;
        background: none !important;
        border: none !important;
        outline: none;
        box-shadow: none !important;
    }

    section#wrapper ul.breadcrumb li a {
        color: #3f4441;
        background-color: none !important;
    }

    section#wrapper ul.breadcrumb li.active {
        color: #5e6f64;
    }

    .password_css_siswa {
        position: absolute;
        top: 40px;
        right: 10px;
        cursor: pointer;
    }
</style>
<br><br><br><br>
<?php
error_reporting(0);
$profile = get_my_id()['row'];
$guru = check_join_all_kelas_siswa($profile->kelas_siswa_id);
?>
<section id="wrapper" class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="poppins float-left pt-3"><?= $title; ?></div>
            <?= $breadcrumb; ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="card" style="min-height: 400px;">
        <div class="card-body">
            <?php $error = validation_errors();
            if ($error != null) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= validation_errors() ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="RiwayatTtq">
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
                                    <td><img src="<?= base_url('vendor/img/siswa/' . $profile->gambar) ?>" alt="Gambar siswa" class="img-thumbnail"></td>
                                    <td>
                                        <a href="#" id="get_siswa" data-toggle="modal" data-target="#modalSiswa" data-id_siswa="<?= $profile->id_siswa; ?>"><i class="fas fa-pencil-alt text-primary"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="modalSiswa" tabindex="-1" role="dialog" aria-labelledby="modalSiswaLabel" aria-hidden="true" style="z-index:999999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="modalSiswaLabel"><i class="fas fa-pencil-alt"></i> Edit Biodata</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart(base_url('Siswa/Profile/process')) ?>
            <?= form_hidden('id_siswa', '') ?>
            <form id="form_detail_siswa" method="post" action="<?= base_url('Siswa/Profile/process') ?>">
                <div class="modal-body" style="height: 500px; overflow: auto;">
                    <div class="form-group">
                        <label for="">Nomor Induk</label>
                        <input type="text" id="nomor_induk" class="form-control" readonly>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control password_siswa" placeholder="Password...">
                        <i class="fas fa-eye password_css_siswa"></i>
                    </div>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Tempat lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal lahir</label>
                        <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control datepicker">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">No. Telephone</label>
                        <input type="number" name="telephone" id="telephone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Jenis kelamin</label><br>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="pria" name="jenis_kelamin" class="custom-control-input checked-l" value="L">
                            <label class="custom-control-label" for="pria">Laki laki</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="wanita" name="jenis_kelamin" class="custom-control-input checked-p" value="P">
                            <label class="custom-control-label" for="wanita">Perempuan</label>
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label for="">Kelas Siswa</label>
                        <input type="text" id="kelas_siswa" class="form-control" readonly>
                    </div>-->
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
            </form>
            <?= form_close() ?>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '.password_css_siswa', function() {
            var type = $('.password_siswa').attr('type');
            if (type == 'password') {
                $('.password_siswa').attr('type', 'text');
                $('.password_css_siswa').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $('.password_siswa').attr('type', 'password');
                $('.password_css_siswa').removeClass('fa-eye-slash').addClass('fa-eye');

            }
        })
    })
    $(document).on('click', '#get_siswa', function() {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        var val = $(this).data('id_siswa');
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $.ajax({
            url: "<?= base_url('Siswa/Profile/getData') ?>",
            method: "post",
            dataType: 'json',
            data: {
                id_siswa: val
            },
            success: function(data) {
                if (data != null) {
                    $('#form_detail_siswa').trigger("reset");
                    $('#nomor_induk').val(data.row.nomor_induk);
                    $('#nama').val(data.row.nama);
                    $('#tempat_lahir').val(data.row.tempat_lahir);
                    $('#tanggal_lahir').val(data.tanggal);
                    $('#alamat').val(data.row.alamat);
                    $('#telephone').val(data.row.no_telephone);
                    if (data.row.jenis_kelamin == 'L') {
                        $('.checked-l').attr('checked', true);
                    }
                    if (data.row.jenis_kelamin == 'P') {
                        $('.checked-p').attr('checked', true);
                    }
                    $('#kelas_siswa').val(data.kelas);
                    $('#load_gambar').html(data.gambar);
                    $('input[name="id_siswa"]').val(data.row.id_siswa);
                }
            },
            error: function(x, t, m) {
                console.log(x.responseText);
            }
        })
    })
</script>