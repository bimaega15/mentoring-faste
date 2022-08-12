<style>
    .link_kategori {
        transition: 0.5s all;
    }

    .link_kategori:hover {
        padding-left: 10px;
    }
</style>
<div class="right_col" role="main" style="min-height: 676px;">
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
                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-left mb-3">
                                <a class="btn btn-primary text-white" href="<?= base_url('Admin/LaporanYaumiyah/siswa/' . $id_siswa . '?kelas_siswa=' . $id_kelas_siswa) ?>"><i class="fas fa-eye"></i> Laporan Amalan Yaumiyah</a>
                            </div>
                            <?= form_open(base_url('Admin/RolesYaumiyah/processSubmitYaumiyah')) ?>
                            <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
                            <input type="hidden" name="id_kelas_siswa" value="<?= $id_kelas_siswa ?>">
                            <div class="accordion poppins" id="accordionExample">
                                <?php foreach ($kategori as $result_kategori) : ?>
                                    <div class="card">
                                        <div class="card-header" style="padding:5px 10px; border-top: 2px solid #7cd848; background-color: rgba(255, 255, 255, 0.3);" id="headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left text-decoration-none" type="button" data-toggle="collapse" data-target="#kategori<?= $result_kategori->kategori_id; ?>" aria-expanded="true" aria-controls="collapseOne">
                                                    <?= $result_kategori->nama; ?>
                                                </button>
                                            </h2>
                                        </div>
                                        <?php foreach ($sub_kategori as $result) : ?>
                                            <?php if ($result->kategori_id == $result_kategori->kategori_id) : ?>
                                                <div id="kategori<?= $result->kategori_id; ?>" class="collapse" aria-labelledby="kategori<?= $result_kategori->kategori_id; ?>" data-parent="#kategori<?= $result_kategori->kategori_id; ?>">
                                                    <div class="card-body" style="padding:5px 20px;">
                                                        <div class="form-check">
                                                            <input name="sub_kategori[]" class="form-check-input" type="checkbox" value="<?= $result->id_sub_kategori; ?>" id="subkategori<?= $result->id_sub_kategori; ?>" <?= checkedYaumiyah($result->id_sub_kategori, $id_siswa, $id_kelas_siswa); ?>>
                                                            <label class="form-check-label" for="subkategori<?= $result->id_sub_kategori; ?>">
                                                                <?= $result->nama; ?>
                                                            </label>
                                                            <?= form_error('sub_kategori[]') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-submit btn-primary w-100 py-2 mt-3"> <i class="fas fa-check"></i> Submit</button>
                            </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>