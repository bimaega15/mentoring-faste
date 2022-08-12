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
<?php
error_reporting(0);
$profile = get_my_id()['row'];
$guru = check_join_all_kelas_siswa();
?>
<br><br><br><br><br><br>
<section id="wrapper" class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="poppins float-left pt-3"><?= $title; ?></div>
            <?= $breadcrumb; ?>
            <div class="clearfix"></div>
        </div>

        <div class="col-md-12" style="min-height: 400px;">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a style="color: #64676b;" class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Data Diri</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a style="color: #64676b;" class="nav-link" id="profile-tab" data-toggle="tab" href="#guru" role="tab" aria-controls="profile" aria-selected="false">Data Guru</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h6>Data Diri Saya</h6>
                            <hr>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td><?= $profile->nama; ?></td>
                                </tr>
                                <tr>
                                    <td>Nomor Induk Siswa</td>
                                    <td><?= $profile->nomor_induk; ?></td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tanggal lahir</td>
                                    <td><?= $profile->tempat_lahir; ?>, <?= tanggal_indo($profile->tanggal_lahir) ?></td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td><?= $profile->jenis_kelamin == "L" ? "Laki laki" : "Perempuan"; ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td><?= $profile->alamat; ?></td>
                                </tr>
                                <tr>
                                    <td>No. Telephone</td>
                                    <td><?= $profile->no_telephone; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="guru" role="tabpanel" aria-labelledby="profile-tab">
                            <?php foreach ($guru as $guru) : ?>
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="<?= base_url('vendor/img/guru/' . $guru->gambar) ?>" alt="Gambar guru" class="rounded-circle w-50 ml-5">
                                    </div>
                                    <div class="col-md-10">
                                        <strong><?= $guru->nama; ?></strong><br><br>
                                        <!-- <span>Bertugas membimbing siswa</span><br><br> -->
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <td>NIK</td>
                                                <td><?= $guru->nomor_induk ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nomor Telephone</td>
                                                <td><?= $guru->no_telephone; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Status Dosen</td>
                                                <td><?= $guru->status_ttq == "y" ? "Dosen Penasehat Akademik" : "Dosen Penasehat Akademik"; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Dosen Prodi</td>
                                                <td><?= $guru->kelas_ngajar; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>