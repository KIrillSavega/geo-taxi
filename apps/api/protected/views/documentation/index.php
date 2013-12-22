<h2> Protocol Description </h2>

    <p>
        Webservice supports only json.
    </p>
    
    <p>
        Url has following format: /format/object/action
        If you need to get object by ID: /format/object/ID
    </p>
    
    <p>
        To send your query params you need to encode them as selected format object and add as POST body of HTTP request.
    </p>

<h2>List of API Objects</h2>

<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', 
    'stacked'=>true,
    'items'=>$objects,
)); ?>

<h2>Error Code Dictionary</h2>

<?php 
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$errorDictionaryDataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'Error Code'),
        array('name'=>'description', 'header'=>'Error Description'),
    ),
));
 ?>

