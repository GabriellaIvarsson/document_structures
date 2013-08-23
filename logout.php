<?php include 'prefix.php';?>

<?php
        setcookie("theUser", ''); //empty cookie to log out
        $returnstring = "<calendar>";
        $returnstring = $returnstring . "</calendar>";
        $returnstring =  utf8_encode($returnstring); 
        header("refresh:0;url=index.php");
    ?>
<?php include 'postfix.php';?>