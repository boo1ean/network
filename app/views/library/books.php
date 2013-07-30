<?php

use yii\helpers\Html;
use app\models\Book;
use app\models\Booktaking;
?>

<h1>Library</h1>

<ul class="nav nav-pills">
    <li><?php echo Html::a('New book', 'library/addbook'); ?></li>
    <li><?php echo Html::a('Show available books', 'library/books/available'); ?></li>
    <li><?php echo Html::a('Show taken books', 'library/books/taken'); ?></li>
    <li><?php echo Html::a('Sort books by title', 'library/books/bytitle'); ?></li>
    <li><?php echo Html::a('Sort books by author', 'library/books/byauthor'); ?></li>
</ul>

<?php

foreach ($all_tags as $tag) {
    echo Html::a($tag->title, array('library/books/'.$tag->id), array('class' => 'label label-info')).' ';
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

    <?php
        $book_current = Book::find($book->id);
        $tags_by_book = $book_current->tags;

        foreach ($tags_by_book as $tag) {
    ?>
        <span class='label label-info'>
            <?php echo $tag->title; ?>
        </span>
    <?php
        }
    ?>

    </ul>

    <br/>

    <?php if($book->status == 'available') { ?>

        <ul class="nav nav-pills">
            <li><?php echo Html::a('Take book', array('library/takebook/' . $book->id )); ?></li>
        </ul>

    <?php } else {

        //check if current user took this book to paint "Untake" button or not
        $taken = 1;
        $book_take = Booktaking::findByBookIdAndStatus($book->id, $taken);

        if(Yii::$app->getUser()->getId() == $book_take->user_id) {

    ?>

        <ul class="nav nav-pills">
            <li><?php echo Html::a('Untake book', array('library/untakebook/' . $book->id )); ?></li>
        </ul>

    <?php } } ?>

    <ul class="nav nav-pills">
        <li><?php echo Html::a('Edit', array('library/editbook/' . $book->id )); ?></li>
        <li><?php echo Html::a('Delete', array('library/deletebook/' . $book->id)); ?></li>
    </ul>

<?php

}

?>