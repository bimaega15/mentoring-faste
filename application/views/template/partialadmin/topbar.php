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
?>
<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?= base_url($src) ?>" alt=""><?= $row->nama; ?>
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('Admin/Profile') ?>"> Profile</a>
                        <a class="dropdown-item" href="<?= base_url('Login/logout') ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>