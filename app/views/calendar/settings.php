<?php

use yii\helpers\Html;
use app\models\User;
use yii\widgets\ActiveForm;
?>

<h1>Settings</h1>

<br/>

<?php
    if(isset($message)) {
        echo Html::tag('div class="alert alert-success"', $message);
        echo Html::tag('/div');
    }

    $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
?>

<?php echo Html::label('Import GCal feed', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::textInput('feed', $gcal, array('placeholder' => 'Enter your GCal feed')) ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::submitButton('Save settings', array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>