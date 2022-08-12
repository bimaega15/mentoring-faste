<?php
$uri = $this->uri->segment(2);
?>
<?php if ($uri == 'Manage' || $uri == 'Profile') : ?>
    <section class="container-fluid p-3" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <span style="color:rgb(152, 166, 173);"> Developed by : &copy; <a target="_blank" class="font-weight-bold" href="https://wa.me/6281371674646">Prodi Teknik Informatika</a></span>
                </div>
            </div>
        </div>
    </section>
<?php else : ?>
    <section class="container-fluid p-3" style="background: #f4f6ff; margin-top:100px;" style="position: absolute; bottom:0;left:0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <span style="color:rgb(152, 166, 173);"> Developed by : &copy; <a target="_blank" class="font-weight-bold" href="https://wa.me/6281371674646">Prodi Teknik Informatika</a></span>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>