<?php
/* @var $this ReportController */
/* @var $model Report */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'report-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'title', array('span' => 5, 'maxlength' => 255)); ?>

    <?php echo $form->labelEx($model, 'description'); ?>
    <?php $this->widget('yiiwheels.widgets.redactor.WhRedactor', array('model' => $model, 'attribute' => 'description')); ?>
    <br />

    <?php echo $form->textFieldControlGroup($model, 'reporter', array('span' => 5, 'maxlength' => 255)); ?>

    <?php echo $form->textFieldControlGroup($model, 'published_by', array('span' => 5, 'maxlength' => 150)); ?>

    <?php echo $form->textFieldControlGroup($model, 'published_time', array('span' => 5, 'maxlength' => 100)); ?>

    <?php echo $form->DropDownListControlGroup($model, 'status', array('0' => 'Inactive', '1' => 'Active'), array('span' => 5)); ?>

    <?php
    echo TbHtml::formActions(array(
        TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE)),
        TbHtml::resetButton('reset', array('color' => TbHtml::BUTTON_COLOR_INVERSE, 'size' => TbHtml::BUTTON_SIZE_LARGE)),
    ));
    ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->