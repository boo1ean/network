<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1>Add paper book</h1>

<br/>

<?php

if(isset($message)) {
    echo Html::tag('div class="alert alert-success"', $message);
    echo Html::tag('/div');
}

$tagsList = array(
    'IT',
    'design',
    'Yii Framework'
);

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

echo $form->field($model, 'author')->textInput();
echo $form->field($model, 'title')->textInput();
echo $form->field($model, 'description')->textArea();

?>

<?php echo Html::label('Tags', null, array('class' => 'control-label')); ?>

<div class="control-group">
    <div class="controls">
        <?php echo Html::textInput('tags'); ?>
    </div>
</div>

<div class="control-group">
    <div class="controls">
        <?php echo Html::submitButton('Add book', array('class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>