<?php

namespace app\views;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii;
use app\models\Book;

?>

<h1>Edit book</h1>

<br/>

<?php

if(isset($message)) {
    echo Html::tag('div class="alert alert-success"', $message);
    echo Html::tag('/div');
}

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

$author = $book->author;
$title = $book->title;
$description = $book->description;

echo $form->field($model, 'author')->textInput(array('value' => $author));
echo $form->field($model, 'title')->textInput(array('value' => $title));

?>

<?php echo Html::label('Description', null, array('class' => 'control-label')); ?>

<div class="control-group">
    <div class="controls">
        <?php echo Html::textarea('description', $description); ?>
    </div>
</div>

<div class="controls">
    <?php echo Html::submitButton('Save book', array('class' => 'btn btn-primary')); ?>
</div>

<br/>

<?php

$form->end();

?>