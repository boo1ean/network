<?php

use yii\helpers\Html;
use app\models\Book;
use app\models\Booktaking;
use app\models\User;
?>

<div class="row">
    <div class="col-lg-6 col-lg-offset-3">

<h1>Library

<?php

if (Yii::$app->getUser()->getIdentity()->type == 0) {
// Link for new book
echo Html::tag(
    'a',
    '+ New book',
    array(
        'class' => 'btn btn-success',
        'id'    => 'conversation-create',
        'href'   => 'addbook'
    )
);

}
?>

</h1>

<br/>

<ul class="nav nav-pills">

    <li class="active"><?php echo Html::a('Show all books', null, array(
            'id' => 'all',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>

    <li><?php echo Html::a('Show available books', null, array(
            'id' => 'available',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>

    <li><?php echo Html::a('Show taken books', null, array(
            'id' => 'taken',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>

</ul>

<br/>

<ul class="nav nav-pills">

    <li><?php echo Html::a('Sort books by title', null, array(
            'id' => 'title',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>

    <li><?php echo Html::a('Sort books by author', null, array(
            'id' => 'author',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>
</ul>

<br/>

<h4 class="tag">

<p>

<?php

foreach ($all_tags as $tag) {

    echo Html::a($tag->title, null, array(
        'id' => $tag->title,
        'onclick' => 'return showByTags(this)',
        'class' => 'label label-info',
    )).' ';

}

?>

</p>

</h4>

<div class="bookslist">

<?php

foreach ($books as $book) {

?>

    <ul class="nav nav-list">
        <hr>

        <li><p><?php echo $book->author; ?></p>

            <p class='lead'><?php echo $book->title; ?>
                <?php if ($book->status == 'available') { ?>
                    <span class='label label-success'><?php echo $book->status; ?></span>
                <?php } else { ?>
                    <span class='label label-danger'><?php echo $book->status; ?></span></p>

                <small>
                    Taken by
                    <?php
                        $booktake = Booktaking::findByBookIdAndStatus($book->id, 1);
                        echo User::getUserNameById($booktake->user_id).' '.$booktake->taken.
                            '. Will be returned '.$booktake->returned.'.';
                    ?>
                </small>

                <br/><br/>

                <?php } ?>

        <blockquote>
           <p><?php echo $book->description; ?></p>
        </blockquote>

        <?php if ($book->type == 1) { ?>
            <span class='label label-success'><?php echo Html::a('Download Ebook', $book->link, array(
                    'target' => '_blank')); ?></span>
        <?php } ?>
    </ul>

    <h4 class="tag">

    <?php
        $book_current = Book::find($book->id);
        $tags_by_book = $book_current->tags;

        foreach ($tags_by_book as $tag) {
            echo Html::a($tag->title, null, array(
                    'id' => $tag->title,
                    'onclick' => 'return showByTags(this)',
                    'class' => 'label label-info'
                )).' ';
        }
    ?>

    </h4>

    <br/>

    <?php if($book->status == 'available') { ?>

        <ul class="nav nav-pills">
            <li><?php echo Html::a('Take book', null, array(
                    'id' => $book->id,
                    'class' => 'cursorOnNoLink',
                    'onclick' => 'return takeBook(this)',
                )); ?></li>
        </ul>

    <?php } else {

        //check if current user took this book to paint "Untake" button or not. Admin also can Untake books
        $taken = 1;
        $book_take = Booktaking::findByBookIdAndStatus($book->id, $taken);

        if(Yii::$app->getUser()->getId() == $book_take->user_id || Yii::$app->getUser()->getIdentity()->type == 0) {

    ?>

        <ul class="nav nav-pills">
            <li><?php echo Html::a('Untake book', null, array(
                    'id' => $book->id,
                    'class' => 'cursorOnNoLink',
                    'onclick' => 'return untakeBook(this)'
                )); ?></li>
        </ul>

    <?php } }

    //only admin can edit and delete book
    if (Yii::$app->getUser()->getIdentity()->type == 0) {
    ?>

    <ul class="nav nav-pills">
        <li><?php echo Html::a('Edit', array('library/editbook/' . $book->id )); ?></li>
        <li><?php echo Html::a('Delete', null, array(
                'book-id' => $book->id,
                'class' => 'cursorOnNoLink',
                'onclick' => 'return deleteBook(this);')); ?></li>
    </ul>

<?php
    }
}

?>

</div>

    </div>
</div>