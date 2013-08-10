<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Recover password</h3>
        </div>
        <?php $form = ActiveForm::begin();?>
        <div class="modal-body">
            <?php
            echo Html::tag('b', 'Enter your email and we will send instructions how to recover your password.');
            echo $form->field($model, 'email')->textInput(array('value' => $model->email, 'id' => 'email'));
            ?>
        </div>
        <div class="modal-footer">
            <?php echo Html::submitButton('Send', array('class' => 'btn btn-success', 'id' => 'forgot-save'));?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>