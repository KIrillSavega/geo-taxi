<!doctype html>
<html lang="en-US">
<head>
    <title>Webservice API Documentation</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo Yii::app()->getBaseUrl().'/favicon.ico'?>" />
    <link href="<?php echo Yii::app()->storage->getBaseUrl(); ?>/js/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <?php Yii::app()->getClientScript()->registerCoreScript('jquery'); ?>
    <script type="text/javascript" src="<?php echo Yii::app()->getBaseUrl().'/js/vkbeautify.js'?>"></script>
    
</head>
<body style="padding: 10px;">
    <div class="page-header">
        <h1>Webservice API Documentation</h1>
    </div>
    <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>$this->breadcrumbs,
    )); ?>    
    
    <?php echo $content  ?>
    
</body>