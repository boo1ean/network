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
        <?php echo $form->field($model, 'title')->textInput(array(
            'placeholder' => 'Enter event title',
        )); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo $form->field($model, 'description')->textInput(array(
            'placeholder' => 'Enter event description',
        )); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <?php echo $form->field($model, 'start_date')->input('date'); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <?php echo $form->field($model, 'start_time')->input('time'); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <?php echo $form->field($model, 'end_date')->input('date'); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <?php echo $form->field($model, 'end_time')->input('time'); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::label('Type'); ?>
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

<br/>

<div class="row">
    <div class="col-lg-4">
        <?php echo Html::label('Invite friends'); ?>
        <?php echo Html::dropDownList('invitations', null, $array_of_users, array(
            'multiple' => 'multiple',
            'class' => 'form-control'
        )); ?>
    </div>
</div>

<br/>

<?php echo Html::submitButton('Add event', array('class' => 'btn btn-primary')); ?>

<?php ActiveForm::end(); ?>