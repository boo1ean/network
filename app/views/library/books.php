<?php

use yii\helpers\Html;
use app\models\Book;
use app\models\Booktaking;
?>

<h1>Library</h1>

<ul class="nav nav-pills">

    <li>
        <?php if (Yii::$app->getUser()->getIdentity()->type == 0) {
            //only admin can add books
            echo Html::a('New book', 'library/addbook');
        } ?>
    </li>

    <li><?php echo Html::a('Show available books', null, array(
            'id' => 'available',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?></li>

    <li><?php echo Html::a('Show taken books', null, array(
            'id' => 'taken',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?></li>

    <li><?php echo Html::a('Sort books by title', null, array(
            'id' => 'bytitle',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?></li>

    <li><?php echo Html::a('Sort books by author', null, array(
            'id' => 'byauthor',
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink'
        )); ?></li>
</ul>

<?php

foreach ($all_tags as $tag) {
    echo Html::a($tag->title, null, array(
            'id' => $tag->title,
            'onclick' => 'return sortBooks(this)',
            'class' => 'cursorOnNoLink',
            'class' => 'label label-info'
        )).' ';
}

foreach ($books as $book) {

?>

    <ul class="nav nav-list">
        <hr>

        <li><p><?php echo $book->author; ?></p>

            <p class='lead'><?php echo $book->title; ?>
                <?php if($book->status == 'available') { ?>
                    <span class='label label-success'><?php echo $book->status; ?></span>
                <?php } else { ?>
                    <span class='label label-important'><?php echo $book->status; ?></span>
                <?php } ?>
            </p>

        <blockquote>
           <p><?php echo $book->description; ?></p>
        </blockquote>
    </ul>

    <?php
        $book_current = Book::find($book->id);
        $tags_by_book = $book_current->tags;

        foreach ($tags_by_book as $tag) {
            echo Html::a($tag->title, null, array(
                    'id' => $tag->title,
                    'onclick' => 'return sortBooks(this)',
                    'class' => 'cursorOnNoLink',
                    'class' => 'label label-info'
                )).' ';
        }
    ?>

    <br/><br/>

    <?php if($book->status == 'available') { ?>

        <ul class="nav nav-pills">
            <li><?php echo Html::a('Take book', null, array(
                    'id' => $book->id,
                    'class' => 'cursorOnNoLink',
                    'onclick' => 'return takeBook(this)',
                )); ?></li>
        </ul>

    <?php } else {

        //check if current user took this book to paint "Untake" button or not
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