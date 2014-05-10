<?php
/* @var $this MagazineController */
/* @var $model Magazine */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'magazine-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'title',array('span'=>5,'maxlength'=>255)); ?>

            <?php echo $form->textFieldControlGroup($model,'cover_image',array('span'=>5,'maxlength'=>250)); ?>

            <?php echo $form->textAreaControlGroup($model,'description',array('rows'=>6,'span'=>8)); ?>

            <?php echo $form->textFieldControlGroup($model,'issue_no',array('span'=>5,'maxlength'=>255)); ?>

            <?php echo $form->textFieldControlGroup($model,'published_by',array('span'=>5,'maxlength'=>150)); ?>

            <?php echo $form->textFieldControlGroup($model,'published_time',array('span'=>5,'maxlength'=>100)); ?>

            <?php echo $form->textFieldControlGroup($model,'created_by',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'created_time',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'modified_by',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'modified_time',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'status',array('span'=>5)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->