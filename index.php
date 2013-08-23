<?php include 'prefix.php';?>


<?php
    $returnstring = "<calendar>";
    $returnstring = $returnstring . "<div id='not' value='Welcome!'>";
    $returnstring = $returnstring . "</div>";
    $returnstring = $returnstring . "</calendar>";
    $returnstring =  utf8_encode($returnstring); 
include 'postfix.php';?>