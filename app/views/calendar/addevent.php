<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="modal-dialog">

    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Add event</h4>
        </div>

        <?php
            $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
        ?>

        <div class="modal-body">

            <div class="row">
                <div class="col-lg-6">
                    <?php echo $form->field($model, 'title')->textInput(array(
                        'placeholder' => 'Enter event title',
                        'id' => 'title'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <?php echo $form->field($model, 'description')->textInput(array(
                        'placeholder' => 'Enter event description',
                        'id' => 'description'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'start_date')->input('date', array(
                        'id' => 'start_date'
                    )); ?>
                </div>

                <div class="col-lg-3 col-lg-push-1">
                    <?php echo $form->field($model, 'start_time')->input('time', array(
                        'id' => 'start_time'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'end_date')->input('date', array(
                        'id' => 'end_date'
                    )); ?>
                </div>

                <div class="col-lg-3 col-lg-push-1">
                    <?php echo $form->field($model, 'end_time')->input('time', array(
                        'id' => 'end_time'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
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
                <div class="col-lg-6">
                    <?php echo Html::label('Invite friends'); ?>
                    <?php echo Html::dropDownList('invitations', null, $array_of_users, array(
                        'multiple' => 'multiple',
                        'class' => 'form-control'
                    )); ?>
                </div>
            </div>

        </div><!--body-->

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            <?php echo Html::submitButton('Add event', array('class' => 'btn btn-primary')); ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->