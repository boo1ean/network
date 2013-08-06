<?php

use yii\helpers\Html;

?>

<h1>Calendar</h1>

<br/>

<ul class="inline">
    <li><?php echo Html::a('Add event', 'calendar/addevent', array('class' => 'btn btn-primary')); ?></li>
    <li><?php echo Html::a('Events', 'calendar/events', array('class' => 'btn btn-primary')); ?></li>
    <li><?php echo Html::a('Settings', 'calendar/settings', array('class' => 'btn btn-primary')); ?></li>
</ul>

<br/>

<div class="myevents"><?php echo $events_json; ?></div>

<div class="gcal"><?php echo $gcal; ?></div>

<div id="calendar"></div>