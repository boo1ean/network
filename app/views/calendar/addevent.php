<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1>Add event</h1>

<br/>

<?php
    $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
?>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'title', array('class' => 'control-label')); ?>
        <?php echo Html::activeInput('string', $model, 'title', array(
            'class' => 'form-control',
            'placeholder' => 'Enter event title'
        )) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'description', array('class' => 'control-label')); ?>
        <?php echo Html::activeInput('string', $model, 'description', array(
            'class' => 'form-control',
            'placeholder' => 'Enter event description',
        )) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'start_date', array('class' => 'control-label')); ?>
        <?php echo Html::activeInput('date', $model, 'start_date', array(
           'class' => 'form-control'
        )) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'start_time', array('class' => 'control-label')); ?>
        <?php echo Html::activeInput('time', $model, 'start_time', array(
            'class' => 'form-control'
        )) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'end_date', array('class' => 'control-label')); ?>
        <?php echo Html::activeInput('date', $model, 'end_date', array(
            'class' => 'form-control'
        )) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'end_time', array('class' => 'control-label')); ?>
        <?php echo Html::activeInput('time', $model, 'end_time', array(
            'class' => 'form-control'
        )) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::activeLabel($model, 'type', array('class' => 'control-label')); ?>
        <?php echo Html::dropDownList('type', null, array('birthday', 'corp. event', 'holiday', 'day-off'),
            array('class' => 'form-control')) ?>
    </div>
</div>

<?php
    $array_of_users = array();

    foreach($users as $user) {
        $array_of_users[$user->email] = $user->first_name.' '.$user->last_name;
    }
?>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::label('Invite friends', null, array('class' => 'control-label')); ?>
        <?php echo Html::dropDownList('invitations', null, $array_of_users, array(
            'multiple' => 'multiple',
            'class' => 'form-control'
        )); ?>
    </div>
</div>

<br/>

<?php echo Html::submitButton('Add event', array('class' => 'btn btn-primary')); ?>

<?php ActiveForm::end(); ?>