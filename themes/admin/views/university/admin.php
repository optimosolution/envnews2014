<?php
$this->breadcrumbs = array(
    'Universities' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Manage', 'url' => array('admin'), 'active' => true, 'icon' => 'icon-home'),
    array('label' => 'New', 'url' => array('create'), 'active' => true, 'icon' => 'icon-file'),
    array('label' => '', 'class' => 'search-button', 'url' => '#', 'active' => true, 'icon' => 'icon-search search-button'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('university-grid', {
data: $(this).serialize()
});
return false;
});
");
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'university-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'title',
        array(
            'name' => 'status',
            'value' => '$data->status?Yii::t(\'app\',\'Active\'):Yii::t(\'app\', \'Inactive\')',
            'filter' => array('' => Yii::t('app', 'All'), '0' => Yii::t('app', 'Inactive'), '1' => Yii::t('app', 'Active')),
            'htmlOptions' => array('style' => "text-align:center;"),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
