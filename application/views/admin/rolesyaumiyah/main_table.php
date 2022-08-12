<style>
    .link_kategori {
        transition: 0.5s all;
    }

    .link_kategori:hover {
        padding-left: 10px;
    }
</style>
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
    <div class="row" style="display: block;">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table-bordered table" id="tableKelasSiswa">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kelompok / Kelas PA</th>
                                        <th>Tahun Ajaran</th>
                                        <th class="text-center" width="150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($kelas as $kelas) :
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $kelas->kelas_siswa; ?></td>
                                            <td><?= $kelas->nama_tahun; ?></td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-primary" href="<?= base_url('Admin/RolesYaumiyah/kategoriYaumiyah/' . $kelas->id_kelas_siswa) ?>"> <i class="fas fa-eye    "></i> Roles Yaumiyah</a>
                                                <a class="btn btn-sm btn-primary" href="<?= base_url('Admin/RolesYaumiyah/siswaYaumiyah/' . $kelas->id_kelas_siswa) ?>"> <i class="fas fa-eye    "></i> Siswa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalTtq" tabindex="-1" role="dialog" aria-labelledby="modalTtqLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary font-weight-bold">
                <h5 class="modal-title text-white" id="modalTtqLabel"><i class="fas fa-file-excel"></i> Import TTQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart(base_url('Admin/Ttq/importData')) ?>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" name="file" class="custom-file-input" id="file-excel" accept=".xls, .xlsx">
                        <label class="custom-file-label" for="file-excel">Masukan file</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Batal</button>
                <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tableKelasSiswa').DataTable({
            "order": [],
            "columnDefs": [{
                "targets": [0, 3],
                "orderable": false,
            }, ],
        });
    })
</script>