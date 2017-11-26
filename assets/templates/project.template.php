<style media="screen">
.project-menu{
  background: #222;
  color: #fff;
  transform: translateY(100%);
  transition: transform .35s ease;
  overflow: scroll;
}

.expand .project-menu{
  transform: translateY(0%);
  transition: transform .7s ease;
}

body.expand{
  overflow-y: hidden;
}

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

.project-ui{
  position: absolute;
  right: 0;
  top: 0;
  display: flex;
  flex-direction: column;
  height: 100%;
  align-items: center;
}

.project-info{
  line-height: 1.5em
}

.main-ui .material-icons:first-of-type{
  margin-bottom: auto;
}

.project-meta{
    text-transform: uppercase;
    font-size: .8em;
    margin-bottom: 6em;
}

.project-counters {
  display: flex;
  align-items: center;
  font-size: .8em;
}

.project-counters > span:nth-child(odd){
  padding-right: .3em;
}
.project-counters > span:nth-child(even){
  padding-right: 1em;
}

.project-ui span.material-icons:nth-child(1) {
    margin-bottom: 2em;
}
.project-ui span.material-icons:nth-child(n+2) {
    margin-bottom: 1em;
}
.main-ui span.material-icons:nth-child(n+2) {
    margin-top: 1em;
}
.main-ui span.material-icons:last-child {
    margin-bottom: 3em;
}

.project-counters .material-icons{ font-size: inherit!important;}

header h1 {
    text-transform: uppercase;
    font-size: 2em;
    font-weight: bold;
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

 <div class="main-ui p-4">
   <span class="material-icons">close</span>
   <span class="material-icons" onclick="body.classList.add('expand')">info</span>
   <span class="material-icons">share</span>
   <span class="material-icons">file_download</span>
 </div>
 <main onclick="if(body.classList.contains('expand')) body.classList.remove('expand')" class="container-fluid">
   <div class="row">
     <div class="col-12 text-center">
       <h1>Clémentine</h1>
       <img src="<?=UPLOADS_DIR.'img1.jpg'?>" class="img-fluid" alt="">
       <p>C'est un bon fruit.C'est un bon fruit.C'est un bon fruit.C'est un bon fruit.C'est un bon fruit.</p>
       <img src="<?=UPLOADS_DIR.'img2.jpg'?>" class="img-fluid" alt="">
       <img src="<?=UPLOADS_DIR.'img3.png'?>" class="img-fluid" alt="">
     </div>



   </div>

 </main>
<section class="project-menu container-fluid fixed-bottom p-4 pr-5">
   <header>

    <section class="project-info">
      <h1><?=$project->title?></h1>
      <div class="project-counters">
        <span class="material-icons md-18">visibility</span> <span><?=$project->nb_views?></span>
        <span class="material-icons md-18">share</span> <span><?=$project->nb_shares?></span>
        <span class="material-icons md-18">file_download</span> <span><span><?=$project->nb_downloads?></span> / <span><?=$project->nb_downloads_limit?></span></span>

      </div>
      <div class="project-meta">
        <span><?=$project->type?></span> — <span><?=$project->date?></span>
      </div>
    </section>

    <section class="project-ui p-4">
      <span class="material-icons" onclick="body.classList.remove('expand')">close</span>
      <span class="material-icons">share</span>
      <span class="material-icons">file_download</span>
    </section>

   </header>

   <content>

     <p><?=$project->description?></p>

   </content>

</section>
