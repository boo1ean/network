<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Create conversation </h3>
</div>
<?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));?>
    <div class="modal-body">

        <?php echo $form->field($model, 'title')->textInput(array('value'=> $model->title)); ?>
        <div class="control-group">
            <lable class="control-label">Add new member:</lable>
            <div class="controls">
                <input type="text" id="new-member-list" class="typeahead">
                <span class="help-inline" style="display: none;"></span>
            </div>
        </div>
        <br/>
        <ul class="inline" id="member-list"></ul>
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