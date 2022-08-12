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
                        <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Menu</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form method="post" action="<?= base_url('Admin/Menu/process') ?>">
                            <?= form_hidden('id_menu', $row->id_menu_management) ?>
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="icon">Icon
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="icon" class="form-control <?= form_error('icon') != null ? 'border border-danger' : '' ?>" name="icon" value="<?= $row->icon == null ? set_value('icon') : $row->icon ?>" placeholder="Enter your icon">
                                </div>
                                <?= form_error('icon') ?>
                            </div>
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="nama">Nama Menu<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="nama" name="nama" class="form-control <?= form_error('nama') != null ? 'border border-danger' : '' ?>" name="nama" value="<?= $row->nama == null ? set_value('nama') : $row->nama; ?>" placeholder="Enter your name">
                                </div>
                                <?= form_error('nama') ?>

                            </div>
                            <div class="item form-group">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Link <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input id="middle-name" class="form-control <?= form_error('link') != null ? 'border border-danger' : '' ?>" type="text" name="link" value="<?= $row->link == null ? set_value('link') : $row->link  ?>" placeholder="Enter your link">
                                </div>
                                <?= form_error('link') ?>
                            </div>
                            <div class="item form-group">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">No. Urut <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input id="middle-name" class="form-control <?= form_error('urut') != null ? 'border border-danger' : '' ?>" type="text" name="urut" value="<?= $row->urut == null ? set_value('urut') : $row->urut  ?>" placeholder="Enter your no. urut">
                                </div>
                                <?= form_error('urut') ?>
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