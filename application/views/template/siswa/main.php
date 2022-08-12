<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets_js/bootstrap-4.3.1-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets_js/fontawesome-free-5.13.0-web/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets_js/css/style.css') ?>">
    <link href="<?= base_url('assets_js/') ?>datatables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets_js/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets_js/timepicker/timepicker.css') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('vendor/img/icon/icon.png'); ?>">

    <style>
        body {
            background: #ebeff2;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>


    <script src="<?= base_url('assets_js/js/jquery-3.4.1.js') ?>"></script>
    <script src="<?= base_url('assets_js/') ?>datatables/datatables.min.js"></script>
    <script src="<?= base_url('assets_js/js/select2.min.js') ?>"></script>
    <script src="<?= base_url('assets_js/js/sweetalert2.js') ?>"></script>
    <script src="<?= base_url('assets_js/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets_js/timepicker/timepicker.js') ?>"></script>
    <script src="<?= base_url('assets_js/garand-sticky-73b0fbe/jquery.sticky.js') ?>"></script>
</head>

<body>
    <?= $topbar; ?>

    <?= $content; ?>


    <?= $footer; ?>

    <script src="<?= base_url('assets_js/bootstrap-4.3.1-dist/js/bootstrap.min.js') ?>"></script>
</body>

</html>