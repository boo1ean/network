<?php

use yii\helpers\Html;
use app\models\User;
?>

<div id="filtered_events">

<div class="row">
    <div class="col-lg-6 col-lg-offset-3">

<h1>Events

    <strong class="event-filter">

<?php echo Html::a('+ New event', null, array(
    'class' => 'btn btn-success',
    'name' => 'event-add',
    'data-target' => '#myModal',
    'data-toggle' => 'modal'
)); ?>

    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Filter by event type <span class="glyphicon glyphicon-filter"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a name='event-filter' id='birthday' class='cursorOnNoLink'>Birthday</a></li>
            <li><a name='event-filter' id='corpevent' class='cursorOnNoLink'>Coprevent</a></li>
            <li><a name='event-filter' id='holiday' class='cursorOnNoLink'>Holiday</a></li>
            <li><a name='event-filter' id='dayoff' class='cursorOnNoLink'>Day-off</a></li>
        </ul>
    </div>

    </strong>

</h1>

<br/>

<?php
    foreach ($events as $event) {
?>

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

<div class="panel panel-info" style="border-color: <?php echo $event->color; ?>">
    <div class="panel-heading" style="background-color: <?php echo $event->color; ?>;">
        <b class='events-heading'>
            <?php echo $event->start_date.' '.$event->start_time.' - '.$event->end_date.' '.$event->end_time; ?>
        </b>

        <b class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-edit white"></span>', null, array(
                'name' => 'event-edit',
                'event-id' => $event->id,
                'class' => 'cursorOnNoLink',
                'data-target' => '#myModal',
                'data-toggle' => 'modal'
            )); ?>

            <?php echo Html::a('<span class="glyphicon glyphicon-remove white"></span>', null, array(
                'name' => 'event-delete',
                'event-id' => $event->id,
                'class' => 'cursorOnNoLink'
            )); ?>
        </b>
    </div>

    <div class="panel-body">

        <p class='lead'>
            <?php echo Html::a($event->title, 'calendar/eventpage/'.$event->id, array(
                'style' => 'color: '.$event->color.''
            )); ?>

            <b class="pull-right">
                <span class="label label-default"><?php echo $type; ?></span>
            </b>
        </p>

        <em style="color: <?php echo $event->color; ?>;">
            <p><?php echo $event->description; ?></p>
        </em>

        <small class="pull-right" style="color: <?php echo $event->color; ?>;">
            Organized by: <?php echo User::getUserNameById($event->user_id); ?>
        </small>

        <br/>

    </div>
</div>

<?php

}

?>

    </div>
</div>

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>