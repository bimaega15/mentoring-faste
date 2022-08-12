<?php
$row = get_my_id()['row'];
$roles = get_my_id()['roles'];
if ($roles == 1) {
    $src = 'vendor/img/admin/' . $row->gambar;
}
if ($roles == 2) {
    $src = 'vendor/img/guru/' . $row->gambar;
}
if ($roles == 3) {
    $src = 'vendor/img/siswa/' . $row->gambar;
}

foreach (access_menu() as $result) {
    if ($result->link == 'Admin/TahunAkademik' || $result->link == 'Admin/Kelas' || $result->link == 'Admin/KelasSiswa' || $result->link == 'Admin/SiswaKelas') {
        $management_kelas = true;
    }
    if ($result->link == 'Admin/Admin' || $result->link == 'Admin/Guru' || $result->link == 'Admin/Siswa') {
        $management_users = true;
    }
    if ($result->link == 'Admin/LaporanTTQ' || $result->link == 'Admin/LaporanYaumiyah') {
        $management_laporan = true;
    }
    if ($result->link == 'Admin/Kategori' || $result->link == 'Admin/SubKategori' || $result->link == 'Admin/Yaumiyah') {
        $management_yaumiyah = true;
    }
    if ($result->link == 'Admin/KategoriTtq' || $result->link == 'Admin/Ttq') {
        $management_tahfidz = true;
    }
}
?>
<style>
    .fas {
        transition: 0.5s ease-in-out;
    }

    .fas.active {
        transform: rotate(90deg);
    }
</style>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?= base_url('Admin/Home') ?>" class="site_title"><i class="fas fa-pray"></i> <span>Mentoring</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="<?= base_url($src) ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?= $row->nama; ?></h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <?php foreach (access_menu() as $result) :  ?>
                        <?php if ($result->icon != '' && $result->link != 'Login/logout' && $result->link != 'Admin/UsersAccess' && $result->link != 'Admin/Menu') : ?>
                            <li><a href="<?= base_url($result->link) ?>"><i class="<?= $result->icon ?> mr-3"></i><span class="label label-success pull-right"><?= $result->nama; ?></span></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if (isset($management_users)) : ?>
                        <li><a><i class="fas fa-users mr-3"></i>Management Users <span class="fas fa-chevron-right float-right mr-2 chevron_right"></span></a>
                            <ul class="nav child_menu">
                                <?php foreach (access_menu() as $result) :  ?>
                                    <?php if ($result->icon == '') : ?>
                                        <?php if ($result->link == 'Admin/Admin') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/Guru') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/Siswa') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($management_kelas)) : ?>
                        <li><a><i class="fas fa-door-open mr-3"></i>Management Kelas <span class="fas fa-chevron-right float-right mr-2 chevron_right"></span></a>
                            <ul class="nav child_menu">
                                <?php foreach (access_menu() as $result) :  ?>
                                    <?php if ($result->icon == '') : ?>
                                        <?php if ($result->link == 'Admin/Kelas') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/KelasSiswa') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/SiswaKelas') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/TahunAkademik') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>



                    <?php if (isset($management_tahfidz)) : ?>
                        <li><a><i class="fas fa-book-reader mr-3"></i>Management Setoran Hafalan Al-Qur'an (TTQ)<span class="fas fa-chevron-right float-right mr-2 chevron_right"></span></a>
                            <ul class="nav child_menu">
                                <?php foreach (access_menu() as $result) :  ?>
                                    <?php if ($result->icon == '') : ?>
                                        <?php if ($result->link == 'Admin/KategoriTtq') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/Ttq') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($management_yaumiyah)) : ?>
                        <li><a><i class="fas fa-sticky-note mr-3"></i>Management Kegiatan Amalan Yaumiyah<span class="fas fa-chevron-right float-right mr-2 chevron_right"></span></a>
                            <ul class="nav child_menu">
                                <?php foreach (access_menu() as $result) :  ?>
                                    <?php if ($result->icon == '') : ?>
                                        <?php if ($result->link == 'Admin/Kategori') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/SubKategori') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/Yaumiyah') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>



                    <?php if (isset($management_laporan)) : ?>
                        <li><a><i class="fas fa-archive mr-3"></i>Management Laporan <span class="fas fa-chevron-right float-right mr-2 chevron_right"></span></a>
                            <ul class="nav child_menu">
                                <?php foreach (access_menu() as $result) :  ?>
                                    <?php if ($result->icon == '') : ?>
                                        <?php if ($result->link == 'Admin/LaporanTTQ') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($result->link == 'Admin/LaporanYaumiyah') : ?>
                                            <li><a href="<?= base_url($result->link) ?>"><?= $result->nama; ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>



                    <?php foreach (access_menu() as $result) :  ?>
                        <?php if ($result->icon != '' && $result->link == 'Login/logout') : ?>
                            <li><a href="<?= base_url($result->link) ?>"><i class="<?= $result->icon ?> mr-3"></i><span class="label label-success pull-right"><?= $result->nama; ?></span></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

    </div>
</div>
<script>
    $(document).on('click', 'ul.nav.side-menu li', function() {
        $('.chevron_right').removeClass('active');
        var Class = $(this).attr('class');
        if (Class == 'active') {
            $(this).find('.chevron_right').addClass('active');
        } else {
            $(this).find('.chevron_right').removeClass('active');

        }
    })
</script>