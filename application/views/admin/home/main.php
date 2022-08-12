<style>
    .area_total {
        position: relative;
    }

    .icon_user {
        font-size: 35px !important;
        position: absolute;
        text-align: right;
        right: 20px;
        bottom: 15px;
    }

    #card_area p {
        font-size: 25px;
    }

    #area_rows {
        position: relative;
    }
</style>
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row" style="display: block;">
        <div class="col-lg-12">
            <?= $breadcrumb; ?>
        </div>
    </div>
    <div class="card" id="card_area">
        <div class="card-body">
            <div id="area_rows">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-left border-primary shadow-sm py-3 border-top-0 border-bottom-0 border-right-0">
                            <div class="card-body">
                                <p>Total Admin</p>
                                <strong style="font-size: 26px;"><?= $admin; ?></strong>
                                <i class="fas fa-user-tie icon_user"></i>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="card border-left border-primary shadow-sm py-3 border-top-0 border-bottom-0 border-right-0">
                            <div class="card-body">
                                <p>Total Guru</p>
                                <strong style="font-size: 26px;"><?= $guru; ?></strong>
                                <i class="fas fa-user-tie icon_user"></i>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="card border-left border-primary shadow-sm py-3 border-top-0 border-bottom-0 border-right-0">
                            <div class="card-body">
                                <p>Total Siswa</p>
                                <strong style="font-size: 26px;"><?= $siswa; ?></strong>
                                <i class="fas fa-user-tie icon_user"></i>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card border-left border-success shadow-sm py-3 border-top-0 border-bottom-0 border-right-0">
                            <div class="card-body">
                                <p>Total Kelas</p>
                                <strong style="font-size: 26px;"><?= $kelas; ?></strong>
                                <i class="fas fa-house-user icon_user"></i>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card border-left border-success border-top-0 border-bottom-0 border-right-0 shadow-sm py-3">
                            <div class="card-body">
                                <p>Total Kelas Siswa</p>
                                <strong style="font-size: 26px;"><?= $kelas_siswa; ?></strong>
                                <i class="fas fa-house-user icon_user"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /top tiles -->
</div>