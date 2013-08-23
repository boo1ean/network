<?php

use yii\helpers\Html;

?>

<br/>

    <?php echo Html::a('New event', null, array(
        'class' => 'btn btn-primary',
        'name' => 'event-add',
        'data-target' => '#myModal',
        'data-toggle' => 'modal',
        'data-pjax' => '#pjax-container'
    )); ?>

    <?php echo Html::a('Agenda', 'calendar/events', array(
        'class' => 'btn btn-primary',
        'data-pjax' => '#pjax-container'
    ));
    ?>

    <?php echo Html::a('Settings', 'calendar/settings', array(
        'class' => 'btn btn-primary',
        'data-pjax' => '#pjax-container'
    ));
    ?>

<br/><br/>

<div class="myevents"><?php echo $events_json; ?></div>

<div class="gcal"><?php echo $gcal; ?></div>

<div id="calendar"></div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>