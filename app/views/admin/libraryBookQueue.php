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
            <div class="list-group">
                <?php foreach ($users as $user):?>
                    <a href="#" class="list-group-item">
                        <?php echo Html::img($user->getAvatar(), array('class' => 'avatar small')); ?>
                        <?php echo $user->first_name . '   ' . $user->last_name?>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
        <div class="modal-footer">
            <?php
            echo Html::button('Confirm', array(
                'class'   => 'btn btn-success',
                'data-id' => $model->id_book,
                'name'    => 'book-give'
            ));
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>