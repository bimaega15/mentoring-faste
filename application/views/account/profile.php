<!DOCTYPE html>
<html>
<title>Validasi Profile</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= base_url('assets_profile/') ?>css.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open Sans">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/png" href="<?= base_url('vendor/img/icon/icon.png'); ?>">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets_js/') ?>bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
<script src="<?= base_url('assets_js/js/sweetalert2.js') ?>"></script>
<style>
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Oswald"
    }

    body {
        font-family: "Open Sans"
    }
</style>


<body>
    <div class="w3-content" style="max-width:1600px">
        <!-- Image header -->


        <!-- Grid -->
        <form id="msform" method="post" action="<?= base_url('Account/Profile/process') ?>" enctype="multipart/form-data">
            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active">Biodata Diri</li>
                <li>Contact</li>
                <li>Gambar</li>
            </ul>
            <!-- fieldsets -->
            <fieldset>
                <h2 class="fs-title">Tambah Biodata Diri</h2>
                <h3 class="fs-subtitle">Step 1</h3>
                <input type="text" name="nama" <?= form_error('nama') != null ? 'style="border: 1px solid red;"' : '' ?> placeholder="Nama..." value="<?= set_value('nama') ?>" />
                <?= form_error('nama') ?>
                <div class="form-group" style="text-align: left;">
                    <label for="">Jenis kelamin</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="pria" value="L" style="width:20px;" <?= set_value('jenis_kelamin') == "L" ? 'checked' : '' ?>>
                        <label class="form-check-label mr-2" for="pria">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="wanita" value="option2" style="width:20px;" value="P" <?= set_value('jenis_kelamin') == "P" ? 'checked' : '' ?>>
                        <label class="form-check-label" for="wanita">Perempuan</label>
                    </div>
                    <?= form_error('jenis_kelamin') ?>

                </div>
                <input type="text" name="tempat_lahir" <?= form_error('tempat_lahir') != null ? 'style="border: 1px solid red;"' : '' ?> placeholder="Tempat lahir..." value="<?= set_value('tempat_lahir') ?>" />
                <?= form_error('tempat_lahir') ?>
                <input class="datepicker" type="text" name="tanggal_lahir" <?= form_error('tanggal_lahir') != null ? 'style="border: 1px solid red;"' : '' ?> placeholder="Tanggal lahir..." value="<?= set_value('tanggal_lahir') ?>" />
                <?= form_error('tanggal_lahir') ?>

                <input type="button" name="next" class="next action-button" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Kontak Profile</h2>
                <h3 class="fs-subtitle">Step 2</h3>
                <textarea name="alamat" placeholder="Alamat.." rows="3" <?= form_error('alamat') != null ? 'style="border: 1px solid red;"' : '' ?>><?= set_value('alamat') ?></textarea>
                <?= form_error('alamat') ?>

                <input <?= form_error('no_telephone') != null ? 'style="border: 1px solid red;"' : '' ?> type="number" name="no_telephone" placeholder="No. Handphone..." value="<?= set_value('no_telephone') ?>" />
                <?= form_error('no_telephone') ?>

                <input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="button" name="next" class="next action-button" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Gambar Profile</h2>
                <h3 class="fs-subtitle">Step 3</h3>
                <div class="custom-file">
                    <input type="file" name="gambar" class="custom-file-input" id="gambar_upload">
                </div>
                <img id="picture" alt="Gambar Anda" class="img-thumbnail w-100" style="display: none;"><br>
                <input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="submit" name="submit" class="action-button" value="Submit" />
            </fieldset>
        </form>

    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>

    <script src="<?= base_url('assets_profile/') ?>js/index.js"></script>
    <script src="<?= base_url('assets_js/') ?>bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $("#gambar_upload").change(function() {
            readURL(this);
            $('#picture').css('display', 'block');
        });
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
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
        // Session 
        const status = $('.flash-data').data('status');
        const content = $('.flash-data').data('content');
        const title = $('.flash-data').data('title');
        if (status == 'error') {
            Swal.fire({
                icon: status,
                title: '<strong>' + title + '</strong>',
                html: '<i>' + content + '</i>',
            })
        } else if (status == 'success') {
            // status success
            var base_url = "<?= base_url() ?>";
            var session = "<?= $this->session->userdata('allowed'); ?>";
            if (session == 2) {
                var sekolah_id = "<?= $this->session->userdata('sekolah_id'); ?>";
                var url = base_url + "SIU/PelaksanaanUjian/index/" + sekolah_id;
                Swal.fire({
                    icon: status,
                    title: '<strong>' + title + '</strong>',
                    html: '<i>' + content + '</i>',
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    window.location = url;
                });
            } else if (session == 3) {
                var sekolah_id = "<?= $this->session->userdata('sekolah_id'); ?>";
                var url = base_url + "SIU/PelaksanaanUjian/index/" + sekolah_id;
                Swal.fire({
                    icon: status,
                    title: '<strong>' + title + '</strong>',
                    html: '<i>' + content + '</i>',
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    window.location = url;
                });
            } else if (session == 4) {
                var url = base_url + "Siswa/Home";
                Swal.fire({
                    icon: status,
                    title: '<strong>' + title + '</strong>',
                    html: '<i>' + content + '</i>',
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    window.location = url;
                });
            }

        }
    </script>
</body>

</html>