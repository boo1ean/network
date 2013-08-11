<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Create conversation </h3>
        </div>
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));?>
            <div class="modal-body" >
                <?php echo $form->field($model, 'title')->textInput(array('value'=> $model->title)); ?>
                <div class="row">
                    <div class="col-lg-6">
                        Add new member: <input type="text" id="new-member-list" class="form-control">
                        <p class="help-block" style="display: none;"></p>
                    </div>
                </div>
                <br/>
                <div id="member-list"></div>
            </div>

            <div class="modal-footer">
                <?php
                echo Html::button('Create', array(
                    'class' => 'btn btn-success',
                    'id'    => 'conversation-save'
                ));
                ?>
            </div>
        <?php ActiveForm::end();?>
    </div>
</div>