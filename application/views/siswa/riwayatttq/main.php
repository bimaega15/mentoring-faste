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
<br><br><br><br>
<section id="wrapper" class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="poppins float-left pt-3"><?= $title; ?></div>
            <?= $breadcrumb; ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <a href="<?= base_url('Siswa/RiwayatTtq/index?export=yes') ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
                    <br><br>
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="RiwayatTtq">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Jumlah Halaman</th>
                                    <th>Prodi</th>
                                    <th>Dosen PA</th>
                                    <th>Keterangan</th>
                                    <th>Kategori Ttq</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('#RiwayatTtq').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Siswa/RiwayatTtq/serverRiwayatTtq') ?>",
                "type": "GET",
            },
            "columnDefs": [{
                "targets": [],
                "orderable": false,
            }, ],
        });

        $(document).on('click', ".delete_RiwayatTtq", function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "Apakah anda yakin ingin menghapus ini?",
                text: "Silahkan pilih salah satu tombol",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, saya yakin',
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "<?= base_url('Siswa/RiwayatTtq/delete') ?>",
                        method: 'post',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                title: '<strong>' + data.title + '</strong>',
                                html: '<i>' + data.msg + '</i>',
                            });
                            $('.delete_RiwayatTtq[data-id="' + data.id + '"]').closest('tr').load('<?= current_url() ?> .delete_RiwayatTtq[data-id="' + data.id + '"]');
                        },
                        error: function(x, t, m) {
                            console.log(x.responseText);
                        }
                    })
                }
            })
        })


    })
</script>