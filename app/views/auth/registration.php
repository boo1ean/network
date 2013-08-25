<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

    <h1>Registration</h1>
    <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));?>
        <?php if(isset($message)): ?>
            <div class="row">
                <div class="col-lg-offset-5">
                    <?php echo Html::tag('div', $message);?>
                </div>
            </div>
        <?php else:?>
            <div class="row">
                <div class="col-lg-1">
                    <p class="control-group">Email:</p>
                </div>
                <div class="col-lg-2">
                    <p class="controls"> <?php echo $model['email']?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'password')->passwordInput();?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'repeat_password')->passwordInput();?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'first_name')->textInput();?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'last_name')->textInput();?>
                </div>
            </div>
            <?php echo Html::submitButton('Registration', array('class' => 'btn btn-success'));?>
        <?php endif;?>
    <?php ActiveForm::end(); ?>