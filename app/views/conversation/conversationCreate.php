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
        <?php $form = ActiveForm::begin();?>
            <div class="modal-body" >
                <?php echo $form->field($model, 'title')->textInput(array('value'=> $model->title, 'class' => 'form-control')); ?>
                <div class="row">
                    <div class="col-lg-6">
                        Message:
                        <?php echo Html::textarea('message', '', array(
                            'class'       => 'form-control',
                            'rows'        => '4',
                            'style'       => 'resize: none',
                            'id'          => 'message'
                        )); ?>
                        <p class="help-block" style="display: none;"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        Add new member: <input type="text" id="new-member-list" class="form-control">
                        <p class="help-block" style="display: none;"></p>
                    </div>
                </div>
                <br/>
                <div id="member-conversation-list"></div>
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