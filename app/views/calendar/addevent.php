<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1>Add event</h1>

<br/>

<?php

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

echo $form->field($model, 'title')->textInput(array('placeholder' => 'Enter event title'));
echo $form->field($model, 'description')->textInput(array('placeholder' => 'Enter event description'));

?>

<?php echo Html::label('Start date', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::input('date', 'start_date', null) ?>
        </div>
    </div>

<?php echo Html::label('Start time', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::input('time', 'start_time', null) ?>
        </div>
    </div>

<?php echo Html::label('End date', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::input('date', 'end_date', null) ?>
        </div>
    </div>

<?php echo Html::label('End time', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::input('time', 'end_time', null) ?>
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
        <?php echo Html::submitButton('Add event', array('class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>