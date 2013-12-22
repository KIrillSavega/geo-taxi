<h2> <?php echo $objectName ?> Object  API Methods </h2>

<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', 
    'stacked'=>true,
    'items'=>$methods,
)); ?>

