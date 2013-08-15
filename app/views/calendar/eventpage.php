<?php

use yii\helpers\Html;
use app\models\Userevent;
use app\models\User;
?>

<h1><?php echo $event->title; ?></h1>

<br/>

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

<div class="row">
    <div class="col-lg-6">
        <?php echo Html::label('Invited friends'); ?>

        <br/>

        <?php
        $invites = Userevent::findByEventId($event->id);
        $array_invited = array();

        foreach($invites as $invite) {
            $array_invited[] = User::getUserNameById($invite->user_id);
        }

        echo Html::listBox('invites', null, $array_invited, array(
            'class' => 'form-control',
            'disabled' => 'disabled'
        ));
        ?>

    </div>
</div>

<br/>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Edit', null, array(
            'name' => 'event-edit',
            'event-id' => $event->id,
            'class' => 'cursorOnNoLink',
            'data-target' => '#myModal',
            'data-toggle' => 'modal'
        )); ?>
    </li>
    <li><?php echo Html::a('Delete', null, array(
            'name' => 'event-delete',
            'event-id' => $event->id,
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>
</ul>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>