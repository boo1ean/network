<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Edit book </h3>
        </div>
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal', 'id' => 'edit-book', 'enctype' => 'multipart/form-data')));?>
        <div class="modal-body">
            <?php echo HTML::hiddenInput('id_edit', $model->id_edit, array('id' => 'id_edit'));?>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'author')->textInput(array(
                        'id'    => 'author',
                        'value' => $model->author
                    ));?>
                </div>

                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'title')->textInput(array(
                        'id'    => 'title',
                        'value' => $model->title
                    ));?>
                </div>

                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'description')->textarea(array(
                        'id'    => 'description',
                        'value' => $model->description
                    )); ?>
                </div>

                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'tags')->textInput(array(
                        'id'    => 'tags',
                        'value' => $model->tags,
                    ));?>
                </div>
            </div>
            <div class="row">
                <div class="input-group col-lg-10 col-lg-offset-1" id="book-types" style="padding-left: 0px;">
                    <span class="input-group-addon"> Paper
                        <input type="radio" name="LibraryForm[type]" value="1" <?php echo 2 != $model->type ? 'checked="checked"' : ''?>>
                    </span>

                    <span class="input-group-addon"> E-book
                        <input type="radio" name="LibraryForm[type]" value="2" <?php echo 2 == $model->type ? 'checked="checked"' : ''?>>
                    </span>
                    <input type="input" id="e-book-state" class="form-control" <?php echo 2 == $model->type ? '' : 'style="display: none;"'?> value="<?php echo $link;?>">
                    <span class="input-group-btn">
                        <?php echo Html::button('Load', array(
                            'class'   => 'btn btn-default fc-state-disabled',
                            'data-id' => $model->resource_id,
                            'id'      => 'e-book-load',
                            'style'   => 2 == $model->type ? '' : 'display: none;'
                        ));?>
                    </span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php
            echo Html::button('Confirm', array(
                'class'   => 'btn btn-success',
                'data-id' => $model->id_edit,
                'name'    => 'book-save'
            ));
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>