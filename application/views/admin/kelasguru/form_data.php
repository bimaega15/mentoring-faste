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
                    <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Kelompok / Kelas Dosen PA</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <form method="post" action="<?= base_url('Admin/KelasGuru/process/' . $id_kelas) ?>">
                        <?= form_hidden('id_KelasGuru', $row->id_kelas_detail) ?>
                        <div class="form-group">
                            <label for="Guru">Dosen PA<span class="required">*</span>
                            </label>
                            <select name="guru_id" id="guru_id" class="form-control <?= form_error('guru_id') != null ? 'border border-danger' : '' ?>">
                                <option value="">Silahkan pilih </option>
                                <?php foreach ($guru as $result) : ?>
                                    <option value="<?= $result->id_guru; ?>" <?= $row->guru_id == $result->id_guru ? 'selected' : '' ?> <?= set_value('guru_id') == $result->id_guru ? 'selected' : '' ?>><?= $result->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('guru_id') ?>
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
        $('#guru_id').select2({
            theme: 'bootstrap4',
        });
    })
</script>