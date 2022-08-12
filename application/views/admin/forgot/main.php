<style>
    .modal-body.Forgot {
        height: 500px;
        overflow-y: auto;
    }
</style>
<div class="right_col" role="main" style="min-height: 676px;">
    <div class="">
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

        <div class="row" style="display: block;">
            <div class="col-md-12">
                <div class="x_panel">
                    <?php if ($update) : ?>
                        <div class="x_title">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="<?= base_url('Admin/Forgot/resetCheck') ?>" class="btn btn-success btn-rounded btn_reset_check"><i class="fas fa-redo-alt"></i> Reset</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="x_content table-responsive">
                        <table class="table table-bordered" id="table_Forgot">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checked_all_Forgot">
                                            <label class="custom-control-label" for="checked_all_Forgot"></label>
                                        </div>
                                    </th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Users</th>
                                    <?php if ($update) : ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($reset_pass as $result) : ?>
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check_item_Forgot" id="check_item_Forgot<?= $no; ?>" value="<?= $result->id_users ?>">
                                                <label class="custom-control-label" for="check_item_Forgot<?= $no; ?>"></label>
                                            </div>
                                        </td>
                                        <td><?= $result->username; ?></td>
                                        <td><span class="badge badge-primary shadow-sm"><?= $result->status; ?></span></td>
                                        <td><span class="badge badge-warning shadow-sm"><?= $result->forgot == 1 ? "Lupa password" : ""; ?></span></td>
                                        <td><?= checkUsers($result->id_users); ?></td>
                                        <?php if ($update) : ?>
                                            <td>
                                                <a href="<?= base_url('Admin/Forgot/resetOne/' . $result->id_users) ?>" class="btn btn-sm btn-active bg-success text-white"><i class="fas fa-redo-alt"></i> Reset</a>
                                            </td>
                                        <?php endif; ?>

                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $(document).on('click', '#resetAll', function() {
            var arr = [];
            $('.check_item_Forgot:checked').each(function() {
                arr.push($(this).val());
            });
            if (arr != '') {
                Swal.fire({
                    title: "Apakah anda yakin ingin reset password beberapa item ini?",
                    text: "Silahkan pilih salah satu tombol",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, saya yakin',
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "<?= base_url('Admin/Forgot/resetPasswordAll') ?>",
                            method: 'post',
                            dataType: 'json',
                            data: {
                                data_item: arr
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    title: '<strong>' + data.title + '</strong>',
                                    html: '<i>' + data.msg + '</i>',
                                }).then(function() {
                                    location.reload(true);
                                });
                            },
                            error: function(x, t, m) {
                                console.log(x.responseText);
                            }
                        })
                    }
                })

            } else {
                Swal.fire({
                    title: '<strong>Data kosong</strong>',
                    icon: 'info',
                    html: '<i>Silahkan terlebih dahulu select item pada table</i>',
                    showCloseButton: true,
                    focusConfirm: false,
                })
            }
        })
        $(document).on('click', '.btn_reset_check', function(e) {
            e.preventDefault();
            var arr = [];
            $('.check_item_Forgot:checked').each(function() {
                arr.push($(this).val());
            });
            if (arr != '') {
                Swal.fire({
                    title: "Apakah anda yakin ingin reset password beberapa item ini?",
                    text: "Silahkan pilih salah satu tombol",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, saya yakin',
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "<?= base_url('Admin/Forgot/resetPasswordAll') ?>",
                            method: 'post',
                            dataType: 'json',
                            data: {
                                data_item: arr
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    title: '<strong>' + data.title + '</strong>',
                                    html: '<i>' + data.msg + '</i>',
                                }).then(function() {
                                    location.reload(true);
                                });
                            },
                            error: function(x, t, m) {
                                console.log(x.responseText);
                            }
                        })
                    }
                })

            } else {
                Swal.fire({
                    title: '<strong>Data kosong</strong>',
                    icon: 'info',
                    html: '<i>Silahkan terlebih dahulu select item pada table</i>',
                    showCloseButton: true,
                    focusConfirm: false,
                })
            }
        })
        $(document).on('click', '#checked_all_Forgot', function() {
            if ($(this).is(':checked')) {
                $('.check_item_Forgot').prop('checked', true);
            } else {
                $('.check_item_Forgot').prop('checked', false);
            }
        })
        $(document).on('click', '.check_item_Forgot', function() {
            if ($('.check_item_Forgot:checked').length == $('.check_item_Forgot').length) {
                $('#checked_all_Forgot').prop('checked', true);
            } else {
                $('#checked_all_Forgot').prop('checked', false);
            }
        })
        $('#table_Forgot').DataTable({
            "order": [],
            "columnDefs": [{
                "targets": [0, 4],
                "orderable": false,
            }, ],
        });
    })
</script>