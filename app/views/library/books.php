<?php

use yii\helpers\Html;
use app\models\Tag;
?>

<h1>Library</h1>

<ul class="nav nav-pills">
    <li><?php echo Html::a('New paper book', 'library/addbook'); ?></li>
    <li><?php echo Html::a('New ebook', '#'); ?></li>
    <li><?php echo Html::a('Show available books', 'library/books/available'); ?></li>
    <li><?php echo Html::a('Show taken books', 'library/books/taken'); ?></li>
    <li><?php echo Html::a('Sort books by title', 'library/books/bytitle'); ?></li>
    <li><?php echo Html::a('Sort books by author', 'library/books/byauthor'); ?></li>
</ul>

<?php

$tags = Tag::getTags();

foreach ($tags as $tag) {
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
    </ul>

    <?php if($book->status == 'available') { ?>

        <ul class="nav nav-pills">
            <li><?php echo Html::a('Take book', array('library/takebook/' . $book->id )); ?></li>
        </ul>

    <?php } ?>

    <ul class="nav nav-pills">
        <li><?php echo Html::a('Edit', array('library/editbook/' . $book->id )); ?></li>
        <li><?php echo Html::a('Delete', array('library/deletebook/' . $book->id)); ?></li>
    </ul>

<?php

}

?>