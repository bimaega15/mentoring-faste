<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= base_url('vendor/img/icon/icon1.png'); ?>">

    <title><?= $title; ?></title>

    <!-- Bootstrap -->
    <link href="<?= base_url('assets/') ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets_js/') ?>css/style.css" rel="stylesheet">

    <link href="<?= base_url('assets_js/') ?>fontawesome-free-5.13.0-web/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/') ?>vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?= base_url('assets/') ?>build/css/custom.min.css" rel="stylesheet">
    <link href="<?= base_url('assets_js/') ?>datatables/datatables.min.css" rel="stylesheet">

    <link href="<?= base_url('assets_js/') ?>bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?= base_url('assets_js/') ?>select2-develop/dist/css/select2.min.css" rel="stylesheet">
    </link>
    <link href="<?= base_url('assets_js/') ?>select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets_js/timepicker/timepicker.css') ?>">

    <script src="<?= base_url('assets_js/') ?>js/jquery-3.4.1.js"></script>

</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">

            <?= $sidebar ?>

            <!-- top navigation -->
            <?= $topbar; ?>
            <!-- /top navigation -->

            <!-- page content -->
            <?= $content; ?>
            <!-- /page content -->

            <!-- footer content -->
            <?= $footer; ?>
            <!-- /footer content -->
        </div>
    </div>

    <!-- jQuery -->
    <!-- Bootstrap -->
    <script src="<?= base_url('assets_js/') ?>datatables/datatables.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?= base_url('assets/') ?>vendors/moment/min/moment.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?= base_url('assets/') ?>build/js/custom.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url('assets_js/') ?>js/sweetalert2.js"></script>
    <script src="<?= base_url('assets_js/') ?>select2-develop/dist/js/select2.min.js"></script>
    <script src="<?= base_url('assets_js/') ?>bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url('assets_js/timepicker/timepicker.js') ?>"></script>
</body>

</html>