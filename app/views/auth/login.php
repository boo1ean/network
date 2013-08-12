<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Captcha;
?>
<div class="col-lg-offset-3">
    <h1>Login</h1>
    <div id="forgot-modal" class="modal fade"></div>
    <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));?>
        <div class="row">
            <div class="col-lg-4">
                <?php echo $form->field($model, 'email')->textInput();?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?php echo $form->field($model, 'password')->passwordInput();?>
            </div>
            <?php //echo $form->field($model, 'captcha')->widget(Captcha::className(), array('options' => array('class' => 'input-small'), 'template' => "{input}<br>{image}", 'captchaAction' => 'auth/captcha'));?>
        </div>
        <div class="control-group">
        <div class="controls">
            <?php echo Html::submitButton('Login', array('class' => 'btn btn-success')); ?>
            <?php echo Html::a('Forgot password?', null, array('id' => 'forgot-open', 'href' => 'javascript:void(0)'))?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>