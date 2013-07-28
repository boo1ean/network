<?php

use yii\helpers\Html;
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

foreach ($books as $book) {

?>

    <ul class="nav nav-list">
        <hr>

        <li><p><?php echo $book->author; ?></p>
            <p class='lead'><?php echo $book->title; ?></p>

        <blockquote>
           <p><?php echo $book->description; ?></p>
        </blockquote>
    </ul>

    <ul class="nav nav-pills">
        <li><?php echo Html::a('Edit', array('library/editbook/' . $book->id )); ?></li>
        <li><?php echo Html::a('Delete', array('library/deletebook/' . $book->id)); ?></li>
    </ul>

<?php

}

?>