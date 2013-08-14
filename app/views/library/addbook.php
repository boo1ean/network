<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1 class="text-center">Add book</h1>

<br/>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Paper book', null, array('onclick' => 'return showPaperBook()')); ?></li>
    <li><?php echo Html::a('E-book', null, array('onclick' => 'return showEbookUpload()')); ?></li>
</ul>

<br/>

<?php

$form = ActiveForm::begin(array('options' => array(
    'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data'
)));

?>

<div class="row">
    <div class="col-lg-5">
        <?php echo $form->field($model, 'author')->textInput(array('placeholder' => 'Enter author')); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <?php echo $form->field($model, 'title')->textInput(array('placeholder' => 'Enter title')); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <?php echo $form->field($model, 'description')->textArea(array('placeholder' => 'Enter description')); ?>
    </div>
</div>

<?php echo Html::label('Tags', null, array('class' => 'control-label')); ?>
<?php echo Html::textInput('tags', null, array('id' => 'tags')); ?>

<br/>

<div class="ebook">
    <?php echo Html::label('Upload ebook', null, array('class' => 'control-label')); ?>
    <?php echo Html::fileInput('ebook', null); ?>
</div>

<br/>

<?php echo Html::submitButton('Add book', array('class' => 'btn btn-primary')); ?>

<?php ActiveForm::end(); ?>