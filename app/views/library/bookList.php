<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

<div class="col-lg-offset-1">
    <h1> Library </h1>

    <div class="navbar navbar-default navbar-left">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'all' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('All', '/library/books/all/'.$order.'/'.$page, array(
                        'id'    => 'filter-all',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'available' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Available', '/library/books/available/'.$order.'/'.$page, array(
                        'id'    => 'filter-available',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'taken' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Taken', '/library/books/taken/'.$order.'/'.$page, array(
                        'id'    => 'filter-taken',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="navbar navbar-default navbar-left col-lg-offset-1">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'author-asc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Author &#8593;', '/library/books/'.$status.'/author-asc/'.$page, array(
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'author-desc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Author &#8595;', '/library/books/'.$status.'/author-desc/'.$page, array(
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'title-asc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Title &#8593;', '/library/books/'.$status.'/title-asc/'.$page, array(
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'title-desc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Title &#8595;', '/library/books/'.$status.'/title-desc/'.$page, array(
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
            </ul>
        </div>
    </div>

    <br/><br/><br/><br/>
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
                            foreach ($book->tags as $tag) {
                                echo Html::a($tag->title, null, array(
                                    'id'      => $tag->title,
                                    'class'   => 'label label-info'
                                )).' ';
                            } ?>
                        </div>
                        <br/><br/><br/><br/>
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