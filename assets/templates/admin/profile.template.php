<style media="screen">
    body{ background: #212121; color: white;}
    main{ color: white;}

    .avatar{
        width: 200px; height: 200px;
        max-width: fit-content;
        object-fit: cover;}

    .profile .header{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 350px;
    }

    .username{
        margin: 0.5em 0em;
        font-size: 1.5em;
    }

    .counters{
        background: rgba(255, 255, 255, 0.15);
        border-radius: 4px;
        padding: 1em;
        width: fit-content;
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin:auto;
    }

    .counters .counter{
        display: flex;
        flex-direction: column;
        text-align: center;
        margin: 0 1.5em;
    }

    .counters .counter span:first-of-type{
        font-size: 2em;
    }

    .actions-wrapper{
        position: absolute;
        top: 0;
        right: 0;
        margin: 1em;
        z-index: 999;
    }

    .actions-wrapper i{
        font-size: 2em;
    }

    .projects-list{
        margin: 1em 0;
    }

    .project-item{
        position: relative;
    }

    .project-item .title{
        position: absolute;
        bottom: 0;
        background: rgba(255, 255, 255, .15);
        padding: .5em 1em .5em 1em;
        width: 100%;
    }

</style>

<main class="profile">

    <div class="actions-wrapper">
        <i onclick="location.assign('<?=ABS_URL?>logout');" class="material-icons">power_settings_new</i>
    </div>
    <section class="container-fluid">
        <div class="header col-5 col-md-2 m-auto">
            <div class="project-item">
                <form action="admin/profile/avatar" method="post" onsubmit="XHR.submit(event, this, updateFromAvatarUpload)" enctype="multipart/form-data">
                    <img onclick="this.nextElementSibling.click();" class="avatar rounded-circle img-thumbnail" src="<?=$user->avatar?>" alt="">
                    <input onchange="this.nextElementSibling.click()" type="file" hidden name="user_avatar" accept="image/*">
                    <button hidden type="submit" name="button"></button>
                </form>
                <div class="username text-center">
                    <?=$user->username?>
                </div>
            </div>
        </div>
    </section>

    <div class="counters">
        <div class="counter">
            <span><?=count($user_projects)?></span>
            <span>Publications</span>
        </div>
    </div>
    <hr>
    <div class="container-fluid projects-list">
        <div class="row">
            <?php foreach ($user_projects as $project): ?>
                    <div class="col-6 col-sm-4 col-md-3" onclick="location.assign('<?=$project->full_url?>')">
                        <div class="project-item">
                            <img class="img-fluid" src="<?=$project->thumbnail?>" alt="">
                            <div class="title">
                                <span><?=$project->title?></span>
                            </div>
                        </div>
                    </div>

            <?php endforeach; ?>
        </div>
    </div>

</main>


<script type="text/javascript">
    function updateFromAvatarUpload(r) {
        var d = new Date();
        var n = d.getTime();
        document.querySelector('img.avatar').src = r.filepath+'?v='+n;
    }
</script>
