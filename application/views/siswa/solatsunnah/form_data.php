
<section id="wrapper" class="container mt-5">

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="btn-group btn-rounded" role="group" aria-label="Basic example">
                <a href="<?= base_url('Siswa/SolatSunnah/index/' . $id_kategori) ?>" class="btn btn-warning shadow-sm"><i class="fas fa-backward"></i> Kembali</a>
            </div>
        </div>
        <div class="col-lg-12">
            <?= form_open(base_url('Siswa/SolatSunnah/process/' . $id_kategori)) ?>
            <div class="form-group">
                <?= form_hidden('id_solat_sunah', $row->id_solat_sunah) ?>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="tanggal">Tanggal <span class="required">*</span>
                        </label>
                        <input type="text" id="tanggal" name="tanggal" class="form-control datepicker <?= form_error('tanggal') != null ? 'border border-danger' : '' ?>" name="tanggal" placeholder="Enter your tanggal" value="<?= $row->tanggal == null ? set_value('tanggal') : $row->tanggal; ?>">
                        <?= form_error('tanggal') ?>
                    </div>
                    <div class="col-lg-6">
                        <label for="waktu">Waktu <span class="required">*</span>
                        </label>
                        <input type="text" id="waktu" name="waktu" class="form-control timepicker <?= form_error('waktu') != null ? 'border border-danger' : '' ?>" name="waktu" placeholder="Enter your waktu" value="<?= $row->waktu == null ? set_value('waktu') : $row->waktu; ?>">
                        <?= form_error('waktu') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" id="nama" name="nama" class="form-control <?= form_error('nama') != null ? 'border border-danger' : '' ?>" name="nama" placeholder="Enter your nama" value="<?= $row->nama == null ? set_value('nama') : $row->nama; ?>">
                <?= form_error('nama') ?>
            </div>
            <div class="form-group">
                <label for="">keterangan</label>
                <textarea name="keterangan" class="form-control <?= form_error('keterangan') != null ? 'border border-danger' : '' ?>" rows="5" placeholder="Enter your keterangan"><?= $row->keterangan == null ? set_value('keterangan') : $row->keterangan; ?></textarea>
                <?= form_error('keterangan') ?>
            </div>
            <hr>
            <div class="form-group d-flex justify-content-center">
                <button type="submit" name="<?= $page; ?>" class="btn btn-success mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button type="reset" class="btn btn-danger"><i class="fas fa-window-close"></i> Close</button>
            </div>
            <?= form_close(); ?>

        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            toggleActive: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm',
            scrollbar: true
        });
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
    })
</script>