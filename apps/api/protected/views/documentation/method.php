<script>
    
    $(document).ready(function() {
        
        var beautify = function(container){
            var format = "<? echo $requestFormat ?>";
            var container = container;
            if( container ){
                var html = container.html();
                if( html ){
                    var rawResponse = container.html();
                    var beautified = '';
                    if( format == 'json' ){
                        beautified = vkbeautify.json(rawResponse);
                    } else if(format == 'xml'){
                        beautified = vkbeautify.xml(rawResponse);
                    }
                    container.html(beautified).text();
                }
                
            }
        };
        
        beautify( $(".formattedRequest") );
        beautify( $(".formattedResponse") );
    });
</script>

<h2> Object: <?php echo $objectName ?>. Method: <?php echo $methodName ?> </h2>

<h3> Method Urls </h3>

<ul>
<?php 
    foreach( $methodUrls as $url ){
        echo '<li>'.CHtml::link($url, $url).'</li>';
    }
?>
</ul>

<?php  if( $methodDescription ){ ?>
<h3> Method Description </h3>
<textarea style="max-width: 100%; min-width: 30%; height: 500px;" disabled>
<?php echo $methodDescription; ?>
</textarea>
<?php } ?>

<h3> Validation Rules </h3>

<?php echo $rules; ?>

<form enctype="multipart/form-data" id="api-form"  method="post" class="well form-vertical">
    
<h3> Query Browser </h3>

<label for="request_format">Request Format</label>
<select name="request_format">
<?php 
    foreach( $formats as $format ){
        echo '<option>'.$format.'</option>';
    }
?>
</select>

<label for="session_id">Session ID</label><input  name="session_id" id="session_id" type="text" value ="<?php echo $session_id?>" /> 


<?php
foreach ($requestParams as $name=> $value)
{
    ?>
    <label for="<?php echo $name ?>"><?php echo $name ?></label>
    <div class="">
        <?php
        if( $name == 'Raw_Encoded_Input' ){
            echo '<textarea  name="params['.$name.']" id="'.$name.'" style="max-width: 100%; min-width: 30%; height: 200px;">'.$value.'</textarea>';
        }else{
            echo '<input  name="params['.$name.']" id="'.$name.'" type="text" value ="'.$value.'" />';
        }
        ?>           
    </div>

    <?php
}
echo CHtml::submitButton("Submit", array('class' => 'btn btn-primary'));
?>
</form>


<div id="url"><?php
if ($requestUrl != '')
{
    ?>
    <h4>Url:</h4>
    <?php
    echo CHtml::link($requestUrl, $requestUrl);
}
?></div>
<div id="request"><?php
if ($request != '')
{
    ?>
    <h4>Request:</h4>
    <textarea class ="formattedRequest" style="max-width: 100%; min-width: 30%; height: 200px;" disabled>
    <?php
    echo $request;
}
?></textarea></div>
<div id="response"><?php
if ($response != '')
{
    ?>
    <h4>Response:</h4>
    <textarea class ="formattedResponse" style="max-width: 100%; min-width: 30%; height: 500px;" disabled>
    <?php
    echo $response ;
}
?></textarea></div>

<?php if($queryTime){?>
    <h5> Query time in microseconds <?php echo $queryTime;?> </h5>
<? }?>

