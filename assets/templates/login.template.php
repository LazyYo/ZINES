<section class="fullscreen flex-column flex-container justify-center centered">
    <section class="logo">
        <span><?=ROOT_NAME?></span>
    </section>

    <form action="login/connect" method="post" onsubmit="XHR.submit(event, this, commitLogin)">
        <div class="row">
            <section class="input-element icon">
                <span class="fa fa-user"></span><input onblur="this.parentNode.classList.remove('dark')" onfocus="this.parentNode.classList.add('dark')" type="text" name="mail" placeholder="E-mail or Username">
            </section>
            <section class="input-element icon">
                <span class="fa fa-lock"></span><input onblur="this.parentNode.classList.remove('dark')" onfocus="this.parentNode.classList.add('dark')" type="password" name="password" placeholder="Password">
            </section>

            <section class="input-element">
                <button type="submit"><span class="fa fa-check"></span> Connect</button>
            </section>
        </div>




    </form>

</section>
