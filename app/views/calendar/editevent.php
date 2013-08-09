<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <h1>Edit event</h1>

    <br/>

<?php

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

echo $form->field($model, 'title')->textInput(array(
    'placeholder' => 'Enter event title',
    'value' => $event->title
));

echo $form->field($model, 'description')->textInput(array(
    'placeholder' => 'Enter event description',
    'value' => $event->description
));

?>

<?php echo Html::activeLabel($model, 'start_date', array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::activeInput('date', $model, 'start_date', array('value' => $event->start_date)) ?>
        </div>
    </div>

<?php echo Html::activeLabel($model, 'start_time', array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::activeInput('time', $model, 'start_time', array('value' => $event->start_time)) ?>
        </div>
    </div>

<?php echo Html::activeLabel($model, 'end_date', array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::activeInput('date', $model, 'end_date', array('value' => $event->end_date)) ?>
        </div>
    </div>

<?php echo Html::activeLabel($model, 'end_time', array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::activeInput('time', $model, 'end_time', array('value' => $event->end_time)) ?>
        </div>
    </div>

<?php echo Html::activeLabel($model, 'type', array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::dropDownList('type', $event->type, array('birthday', 'corp. event', 'holiday', 'day-off')) ?>
        </div>
    </div>

<?php
$array_of_users = array();

foreach($users as $user) {
    $array_of_users[] = $user->first_name.' '.$user->last_name;
}
?>

<?php echo Html::label('Invite friends', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::dropDownList('invitations', null, $array_of_users, array('multiple' => 'multiple')); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::submitButton('Edit event', array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>