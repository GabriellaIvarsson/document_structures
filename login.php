<?php include 'prefix.php';?>

<?php
        $returnstring = "<calendar>";
        
        //want to show login form
        $returnstring = $returnstring . "<form name='login'>";
        $returnstring = $returnstring . "</form>";
 

        $returnstring = $returnstring . "</calendar>";
        $returnstring =  utf8_encode($returnstring); 
    ?>
<?php include 'postfix.php';?>