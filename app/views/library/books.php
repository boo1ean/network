<?php

use yii\helpers\Html;
?>

<h1>Available books</h1>

<?php

echo Html::a('New paper book', 'library/addbook').'<br/>';
echo Html::a('New ebook', '#').'<br/><br/>';

echo Html::a('Show available books', 'library/books/available').'<br/>';
echo Html::a('Show taken books', 'library/books/taken').'<br/><br/>';

echo Html::a('Sort books by title', 'library/books/bytitle').'<br/>';
echo Html::a('Sort books by author', 'library/books/byauthor').'<br/><br/>';

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

<?php

}

?>