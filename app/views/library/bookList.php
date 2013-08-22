<?php

use yii\helpers\Html;
use app\models\Book;
?>

<div class="col-lg-offset-1">
    <h1> Library </h1>

    <div class="bookslist">
        <?php foreach ($books as $book):?>
            <ul class="nav nav-list">
                <li>
                    <p><?php echo $book['author']; ?></p>
                    <p class='lead'>
                        <?php echo $book['title']; ?>
                        <?php if ($book['status'] == 'available'): ?>
                        <span class='label label-success'><?php echo $book['status']; ?></span></p>
                    <?php else: ?>
                        <span class='label label-danger'><?php echo $book['status']; ?></span></p>
                        <br/><br/>
                    <?php endif; ?>

                    <blockquote>
                        <p><?php echo $book->description; ?></p>
                    </blockquote>

                    <?php if ($book->type == 2): ?>
                        <span class='label label-success'>
                        <?php echo Html::a('Download Ebook', $book->link, array('target' => '_blank')); ?>
                    </span>
                    <?php endif;?>
                </li>
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
                    } ?>
            </h4>

        <?php endforeach;?>
    </div>
</div>