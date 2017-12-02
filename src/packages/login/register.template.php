<div class="flex-wrapper background-img">
    <img class="background" src="<?=LOGIN_BACKGROUND?>" alt="">
    <div class="mask"></div>
    <form action="login/register" method="post" class="dark-bg form-ajax" ajax-success="redirectToAdmin" ajax-failure="console.error">
        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">perm_identity</i>
                <input required placeholder="Username" pattern="[A-Za-z0-9]{4,}" title="Must contain at least 4 characters." type="text" name="username">
            </div>
        </div>
        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">mail_outline</i>
                <input required placeholder="Email" type="mail" name="mail">
            </div>
        </div>
        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">lock_outline</i>
                <input required placeholder="Password" type="password" name="password">
            </div>
        </div>
        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">lock_outline</i>
                <input required placeholder="Password confirmation" type="password" name="password_confirmation">
            </div>
        </div>
        <button hidden type="submit">Submit</button>
    </form>
</div>

<script type="text/javascript">
    function redirectToAdmin() {
        location.assign('admin');
    }
</script>
