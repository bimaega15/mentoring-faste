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
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tableSiswaKelas">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nomor Induk</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No. Handphone</th>
                                    <th class="text-center">Lihat Amalan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#tableSiswaKelas').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Admin/LaporanYaumiyah/serverSiswaAmalan') ?>",
                "type": "GET",
                data: {
                    id_kelas_siswa: "<?= $id_kelas_siswa ?>"
                },

            },
            "columnDefs": [{
                "targets": [5],
                "orderable": false,
            }, ],
        });
    })
</script>