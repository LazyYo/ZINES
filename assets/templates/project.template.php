<style media="screen">

    .main-ui{
      position: fixed;
      right: 0;
      bottom: 0;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      height: 100%;
      align-items: center;
      justify-content: center;
    }

    .main-ui .material-icons:first-of-type{
      margin-bottom: auto;
    }

    .main-ui span.material-icons:nth-child(n+2) {
        margin-top: 1em;
    }
    .main-ui span.material-icons:last-child {
        margin-bottom: 3em;
    }
</style>

 <div class="main-ui p-4">
   <span class="material-icons" onclick="location.assign('<?=ABS_URL?>')">close</span>
   <span class="material-icons" onclick="body.classList.add('expand')">info</span>
   <span class="material-icons">share</span>
   <span class="material-icons">file_download</span>
 </div>

<main class="container-fluid">
    <?php include_once($project->contentFile); ?>
</main>
<section class="project-menu container-fluid fixed-bottom p-4">
   <header class="pr-5">
    <section class="project-info">
      <h1><?=$project->title?></h1>
      <div class="project-counters">
        <span class="material-icons">visibility</span> <span><?=$project->nb_views?></span>
        <span class="material-icons">share</span> <span><?=$project->nb_shares?></span>
        <span class="material-icons">file_download</span> <span><span><?=$project->nb_downloads?></span> / <span><?=($project->nb_downloads_limit !== NULL)?$project->nb_downloads_limit:'∞'?></span></span>

      </div>
      <div class="project-meta">
          <?php if($project->type !== NULL) ?>
          <span><?=($project->type !== NULL)?$project->type.' — ':''?></span>
          <span><?=date('F Y', strtotime( $project->date ) ). ' — '?></span>
          <span><?=$project->author->username?></span>
      </div>
    </section>

    <section class="project-ui">
      <span class="material-icons" onclick="body.classList.remove('expand')">close</span>
      <span class="material-icons">share</span>
      <span class="material-icons">file_download</span>
    </section>

   </header>

   <!-- FIX : Replaced content by section-->
   <section class="project-description pr-md-5">
     <p><?=$project->description?></p>
    </section>

</section>

<script type="text/javascript">
    document.querySelector('main').addEventListener('click', function(event){
        if(document.body.classList.contains('expand')) document.body.classList.remove('expand');
    }, false);
</script>
