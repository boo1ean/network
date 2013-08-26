<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Queue of users</h3>
        </div>
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal', 'id' => 'queue-book')));?>
        <div class="modal-body">
            <div class="list-group" id="user-queue">
                <?php foreach ($users as $user):?>
                    <div class="list-group-item" data-id="<?php echo $user->id?>">
                        <span class="cursorOnNoLink">
                            <?php echo Html::img($user->getAvatar(), array('class' => 'avatar small')); ?>
                            <span id="<?php echo $user->id.'_user'?>">
                                <?php echo $user->first_name . '   ' . $user->last_name?>
                            </span>
                        </span>
                        <div class="navbar-right"> </div>
                    </div>
                <?php endforeach;?>

            </div>
        </div>
        <div class="modal-footer">
            <div id="error" class="label label-danger" style="display:none;"></div>
            <?php
            echo Html::button('Confirm', array(
                'class'   => 'btn btn-success',
                'data-id' => $model->book_id,
                'name'    => 'book-give'
            ));
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>