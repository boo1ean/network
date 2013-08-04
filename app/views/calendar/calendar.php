<?php

use yii\helpers\Html;

?>

<h1>Calendar</h1>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Add event', 'calendar/addevent'); ?></li>
    <li><?php echo Html::a('Events', 'calendar/events'); ?></li>
</ul>

<div class="myevents"><?php echo $events_json; ?></div>

<div id="calendar"></div>