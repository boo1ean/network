<?php
use app\models\Book;
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

<div class="col-lg-offset-1">
    <h1> Library </h1>
    <ul class="nav nav-list panel-group" id="books-list">
        <?php foreach ($books as $book):?>
            <li <?php echo $book->status == 'available' ? 'class="panel panel-success"' : 'class="panel panel-danger"'?>>
                <div class="panel-heading ">
                    <h4 class="panel-title modal-header">
                        <div class="navbar-left">
                            <b class="h2" title="Book title">
                                <?php echo $book->title; ?>
                            </b>
                            <div title="Book author">
                                <?php echo $book->author; ?>
                            </div>
                        </div>
                        <div class="navbar-right">
                            <?php
                            $book_current = Book::find($book->id);
                            $tags_by_book = $book_current->tags;

                            foreach ($tags_by_book as $tag) {
                                echo Html::a($tag->title, null, array(
                                    'id'      => $tag->title,
                                    'class'   => 'label label-info'
                                )).' ';
                            } ?>
                        </div> <br/><br/><br/><br/>
                        <a class="accordion-toggle" data-parent="#books-list" data-toggle="collapse" href="<?php echo '#'.$book->id.'-collapse'?>">
                            Description
                        </a>
                    </h4>
                    <?php if ($book->type == 2): ?>
                        <span class='label label-success'>
                        <?php echo Html::a('Download Ebook', $book->link, array('target' => '_blank')); ?>
                    </span>
                    <?php endif;?>
                </div>
                <div id="<?php echo $book->id.'-collapse'?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <blockquote>
                            <p><?php echo $book->description; ?></p>
                        </blockquote>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</div>
<?php if(!is_null($pagination)):?>
    <div class="text-center">
        <?php echo LinkPager::widget(array('pagination' => $pagination)); ?>
    </div>
<?php endif;?>