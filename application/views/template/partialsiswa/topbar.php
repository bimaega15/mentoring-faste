<style>
    .digital-clock {
        margin-left: auto;
        position: absolute;
        top: 20px;
        left: 95px;
        width: 120px;
        height: 30px;
        color: #ffffff;
        border-radius: 5px;
        text-align: center;
        font: 20px/30px 'DIGITAL', Helvetica;
        background: linear-gradient(90deg, #363062, #4d4c7d);
        box-shadow: 0 2px 5px #000;
        align-items: center;
    }

    .dropdown .dropdown-toggle {
        background: none;
        outline: none;
        border: none;
    }

    @media (max-width: 576px) {
        span#name_profile {
            display: none;
        }

        .dropdown-menu {
            left: -105px;
        }
    }
</style>
<?php
$uri = $this->uri->segment(1);
$menu = $this->uri->segment(2);
$my_id = get_my_id()['row'];
$roles = get_my_id()['roles'];
if ($roles == 1) {
    $src = base_url('vendor/img/admin/');
}
if ($roles == 2) {
    $src = base_url('vendor/img/guru/');
}
if ($roles == 3) {
    $src = base_url('vendor/img/siswa/');
}
?>
<header id="menu_siswa_header" class="navbar navbar-expand-lg navbar-light bg-dark" style="position: fixed; top: 0; left: 0; width: 100%; z-index:9999;">
    <div class="container relative">
        <a class="navbar-brand text-white" href="<?= base_url('Siswa/Home') ?>">
            <!-- <div class="digital-clock"> 00:00:00</div> --> <img src="<?= base_url('vendor/img/bg_siswa/logo1.png') ?>" alt="Logo" style="width: 50px; height:50px;"> <span>FAKULTAS SAINS DAN TEKNOLOGI</span>
        </a>
        <div class="ml-auto"></div>
        <div class="dropdown">
            <button class="dropdown-toggle text-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" style="width: 35px;" src="<?= $src ?><?= $my_id->gambar; ?>" alt=""> &nbsp;<span id="name_profile"><?= $my_id->nama; ?></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= base_url('Siswa/Profile') ?>"><i class="fas fa-user text-secondary"></i> Data Diri</a>
                <hr>
                <a class="dropdown-item" href="<?= base_url('Siswa/Manage') ?>"><i class="fas fa-lock text-secondary"></i> Update Password</a>
                <a class="dropdown-item" href="<?= base_url('Login/logout') ?>"><i class="fas fa-sign-out-alt text-secondary"></i> Logout</a>
            </div>
        </div>
    </div>
</header>
<nav id="menu_siswa" class="navbar navbar-expand-lg navbar-light bg-white text-dark" style="position: fixed; top: 75px; left: 0; width: 100%; z-index:9;">
    <div class=" container relative">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item relative mr-3 <?= $uri == 'Siswa' && $menu == 'Home' ? 'active' : '' ?>">
                    <a class="nav-link poppins" href="<?= base_url('Siswa/Home') ?>"><i class="fas fa-home"></i> Home </a>
                </li>
                <!-- <li class="nav-item relative mr-3 <?= $uri == 'Siswa' && $menu == 'RiwayatTtq' ? 'active' : '' ?>">
                    <a class="nav-link poppins" href="<?= base_url('Siswa/RiwayatTtq') ?>"><i class="fas fa-book-open"></i> TTQ </a>
                </li> -->
                <li class="nav-item relative mr-3 <?= $uri == 'Siswa' && $menu == 'RiwayatYaumiyah' ? 'active' : '' ?>">
                    <a class="nav-link poppins" href="<?= base_url('Siswa/RiwayatYaumiyah') ?>"> <i class="fas fa-pray"></i> Amalan Yaumiyah</a>
                </li>

                <li class="nav-item relative">
                    <a class="nav-link poppins" href="<?= base_url("Login/logout") ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<script>
    $(document).ready(function() {
        clockUpdate();
        setInterval(clockUpdate, 1000);

    })

    function clockUpdate() {
        var date = new Date();
        $('.digital-clock').css({
            'color': '#fff',
            'text-shadow': '0 0 6px #c70039'
        });

        function addZero(x) {
            if (x < 10) {
                return x = '0' + x;
            } else {
                return x;
            }
        }

        function twelveHour(x) {
            if (x > 12) {
                return x = x - 12;
            } else if (x == 0) {
                return x = 12;
            } else {
                return x;
            }
        }

        var h = addZero(twelveHour(date.getHours()));
        var m = addZero(date.getMinutes());
        var s = addZero(date.getSeconds());

        $('.digital-clock').text(h + ':' + m + ':' + s)
    }
</script>