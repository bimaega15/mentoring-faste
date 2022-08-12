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
                        <form method="post" action="<?= base_url('Admin/UsersAccess/process') ?>">
                            <?= form_hidden('id_UsersAccess', $row->id_users_access_management) ?>

                            <div class="form-group">
                                <label for="menu_management_id">Menu Management<span class="required">*</span>
                                </label>
                                <select name="menu_management_id" id="menu_management_id" class="form-control <?= form_error('menu_management_id') != null ? 'border border-danger' : '' ?>">
                                    <option value="">Silahkan pilih menu management</option>
                                    <?php foreach ($menu_management as $result) : ?>
                                        <option value="<?= $result->id_menu_management; ?>" <?= $row->menu_management_id  == $result->id_menu_management ? 'selected' : '' ?> <?= set_value('menu_management_id') == $result->id_menu_management ? 'selected' : '' ?>><?= $result->icon ?> | <?= $result->nama ?> | <?= $result->link ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('menu_management_id	') ?>
                            </div>

                            <div class="form-group">
                                <label for="users_roles_id">Users Roles <span class="required">*</span>
                                </label><br>
                                <?php $no = 1;
                                foreach ($users_roles as $result) : ?>
                                    <div class="form-check form-check-inline users_roles_<?= $no ?>">
                                        <input name="users_roles_id[]" class="form-check-input users_roles_<?= $no ?>" type="checkbox" id="users_roles_<?= $no; ?>" value="<?= $result->id_roles ?>" <?= $row->users_roles_id == $result->id_roles ? 'checked' : '' ?>>
                                        <label class="form-check-label users_roles_<?= $no ?>" for="users_roles_<?= $no; ?>"><?= $result->nama; ?></label>
                                    </div>
                                <?php $no++;
                                endforeach; ?>
                                <?= form_error('users_roles_id') ?>
                            </div>
                            <div class="row">
                                <div class="col-md-4 d-admin">
                                    <div class="form-group">
                                        <label> Management Access Admin
                                        </label><br>
                                        <div class="form-check form-check-inline">
                                            <input name="create_admin" class="form-check-input" type="checkbox" id="create_admin" value="ijin" <?= $row->users_roles_id == 1 && $row->create == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="create_admin">Create</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="read_admin" class="form-check-input" type="checkbox" id="read_admin" value="ijin" <?= $row->users_roles_id == 1 && $row->read == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="read_admin">Read</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="update_admin" class="form-check-input" type="checkbox" id="update_admin" value="ijin" <?= $row->users_roles_id == 1 && $row->update == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="update_admin">Update</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="delete_admin" class="form-check-input" type="checkbox" id="delete_admin" value="ijin" <?= $row->users_roles_id == 1 && $row->delete == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="delete_admin">Delete</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-guru">
                                    <div class="form-group">
                                        <label> Management Access Guru
                                        </label><br>
                                        <div class="form-check form-check-inline">
                                            <input name="create_guru" class="form-check-input" type="checkbox" id="create_guru" value="ijin" <?= $row->users_roles_id == 2 && $row->create == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="create_guru">Create</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="read_guru" class="form-check-input" type="checkbox" id="read_guru" value="ijin" <?= $row->users_roles_id == 2 && $row->read == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="read_guru">Read</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="update_guru" class="form-check-input" type="checkbox" id="update_guru" value="ijin" <?= $row->users_roles_id == 2 && $row->update == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="update_guru">Update</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="delete_guru" class="form-check-input" type="checkbox" id="delete_guru" value="ijin" <?= $row->users_roles_id == 2 && $row->delete == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="delete_guru">Delete</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-siswa">
                                    <div class="form-group">
                                        <label> Management Access Siswa
                                        </label><br>
                                        <div class="form-check form-check-inline">
                                            <input name="create_siswa" class="form-check-input" type="checkbox" id="create_siswa" value="ijin" <?= $row->users_roles_id == 3 && $row->create == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="create_siswa">Create</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="read_siswa" class="form-check-input" type="checkbox" id="read_siswa" value="ijin" <?= $row->users_roles_id == 3 && $row->read == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="read_siswa">Read</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="update_siswa" class="form-check-input" type="checkbox" id="update_siswa" value="ijin" <?= $row->users_roles_id == 3 && $row->update == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="update_siswa">Update</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="delete_siswa" class="form-check-input" type="checkbox" id="delete_siswa" value="ijin" <?= $row->users_roles_id == 3 && $row->delete == "ijin" ? "checked" : "" ?>>
                                            <label class="form-check-label" for="delete_siswa">Delete</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
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
        check_access_admin();
        check_access_guru();
        check_access_siswa();

        function check_access_admin() {
            $(document).on('change', '#create_admin', function() {
                if ($('#users_roles_1:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#read_admin', function() {
                if ($('#users_roles_1:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#update_admin', function() {
                if ($('#users_roles_1:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#delete_admin', function() {
                if ($('#users_roles_1:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
        }

        function check_access_guru() {
            $(document).on('change', '#create_guru', function() {
                if ($('#users_roles_2:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#read_guru', function() {
                if ($('#users_roles_2:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#update_guru', function() {
                if ($('#users_roles_2:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#delete_guru', function() {
                if ($('#users_roles_2:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
        }

        function check_access_siswa() {
            $(document).on('change', '#create_siswa', function() {
                if ($('#users_roles_3:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#read_siswa', function() {
                if ($('#users_roles_3:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#update_siswa', function() {
                if ($('#users_roles_3:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
            $(document).on('change', '#delete_siswa', function() {
                if ($('#users_roles_3:checked').length == 0) {
                    $(this).prop('checked', false);
                }
            })
        }

        function reset_admin() {
            $('#create_admin').prop('checked', false);
            $('#read_admin').prop('checked', false);
            $('#update_admin').prop('checked', false);
            $('#delete_admin').prop('checked', false);
        }

        function reset_guru() {
            $('#create_guru').prop('checked', false);
            $('#read_guru').prop('checked', false);
            $('#update_guru').prop('checked', false);
            $('#delete_guru').prop('checked', false);
        }

        function reset_siswa() {
            $('#create_siswa').prop('checked', false);
            $('#read_siswa').prop('checked', false);
            $('#update_siswa').prop('checked', false);
            $('#delete_siswa').prop('checked', false);
        }
        $(document).on('change', '#users_roles_1', function() {
            if ($(this).is(':checked')) {

            } else {
                reset_admin();
            }
        })
        $(document).on('change', '#users_roles_2', function() {
            if ($(this).is(':checked')) {

            } else {
                reset_guru();
            }
        })
        $(document).on('change', '#users_roles_3', function() {
            if ($(this).is(':checked')) {

            } else {
                reset_siswa();
            }
        })

        var page = "<?= $page ?>";
        if (page == 'edit') {
            if ($('#users_roles_1:checked').length > 0 && $('#users_roles_2:checked').length == 0 && $('#users_roles_3:checked').length == 0) {
                $('.d-guru').addClass('d-none');
                $('.users_roles_2').addClass('d-none');
                $('.d-siswa').addClass('d-none');
                $('.users_roles_3').addClass('d-none');

            }
            if ($('#users_roles_2:checked').length > 0 && $('#users_roles_1:checked').length == 0 && $('#users_roles_3:checked').length == 0) {
                $('.d-admin').addClass('d-none');
                $('.users_roles_1').addClass('d-none');
                $('.d-siswa').addClass('d-none');
                $('.users_roles_3').addClass('d-none');

            }
            if ($('#users_roles_3:checked').length > 0 && $('#users_roles_1:checked').length == 0 && $('#users_roles_2:checked').length == 0) {
                $('.d-admin').addClass('d-none');
                $('.users_roles_1').addClass('d-none');
                $('.d-guru').addClass('d-none');
                $('.users_roles_2').addClass('d-none');

            }
        }
    })
</script>