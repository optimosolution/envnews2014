<?php
$this->breadcrumbs = array(
    'Contents' => array('admin'),
    $model->title => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Manage', 'url' => array('admin'), 'active' => true, 'icon' => 'icon-home'),
    array('label' => 'New', 'url' => array('create'), 'active' => true, 'icon' => 'icon-file'),
    array('label' => 'Details', 'url' => array('view', 'id' => $model->id), 'active' => true, 'icon' => 'icon-th-large'),
);
$this->pageTitle = Yii::app()->name . ' - Content';
$this->heading = '<i class="fa fa-edit"></i> Edit Content :: ' . $model->title;
?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>