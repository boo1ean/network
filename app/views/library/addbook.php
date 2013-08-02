<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1>Add book</h1>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Paper book', null, array('onclick' => 'return showPaperBook()')); ?></li>
    <li><?php echo Html::a('E-book', null, array('onclick' => 'return showEbookUpload()')); ?></li>
</ul>

<?php

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

echo $form->field($model, 'author')->textInput(array('placeholder' => 'Enter author'));
echo $form->field($model, 'title')->textInput(array('placeholder' => 'Enter title'));
echo $form->field($model, 'description')->textArea(array('placeholder' => 'Enter description'));

?>

<?php echo Html::label('Tags', null, array('class' => 'control-label')); ?>

<div class="control-group">
    <div class="controls">
        <?php echo Html::textInput('tags', null, array('id' => 'tags')); ?>
    </div>
</div>

<div class="ebook">
    <?php echo Html::label('Upload ebook', null, array('class' => 'control-label')); ?>

    <div class="control-group">
        <div class="controls">
            <?php echo Html::fileInput('ebook', null); ?>
        </div>
    </div>
</div>

<div class="control-group">
    <div class="controls">
        <?php echo Html::submitButton('Add book', array('class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>