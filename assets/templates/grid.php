<?php

$entities = ProjectController::getAll();

?>
<div class="grid">
    <?php
    foreach ($entities as $key => $entity) {
        echo $entity->gridLayout();
    }?>
</div>
