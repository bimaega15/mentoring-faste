<section id="wrapper" class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="<?= base_url('Siswa/SolatWajib/index/' . $id_kategori) ?>" class="btn btn-primary shadow-sm"><i class="fas fa-pray"></i> Sholat Wajib</a>
        </div>
        <div class="col-lg-12">
            <table class="table table-striped table-bordered text-center" id="solatWajib">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Subuh</th>
                        <th>Waktu Subuh</th>
                        <th>Dzuhur</th>
                        <th>Waktu Dzuhur</th>
                        <th>Ashar</th>
                        <th>Waktu Ashar</th>
                        <th>Maghrib</th>
                        <th>Waktu Maghrib</th>
                        <th>Isya</th>
                        <th>Waktu Isya</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('#solatWajib').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Siswa/SolatWajib/serverSolatWajib') ?>",
                "type": "GET",
                data: {
                    'id_kategori': "<?= $id_kategori ?>"
                }
            },
            "columnDefs": [{
                "targets": [],
                "orderable": false,
            }, ],
        });

    })
</script>