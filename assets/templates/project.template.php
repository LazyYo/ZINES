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

<?php /* TODO:
              - title
              - nb_views
              - nb_shares
              - nb_downloads
              - nb_downloads_limit
              - description
              - type
              - date
              - author
      */
 ?>
<body class="expand">

 <div class="main-ui p-4">
   <span class="material-icons">close</span>
   <span class="material-icons" onclick="body.classList.add('expand')">info</span>
   <span class="material-icons">share</span>
   <span class="material-icons">file_download</span>
 </div>

 <main onclick="if(body.classList.contains('expand')) body.classList.remove('expand')" class="container-fluid text-center">
   <h1>Clémentine</h1>
   <img src="<?=UPLOADS_DIR.'img1.jpg'?>" class="img-fluid" alt="">
   <p>C'est un bon fruit.C'est un bon fruit.C'est un bon fruit.C'est un bon fruit.C'est un bon fruit.</p>
   <img src="<?=UPLOADS_DIR.'img2.jpg'?>" class="img-fluid" alt="">
   <img src="<?=UPLOADS_DIR.'img3.jpg'?>" class="img-fluid" alt="">

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
     <p><?=$project->description?></p>
    </section>

</section>

</body>
