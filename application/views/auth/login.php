<style>
    .password_css {
        position: absolute;
        top: 13px;
        right: 10px;
        cursor: pointer;
    }

    .submit {
        border: 2px solid #01a9b4;
        font-size: 14px;
        padding: 10px 20px;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.5);
        transition: 0.5s ease;
        margin-right: 15px;
        width: 100%;
    }

    .submit:hover {
        background-color: #01a9b4;
        color: white;
    }

    .reset_pass {
        margin-top: -5px !important;
        color: #127681;
        margin-bottom: 5px !important;
    }
</style>
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form action="<?= base_url('Login/process') ?>" method="post">
                    <h1>Login Form</h1>
                    <div>
                        <input type="text" name="username" class="form-control <?= form_error('username') != null ? "border border-danger" : "" ?>" placeholder="Username" value="<?= set_value('username') ?>">
                        <?= form_error('username') ?>

                    </div>
                    <div style="position: relative;">
                        <input name="password" type="password" class="form-control <?= form_error('password') != null ? "border border-danger" : "" ?>" placeholder="Password" value="<?= set_value('password') ?>">
                        <?= form_error('password') ?>
                        <i class="fas fa-eye password_css"></i>
                    </div>
                    <a class="reset_pass float-right" href="<?= base_url('Login/forgot') ?>">Forgot ?</a>
                    <div class="float-left mt-3 mb-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="remember_me" type="checkbox" id="remember_me" value="1">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <button type="submit" class="btn btn-default submit">Log in</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <div>
                            <img src="<?=base_url('vendor/img/icon/icon1.png')?>" alt="Gambar logo" width="80px;">
							<h1>Mentoring dan Hafalan AlQur'an</h1>
                            <p>©2021 Fakultas Sains dan Teknologi.</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.password_css').on('click', function() {
            const type = $('input[name="password"]').attr('type');
            if (type == 'password') {
                const type = $('input[name="password"]').attr('type', 'text');
                $('.password_css').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                const type = $('input[name="password"]').attr('type', 'password');
                $('.password_css').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        })
    })
</script>