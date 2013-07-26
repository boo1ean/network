<?php

use yii\helpers\Html;

?>

<h1>Available books</h1>

<?php

echo Html::a('New paper book', 'library/addbook').'<br/>';
echo Html::a('New ebook', '#').'<br/><br/>';

foreach ($books as $book) {
    $title = $book->title == NULL ? 'book #' . $book->id : $book->author . ' - ' . $book->title;

?>

    <ul class="nav nav-list">
        <li><?php echo Html::a($title, 'library/books/' . $book->id); ?>
    </ul>

<?php

}

?>