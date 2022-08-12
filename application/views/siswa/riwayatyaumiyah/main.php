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
</style>
<br><br><br><br>
<section id="wrapper" class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="poppins float-left pt-3"><?= $title; ?></div>
            <?= $breadcrumb; ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm bg-white text-dark">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-user fa-3x"></i><br>
                        <h4><i>Amalan Yaumiyah</i></h4>
                        <h5><i><?= tanggal_indo(date('Y-m-d')); ?></i></h5>
                    </div>
                    <div class="text-left mb-3">
                        <a class="btn btn-primary text-white" href="<?= base_url('Siswa/RiwayatYaumiyah/laporan') ?>"><i class="fas fa-eye"></i> Laporan Amalan Yaumiyah</a>
                    </div>
                    <?= form_open(base_url('Siswa/RiwayatYaumiyah/process')) ?>
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
                                                    <input name="sub_kategori[]" class="form-check-input" type="checkbox" value="<?= $result->id_sub_kategori; ?>" id="subkategori<?= $result->id_sub_kategori; ?>" <?= checkedYaumiyah($result->id_sub_kategori); ?>>
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
</section>