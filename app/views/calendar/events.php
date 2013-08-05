<?php

use yii\helpers\Html;
use app\models\User;
?>

<h1>Events</h1>

<br/>
<?php echo Html::a('Add event', 'calendar/addevent', array('class' => 'btn btn-primary')); ?>

<?php
    foreach ($events as $event) {
?>

<hr>

<p class='lead'>
    <?php echo $event->title; ?><br/>
</p>

<blockquote>
    <p><?php echo $event->description; ?></p>
</blockquote>

<p class="text-info">
    Begins: <?php echo $event->start_date.' '.$event->start_time; ?><br/>
    Ends:   <?php echo $event->end_date.' '.$event->end_time; ?><br/>
</p>

<?php
    switch($event->type) {
        case '0':
            $type = 'birthday';
            break;
        case '1':
            $type = 'corp. event';
            break;
        case '2':
            $type = 'holiday';
            break;
        case '3':
            $type = 'day-off';
            break;
        default:
            break;
    }
?>

<p class="text-success">
    Type: <?php echo $type; ?><br/>
</p>

<small>
    Organized by: <?php echo User::getUserNameById($event->user_id); ?><br/>
</small>

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