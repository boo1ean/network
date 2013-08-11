<?php

namespace app\views;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii;

?>

<div class="col-lg-offset-3">
    <h1>Edit profile</h1>
    <br/>
    <?php if (isset($message)): ?>
        <div class="row">
            <div class="col-lg-offset-5" style="position: fixed;">
                <?php echo Html::tag('div', $message, array('class' => 'alert alert-success'));?>
            </div>
        </div>
    <?php endif;?>

    <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal'))); ?>
        <div class="controls control-group"><?php echo Html::img($model->getAvatar(), array('class' => 'avatar')); ?></div>
        <div class="row">
            <div class="col-lg-4">
                <?php echo $form->field($model, 'email')->textInput();?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?php echo $form->field($model, 'password')->passwordInput(array('placeholder' => 'Enter new password'));?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?php echo $form->field($model, 'repeat_password')->passwordInput(array('placeholder' => 'Repeat password'));?>
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
        <div class="controls">
            <?php

                if ('yes' == $model->notification) {
                    echo Html::checkbox('notification', true);
                }
                else {
                    echo Html::checkbox('notification', false);
                }

                echo ' Send notifications on email when someone send me a private message';
            ?>
        </div>

        <br/><br/>

        <div class="controls">
            <?php echo Html::submitButton('Save', array('class' => 'btn btn-primary')); ?>
        </div>

        <br/>

    <?php $form->end(); ?>
</div>