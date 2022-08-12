<section id="wrapper" class="container mt-5">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
                <a href="<?= base_url('Siswa/SolatSunnah/add/' . $id_kategori) ?>" class="btn btn-info shadow-sm"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
        </div>
        <div class="col-lg-12">
            <table class="table table-striped table-bordered text-center" id="solatSunnah">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Nama</th>
                        <th>Keterangan</th>
                        <th>Action</th>
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
        $('#solatSunnah').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('Siswa/SolatSunnah/serverSolatSunnah') ?>",
                "type": "GET",
                data: {
                    'id_kategori': "<?= $id_kategori ?>"
                }
            },
            "columnDefs": [{
                "targets": [5],
                "orderable": false,
            }, ],
        });

        $(document).on('click', ".delete_SolatSunnah", function(e) {
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
                        url: "<?= base_url('Siswa/SolatSunnah/delete') ?>",
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
                            $('.delete_SolatSunnah[data-id="' + data.id + '"]').closest('tr').load('<?= current_url() ?> .delete_SolatSunnah[data-id="' + data.id + '"]');
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