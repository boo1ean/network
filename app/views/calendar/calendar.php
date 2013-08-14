<?php

use yii\helpers\Html;

?>

<h1>Calendar</h1>

<br/>

    <?php echo Html::a('Add event', null, array(
        'class' => 'btn btn-primary',
        'name' => 'event-add',
        'data-target' => '#myModal',
        'data-toggle' => 'modal'
    )); ?>

    <?php echo Html::a('Events', 'calendar/events', array('class' => 'btn btn-primary')); ?>
    <?php echo Html::a('Settings', 'calendar/settings', array('class' => 'btn btn-primary')); ?>


<br/><br/>

<div class="myevents"><?php echo $events_json; ?></div>

<div class="gcal"><?php echo $gcal; ?></div>

<div id="calendar"></div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>