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
                        <h2><?= $page == 'add' ? 'Tambah' : 'Edit' ?> <small>Users Access</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <?php if (validation_errors()) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><?= validation_errors(); ?></strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        <a href="<?= base_url('Admin/Ttq/catatan/' . $id_kelas . '?kategori=' . $id_kategori_ttq) ?>" class="btn btn-warning btn-sm"><i class="fas fa-backward    "></i> Kembali</a>
                        <div class="row">
                            <div class="col-lg-3 text-right">
                                <label for="">Surah / Ayat</label>
                            </div>
                            <div class="col-lg-9">
                                <?= $row->surah ?>
                            </div>
                            <div class="col-lg-3 text-right">
                                <label for="">Tanggal</label>
                            </div>
                            <div class="col-lg-9">
                                <?= $row->tanggal ?>
                            </div>
                            <div class="col-lg-3 text-right">
                                <label for="">Waktu</label>
                            </div>
                            <div class="col-lg-9">
                                <?= $row->waktu ?>
                            </div>
                            <div class="col-lg-3 text-right">
                                <label for="">Keterangan</label>
                            </div>
                            <div class="col-lg-9">
                                <?= $row->keterangan ?>
                            </div>
                            <div class="col-lg-3 text-right">
                                <label for="">Status</label>
                            </div>
                            <div class="col-lg-9">
                                <?= $row->status_bacaan == null ? 'Tidak ditulis' : $row->status_bacaan; ?>
                            </div>
                            <div class="col-lg-3 text-right">
                                <label for="">Mahasiswa</label>
                            </div>
                            <div class="col-lg-9">
                                <?= check_siswa($row->siswa_id) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $('select[name="siswa"]').select2({
            theme: 'bootstrap4',
        });
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm',
            scrollbar: true
        });
        var page = "<?= $page ?>";
        if (page == 'add') {
            $(document).on('change', 'select[name="kelas_siswa"]', function() {
                var val = $(this).val();
                $.ajax({
                    url: "<?= base_url('Admin/Ttq/getSiswaKelas') ?>",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: val
                    },
                    success: function(data) {
                        if (data != '') {
                            $('#load_siswa').html(data.output);
                            $('#load_guruKelas').html(data.kelas_detail);
                        } else {
                            $('#load_siswa').html('');
                            $('#load_guruKelas').html('');
                        }
                    },
                    error: function(x, t, m) {
                        console.log(x.responseText);
                    }
                })
            })
        } else {
            var val = "<?= $id_kelas ?>";
            var id_siswa = "<?= $row->siswa_id ?>";
            load_kelas_siswa(val, id_siswa);
        }

        function load_kelas_siswa(val = '', id_siswa = '') {
            $.ajax({
                url: "<?= base_url('Admin/Ttq/getSiswaKelas') ?>",
                method: 'post',
                dataType: 'json',
                data: {
                    id: val,
                },
                success: function(data) {
                    if (data != '') {
                        $('#load_siswa').html(data.output);
                        $('#load_guruKelas').html(data.kelas_detail);
                        $('select[name="siswa"]').val(id_siswa).trigger('change');
                    } else {
                        var output = '<option value="">Siswa kosong</option>';
                        $('#load_siswa').html('');
                        $('#load_guruKelas').html('');
                    }
                },
                error: function(x, t, m) {
                    console.log(x.responseText);
                }
            })
        }
        $(document).on('click', '#checkboxAllSiswa', function() {
            if ($(this).is(':checked')) {
                $('.checkboxitemsiswa').prop('checked', true);
            } else {
                $('.checkboxitemsiswa').prop('checked', false);
            }
        })
        $(document).on('click', '.checkboxitemsiswa', function() {
            var checked = $('.checkboxitemsiswa:checked').length;
            var checkbox = $('.checkboxitemsiswa').length;
            if (checked == checkbox) {
                $('#checkboxAllSiswa').prop('checked', true);
            } else {
                $('#checkboxAllSiswa').prop('checked', false);
            }
        })
        var pane = $('#keterangan');
        pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
            .replace(/(<[^\/][^>]*>)\s*/g, '$1')
            .replace(/\s*(<\/[^>]+>)/g, '$1'));



    })
</script>