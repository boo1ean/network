<?php

namespace app\views;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii;
use app\models\Book;

?>

<h1 class="text-center">Edit book</h1>

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

?>

    <div class="row">
        <div class="col-lg-5">
            <?php echo $form->field($model, 'author')->textInput(array('value' => $author)); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <?php echo $form->field($model, 'title')->textInput(array('value' => $title)); ?>
        </div>
    </div>

    <?php echo Html::label('Description', null); ?>

    <div class="row">
        <div class="col-lg-5">
            <?php echo Html::textarea('description', $description, array(
                'class' => 'form-control'
            )); ?>
        </div>
    </div>

    <br/>

    <?php echo Html::label('Tags', null); ?>

    <?php
        $books = Book::find($book->id);
        $tags_by_book = $books->tags;

        $all_tags = '';

        foreach($tags_by_book as $tag)  {
            $all_tags = $all_tags.$tag->title;
            $all_tags = $all_tags.',';
        }
    ?>

    <?php echo Html::textInput('tags', $all_tags, array('id' => 'tags')); ?>

<br/>

<?php echo Html::submitButton('Save book', array('class' => 'btn btn-primary')); ?>

<?php

$form->end();

?>