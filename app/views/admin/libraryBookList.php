<?php
use yii\helpers\Html;
?>
<div class="col-lg-offset-1">
    <h1>Book list</h1>
    <div class="modal fade" id="book-modal"></div>
    <table class="table table-hover">
        <tr >
            <td > <?php echo Html::tag('b', 'Author'); ?> </td>
            <td > <?php echo Html::tag('b', 'Title');?>   </td>
            <td > <?php echo Html::tag('b', 'Type');?>    </td>
            <td > <?php echo Html::tag('b', 'Actions');?> </td>
        </tr>
        <?php foreach($books as $book):?>
            <tr <?php echo 'available' == $book->status ? 'class="success"' : 'class="danger"' ?> >
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
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>