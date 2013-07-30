<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1>Add paper book</h1>

<br/>

<?php

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

echo $form->field($model, 'author')->textInput(array('placeholder' => 'Enter author'));
echo $form->field($model, 'title')->textInput(array('placeholder' => 'Enter title'));
echo $form->field($model, 'description')->textArea(array('placeholder' => 'Enter description'));

?>

<?php echo Html::label('Tags', null, array('class' => 'control-label')); ?>

<div class="control-group">
    <div class="controls">
        <?php echo Html::textInput('tags', null, array('placeholder' => 'Enter few tags with delimiters')); ?>
    </div>
</div>

<div class="control-group">
    <div class="controls">
        <?php echo Html::submitButton('Add book', array('class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>