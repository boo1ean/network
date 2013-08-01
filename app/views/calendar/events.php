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

    echo $event->title;
    echo 'Begin: '.$event->start_date.' '.$event->start_time.'<br/>';
    echo 'End: '.$event->end_date.' '.$event->end_time.'<br/>';
}

?>