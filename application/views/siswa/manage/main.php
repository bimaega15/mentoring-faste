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
            <?php $error = validation_errors();
            if ($error != null) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= validation_errors() ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-lg-12">
                    <?= form_open(base_url('Siswa/Manage/updatePassword')) ?>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" placeholder="Enter your password..." name="password" class="form-control" id="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" placeholder="Enter your confirm password..." name="confirm_password" class="form-control" id="confirm_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirm_password" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <button name="submit" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>

</section>