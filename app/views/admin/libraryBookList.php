<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<div class="col-lg-offset-1">
    <h1> Book list
        <?php echo Html::tag(
            'button',
            '+ New book',
            array(
                'class'   => 'btn btn-success',
                'data-id' => 0,
                'id'      => 'book-create'
            )
        ); ?>
    </h1>

    <div class="navbar navbar-default navbar-left">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'all' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('All', '/admin/library/all/'.$page, array(
                        'id'    => 'filter-all',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'available' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Available', '/admin/library/available/'.$page, array(
                        'id'    => 'filter-available',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'taken' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Taken', '/admin/library/taken/'.$page, array(
                        'id'    => 'filter-taken',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="modal fade" id="book-modal"></div>
    <table class="table table-hover" id="book-table">
        <tr >
            <td > <?php echo Html::tag('b', 'Author'); ?> </td>
            <td > <?php echo Html::tag('b', 'Title');?>   </td>
            <td > <?php echo Html::tag('b', 'Type');?>    </td>
            <td > <?php echo Html::tag('b', 'Actions');?> </td>
        </tr>
        <tr style="display:none" id="recently-added" class="text-center">
            <td colspan="4"><b class="text-info">Recently added</b></td>
        </tr>
        <tr style="display:none" class="fade" id="recently-added-bottom">
            <td colspan="4" style="border-top: 2px solid #000000"></td>
        </tr>
        <?php foreach($books as $book):?>
            <tr <?php
                echo 'id="'.$book->id.'" ';
                if('E-book' == $book->type) {
                    echo 'class="default"';
                } else {
                    echo 'available' == $book->status ? 'class="success"' : 'class="danger"';
                } ?> >
                <td id="<?php echo $book->id?>_author"> <?php echo $book->author;?> </td>
                <td id="<?php echo $book->id?>_title">  <?php echo $book->title;?>  </td>
                <td id="<?php echo $book->id?>_type">   <?php echo $book->type;?>   </td>
                <td >
                    <?php

                    echo Html::button('Edit', array(
                        'class'   => 'btn btn-sm btn-success',
                        'data-id' => $book->id,
                        'name'    => 'book-edit'
                    ));
                    echo Html::submitButton('Delete', array(
                        'class'   => 'btn btn-sm btn-danger',
                        'data-id' => $book->id,
                        'name'    => 'book-delete'
                    ));
                    if('E-book' == $book->type) {
                        echo Html::a('Download', $book->link, array(
                            'class'   => 'btn btn-sm btn-primary '.('' == $book->link ? 'disabled' : ''),
                            'data-id' => $book->id,
                            'name'    => 'book-download',
                            'target'  => '_blank'
                        ));
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
<?php if(!is_null($pagination)):?>
    <div class="text-center">
        <?php echo LinkPager::widget(array('pagination' => $pagination)); ?>
    </div>
<?php endif;?>