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

            echo Html::hiddenInput('param', 'add');
        ?>

        <div class="modal-body">

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'title')->textInput(array(
                        'id'          => 'title',
                        'placeholder' => 'Enter event title'
                    )); ?>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'description')->textarea(array(
                        'id'          => 'description',
                        'placeholder' => 'Enter event description'
                    )); ?>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <b>Start date</b>
                        <div class="date-time-picker input-group" style="padding-left: 0px;">
                            <input name="start_datetime" data-format="dd/MM/yyyy hh:mm" type="text" class="form-control" value="<?php echo date('d/m/Y H:i', strtotime($start_date))?>"/>
                            <span class="input-group-addon add-on">
                                <i style="color: #000000" class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <b>End date</b>
                        <div class="date-time-picker input-group" style="padding-left: 0px;">
                            <input name="end_datetime" data-format="dd/MM/yyyy hh:mm" type="text" class="form-control" value="<?php echo date('d/m/Y H:i', strtotime($end_date))?>"/>
                            <span class="input-group-addon add-on">
                                <i style="color: #000000" class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group drop">
                        <?php echo Html::label('Type'); ?>
                        <?php echo Html::dropDownList('type', null, array('birthday', 'corp. event', 'holiday', 'day-off'),
                            array('class' => 'form-control')) ?>
                    </div>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="form-group">
                        <?php echo Html::label('Add new member'); ?>
                        <input type="text" id="new-member-list" class="form-control">
                        <p class="help-block" style="display: none;"></p>
                    </div>
                </div>

                <div class="form-group">
                    <div id="member-event-list" class="col-lg-10 col-lg-offset-1"> </div>
                </div>

                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <?php echo Html::label('Choose event color'); ?>
                        <?php echo Html::input('text', 'colorpicker', '#d17875', array(
                            'id' => 'colorpicker'
                        )); ?>
                    </div>
                </div>
            </div>


        </div><!--body-->

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            <?php echo Html::button('Add event', array(
                'class' => 'btn btn-primary',
                'name' => 'event-add'
            )); ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->