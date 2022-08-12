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
                        <form method="post" action="<?= base_url('Admin/Kategori/process') ?>">
                            <?= form_hidden('id_Kategori', $row->id_kategori) ?>
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="form-group row">
                                        <label for="nama" class="col-sm-2 col-form-label">Nama <span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input name="nama" class="form-control datepicker <?= form_error('nama') != null ? 'border border-danger' : '' ?>" type="text" id="nama" placeholder="Enter your nama kategori" value="<?= $row->nama == null ? set_value('nama') : $row->nama; ?>">
                                            <?= form_error('nama') ?>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="item form-group">
                                        <div class="d-flex justify-content-center w-100">
                                            <button class="btn btn-danger shadow-sm" type="reset"><i class="fas fa-window-close"></i> Reset</button>
                                            <button name="<?= $page ?>" type="submit" class="btn btn-success shadow-sm"><i class="fas fa-save"></i> Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>