<div class="flex-wrapper background-img">
    <img class="background" src="<?=LOGIN_BACKGROUND?>" alt="">
    <div class="mask"></div>
    <form action="login/connect" method="post" class="dark-bg form-ajax" ajax-success="redirectToAdmin" ajax-failure="console.error">
        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">perm_identity</i>
                <input placeholder="Email or Username" type="text" name="mail">
            </div>
        </div>

        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">lock_outline</i>
                <input placeholder="Password" type="password" name="password">
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
