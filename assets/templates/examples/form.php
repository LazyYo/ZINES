<link rel="stylesheet" href="<?=ASSETS_DIR?>CSS/forms.css">

<div class="container">
    <form class="" action="index.html" method="post">
        <div class="row">
            <div class="col text-center">
                <span>FORM</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="input-element">
                    <input type="text" name="input_text" placeholder="My text here...">
                    <span class="fa fa-file"></span>
                </div>
                <div class="input-element required">
                    <input type="text" name="input_text2" placeholder="My text here..." required>
                    <!-- <span class="fa fa-file"></span> -->
                    <span class="material-icons">share</span>
                </div>
                <div class="input-element">
                    <input type="text" name="input_text2" placeholder="My text here..." required>
                    <span class="">Text Input</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="input-element required">
                    <input type="text" name="input_text2" placeholder="My text here..." required>
                    <span class="fa fa-file"></span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="input-element disabled">
                    <input type="password" name="input_password" placeholder="My password here..." disabled>
                    <span class="fa fa-lock"></span>
                </div>
            </div>
        </div>
    </form>
</div>
