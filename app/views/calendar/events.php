<?php

use yii\helpers\Html;

?>

<h1>Events</h1>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Add event', 'calendar/addevent'); ?></li>
</ul>

<?php

foreach ($events as $event) {
    echo '<hr>';

    echo $event->title.'<br/>';
    echo $event->description.'<br/>';
    echo 'Begin Date: '.$event->start_date.' '.$event->start_time.'<br/>';
    echo 'End Date: '.$event->end_date.' '.$event->end_time.'<br/>';
?>

<br/>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Edit', array('calendar/editevent/' . $event->id )); ?></li>
    <li><?php echo Html::a('Delete', null, array(
            'event-id' => $event->id,
            'class' => 'cursorOnNoLink',
            'onclick' => 'return deleteEvent(this);')); ?></li>
</ul>

<?php

}

?>

<div id="calendar"></div>