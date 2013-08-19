<?php

use yii\helpers\Html;
use app\models\Book;
use app\models\Booktaking;
use app\models\User;
?>

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

                    if ($booktake) {
                        echo User::getUserNameById($booktake->user_id).' '.$booktake->taken.
                            '. Will be returned '.$booktake->returned.'.';
                    }
                ?>
            </small>

            <br/><br/>

            <?php } ?>

            <blockquote>
                <p><?php echo $book->description; ?></p>
            </blockquote>

            <?php if ($book->type == 2) { ?>
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
        $book_take = Booktaking::findByBookIdAndStatus($book->id, 1);

        if ($book_take) {
            if(Yii::$app->getUser()->getId() == $book_take->user_id || Yii::$app->getUser()->getIdentity()->type == 0) {

            ?>

            <ul class="nav nav-pills">
                <li><?php echo Html::a('Untake book', null, array(
                        'id' => $book->id,
                        'class' => 'cursorOnNoLink',
                        'onclick' => 'return untakeBook(this)'
                    )); ?></li>
            </ul>

        <?php } } }

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