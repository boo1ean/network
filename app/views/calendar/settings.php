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

<div class="row">
    <div class="col-lg-6">
        <?php echo Html::label('Import Google Calendar feed', null, array('class' => 'control-label')); ?>
        <?php echo Html::textInput('feed', $gcal, array(
            'placeholder' => 'Enter your GCal feed',
            'class' => 'form-control'
        )) ?>
    </div>
</div>

    <br/>

    <?php echo Html::submitButton('Save settings', array('class' => 'btn btn-primary')); ?>

<?php ActiveForm::end(); ?>